<?php
include_once 'db_configuration.php';

if (isset($_POST['sponsor_id'])){
    $sponsor_id = mysqli_real_escape_string($db, $_POST['sponsor_id']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $type = mysqli_real_escape_string($db, $_POST['type']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $website_url = mysqli_real_escape_string($db, $_POST['website_url']);
    
    // Handle the logo upload if a new file is provided
    if (!empty($_FILES["logoToUpload"]["name"])) {
        $target_dir = "images/sponsor_logos/";
        $logo = basename($_FILES["logoToUpload"]["name"]);
        $target_file = $target_dir . $logo;

        // You should include file validation checks here

        if (move_uploaded_file($_FILES["logoToUpload"]["tmp_name"], $target_file)) {
            // File is uploaded successfully
            $logo = $target_file;
        } else {
            // Handle error
            echo "Sorry, there was an error uploading your file.";
            exit; // Exit script if logo upload fails
        }
    }

    // Update database with possibly new logo path
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

