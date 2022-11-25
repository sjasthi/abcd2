<?php
ob_start();
session_start();
require 'bin/functions.php';
require 'db_configuration.php';
include('header.php');

$query = "SELECT id, CONCAT(first_name,' ',last_name) AS name, profile_picture, description, country, facebook, instagram, twitter, whatsapp, art_site, other FROM artists LEFT JOIN users ON id = user_id";
$GLOBALS['data'] = mysqli_query($db, $query);
ob_end_flush();
?>

<head>
    <title>ABCD</title>

    <link rel="stylesheet" href="css/artistShowcase.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f40040d297.js" crossorigin="anonymous"></script>
</head>
<body>
    <h2 class="artistTitle">Artist Showcase</h2>
    <div class="btnContainer">
        <button class="artistBtn"><a class="btn btn-sm" href="artistForm.php">Become an Affiliated Artist</a></button>
    </div>
    <div class="contentContainer">
    
    <?php

    if ($data->num_rows > 0) {
        $counter = 0;
        echo"<table>";
        while($row = $data->fetch_assoc()) {
            $id = $row["id"];
            $name = $row["name"];
            $profile_picture = $row["profile_picture"];
            $description = $row["description"];
            $country = $row["country"];
            $facebook = $row["facebook"];
            $instagram = $row["instagram"];
            $twitter = $row["twitter"];
            $whatsapp = $row["whatsapp"];
            $art_site = $row["art_site"];
            $other = $row["other"];

            
            if($counter == 0 || $counter % 2 == 0)
            {
                echo '<tr>';
            }

            echo"
                <td class='profile'>
                    <img src='images/profile_images/$profile_picture'>
                    <div class='aboutArtist'>
                        <div class='aboutArtistContent'>
                            <p><strong>About Me: </strong>$description</p>
                            <p><strong>My Work: </strong><a href='$art_site'>$art_site</a></p>

                            <span>

                            <a href='$facebook'>
                            <i class='fa-brands fa-facebook'></i>
                            </a>

                            <a href='$instagram'>
                            <i class='fa-brands fa-instagram'></i>
                            </a>

                            <a href='$twitter'>
                            <i class='fa-brands fa-twitter'></i>
                            </a>

                            <a href='$whatsapp'>
                            <i class='fa-brands fa-whatsapp'></i>
                            </a>
                        
                            </span>
                        </div>
                    </div>
                </td>
                ";

            $counter++;

            if($counter % 2 == 0) 
            {
                echo '</tr>';
            }
            
        }

        echo "</table>";
    }

    ?>
    </div>
</body>