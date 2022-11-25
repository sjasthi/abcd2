<html>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/display_the_dress.css">

<body class= "body">

<?php
include('header.php');
include_once 'db_configuration.php';

if (isset($_GET['fav_status'])) {

  $fav_status = mysqli_real_escape_string($db, $_GET['fav_status']);
  if ($fav_status == "COOKIE_NOT_FOUND")
  {
    echo "Cookie Not Found. Using the system's default";
  }
  if ($fav_status == "DRESS_NOT_FOUND")
  {
    echo "Dress Not Found. Using the system's default";
  }
}

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT * FROM `dresses` WHERE id = " . $id;
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
} 
else if(isset($_GET['name'])) {

    $name = mysqli_real_escape_string($db, $_GET['name']);
    $sql = "SELECT * FROM `dresses` WHERE name = '" . $name ."'";
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
}

if ($row_data->num_rows > 0) {
    // fetch row_data from $_Globals
    while($row = $row_data->fetch_assoc()) {
      print( '<div class ="containerTitle"><h2 class= "headTwo">'.$row["name"]. '</h2></div>');
      print( '<div class ="container">');
      echo("<div class='containerImage'><image class = 'image' src = images/dress_images/".$row["image_url"]. "></image>");  
      print( '</div>');  
      print( '<div class="containerText">
      <h3 class= "title"><strong> Description: </strong></h3><p class= "words" >'.$row["description"]. '</p>
      <h3 class= "title"><strong> Did You Know? </strong></h3><p class= "words">' .$row["did_you_know"]. '</p>
      <h3 class= "title"><strong> Category </strong></h3><p class= "words">' .$row["category"]. '</p>
      <h3 class= "title"><strong> Type </strong></h3><p class= "words">' .$row["type"]. '</p>
      <h3 class= "title"><strong> State Name </strong></h3><p class= "words">' .$row["state_name"]. '</p>
      <h3 class= "title"><strong> Key Words </strong></h3><p class= "words">' .$row["key_words"]. '</p>
      <h3 class= "title"><strong> Status </strong></h3><p class= "words">' .$row["status"]. '</p>
      <h3 class= "title"><strong> Notes </strong></h3><p class= "words">' .$row["notes"]. '</p></div>' );
      echo( '</div>');
    }
} else {
  print('no data');
}

?>
</body>
</html>
