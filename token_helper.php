<?php
/**
 * token_helper.php
 *
 * Shared helpers for the two "click the link in your email" features:
 *   - Issue #7  : email validation after registration  (token type 'verify')
 *   - Issue #11 : "Forgot password" reset links         (token type 'reset')
 *
 * How a token works:
 *   1. create_token() makes 32 random bytes with random_bytes() (cryptographically
 *      secure) and shows them as 64 hex characters. That is the "raw" token.
 *   2. Only the SHA-256 hash of the raw token goes into the `user_tokens` table.
 *      The raw token exists only in the email. If the database leaks, the hashes
 *      are useless to an attacker.
 *   3. The token expires (24 h for verification, 1 h for reset) and can be used
 *      exactly once (consume_token() flips `used_at` atomically).
 */

require_once __DIR__ . '/db_configuration.php';   // gives us $db (mysqli)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const TOKEN_VERIFY    = 'verify';
const TOKEN_RESET     = 'reset';
const VERIFY_TTL      = 86400;  // validation links live 24 hours
const RESET_TTL       = 3600;   // reset links live 1 hour
const RESEND_COOLDOWN = 60;     // seconds before another email may be requested
const MIN_PASSWORD_LENGTH = 8;  // applies to new registrations and resets
const MAX_PASSWORD_LENGTH = 72; // bcrypt (PASSWORD_DEFAULT) ignores bytes after 72


/** Escape text for safe output inside HTML. */
function esc_html($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Settings come from config.ini. If a config.local.ini exists next to it, its
 * values win. That lets you test on localhost without editing (and accidentally
 * committing) the production settings. config.local.ini is in .gitignore.
 */
function app_settings(): array
{
    static $settings = null;
    if ($settings === null) {
        $settings = @parse_ini_file(__DIR__ . '/config.ini') ?: [];
        if (is_readable(__DIR__ . '/config.local.ini')) {
            $settings = array_merge($settings, @parse_ini_file(__DIR__ . '/config.local.ini') ?: []);
        }
    }
    return $settings;
}

/**
 * Base URL used in email links, e.g. https://abcd2.projectabcd.com
 * Taken from config, NOT from the request: a request's Host header can be
 * forged, which would let an attacker send victims a reset link pointing at
 * the attacker's own site ("host header poisoning").
 */
function app_base_url(): string
{
    return rtrim(app_settings()['URL'] ?? '', '/');
}


/* ------------------------------------------------------------------ tokens */

/**
 * Create a new token for a user and return the RAW token (for the email).
 * Any older unused token of the same type for that user is deleted, so only
 * the newest link works.
 */
function create_token(mysqli $db, int $userId, string $type, int $ttlSeconds): string
{
    // Housekeeping: forget tokens that expired more than a week ago.
    $db->query("DELETE FROM user_tokens WHERE expires_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");

    $del = $db->prepare("DELETE FROM user_tokens WHERE user_id = ? AND type = ? AND used_at IS NULL");
    $del->bind_param('is', $userId, $type);
    $del->execute();

    $raw  = bin2hex(random_bytes(32));
    $hash = hash('sha256', $raw);

    // NOW() is evaluated by the database for both writing and reading, so PHP
    // and MySQL time zones can never disagree about when a token expires.
    $ins = $db->prepare(
        "INSERT INTO user_tokens (user_id, type, token_hash, expires_at)
         VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND))"
    );
    $ins->bind_param('issi', $userId, $type, $hash, $ttlSeconds);
    $ins->execute();

    return $raw;
}

/**
 * Look up a raw token. Returns ['id' => token row id, 'user_id' => ...] if it
 * exists, has the right type, is unused and not expired. Otherwise null.
 */
function find_valid_token(mysqli $db, string $raw, string $type): ?array
{
    // Cheap format check first: our tokens are always exactly 64 hex characters.
    if (!preg_match('/^[a-f0-9]{64}$/', $raw)) {
        return null;
    }
    $hash = hash('sha256', $raw);

    $st = $db->prepare(
        "SELECT id, user_id FROM user_tokens
         WHERE token_hash = ? AND type = ? AND used_at IS NULL AND expires_at > NOW()"
    );
    $st->bind_param('ss', $hash, $type);
    $st->execute();
    $row = $st->get_result()->fetch_assoc();

    return $row ?: null;
}

/**
 * Mark a token as used. Returns true only for the single request that wins.
 * (If two requests race with the same link, the second one gets false.)
 */
function consume_token(mysqli $db, int $tokenId): bool
{
    $st = $db->prepare(
        "UPDATE user_tokens SET used_at = NOW()
         WHERE id = ? AND used_at IS NULL AND expires_at > NOW()"
    );
    $st->bind_param('i', $tokenId);
    $st->execute();
    return $st->affected_rows === 1;
}

/** Was a token of this type created for this user in the last $seconds? (anti-spam) */
function token_recently_issued(mysqli $db, int $userId, string $type, int $seconds): bool
{
    $st = $db->prepare(
        "SELECT 1 FROM user_tokens
         WHERE user_id = ? AND type = ? AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
         LIMIT 1"
    );
    $st->bind_param('isi', $userId, $type, $seconds);
    $st->execute();
    return $st->get_result()->num_rows > 0;
}


/* -------------------------------------------------------------------- mail */

/**
 * Send an email using the SMTP settings in config.ini.
 *
 * Local testing: put   mail_mode = "log"   in config.local.ini. Nothing is
 * sent; the email is kept in the session and shown on screen by
 * dev_mail_preview_html(), so you can click the link without a mail server.
 */
function send_app_mail(string $to, string $subject, string $body): bool
{
    $s = app_settings();

    if (($s['mail_mode'] ?? 'smtp') === 'log') {
        $_SESSION['dev_mail'] = ['to' => $to, 'subject' => $subject, 'body' => $body];
        return true;
    }

    if (isset($s['SMTP']))          ini_set('SMTP', $s['SMTP']);
    if (isset($s['smtp_port']))     ini_set('smtp_port', $s['smtp_port']);
    if (isset($s['sendmail_from'])) ini_set('sendmail_from', $s['sendmail_from']);

    // $to has already been checked with FILTER_VALIDATE_EMAIL (no newlines),
    // so it cannot be used to inject extra mail headers.
    return @mail($to, $subject, $body, "From: noreply@projectabcd.com\r\n");
}

/** HTML box showing the "sent" email in log mode (empty string in normal mode). */
function dev_mail_preview_html(): string
{
    if (empty($_SESSION['dev_mail'])) {
        return '';
    }
    $m = $_SESSION['dev_mail'];
    unset($_SESSION['dev_mail']);   // show once
    $body = nl2br(esc_html($m['body']));
    // Make the link clickable for convenience.
    $body = preg_replace('~(https?://[^\s<]+)~', '<a href="$1">$1</a>', $body);

    return '<div style="margin:20px auto;max-width:560px;padding:12px 16px;border:2px dashed #c60;'
         . 'background:#fff8e6;color:#333;font-size:14px;text-align:left;word-break:break-all;">'
         . '<strong>Developer mode (mail_mode = "log"): no email was sent.</strong><br>'
         . 'To: ' . esc_html($m['to']) . '<br>Subject: ' . esc_html($m['subject']) . '<br><br>'
         . $body . '</div>';
}


/* -------------------------------------------------------------------- CSRF */

/** Per-session secret to put in forms that change state. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_valid($submitted): bool
{
    return is_string($submitted) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $submitted);
}
