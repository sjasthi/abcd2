<?php
require 'db_configuration.php';
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

            <?php
            // (SU23-30) (Feature) user email validation
            if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['email_validation']) && !empty($_GET['email_validation'])){
                // Verify data 
                $email = $db->escape_string($_GET['email']);
                $email_validation = $db->escape_string($_GET['email_validation']);
                $sql = "SELECT email, email_validation, active FROM users WHERE email='".$email."' AND email_validation='".$email_validation."' AND active='no'";
                $match = run_sql($sql)->num_rows;

                if($match > 0) {
                    $sql = "UPDATE users SET active='yes' WHERE email='".$email."' AND email_validation='".$email_validation."' AND active='no'";
                    if (mysqli_query($db, $sql)) {
                        // account successfully activated
                        echo "Your account has been activated.</br>
                        Please proceed to the login page.";
                    }
                }
                else {
                    // invalid URL or account already activated
                    echo "This URL is invalid.</br>
                    If you are trying to activate an account, then it may already be active.";
                }

            }
            else if (isset($_SESSION['email'])){
                // this shows after registration
                echo "A validation link was sent to your email ".$_SESSION['email'].".</br>
                Please click the link to activate your account.</br>
                </br>
                Click here to send a new email. (Not implemented)";
            }
            else {
                // invalid URL or account already activated
                echo "This URL is invalid.</br>
                If you are trying to activate an account, then it may already be active.";
            }
            ?>
        </div>
    </div>
</body>