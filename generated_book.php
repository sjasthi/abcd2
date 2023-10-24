<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request
    echo 'This script should only be accessed through a POST request.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request
    if (isset($_POST['dress_numbers'])) {
        // Split the received dress numbers into an array
        $dressNumbers = $_POST['dress_numbers'];
        $dressNumbersArray = explode(',', $dressNumbers);

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "abcd_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Construct the SQL query to select dresses with matching IDs
        $sql = "SELECT * FROM dresses WHERE id IN (" . implode(',', $dressNumbersArray) . ")";
        $result = $conn->query($sql);

		echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Generated Book</title>
            
            <!-- Include the CSS stylesheet -->
            <link rel="stylesheet" href="css/display_the_dress.css">
        </head>
        <body>
        ';


        if ($result->num_rows > 0) {
            echo '<ul>';

            while ($row = $result->fetch_assoc()) {
				$name = $row["name"];
				$description = $row["description"];
				$did_you_know = $row["did_you_know"];
				$image = $row["image_url"];
				$ID = $row["id"];

				//Dress Name
				echo '<div class="title" style="text-align: center;">';
				echo '<li>';
				echo '<span style="text-transform: uppercase; color: #000080; height: 100px; font-size: 70px; font-weight: bold;">' . $name . '</span><br>';
				echo '</li>';
				echo '</div>';

				echo '<div class="container" style="display: flex; width: 80%; max-width: 1400px; margin: 0 auto;">';

				//Dress Image
				if (file_exists("images/dress_images/" . $image)) {
					echo '<img src="' . "images/dress_images/" . $image . '" style="max-width: 30%; height: auto; display: block;">';
				} else {
					echo 'Image not found<br>';
				}

				echo '<div>';
				//Dress ID
				echo 'ID: ' . $ID . '<br>';
				//Dress Description
				echo '<span style="color: blue; font-size: 28px">Description:</span><br>';
				echo '<span style="flex: 1; padding: 10px; font-size: 28px;">' . $description . '</span><br><br>';
				echo '<br>';
				//Dress Did_You_Know
				echo '<span style="color: blue; font-size: 28px">Did You Know?</span><br>';
				echo '<span style="flex: 1; padding: 10px; font-size: 28px;">' . $did_you_know . '</span>';
				echo '</div>';

				echo '</div>';
				echo '</li>';
				//Page Break
				echo '<p style="page-break-before: always">';
			}


            echo '</ul>';
        } else {
            echo 'No data found for the provided dress numbers.';
        }

        $conn->close();
    } else {
        echo 'Dress numbers not provided.';
    }
} else {
    // Handle other request methods
    echo 'Unsupported request method.';
}
?>


