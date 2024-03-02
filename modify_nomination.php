<?php $page_title = 'Modify Nomination'; ?>
<?php $page_title = 'Project ABCD > Modify Users'; ?> 
<?php 
    require 'bin/functions.php';
    require 'db_configuration.php';
    include('header.php'); 
    $page="manageNominations.php";
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
    
    $sql = "SELECT * FROM nominations
            WHERE id = '$id'";
    if (!$result = $db->query($sql)) {
        die ('There was an error running query[' . $connection->error. ']');
    }//end if
}//end if

if ($result-> num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      if(isset($_GET['modifyNomination'])){
        if($_GET["modifyNomination"] == "fileRealFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your image is not real, Please Try Again!</h3>';
        }
      }
      if(isset($_GET['modifyNomination'])){
        if($_GET["modifyUser"] == "answerFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your answer was not one of the choices, Please Try Again!</h3>';
        }
      }
      if(isset($_GET['modifyNomination'])){
        if($_GET["modifyNomination"] == "fileTypeFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your image is not a valid image type (jpg,jpeg,png,gif), Please Try Again!</h3>';
        }
      }
      if(isset($_GET['modifyNomination'])){
        if($_GET["modifyNomination"] == "fileExistFailed"){
            echo '<br><h3 align="center" class="bg-danger">FAILURE - Your image does not exist, Please Try Again!</h3>';
        }
      }
    
      echo '<h2 id="title">Modify Nomination</h2><br>';
      echo '<form action= "modify_the_nomination.php" method="POST" enctype="multipart/form-data">
      <br>
      
      <div>
        <label for="id">Id</label>
        <input type="text" class="form-control" name="id" value="'.$row["id"].'"  maxlength="5" style=width:400px readonly><br>
      </div>

      <div>
        <label for="category">Category</label>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <div class="form-check-input">
                    <input type="radio" @if (.$row["category"] == "Hero") checked  @endif id="Hero" name="category" value="Hero"  required>
                    <label class="form-check-label" for="hero">Hero</label>
                </div>
            </div>
            <div class="form-check form-check-inline">
                <div class="form-check-input">
                    <input type="radio" @if (.$row["category"] == "Shero") checked  @endif id="Shero" name="category" value="Shero" required>
                    <label class="form-check-label" for="shero">Shero</label>
                </div>
            </div>
            <div class="form-check form-check-inline">
                <div class="form-check-input">
                    <input type="radio" @if (.$row["category"] == "Other") checked  @endif id="Other" name="category" value= "Other" required>
                    <label class="form-check-label" for="other">Other</label>
                </div>
            </div>
        </div>
        
      </div>

      <div>
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" value="'.$row["name"].'"  maxlength="255" style=width:400px required><br>
      </div>

      <div>
        <label for="description">description</label>
        <textarea style=width:400px class="form-control" name= "description" cols="55" rows="6" required>'.$row["description"].'</textarea>
        </div>

      <div class="btnContainer">
          <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Nomination</button>
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
