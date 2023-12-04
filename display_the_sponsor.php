<?php
// Start the session at the very beginning
session_start();

// Include necessary files
include('header.php');
include_once 'db_configuration.php';

// Initialize SQL to an empty string
$sql = "";

// Check if 'id' or 'name' is set in GET request and prepare SQL accordingly
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT * FROM `sponsors` WHERE sponsor_id = $id";
} elseif(isset($_GET['name'])) {
    $name = mysqli_real_escape_string($db, $_GET['name']);
    $sql = "SELECT * FROM `sponsors` WHERE name = '$name'";
}

// Initialize $row_data variable
$row_data = null;

// Execute SQL query if $sql is not empty
if (!empty($sql)) {
    $row_data = mysqli_query($db, $sql);
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Head content including stylesheets and fonts -->
</head>
<body>
    <!-- Navigation and other body content -->
    
    <div class="ribbon">Sponsor Information</div>
    <div class="sponsorContainer">
        <?php
        // Check if $row_data is set and has rows
        if (isset($row_data) && $row_data->num_rows > 0) {
            while($row = $row_data->fetch_assoc()) {
                ?>
                <div class="sponsorSection">
                    <div class="logoContainer">
                        <img src="<?php echo $row["logo"]; ?>" class="thumbnailSize">
                    </div>
                    <div class="textContainer">
                        <h3 class="title">Name:</h3>
                        <p class="words"><?php echo $row["name"]; ?></p>
                        <h3 class="title">Type:</h3>
                        <p class="words"><?php echo $row["type"]; ?></p>
                        <h3 class="title">Description:</h3>
                        <p class="words"><?php echo $row["description"]; ?></p>
                        <h3 class="title">Website URL:</h3>
                        <a href="<?php echo $row["website_url"]; ?>" target="_blank" class="words">
                            <?php echo $row["website_url"]; ?>
                        </a>
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


