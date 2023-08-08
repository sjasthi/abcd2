<?php
   ob_start();
   require "db_configuration.php";
   include "header.php"; 
   ob_flush();
   if (isset($_GET["token"])){
   $token = $_GET["token"];
   $email = $_GET["email"];
   $curDate = date("Y-m-d H:i:s");
   $result = $db->query("SELECT * FROM `password_reset_temp` WHERE `token`='".$token."' and `email`='".$email."';");
   if ( $result->num_rows == 0 ){ // User doesn't exist   
   }
   else (
   $row = mysqli_fetch_assoc($query);
   $expDate = $row['expDate'];
   if ($expDate >= $curDate){
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
   <body>
      <div class="form">
         <ul class="tab-group">
            <li class= "tab active align = right"><a href="#register">New User</a></li>
         </ul>
         <div class="tab-content">
            <div id="login">
               <h1 id="welcomeText">Welcome Back!</h1>
               <form action="confirmPassword.php" method="post" autocomplete="off">
                  <div class="field-wrap">
                     <label>
                     Password
                     <span class="req">*</span>
                     </label>
                     <input type="password" required autocomplete="off" name="pass"/>
                     <label>
                     Confirm Password
                     <span class="req">*</span>
                     </label>
                     <input type="password" required autocomplete="off" name="cpass"/>
                     <input type="hidden" name="email" value="<?php echo $email;?>"/>
                     <input type="hidden" name="action" value="update"/>
                  </div>
                  <div class="btnContainer">
                     <button class="button button-block" name="change_password" />Confirm Password</button>
                  </div>
               </form>
            </div>
         </div>
         <!-- tab-content -->
      </div>
      <!-- /form -->
      <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
      <script src="js/loginForm.js"></script>
   </body>
</html>
<?php
      }
  ) }

if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"]=="update")){
   $error="";
   $pass1 = $db->escape_string($_POST['pass']);
   $pass2 = $db->escape_string($_POST['cpass']);
   $email = $_POST["email"];
   if ($pass1!=$pass2){
   $error.= "<p>Password do not match, both password should be same.<br /><br /></p>";
     }
     if($error!=""){
      header("Location: confirmEmail.php?status=errorPassword");
   }else{
   $pass1 = password_hash($token, PASSWORD_DEFAULT);
   mysqli_query($db,
   "UPDATE `users` SET `hash`='".$pass1."'
   WHERE `email`='".$email."';"
   );
   
   mysqli_query($db,"DELETE FROM `password_reset_temp` WHERE `email`='".$email."';");
   header("Location: loginForm.php");
}
}
?>