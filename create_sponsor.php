<?php 
$page_title = 'Admin > Create New Sponsor';
require 'bin/functions.php';
require 'db_configuration.php';
include('header.php'); 
$page = "sponsors.php";
?>

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

    <form action="create_the_sponsor.php" method="POST" enctype="multipart/form-data">
        <br>
        <h3 id="title">Create New Sponsor</h3> <br>

        <div>
            <label>Name</label> <br>
            <input style="width:400px" class="form-control" type="text" name="name" required></input>
        </div>

        <div>
            <label>Type</label> <br>
            <input style="width:400px" class="form-control" type="text" name="type" required></input>
        </div>

        <div>
            <label>Logo (URL)</label> <br>
            <input style="width:400px" class="form-control" type="text" name="logo"></input>
        </div>

        <div>
            <label>Description</label> <br>
            <textarea style="width:400px" class="form-control" name="description" rows="4"></textarea>
        </div>

        <div>
            <label>Website URL</label> <br>
            <input style="width:400px" class="form-control" type="url" name="website_url"></input>
        </div>
         
        <br><br>
        <div align="center" class="text-left">
            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Create Sponsor</button>
        </div>
        <br> <br>
    </form>
</div>
