
<?php $page_title = 'Modify Users'; ?>
<?php $page_title = 'Project ABCD > Modify Users'; ?>
<?php 
    require 'bin/functions.php';
    require 'db_configuration.php';
    include('header.php'); 
    $page="users.php";
    //verifyLogin($page);
 
?>
<html>
<head>
<link href="css/modify_users.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
  
</head>

<div class="container">


<?php
include_once 'db_configuration.php';

if (isset($_GET['id'])){

    $id = $_GET['id'];
    
    $sql = "SELECT * FROM users
            WHERE id = '$id'";
    //echo $sql;
    if (!$result = $db->query($sql)) {
        die ('There was an error running query[' . $connection->error . ']');
    }//end if
}//end if

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      if(isset($_GET['modifyUser'])){
        if($_GET["modifyUser"] == "fileRealFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your image is not real, Please Try Again!</h3>';
        }
      }
      if(isset($_GET['modifyUser'])){
        if($_GET["modifyUser"] == "answerFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your answer was not one of the choices, Please Try Again!</h3>';
        }
      }
      if(isset($_GET['modifyUser'])){
        if($_GET["modifyUser"] == "fileTypeFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your image is not a valid image type (jpg,jpeg,png,gif), Please Try Again!</h3>';
        }
      }
      if(isset($_GET['modifyUser'])){
        if($_GET["modifyUser"] == "fileExistFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your image does not exist, Please Try Again!</h3>';
        }
      }
       // Obtain status value from DB and set as selected for drop down
      //$status_array=['proposed', 'approved', 'writeup_done' , 'art_work_done' , 'designed', 'completed'];
      //$res = $db->query($sql);
        //$r = $res->fetch_assoc();
      
        //$num = count($status_array);

        //for ($i=0 ; $i < $num ; $i++) {

         // if ($status_array[$i] == $r["status"]){
           // $status_array[$i] = "$status_array[$i]" .'"'. ' selected = "selected" ' ;
           // }
       // else $status_array[$i] = "$status_array[$i]";
       // }
    



      echo '<h2 id="title">Modify User</h2><br>';
      echo '<form action="modify_the_user.php" method="POST" enctype="multipart/form-data">
      <br>
      <h3 id="userName">'.$row["first_name"].' </h3> <br>
      
      <div>
        <label for="id">Id</label>
        <input type="text" class="form-control" name="id" value="'.$row["id"].'"  maxlength="5" style=width:400px readonly><br>
      </div>
      
      <div>
        <label for="name">First Name</label>
        <input type="text" class="form-control" name="first_name" value="'.$row["first_name"].'"  maxlength="255" style=width:400px required><br>
      </div>
      
      <div>
      <label for="name">Last Name</label>
      <input type="text" class="form-control" name="last_name" value="'.$row["last_name"].'"  maxlength="255" style=width:400px required><br>
    </div>
      
      <div>
      
        <label for="description">Email</label>
        <textarea style=width:400px class="form-control" name= "email" cols="55" rows="6" required>'.$row["email"].'</textarea>
        </div>

        <div>
        <label for="name">Role</label>
        <input type="text" class="form-control" name="role" value="'.$row["role"].'"  maxlength="255" style=width:400px required><br>
      </div>
        
          
       
       
       
      <div class="btnContainer">
          <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify User</button>
      </div>
      <br>

      <br> <br>
      
      </form>';
    
    }//end while
}//end if
else {
    echo "0 results";
}//end else

?>

</div>


