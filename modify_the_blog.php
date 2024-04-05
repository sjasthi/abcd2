<?php 
session_start();
include 'db_configuration.php';

if (isset($_POST['update_blog'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $video_link = $_POST['video_link'];


    $query = "UPDATE blogs
    SET Title = '$title',
        Author = '$author',
        Description = '$description',
        Video_Link = '$video_link'
    WHERE Blog_Id ='$id'";

    echo $query;

    $query_use = mysqli_query($db, $query);

    if ($query_use) {
        header('location:blogs.php?Updated=Success');

    } else {
        header('location:blogs.php?Updated=Failed&id='.$id);
    }

}

?>;