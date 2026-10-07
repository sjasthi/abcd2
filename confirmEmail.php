<?php 
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["RESET"])) {
          require_once "confirmEmailCheck.php";
      } 
   }
?>
<?php   
   /* Main page with two forms: sign up and log in */
   require_once __DIR__ . "/token_helper.php";   // also loads db_configuration.php
   include_once "header.php";
?>
<!DOCTYPE html>
<html>
   <head>
      <title>Register/Login Form</title>
      <link href="css/loginForm.css" rel="stylesheet">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
   </head>
   <br>
   <br>

   <body>
      <div class="form">
         <div class="tab-content">
            <div id="login">
               <h1 id="welcomeText">Welcome Back!</h1>
               <form action="confirmEmail.php" method="post" autocomplete="off">
                  <div class="field-wrap">
                     <label>
                     Confirm Email Address
                     <span class="req">*</span>
                     </label>
                     <input type="email" required autocomplete="off" name="email"/>
                  </div>
                  <div class="btnContainer">
                     <button class="button button-block" name="RESET" />Reset Password</button>
                  </div>
                  <?php
$status = $_GET['status'] ?? '';

// The "success" text is deliberately neutral: it is shown whether or not the
// email belongs to an account, so this page cannot be used to find out who is registered.
if ($status === 'success') {
    echo esc_html("If an account exists for that email, a reset link has been sent. The link expires in 1 hour.");
} elseif ($status === 'invalid') {
    echo esc_html("Please enter a valid email address.");
}

// Developer mode only (mail_mode = "log" in config.local.ini): shows the "sent" email on screen.
echo dev_mail_preview_html();
?>
               </form>
            </div>
         </div>
         <!-- tab-content -->
      </div>
      <!-- /form -->
      <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
      <script src="js/loginForm.js"></script>
   </body>
</html>