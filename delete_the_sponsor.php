<?php
ob_start();
include_once 'db_configuration.php';

if (isset($_POST['sponsor_id'])){

    $id = mysqli_real_escape_string($db, $_POST['sponsor_id']);

    $sql = "DELETE FROM sponsors
            WHERE sponsor_id = '$id'";

    mysqli_query($db, $sql);
    header('location: sponsors.php?sponsorDeleted=Success');
}
?>
