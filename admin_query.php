<?php

use PhpOffice\PhpPresentation\Shape\Chart\Type\Bar;

ob_start();
session_start();

if ($_SESSION['role'] != 'admin'){
    header('Location: index.php');
    exit;
}

require 'bin/functions.php';
require 'db_configuration.php';
require 'api/objects/dress.php';
include('header.php');

ob_end_flush();

$result = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $type = $_POST['type'];
    $keywords = $_POST['keywords'];
    
    
    $result = Dress::getByCategoryAndTypeAndKeyword(array($category), array($type), array($keywords));
}
?>

<html>
<head>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f40040d297.js" crossorigin="anonymous"></script>
</head>
<body>
    <div style="padding: 50px;">
        <form method="POST" action="admin_query.php">
            <label for="category">Category:</label>
            <input type="text" name="category">

            <label for="type">Type:</label>
            <select name="type">
                <option value="boy">Boy</option>
                <option value="women">women</option>
                <option value="other">Other</option>
            </select>

            <label for="keywords">Keywords:</label>
            <input type="text" name="keywords">

            <button type="submit">Search</button>
        </form>
    </div>

    <div style="padding: 50px;">
    <textarea readonly id="resbox">
    <?php if (!empty($result)) { 
        foreach($result as $dress){ 
          echo ($dress->id . ", ");
    }
}

         ?>
         </textarea>
        </div>
</body>
</html>
