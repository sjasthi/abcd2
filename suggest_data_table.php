<?php
include('header.php');
include('db_configuration.php');

$conn = new mysqli("localhost", "root", "", "abcd_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM suggest";
$result = $conn->query($sql);

$itemsPerPage = 10;

// Check if the user has submitted a different value
if (isset($_POST['items_per_page'])) {
    $itemsPerPage = intval($_POST['items_per_page']);
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            text-align: center;
            min-height: 100vh;
        }

        #searchInput, #filterInput {
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%; /* Adjust the width as needed */
            margin: auto;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2; /* Add a background color for header row if needed */
            cursor: pointer;
        }

        img {
            max-width: 100px;
        }
        .flex-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flex-item {
            flex: 1;
            margin-right: 10px; /* Adjust as needed */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $("table").on("click", ".delete-button", function() {
                var row = $(this).closest("tr");
                var dressId = row.data("dress-id");

                // Confirm deletion
                if (confirm("Are you sure you want to delete this record?")) {
                    // Send AJAX request to delete.php with the dress ID
                    $.ajax({
                        type: "POST",
                        url: "delete_suggest_dress.php",
                        data: { dressId: dressId },
                        success: function(response) {
                            // Handle the response, e.g., remove the row from the table
                            if (response === "success") {
                                row.remove();
                            } else {
                                alert("Failed to delete record. Please try again.");
                            }
                        }
                    });
                }
            });

            $("th").click(function() {
                var table = $(this).parents("table").eq(0);
                var rows = table.find("tr:gt(0)").toArray().sort(compare($(this).index()));
                this.asc = !this.asc;
                if (!this.asc){ rows = rows.reverse(); }
                for (var i = 0; i < rows.length; i++){ table.append(rows[i]); }
            });

            function compare(index) {
                return function(a, b) {
                    var valA = getCellValue(a, index), valB = getCellValue(b, index);
                    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB);
                };
            }

            function getCellValue(row, index){
                return $(row).children('td').eq(index).text();
            }
        });

        
    </script>
</head>
<body>
    <br>
    <br>
    <br>
    <div class="container">
        <br><br><br><br>
        <br>
        <h2>Suggested Dresses</h2>
        <div class="flex-container">
            <div class="flex-item">
                <label>Search: </label>
                <input type="text" id="searchInput" placeholder="Name">
            </div>
            <div class="flex-item">
                <!-- Add your form here if needed -->
                <!-- Example form placeholder -->
                <form method="post">
                    Show: 
                    <select name="items_per_page" id="itemsPerPage" onchange="this.form.submit()">
                    <option value="5" <?php if ($itemsPerPage == 5) echo 'selected'; ?>>5</option>
                    <option value="10" <?php if ($itemsPerPage == 10) echo 'selected'; ?>>10</option>
                    <option value="20" <?php if ($itemsPerPage == 20) echo 'selected'; ?>>20</option>
                <!-- Add more options as needed -->
                        </select>
                </form>
            </div>
        </div>
        <table border="2">
            <tr>
                <th>Name</th>
                <th>References</th>
                <th>Image</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo "<tr data-dress-id='{$row['dress_id']}'>";
                    echo "<td>{$row['Name']}</td>";
                    echo "<td>{$row['References']}</td>";
                    echo "<td><img src='{$row['Dress_images']}' alt='Image' style='max-width: 100px;'></td>";
                    echo "<td>{$row['Email']}</td>";
                    echo "<td><button class='delete-button'>Delete</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No data available</td></tr>";
            }

            ?>
        
        </table>
    </div>
</body>
</html>
