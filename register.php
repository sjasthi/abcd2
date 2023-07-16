<html>
    <style type="text/css">
        h1 {
            font-size: 10;
            color: black;
        }
    </style>

    <?php
    session_start();
    
    //Enable mySQL error messages
    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
    
    require 'db_configuration.php';
    
    /* User registers as a new user, (checks if user exists and password is correct) */
    if (isset($_POST['password']) && isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
        //escape email to protect against SQL injections
        $pass = $db->escape_string($_POST['password']);

        //hash password to store in DB
        $hashPass = password_hash($pass, PASSWORD_DEFAULT);

        $email = $db->escape_string($_POST['email']);
        $first_name = $db->escape_string($_POST['first_name']);
        $last_name = $db->escape_string($_POST['last_name']);

        // (SU23-30) (Feature) create an email validation token
        // reference https://code.tutsplus.com/how-to-implement-email-verification-for-new-members--net-3824t
        $email_validation = md5(rand(0, 1000));

        //insert user info into DB
        $sql = "INSERT INTO users (first_name, last_name, email, hash, email_validation, active, role, modified_time, created_time)
                VALUES ('$first_name', '$last_name', '$email', '$hashPass', '$email_validation', 'no', 'user', '0000-00-00', '0000-00-00')";

        if (mysqli_query($db, $sql)) {

            // SMTP server
            // reference https://stackoverflow.com/questions/25909348/how-to-send-email-with-smtp-in-php
            ini_set('SMTP', "smtp.TODO.com");
            ini_set('smtp_port', "465");
            ini_set('sendmail_from', "email@domain.com");

            // send validation email
            // TODO update the activation link URL to match the deployed server
            $email_subject = 'Signup | Validation';
            $email_message = '

            Your account has been created. Please click this link to activate your account:
            https://abcd2.projectabcd.com/validation.php?email='.$email.'&email_validation='.$email_validation.'
            
            ';
            $email_headers = 'From:noreply@projectabcd.com'."\r\n";
            // TODO uncomment the following code after the SMTP server is working
            //if(mail($email, $email_subject, $email_message, $email_headers)){
                $_SESSION['status'] = "Sucess";
                $_SESSION['email'] = $email;
                header("location: validation.php");
            /*}
            else {
                echo "email failed";
            }*/
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
    }

    ?>
</html>