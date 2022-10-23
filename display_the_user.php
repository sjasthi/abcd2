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
    $sql = "SELECT * FROM `users` WHERE id = " . $id;
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
} 
else if(isset($_GET['first_name'])) {

    $name = mysqli_real_escape_string($db, $_GET['first_name']);
    $sql = "SELECT * FROM `users` WHERE name = '" . $first_name ."'";
    $GLOBALS['row_data'] = mysqli_query($db, $sql);
}

if ($row_data->num_rows > 0) {
    // fetch row_data from $_Globals
    while($row = $row_data->fetch_assoc()) {
      print( '<h2 class= "head">'.$row["id"]. '</h2>'); 
      print( '<h3 class= "title"> First name: </h3><p class= "words" >'.$row["first_name"]. '</p>
              <h3 class= "title"> Last name: </h3><p class= "words" >'.$row["last_name"]. '</p>
              <h3 class= "title"> Email: </h3><p class= "words">' .$row["email"]. '</p>
              <h3 class= "title"> Role: </h3><p class= "words">' .$row["role"]. '</p>' );
    }
} else {
  print('no data');
}

?>
</div>
</body>
</html>
