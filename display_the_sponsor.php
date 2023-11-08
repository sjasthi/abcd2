<html>

<head>
    <link href="css/display_the_user.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
</head>

<body>

<?php
include('header.php');
include_once 'db_configuration.php';
?>

<div class="sponsorContainer">
<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT * FROM `sponsors` WHERE sponsor_id = " . $id;
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
} 
else if(isset($_GET['name'])) {
    $name = mysqli_real_escape_string($db, $_GET['name']);
    $sql = "SELECT * FROM `sponsors` WHERE name = '" . $name ."'";
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
}

if ($row_data->num_rows > 0) {
    while($row = $row_data->fetch_assoc()) {
      print('<h2 class= "head">'.$row["sponsor_id"]. '</h2>'); 
      print('<h3 class= "title"> Name: </h3><p class= "words">'.$row["name"]. '</p>
             <h3 class= "title"> Type: </h3><p class= "words">'.$row["type"]. '</p>
             <h3 class= "title"> Logo: </h3><img src="'.$row["logo"].'" class="thumbnailSize">
             <h3 class= "title"> Description: </h3><p class= "words">' .$row["description"]. '</p>
             <h3 class= "title"> Website URL: </h3><a href="'.$row["website_url"].'" target="_blank" class= "words">' .$row["website_url"]. '</a>' );
    }
} else {
    print('no data');
}

?>
</div>
</body>
</html>
