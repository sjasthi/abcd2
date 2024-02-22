<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}
#pulling in helper functions
require 'bin/functions.php';
require 'db_configuration.php';
include('header.php');

$page_title = 'Nominations';
$page = "nomination.php";
#checking if user is logged in and redirecting to login page if not logged in
verifyLogin($page);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>