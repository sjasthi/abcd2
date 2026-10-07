<?php
/**
 * confirmEmailCheck.php  -  Issue #11: step 1 of "Forgot password".
 * Included by confirmEmail.php when the visitor submits their email address.
 *
 * What was wrong before:
 *   - The link carried password_hash($token) - bcrypt text full of $ . / characters
 *     that was never URL-encoded - and the page that received it compared it to the
 *     user's PASSWORD hash, so a real link could never work.
 *   - It wrote to a table (password_reset_temp) that does not exist in abcd_db.sql.
 *   - It answered "No email found", which tells attackers which emails have accounts.
 *   - The link pointed at a hard-coded production URL.
 *
 * What it does now:
 *   - Always answers with the same message, whether or not the email exists.
 *   - Creates a random single-use token (stored hashed, valid 1 hour) in user_tokens.
 *   - Ignores repeat requests for the same account within a minute (anti-spam).
 */
require_once __DIR__ . '/token_helper.php';

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: confirmEmail.php?status=invalid');
    exit();
}

$st = $db->prepare('SELECT id FROM users WHERE email = ?');
$st->bind_param('s', $email);
$st->execute();
$user = $st->get_result()->fetch_assoc();

if ($user && !token_recently_issued($db, (int)$user['id'], TOKEN_RESET, RESEND_COOLDOWN)) {
    $token = create_token($db, (int)$user['id'], TOKEN_RESET, RESET_TTL);
    $link  = app_base_url() . '/confirmPassword.php?token=' . $token;

    $body = "Dear user,\n\n"
          . "Click on the link below within 1 hour to reset your password:\n"
          . $link . "\n\n"
          . "If you did not request a password reset, please ignore this email.";

    if (!send_app_mail($email, 'Password Reset Link', $body)) {
        error_log('ABCD: could not send password reset email');   // visitor still sees the neutral message
    }
}

// Identical response for "account exists" and "no such account".
header('Location: confirmEmail.php?status=success');
exit();
