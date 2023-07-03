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

        //insert user info into DB

        $sql = "INSERT INTO users (first_name, last_name, email, hash, active, role, modified_time, created_time)
                VALUES ('$first_name', '$last_name', '$email', '$hashPass', 'no', 'user', '0000-00-00', '0000-00-00')";

        if (mysqli_query($db, $sql)) {
            $_SESSION['status'] = "Sucess";
            header("location: index.php");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
    }

    ?>
</html>