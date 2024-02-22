<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}

require 'bin/functions.php';
require 'db_configuration.php';
include('header.php');

$page_title = 'Nomination > Create A Nomination';
$page = "nomination.php";
//Check if user is login
verifyLogin($page);

?>
<?php
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

        <h3 id="title">Create Your Nomination!</h3> <br>

        <div>
        <div>
            <p>Please select the nomination type:</p>
            <div>
                <input type="radio" id="hero" name="category" value="Hero" required>
                <label for="hero">Hero</label>
            </div>
            <div>
                <input type="radio" id="shero" name="category" value="Shero" required>
                <label for="shero">Shero</label>
            </div>
        </div>

        </div>
        <div>
            <label>Name</label> <br>
            <textarea style=width:400px class="form-control" name="name" cols="55" rows="5" required title="Please enter a Name"></textarea>
        </div>
        <div>
            <label>Description</label> <br>
            <textarea style=width:400px class="form-control" name="description" cols="55" rows="2" required title="Please enter a Description"></textarea>
        </div>
        <br><br>
        <div align="center" class="text-left">
            <input type= "hidden" id = "email" name ="nominator" value = <?php echo $_SESSION['email'] ?> required> 

            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Submit Nomination</button>
        </div>
        <br> <br>

    </form>
</div>


</body>
</html>