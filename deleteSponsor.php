<?php 
$page_title = 'Project ABCD > Delete Sponsor';
require 'bin/functions.php';
require 'db_configuration.php';
include('header.php'); 
$page="sponsors.php";
?>
<div class="container">
    <style>
        #title {text-align: center; color: darkgoldenrod;}
        .thumbnailSize{
            height: 100px;
            width: 100px;
            transition:transform 0.25s ease;
        }
        .thumbnailSize:hover {
            -webkit-transform:scale(3.5);
            transform:scale(3.5);
        }
    </style>

    <?php
    if (isset($_GET['id'])){

        $id = $_GET['id'];

        $sql = "SELECT * FROM sponsors
                WHERE sponsor_id = '$id'";

        if (!$result = $db->query($sql)) {
            die ('There was an error running the query[' . $db->error . ']');
        }
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<form action="delete_the_sponsor.php" method="POST">
                <br>
                <h3 id="title">Delete Sponsor?</h3><br>

                <div class="form-group col-md-4">
                  <label for="sponsor_id">ID</label>
                  <input type="text" class="form-control" name="sponsor_id" value="'.$row["sponsor_id"].'"  maxlength="5" readonly>
                </div>

                <div class="form-group col-md-8">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="name" value="'.$row["name"].'" maxlength="255" readonly>
                </div>
                
                <!-- Add other fields if necessary -->

                <br>
                <div class="text-left">
                    <button type="submit" name="submit" class="btn btn-danger btn-md align-items-center">Delete</button>
                </div>
                <br> <br>

                </form>';

        }
    } else {
        echo "0 results";
    }
    ?>
</div>
