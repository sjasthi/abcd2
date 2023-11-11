<?php
include_once 'db_configuration.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $type = mysqli_real_escape_string($db, $_POST['type']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $website_url = mysqli_real_escape_string($db, $_POST['website_url']);

    // Initialize a variable to hold the path of the uploaded logo
    $logo = "";

    // Check if a file was uploaded
    if (isset($_FILES['logoToUpload']) && $_FILES['logoToUpload']['error'] == 0) {
        $target_dir = "images/sponsor_logos/";
        $target_file = $target_dir . basename($_FILES["logoToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["logoToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["logoToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // if everything is ok, try to upload file
            if (move_uploaded_file($_FILES["logoToUpload"]["tmp_name"], $target_file)) {
                $logo = $target_file; // Assign the path of the uploaded file to the logo variable
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // If the file was uploaded successfully or no file was uploaded, insert the data into the database
    if ($uploadOk == 1 || $logo == "") {
        $sql = "INSERT INTO `sponsors` (`name`, `type`, `logo`, `description`, `website_url`) VALUES (?, ?, ?, ?, ?)";
        
        if($stmt = $db->prepare($sql)){
            $stmt->bind_param("sssss", $name, $type, $logo, $description, $website_url);
            $stmt->execute();
            header('location: sponsors.php?create_sponsor=Success');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
    }
}
?>

