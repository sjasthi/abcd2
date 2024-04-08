<?php 
$page_title = 'Modify Artist';
require 'bin/functions.php';
require 'db_configuration.php';
include('header.php'); 
$page = "artistEdit.php";
//verifyLogin($page);
?>

<html>
<head>
    <link href="css/modify_users=.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
</head>

<div class="container">
<?php
include_once 'db_configuration.php';

if (isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "SELECT id, CONCAT(first_name,' ',last_name) AS name, profile_picture, description, country, facebook, instagram, twitter, whatsapp, art_site, other FROM artists LEFT JOIN users ON id = $id";;
    if (!$result = $db->query($sql)) {
        die ('There was an error running query[' . $connection->error. ']');
    }
}

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
?>
        <h2 id="title">Modify Artist</h2><br>
        <form action="modify_the_artist.php" method="POST" enctype="multipart/form-data">
            <br>
            <div>
                <label for="id">Id</label>
                <input type="text" class="form-control" name="id" value="<?php echo $row["id"]; ?>" maxlength="5" style="width:400px" readonly><br>
            </div>
            <div>
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["name"]; ?>" maxlength="255" style="width:400px" required><br>
            </div>
            <div>
                <label for="description">Description</label>
                <textarea style="width:400px" class="form-control" name="description" cols="55" rows="6" required><?php echo $row["description"]; ?></textarea>
            </div>
            <div>
                <label for="country">Country</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["country"]; ?>" maxlength="255" style="width:400px" required><br>            
            </div>
            <div>
                <label for="facebook">Facebook</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["facebook"]; ?>" maxlength="255" style="width:400px" required><br>            
            </div>
            <div>
                <label for="instagram">Instagram</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["instagram"]; ?>" maxlength="255" style="width:400px" required><br>            
            </div>
            <div>
                <label for="twitter">Twitter</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["twitter"]; ?>" maxlength="255" style="width:400px" required><br>            
            </div>
            <div>
                <label for="whatsapp">WhatsApp</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["whatsapp"]; ?>" maxlength="255" style="width:400px" required><br>            
            </div>
            <div>
                <label for="artist_site">Artist Site</label>
                <input type="text" class="form-control" name="name" value="<?php echo $row["art_site"]; ?>" maxlength="255" style="width:400px" required><br>            
            </div>
            <div>
            <?php echo '<td><img src="images/profile_images/'.$row["profile_picture"].'" style="width:100px;height:120px;">' ?>

            <div class="form-group col-md-4">
                <label for="profile_picture"> Choose a file to change above image (Optional)</label>
                <input type="file" name="fileToUpload" id="fileToUpload" maxlength="255">
            </div>
            </div>

            <div class="btnContainer">
                <button type="submit" name="update_artist" class="btn btn-primary btn-md align-items-center">Modify Artist</button>
            </div>
            <br>
            <br>
        </form>
<?php
    }
} else {
    echo "0 results";
}
?>
</div>
</html>
