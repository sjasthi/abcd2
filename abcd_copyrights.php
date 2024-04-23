<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($page_title)) {
        $page_title = 'Project ABCD';
    }
    include('header.php');

?>
<html>
    <body>
        <h1 style="text-align: center; margin: 100px;">TBD</h1>
    </body>
</html>