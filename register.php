<?php
/**
 * register.php  -  Issue #7: New User Registration, email validation token
 *
 * What was wrong before:
 *   - The validation token was md5(rand(0,1000)): only 1,001 possible values,
 *     so anyone could activate any pending account by trying them all.
 *   - The password was passed through escape_string() BEFORE hashing, which
 *     changes passwords containing ' " or \ so they could never log in
 *     (login.php checks the raw password).
 *   - The email address was put in the link unencoded, accounts had zero-dates,
 *     duplicates were not checked, and failures printed raw SQL to the visitor.
 *
 * What it does now:
 *   - Prepared statements for every query (no SQL injection, no escape_string).
 *   - A 256-bit random token, stored hashed, valid 24 hours, single use.
 *   - The account starts with active = 'no' and becomes 'yes' in validation.php.
 */

require_once __DIR__ . '/token_helper.php';   // also loads db_configuration.php and starts the session

/** Send the visitor to the shared error page with a message, then stop. */
function register_fail(string $message): void
{
    $_SESSION['message'] = $message;
    header('Location: error.php');
    exit();
}

if (isset($_POST['password'], $_POST['email'], $_POST['first_name'], $_POST['last_name'])) {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];   // raw on purpose: password_hash() needs the real characters

    // ---- validate input (limits match the column sizes in the users table) ----
    if ($first_name === '' || strlen($first_name) > 30 || $last_name === '' || strlen($last_name) > 30) {
        register_fail('Please enter your first and last name (30 characters or fewer each).');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 75) {
        register_fail('Please enter a valid email address.');
    }
    if (strlen($password) < MIN_PASSWORD_LENGTH || strlen($password) > MAX_PASSWORD_LENGTH) {
        register_fail('Your password must be between ' . MIN_PASSWORD_LENGTH . ' and ' . MAX_PASSWORD_LENGTH . ' characters.');
    }

    // ---- is the email already registered? ----
    $check = $db->prepare('SELECT id FROM users WHERE email = ?');
    $check->bind_param('s', $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        register_fail('An account with that email already exists. Try logging in, or use "Forgot password".');
    }

    // ---- create the (not yet active) account ----
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = $db->prepare(
        "INSERT INTO users (first_name, last_name, email, hash, active, role, modified_time, created_time)
         VALUES (?, ?, ?, ?, 'no', 'user', CURDATE(), CURDATE())"
    );
    $insert->bind_param('ssss', $first_name, $last_name, $email, $hash);
    $insert->execute();
    $userId = $db->insert_id;

    // ---- email the validation link ----
    $token = create_token($db, $userId, TOKEN_VERIFY, VERIFY_TTL);
    $link  = app_base_url() . '/validation.php?token=' . $token;

    $body = "Your ABCD account has been created.\n\n"
          . "Please click this link within 24 hours to activate your account:\n"
          . $link . "\n\n"
          . "If you did not sign up, you can ignore this email.";

    $_SESSION['email'] = $email;   // validation.php uses this to show the "check your email" page
    if (!send_app_mail($email, 'Signup | Validation', $body)) {
        // The account exists, so don't lose it: validation.php offers a "Resend" button.
        $_SESSION['mail_failed'] = true;
    }

    header('Location: validation.php');
    exit();
}
?>
