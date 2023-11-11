<?php

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

if(!isset($page_title)) { $page_title = 'Project ABCD'; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo htmlspecialchars($page_title);?></title>
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/publishersdb.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="css/header.css"> 
    

     <style>
        #header {
            color: darkgoldenrod;
        }

        .thumbnailSize {
            max-height: 100px; /* Limit the height */
            max-width: 100px; /* Limit the width */
            width: auto; /* Maintain the aspect ratio */
            height: auto; /* Maintain the aspect ratio */
        }
    </style>
    <script src="https://kit.fontawesome.com/f40040d297.js" crossorigin="anonymous"></script>
    <script>
        // When the user scrolls the page, execute myFunction
</script>

</head>
<body onload="displayAdminFields('admin1')">
<!-- Navigation -->
<nav class="navbar navbar-expand-lg sticky">
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <?php
        
        // if ($_COOKIE["home_view"] == "Carousel") {
        //         echo '<a href="carousel.php" title="SILC Project ABCD"><img src="images/about_images/abcd_logo.png"></a>' ;   
        //     } 
        //     else  {
                echo '<a href="index.php" title="SILC Project ABCD"><img src="images/about_images/abcd_logo2.png"></a>';
                if(isset($_SESSION['role'])){
                    echo '<div class="profileInfo">';
                    echo '<i class="fa-solid fa-user" id="userIcon"></i>';
                    echo "<p>".$_SESSION["first_name"]." ".$_SESSION["last_name"]. " / <i>".$_SESSION["role"]. "</i></p>";
                    echo '</div>';
                }
        //     }  
       
         ?>   
            
            <!-- Login / Logout Nav menu item
               Checks if there is a valid session and if so displays "logout"
               If there isn's a valid session display login. -->

            <!-- End Login / Logout Nav menu item -->
        </ul>
        <!--<form class="form-inline my-2 my-lg-0" action="index.php" method="POST">
            <input class="form-control mr-sm-2" type="search" type="text" name="search" placeholder="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit" name="submit-search"></i>Search</button>
        </form>-->
        <ul class="navbar-nav mr-right">
        <li class="nav-item">
          
        <?php
        
        if (isset($_SESSION['role'])){
            echo '<div class="search-container"><form action="./searchbar.php" method="POST"><input required type="text" placeholder="Search.." name="search"><button class="btn" type="submit"><i class="fa fa-search"></i></button></form></div>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="list_dresses.php">Dresses<span class="sr-only">(current)</span></a>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="artistShowcase.php">Artists<span class="sr-only">(current)</span></a>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="about.php">About<span class="sr-only">(current)</span></a></li>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="help.php">Help<span class="sr-only">(current)</span></a></li>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="shop.php">Shop<span class="sr-only">(current)</span></a></li>';
            // Yeliz: Go to the Book Form Added 
            // echo '<li class="nav-item active"><a class="nav-link" id="header" href="book_form.php" target="_blank">Go to the Book Form<span class="sr-only">(current)</span></a></li>';
            // Yeliz:  "Sponsors" option
            echo '<li class="nav-item"><a class="nav-link" id="header" href="sponsors.php" target="_blank">Sponsors</a></li>';


            
                if ($_SESSION['role'] == 'admin'){
                    echo '<li class="nav-item active"><a class="nav-link" id="header" href="admin.php">Admin<span class="sr-only">(current)</span></a></li>';
                }
            
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="logout.php">Logout<span class="sr-only">(current)</span></a></li>';

            } else {
            echo '<div class="search-container"><form action="./searchbar.php" method="POST"><input required type="text" placeholder="Search.." name="search"><button class="btn" type="submit"><i class="fa fa-search"></i></button></form></div>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="list_dresses.php">Dresses<span class="sr-only">(current)</span></a>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="artistShowcase.php">Artists<span class="sr-only">(current)</span></a>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="about.php">About<span class="sr-only">(current)</span></a></li>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="help.php">Help<span class="sr-only">(current)</span></a></li>';
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="shop.php">Shop<span class="sr-only">(current)</span></a></li>';
            // Yeliz:  "Sponsors" option
            echo '<li class="nav-item"><a class="nav-link" id="header" href="sponsors.php" target="_blank">Sponsors</a></li>'; 
            echo '<li class="nav-item active"><a class="nav-link" id="header" href="loginForm.php">Login<span class="sr-only">(current)</span></a></li>';
            }
            ?>
        </li>
        </ul>
    </div>
</nav>
