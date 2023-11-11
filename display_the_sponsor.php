<!DOCTYPE html>
<html>
<head>
    <!-- <link href="css/display_the_user.css" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
    <link href="css/display_the_sponsor.css" rel="stylesheet">
</head>

<body>
    <?php
    include('header.php');
    include_once 'db_configuration.php';
    ?>

    <div class="ribbon">Sponsor Information</div>
    <div class="sponsorContainer">
        <?php
        if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($db, $_GET['id']);
            $sql = "SELECT * FROM `sponsors` WHERE sponsor_id = " . $id;
        } else if(isset($_GET['name'])) {
            $name = mysqli_real_escape_string($db, $_GET['name']);
            $sql = "SELECT * FROM `sponsors` WHERE name = '" . $name . "'";
        }

        $GLOBALS['row_data'] = mysqli_query($db, $sql);

        if ($row_data->num_rows > 0) {
            while($row = $row_data->fetch_assoc()) {
                ?>
                <div class="sponsorSection">
                    <div class="logoContainer"><img src="<?php echo $row["logo"]; ?>" class="thumbnailSize"></div>
                    <div class="textContainer">
                        <h3 class="title">Name:</h3><p class="words"><?php echo $row["name"]; ?></p>
                        <h3 class="title">Type:</h3><p class="words"><?php echo $row["type"]; ?></p>
                        <h3 class="title">Description:</h3><p class="words"><?php echo $row["description"]; ?></p>
                        <h3 class="title">Website URL:</h3><a href="<?php echo $row["website_url"]; ?>" target="_blank" class="words"><?php echo $row["website_url"]; ?></a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo 'No data';
        }
        ?>
    </div>
    <!-- Footer -->
    <footer class="page-footer text-center">
        <p>Created for FA23 ICS 499 ♡</p>
    </footer>
</body>
</html>

