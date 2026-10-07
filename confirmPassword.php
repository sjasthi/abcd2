<?php
/**
 * confirmPassword.php  -  Issue #11: step 2 of "Forgot password" (choose a new password).
 * Reached from the link in the reset email:  confirmPassword.php?token=<64 hex characters>
 *
 * What was wrong before (this was a serious security hole):
 *   - The form POST only needed an email address. NO token was checked when the
 *     password was actually changed, so anyone could reset anybody's password
 *     (including admins) with a single request.
 *   - The page showed the "new password" form even when the token was fake, and the
 *     token lookup compared the link to the user's password hash, so it never matched.
 *   - The token lookup was built by string concatenation (SQL injection), the email
 *     was printed unescaped into the form (XSS) and display_errors was switched on.
 *
 * What it does now:
 *   - The token is checked on the page load AND again on the POST that changes the password.
 *   - The user whose password changes is the one the TOKEN belongs to, never an email
 *     typed into a form.
 *   - The token is single use and expires after 1 hour. A successful reset also
 *     cancels any other outstanding reset links for that account.
 */
require_once __DIR__ . '/token_helper.php';
header('Referrer-Policy: no-referrer');   // keep the token out of Referer headers sent to CDNs

$error = '';
$token = $_POST['token'] ?? $_GET['token'] ?? '';
$token = is_string($token) ? $token : '';

$found = find_valid_token($db, $token, TOKEN_RESET);   // null when fake / expired / already used

if ($found && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    // Raw values on purpose: password_hash() must see exactly what the user typed
    // (the old escape_string() call changed passwords containing ' " or \).
    $pass1 = $_POST['pass']  ?? '';
    $pass2 = $_POST['cpass'] ?? '';

    if ($pass1 !== $pass2) {
        $error = "Passwords don't match.";
    } elseif (strlen($pass1) < MIN_PASSWORD_LENGTH || strlen($pass1) > MAX_PASSWORD_LENGTH) {
        $error = 'Your password must be between ' . MIN_PASSWORD_LENGTH . ' and ' . MAX_PASSWORD_LENGTH . ' characters.';
    } else {
        $db->begin_transaction();
        try {
            // Wins for exactly one request; a second use of the same link fails here.
            if (!consume_token($db, (int)$found['id'])) {
                throw new RuntimeException('token already used');
            }

            $newHash = password_hash($pass1, PASSWORD_DEFAULT);
            $up = $db->prepare('UPDATE users SET hash = ?, modified_time = CURDATE() WHERE id = ?');
            $up->bind_param('si', $newHash, $found['user_id']);
            $up->execute();

            // Cancel any other reset links still floating around for this account.
            $del = $db->prepare("DELETE FROM user_tokens WHERE user_id = ? AND type = 'reset' AND used_at IS NULL");
            $del->bind_param('i', $found['user_id']);
            $del->execute();

            $db->commit();
            header('Location: loginForm.php?reset=success');
            exit();
        } catch (Throwable $e) {
            $db->rollback();
            $found = null;   // fall through to the "invalid or expired" message below
        }
    }
}

include_once 'header.php';
?>
<!DOCTYPE html>
<html>
   <head>
      <title>Reset password</title>
      <link href="css/loginForm.css" rel="stylesheet">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
   </head>
   <body>
      <div class="form">
         <div class="tab-content">
            <div id="login">
<?php if (!$found): ?>
               <h1 id="welcomeText">Link expired</h1>
               <p>This password reset link is invalid, has expired, or has already been used.</p>
               <p><a href="confirmEmail.php">Request a new reset link</a></p>
<?php else: ?>
               <h1 id="welcomeText">Choose a new password</h1>
               <?php if ($error !== ''): ?>
                  <p style="color:#c00;"><?php echo esc_html($error); ?></p>
               <?php endif; ?>
               <form action="confirmPassword.php" method="post" autocomplete="off">
                  <div class="field-wrap">
                     <label>New password <span class="req">*</span></label>
                     <input type="password" required autocomplete="new-password" name="pass"
                            minlength="<?php echo MIN_PASSWORD_LENGTH; ?>" maxlength="<?php echo MAX_PASSWORD_LENGTH; ?>"/>
                     <label>Confirm password <span class="req">*</span></label>
                     <input type="password" required autocomplete="new-password" name="cpass"
                            minlength="<?php echo MIN_PASSWORD_LENGTH; ?>" maxlength="<?php echo MAX_PASSWORD_LENGTH; ?>"/>
                     <!-- The token (not an email address) is what proves who may change which password. -->
                     <input type="hidden" name="token"  value="<?php echo esc_html($token); ?>"/>
                     <input type="hidden" name="action" value="update"/>
                  </div>
                  <div class="btnContainer">
                     <button class="button button-block" name="change_password">Confirm Password</button>
                  </div>
               </form>
<?php endif; ?>
            </div>
         </div>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
      <script src="js/loginForm.js"></script>
   </body>
</html>
