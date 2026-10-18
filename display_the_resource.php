<html>

<head>
<link href="css/display_the_resource.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
  
</head>

<body>

<?php
include('header.php');
include_once 'db_configuration.php';
require_once 'youtube_helpers.php'; // Issue #68: shared YouTube player helper
?>

<div class="container">

<?php

if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($db, $_GET['id']);
    $sql = "SELECT * FROM `resources` WHERE id = " . $id;
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
} else if(isset($_GET['name'])) {

    $name = mysqli_real_escape_string($db, $_GET['name']);
    $sql = "SELECT * FROM `resources` WHERE name = '" . $name ."'";
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
}

if ($row_data->num_rows > 0) {
    // fetch row_data from $_Globals
    while($row = $row_data->fetch_assoc()) {
      print( '<h2 class= "head">'.$row["name"]. '</h2>');
      print( '
              <h3 class= "title"> Dress Name </h3><p class= "words">' .$row["name"]. '</p>
              <h3 class= "title"> Type </h3><p class= "words">' .$row["type"]. '</p>');
              print('<h3 class= "title"> Url </h3><p class= "words">' 
              .'<a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a>' . '</p>');
      if(strcasecmp($row["type"], 'image') == 0) {
         print('<p class= "words">' 
         .'<img src="'.$row["url"].'"style="width:50%">'. '</p>');

      } else if(strcasecmp($row["type"], 'video') == 0) {
        // Issue #68: YouTube only plays "/embed/" addresses inside a frame, so a normal
        // "watch?v=" or "youtu.be" link used to show an error. youtube_embed_html()
        // converts any YouTube link into a working player.
        $player = youtube_embed_html($row["url"], $row["name"]);
        if ($player !== '') {
          print('<div class= "words">' . $player . '</div>');
        } else {
          // Not a YouTube link: keep the previous behavior (frame the URL as-is).
          print('<p class= "words"><iframe height="480" width="500"
          src="'.htmlspecialchars($row["url"], ENT_QUOTES, 'UTF-8').'">
         </iframe></p>');
        }
      }
      print('<h3 class= "title"> Notes </h3><p class= "words">' .$row["notes"]. '</p>' );
    }
} else {
  print('no data');
}

?>
</div>
</body>
</html>
