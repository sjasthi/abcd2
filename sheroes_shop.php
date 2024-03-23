<?php

    include('header.php');

?>
<html>
    <head>
        <link rel="stylesheet" href="css/shopOptions.css">
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/f40040d297.js" crossorigin="anonymous"></script>
    </head>
    
    <body>
        <div class="shopTitleContainer">
            <h1>Shop<h1>
            <i class="fa-solid fa-cart-shopping"></i>
        </div>  

        <div class="contentContainer container">
            <div class="col">
                <div class="linkContainer">
                    <a href="./abcd_shop.php">ABCD</a>
                    <a href="./artists_shop.php">Artists</a>
                    <a href="./redbubble_shop.php">Redbubble</a>
                    <a href="./threadless_shop.php">Threadless</a>
                    <a class="activeLink" href="./sheroes_shop.php">Sheroes</a>
                </div>
            </div>
            <div class="shoppingContainer">
                <div class="col">
                    <div class="row justify-content-center">   
                        <h2 class="shopHeader">Click this button to get your copy of SHEROES!</h2>
                    </div>
                    <div class="row justify-content-center">  
                        <button style="margin-top: 10px;" class="btn btn-lg btn-outline-success" type="button" onclick="alert('Coming Soon!')">Get my copy!</button>
                    </div>
                    <div class="row justify-content-center">
                        <img class="sheroCover" src="images/sheroesbook.png" alt="Sheroes Book Cover">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>