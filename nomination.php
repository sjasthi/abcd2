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
<div class="container">
    <style>
        #title {
            text-align: center;
            color: darkgoldenrod;
        }

        #guidance {
            color: grey;
            font-size: 10px;
        }
    </style>
    <form action="create_nomination.php" method="POST" enctype="multipart/form-data">
        <br>
        <br>
        
        <h3 id="title">Nominate</h3> <br>

        <div>
            <p>Please select the nomination type:</p>
            <div>
                <input type="radio" id="hero" name="nomination_type" value="Hero" required>
                <label for="hero">Hero</label>
            </div>
            <div>
                <input type="radio" id="shero" name="nomination_type" value="Shero" required>
                <label for="shero">Shero</label>
            </div>
        </div>
        <br>

        <div>
            <label>Name</label> <br>
            <input style=width:400px class="form-control" type="text" name="name" maxlength="100" size="50" required title="Please enter a name"></input>
        </div>

        <div>
            <label>Description</label> <br>
            <textarea style=width:400px class="form-control" name="description" cols="55" rows="5" required title="Please enter a description"></textarea>
        </div>

        <br><br>
        <div align="center" class="text-left">
            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Submit Nomination</button>
        </div>
        <br> <br>

    </form>
</div>

<script>
    var loadFile = function(event) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
</body>
</html>