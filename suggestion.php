<?php
$status = session_status();
if($status == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}

include('header.php');
include('db_configuration.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orange Button</title>
  <style>
    /* Style for the container */
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh; /* 100% of the viewport height */
      margin: 0;
    }

    /* Style for the orange button */
    .orange-button {
      padding: 10px 20px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
      background-color: orange;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Style for hover effect (optional) */
    .orange-button:hover {
      background-color: darkorange;
    }
  </style>
</head>
<body>

  <!-- HTML button element with the orange-button class -->
  <a href="suggestion_form.php" class="orange-button">Suggest</a>
</body>
</html>