<?php
session_start();
$status = session_status();
if($status == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}

include('header.php');
include('db_configuration.php');

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

// Retrieve user information from the session
$email = $_SESSION["email"];
$validate = true; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assuming form fields are submitted via POST
  $name = $_POST["name"];
  $references = $_POST["references"];
  $email = $_POST["email"];

  // Handle image upload (you may need to implement proper file handling)
  $imagePath = 'images/' . $_FILES['image']['name'];
  move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

  // Store data in the database
  $conn = new mysqli("localhost", "root", "", "abcd_db");

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "INSERT INTO `suggest` (`Name`, `References`, `Dress_images`, `Email`) VALUES (?, ?, ?, ?)";

  
  $stmt = $conn->prepare($sql);
  
  $stmt->bind_param("ssss", $name, $references, $imagePath, $email);

  if ($stmt->execute()) {
      $_SESSION['success_message'] = 'Data inserted successfully.';
  } else {
      $_SESSION['error_message'] = 'Error inserting data: ' . $stmt->error;
  }

  header('location: suggest_data_table.php');
  
  $stmt->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggested Dresses</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    form {
      max-width: 400px;
      margin: 0 auto;
    }

    label {
      display: block;
      margin-bottom: 8px;
    }

    input, select {
      width: 100%;
      padding: 8px;
      margin-bottom: 16px;
      box-sizing: border-box;
    }

    button {
      background-color: orange;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: darkorange;
    }
  </style>
</head>
<body>
  <br>
  <br>
  <br>
  <?php
    if (isset($_SESSION['success_message'])) {
        echo '<p style="color: green;">' . $_SESSION['success_message'] . '</p>';
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>
  <form action="suggestion_form.php" method="post" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" placeholder="Enter your name" required>

    <label for="references">References:</label>
    <textarea id="references" name="references" rows="4" required></textarea>

    <label for="image">Upload Image:</label>
    <input type="file" id="image" name="image" accept="image/*" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

    <button type="submit">Submit</button>
  </form>

</body>
</html>