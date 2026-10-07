<?php
/**
 * validation.php  -  Issue #7: activates an account when the user clicks the
 * link in their validation email, and lets them request a fresh link.
 *
 * Link format:  validation.php?token=<64 hex characters>
 * (The old  ?email=...&email_validation=...  links are gone on purpose: their
 *  10-character code came from only 1,001 possible values and was guessable.)
 */
require_once __DIR__ . '/token_helper.php';
header('Referrer-Policy: no-referrer');   // keep the token out of Referer headers sent to CDNs

$message = '';          // text shown to the visitor
$activated = false;     // true after a successful activation
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend'])) {
    /* ---------- "Send me a new validation email" button ---------- */
    if (!csrf_valid($_POST['csrf'] ?? '')) {
        $message = 'Your session expired. Please reload the page and try again.';
    } elseif (empty($_SESSION['email'])) {
        $message = 'Please log in first so we know which account to send the link to.';
    } else {
        $st = $db->prepare("SELECT id, email FROM users WHERE email = ? AND active <> 'yes'");
        $st->bind_param('s', $_SESSION['email']);
        $st->execute();
        $user = $st->get_result()->fetch_assoc();

        if ($user && token_recently_issued($db, (int)$user['id'], TOKEN_VERIFY, RESEND_COOLDOWN)) {
            $message = 'A link was just sent. Please wait a minute before asking for another one.';
        } else {
            if ($user) {
                // Accounts created before this fix may hold an old random code in `active`: normalise it.
                $fix = $db->prepare("UPDATE users SET active = 'no' WHERE id = ?");
                $fix->bind_param('i', $user['id']);
                $fix->execute();

                $newToken = create_token($db, (int)$user['id'], TOKEN_VERIFY, VERIFY_TTL);
                $link = app_base_url() . '/validation.php?token=' . $newToken;
                send_app_mail($user['email'], 'Signup | Validation',
                    "Please click this link within 24 hours to activate your account:\n" . $link);
            }
            // Same wording whether or not the account exists, so this can't be used to probe emails.
            $message = 'If that account still needs validating, a new link has been sent to your email.';
        }
    }

} elseif ($token !== '') {
    /* ---------- the visitor clicked the link in the email ---------- */
    $found = is_string($token) ? find_valid_token($db, $token, TOKEN_VERIFY) : null;

    if ($found) {
        $db->begin_transaction();
        try {
            // consume_token() succeeds for exactly one request, so a link can't be replayed.
            if (!consume_token($db, (int)$found['id'])) {
                throw new RuntimeException('token already used');
            }
            $up = $db->prepare("UPDATE users SET active = 'yes', modified_time = CURDATE() WHERE id = ?");
            $up->bind_param('i', $found['user_id']);
            $up->execute();
            $db->commit();
            $activated = true;
            $message = 'Your account has been activated. Please proceed to the login page.';
        } catch (Throwable $e) {
            $db->rollback();
        }
    }
    if (!$activated) {
        $message = 'This link is invalid or has expired. If your account is not active yet, request a new link below.';
    }

} elseif (!empty($_SESSION['email'])) {
    /* ---------- shown right after registering, or after logging in before validating ---------- */
    if (!empty($_SESSION['mail_failed'])) {
        unset($_SESSION['mail_failed']);
        $message = 'Your account was created, but we could not send the validation email. Please press "Send a new link" below.';
    } else {
        $message = 'A validation link was sent to your email ' . $_SESSION['email'] . '. Please click the link within 24 hours to activate your account.';
    }
} else {
    $message = 'This URL is invalid. If you are trying to activate an account, it may already be active.';
}

include('header.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Validation</title>
    <link href="css/loginForm.css" rel="stylesheet">
</head>
<body>
    <div class="tab-content">
        <div id="login">
            <p><?php echo esc_html($message); ?></p>

            <?php echo dev_mail_preview_html(); ?>

            <?php if ($activated): ?>
                <p><a href="loginForm.php">Go to the login page</a></p>
            <?php elseif (!empty($_SESSION['email'])): ?>
                <form action="validation.php" method="post">
                    <input type="hidden" name="csrf" value="<?php echo esc_html(csrf_token()); ?>">
                    <button class="button button-block" name="resend" value="1">Send a new link</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
