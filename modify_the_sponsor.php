<?php
    include_once 'db_configuration.php';

    if (isset($_POST['sponsor_id'])){
        $sponsor_id = mysqli_real_escape_string($db, $_POST['sponsor_id']);
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $type = mysqli_real_escape_string($db, $_POST['type']);
        $logo = mysqli_real_escape_string($db, $_POST['logo']);
        $description = mysqli_real_escape_string($db, $_POST['description']);
        $website_url = mysqli_real_escape_string($db, $_POST['website_url']);

        $sql = "UPDATE sponsors
                SET name = '$name',
                    type = '$type',
                    logo = '$logo',
                    description = '$description',
                    website_url = '$website_url'
                WHERE sponsor_id = '$sponsor_id'";

        mysqli_query($db, $sql);
        header('location: sponsors.php?sponsorUpdated=Success');
    }
?>
