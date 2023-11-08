<?php 
    $page_title = 'Project ABCD > Modify Sponsor'; 
    require 'bin/functions.php';
    require 'db_configuration.php';
    include('header.php'); 
?>

<html>
<head>
    <link href="css/modify_users.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">  
</head>

<div class="container">
    <?php
        include_once 'db_configuration.php';

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM sponsors WHERE sponsor_id = '$id'";

            if (!$result = $db->query($sql)) {
                die('There was an error running query[' . $db->error . ']');
            }
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<h2 id="title">Modify Sponsor</h2><br>';
                echo '<form action="modify_the_sponsor.php" method="POST" enctype="multipart/form-data">
                <br>
                <h3 id="sponsorName">'.$row["name"].' </h3> <br>
                <div>
                    <label for="sponsor_id">ID</label>
                    <input type="text" class="form-control" name="sponsor_id" value="'.$row["sponsor_id"].'" readonly><br>
                </div>
                <div>
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="'.$row["name"].'" required><br>
                </div>
                <div>
                    <label for="type">Type</label>
                    <input type="text" class="form-control" name="type" value="'.$row["type"].'"><br>
                </div>
                <div>
                    <label for="logo">Logo</label>
                    <input type="text" class="form-control" name="logo" value="'.$row["logo"].'"><br>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description">'.$row["description"].'</textarea>
                </div>
                <div>
                    <label for="website_url">Website URL</label>
                    <input type="url" class="form-control" name="website_url" value="'.$row["website_url"].'"><br>
                </div>
                <div class="btnContainer">
                    <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Sponsor</button>
                </div>
                <br>
                </form>';
            }
        } else {
            echo "0 results";
        }
    ?>
</div>
