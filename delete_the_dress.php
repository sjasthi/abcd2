<?php
ob_start();

include_once 'db_configuration.php';

if (isset($_POST['id'])){

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $file = mysqli_real_escape_string($db, $_POST['image_url']);

    unlink($file);

    $sql = "DELETE FROM dresses
            WHERE id = '$id'";

    mysqli_query($db, $sql);
    // Ask ABCD: remove the deleted entry from the chatbot's knowledge base
    require_once 'rag_functions.php';
    rag_sync_dress($db, (int)$id);
    header('location: list_dresses.php?dressDeleted=Success');
}//end if
?>

