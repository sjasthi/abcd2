<?php

include_once 'db_configuration.php';

if (isset($_POST['id'])){

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $role = mysqli_real_escape_string($db, $_POST['role']);
    //$validate = emailValidate($answer);

echo $first_name;
echo $last_name;
echo $email;
echo $role;


    //if($validate){                    
                    $sql = "UPDATE users
                    SET first_name = '$first_name',
                        last_name = '$last_name',
                        email = '$email',
                        role = '$role'    
                    WHERE id = '$id'";
echo $sql;

                    mysqli_query($db, $sql);
                 header('location: users.php?userUpdated=Success');
                    }
        

                     else{
                header('location: users.php?userUpdated=Success');
                }
    

?>
