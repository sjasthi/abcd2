<html>
<head>
    <link href="css/display_the_resource.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    include('header.php');
    include_once 'db_configuration.php';
    ?>

    <div class="container" style="margin-top: 60px;">
        <?php
        if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($db, $_GET['id']);
            $sql = "SELECT * FROM `nominations` WHERE id = " . $id;
            $GLOBALS['row_data'] = mysqli_query($db, $sql);
        }

        if ($row_data->num_rows > 0) {
            // fetch row_data from $_Globals
            while($row = $row_data->fetch_assoc()) {
                echo '<h3 class="title"> Category </h3><p class="words">' .$row["category"]. '</p>';
                echo '<h3 class="title"> Name </h3><p class="words">' .$row["name"]. '</p>';
                echo '<h3 class="title"> Description </h3><p class="words">' .$row["description"]. '</p>';
                echo '<h3 class="title"> Nominator </h3><p class="words">' .$row["nominator"]. '</p>';
            }
        } else {
            echo 'No data';
        }
        ?>
    </div>
</body>
</html>
