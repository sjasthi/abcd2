<?php 
include_once 'db_configuration.php';

if (isset($_POST['id'])){
    $id = mysqli_real_escape_string($db, $_POST['id']);;
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
}

echo $id;
echo $category;
echo $name;
echo $description;


    $sql = "UPDATE nominations
    SET category = '$category',
        name = '$name',
        description = '$description'
    Where id ='$id'";

echo $sql;

mysqli_query($db, $sql);

header('location:manageNominations.php?Updated=Success');


?>;