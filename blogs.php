<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}

//require 'bin/functions.php';
require 'db_configuration.php';
include('header.php');
require 'blog_fill.php';

$page_title = 'Project ABCD2 Blog';
//$page = "blogs.php";
//Check if user is login
//verifyLogin($page);
?>

<!DOCTYPE html>
<html>
    <script type="text/javascript" src="js/blog_functions.js"></script>
    <title>Project ABCD2 Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
    <link href="css/blog.css" rel="stylesheet">

    <script>
      function show_form() {
        var form = document.getElementById("blog_creation_form");
        var show_button = document.getElementById("form_show_button");
        form.removeAttribute("hidden");
        show_button.setAttribute("hidden", "hidden");
      }
      function hide_form() {
        var form = document.getElementById("blog_creation_form");
        var show_button = document.getElementById("form_show_button");
        form.setAttribute("hidden", "hidden");
        show_button.removeAttribute("hidden");
      }

    </script>

  <body>
    <div class="heading">
      <div class="container">
        <h1><span class="accent-text">Blog</span></h1>
      </div>
    </div>

      <?php
        if (isset($_SESSION['role'])) {
            echo '<button id="form_show_button" onclick="show_form();">Create Post</button>';
        }
      ?>


    <div class="card blog-form-creation-container">
      <form id="blog_creation_form" action="create_blog_post.php" method="POST" enctype="multipart/form-data" hidden="hidden">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="title">Blog Title</label>
            <input type="text" name="title" class="form-control" maxlength=100 placeholder="Blog Title..." required>
          </div>
          <div class="form-group col-md-6">
            <label for="author">Author</label>
            <input type="text" name="author" class="form-control" maxlength=50 placeholder="Author Name..." required>
          </div>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
            <textarea name="description" class="form-control" rows=9 cols=50 placeholder="Enter your description..." required></textarea>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Image(s)</label>
            <input type="file" name="file[]" class="form-control btn" accept="image/*" multiple="multiple">
          </div>
          <div class="form-group col-md-6">
            <label>Video Link</label>
            <input type="text" name="video_link" class="form-control" maxlength=100 placeholder="Optional">
          </div>
        </div>
        <div>
          <button type="submit" name="create_post" value="Publish" class="btn btn-md">Publish</button>
          <button id="cancel-button" type="button" onclick="hide_form();" class="btn btn-secondary btn-md">Cancel</button>
        </div>
      </form>
    </div>


      <div>
        <div id="blog_TOC" class="sticky-top" style="top: 120px;">
          <h3 id="TOC_title">Table of Contents</h3>
          <ul>
            <?php fill_TOC($db); ?>
          </ul>
        </div>
        <?php fill_blog($db);
         ?>
        <div id="blog_buttons">
          <button id="blog_previous" onclick="handlePageButton('previous')" hidden="hidden">Previous</button>
          <button id="blog_next" onclick="handlePageButton('next')">Next</button>
      </div>

  </body>
</html>