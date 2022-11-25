<html>
    <style type="text/css">
        h1 {
            font-size: 10;
            color: black;
        }
    </style>

    <?php
    require 'db_configuration.php';

    /* User registers as a new user, (checks if user exists and password is correct) */

    //escape email to protect against SQL injections
    $pass = $db->escape_string($_POST['password']);

    //hash password to store in DB
    $hashPass = password_hash($pass, PASSWORD_DEFAULT);

    $email = $db->escape_string($_POST['email']);
    $first_name = $db->escape_string($_POST['first_name']);
    $last_name = $db->escape_string($_POST['last_name']);

    //insert user info into DB
    $sql = "INSERT INTO users (first_name, last_name, email, hash)
            VALUES ('$first_name', '$last_name', '$email', '$hashPass')";

    if (mysqli_query($db, $sql)) {
        echo "<h1> Account Successfully Created! </h1>";
        header("location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db);
    }

    // header("location: index.php");
    ?>
</html>