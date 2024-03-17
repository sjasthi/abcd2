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
  <body>
    <header class="inverse">
      <div class="container">
        <h1><span class="accent-text">Blog</span></h1>
      </div>
    </header>
    <script>
      function show_form(){
        var form = document.getElementById("blog_creation_form");
        var show_button = document.getElementById("form_show_button");
        form.removeAttribute("hidden");
        show_button.setAttribute("hidden", "hidden");
      }
    </script>
      <?php
        if (isset($_SESSION['role'])) {
            echo '<button id="form_show_button" onclick="show_form();">Create Post</button>';
        }
      ?>
      <form id="blog_creation_form" action="create_blog_post.php" method="POST" enctype="multipart/form-data" hidden="hidden">
        <div id=blog_creation_left>
          <label>Blog Title</label>
          <br>
          <input type="text" name="title" maxlength=100 required>
          <br>
          <label for="description">Description</label>
          <br>
          <textarea name="description" rows=9 cols=50 required></textarea>
        </div>
        <div id=blog_creation_right>
          <label for="author">Author</label>
          <br>
          <input type="text" name="author" maxlength=50 required>
          <br>
          <label>Image(s)</label>
          <br>
          <input type="file" name="file[]" accept="image/*" multiple="multiple">
          <br>
          <label>Video Link</label>
          <br>
          <input type="text" name="video_link" maxlength=100 placeholder="Optional">
        </div>
        <br>
        <input type="submit" name="create_post" value="Publish">
      </form>
      <div>
        <div id="blog_TOC">
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