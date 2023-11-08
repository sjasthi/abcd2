<?php

include_once 'db_configuration.php';

if(isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $type = mysqli_real_escape_string($db, $_POST['type']);
    $logo = mysqli_real_escape_string($db, $_POST['logo']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $website_url = mysqli_real_escape_string($db, $_POST['website_url']);

    $sql = "INSERT INTO `sponsors` ( `name`, `type`, `logo`, `description`, `website_url` )
            VALUES ('$name', '$type', '$logo', '$description', '$website_url')";
    
    mysqli_query($db, $sql);
    header('location: sponsors.php?create_sponsor=Success');
}

?>
