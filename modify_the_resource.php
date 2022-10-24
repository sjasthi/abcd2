<?php

include_once 'db_configuration.php';

if (isset($_POST['id'])){

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    //$description = mysqli_real_escape_string($db, $_POST['description']);
    //$did_you_know = mysqli_real_escape_string($db, $_POST['did_you_know']);
    //$category = mysqli_real_escape_string($db, $_POST['category']);
    $type = mysqli_real_escape_string($db, $_POST['type']);
    //$state_name = mysqli_real_escape_string($db, $_POST['state_name']);
    //$key_words = mysqli_real_escape_string($db, $_POST['key_words']);
   //$oldimage = mysqli_real_escape_string($db, $_POST['oldimage']);
    //$imageName = basename($_FILES["fileToUpload"]["name"]);
    $validate = true;
    //$status = mysqli_real_escape_string($db, $_POST['status']);
    $notes = mysqli_real_escape_string($db, $_POST['notes']);

    $url = mysqli_real_escape_string($db, $_POST['url']);
    //$validate = emailValidate($answer);
    //echo 'name '+$name;
    echo "name" . $name;
    echo "notes" . $notes;
    echo "type" . $type;
    echo "url" . $url;
    if($validate){
        $sql = "UPDATE resources
        SET name = '$name',
                notes = '$notes',
                type = '$type',
                url = '$url'           
        WHERE id = '$id'";
        echo "\nsql " . $sql;
        mysqli_query($db, $sql);
        
        header('location: resources.php?dressUpdated=Success');
        
    }else{
        header('location: modify_dress.php?modify_dress=answerFailed&id='.$id);}
}//end if
?>