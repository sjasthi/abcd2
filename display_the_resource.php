<html>

<style>
.head {
  font-family: "Times New Roman";
  color: darkgoldenrod;
  text-align: center;
}

.title {
  font-family: "Times New Roman";
  color: rgb(0,200,55);
  font-size: 1.5rem;
}

.words{
  font-family: "Times New Roman";
}

.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

.image {
    height: 300px;
    padding: 8px 8px 8px 8px;
}
</style>

<body>
<div class="container">
<?php
include('header.php');
include_once 'db_configuration.php';

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

      } else if($row["type"] == 'video') {
        print('<p class= "words"><iframe height="480" width="500"
        src="'.$row["url"].'"> 
       </iframe> ');

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
