<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
include_once 'db_configuration.php';


$status = session_status();
if($status == PHP_SESSION_NONE){
    session_start();
}
ob_start();
$email = $db->escape_string($_POST['email']);
$result = $db->query("SELECT * FROM users WHERE email='$email'");
if ( $result->num_rows == 0 ){ // User doesn't exist
   header("Location: confirmEmail.php?status=error");
   exit();
}else{
   $baseURL = "https://abcd2.projectabcd.com"; // Replace with your actual domain and resetpassword.php path
   $token = bin2hex(random_bytes(32)); // Generate a random token for the link
   $hashToken = password_hash($token, PASSWORD_DEFAULT);
   $link = $baseURL . "?token=" . $hashToken."&email=".$email; // Append the token as a query parameter to the link
   sendResetPasswordEmail($db,$email, $link,$hashToken); // You need to implement this function to send the reset password email
   header("Location: confirmEmail.php?status=success");
   exit();
}

// Function to send the reset password email
function sendResetPasswordEmail($db,$email, $resetPasswordLink,$hashToken)
{
   try{
      $message = "Dear user,\n\n";
      $message .= "Click on the link below to reset your password:\n";
      $message .= $resetPasswordLink;
      $message .= "\n\nIf you did not request a password reset, please ignore this email.";
      $mail = new PHPMailer;
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'ics_abcd@projectabcd.com';                 // SMTP username
      $mail->Password = 'iLoveMetro';                           // SMTP password
      $mail->SMTPSecure = 'tls';
      $mail->Port = 465;                                    // TCP port to connect to
      $mail->setFrom('email@projectabcd.com', 'Mailer');
      $mail->addAddress($email, 'You');     // Add a recipient
      $mail->Subject = "Reset Your Password";
      $mail->Body    = $message; 
      $mail->send();
      $expFormat = mktime(
         date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
         );
      $expDate = date("Y-m-d H:i:s",$expFormat);
      $sql = "INSERT INTO `password_reset_temp` (`email`, `token`, `expDate`) VALUES ('".$email."', '".$hashToken."', '".$expDate."');";
      $result = mysqli_query($db, $sql);
   }catch (Exception $e) {
      echo var_dump($mail->ErrorInfo);
  }


}

?>