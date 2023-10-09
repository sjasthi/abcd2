<?php
$status = session_status();
if($status == PHP_SESSION_NONE){
    //There is no active session
    session_start();
}

include('header.php');
$page_title = 'Project ABCD > Help'; 
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <meta charset="UTF-8">
    <title>Book Form Help Page</title>
    <br><br>
    <style>
        body {
            
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            font-weight: 800;
            color: #fc4103
            
        }
        h2 {
            font-size: 20px;
            text-align: center;
            color: #fc4103;
            font-weight: 600
        }
        h3 {
            font-size: 16px;
            text-align: center;
        }
        p {
            font-size: 18px;
            text-align: center;
        }
        ul {
            list-style-type: decimal;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Book Form Help Page</h1>
    <br><br>
    
    <h2>Dress Numbers</h2>
    <p>Dress numbers refer to your desired selection. For ex. Dress 1 = 1, Dress 100 = 100.</p>

    <h2>Layout</h2>
    <p>Layout refers to the arrangement and organization of your information being displayed in PDF format.</p>

    <h2>Sort Order</h2>
    <p>Sort order determines how items are arranged, for example, in alphabetical or order input and ID.</p>

    <h2>Text/Title/Subtitle Size</h2>
    <p>Title size specifies the font size for titles or headings on the page.</p>

    <h2>Text/Title/Subtitle Font</h2>
    <p>Text font allows you to choose the typeface or font style for the main text on the page.</p>

    <h2>Picture Width and Height</h2>
    <p>Pic width and pic height control the dimensions of images or pictures on the page.</p>

    <h2>Numbering</h2>
    <p>Numbering determines how items are labeled or numbered, such as using numbers, letters, or bullets.</p>

    <h2>Translate To</h2>
    <p>Allowing for multiple langauge translations depending on Google API capabilities.</p>
</body>
</html>