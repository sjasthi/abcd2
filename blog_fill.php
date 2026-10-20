<?php

  // Shared YouTube helpers (Issue #68): turns any YouTube link into a working player.
  require_once 'youtube_helpers.php';

  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }

  function fill_blog($db) {
    $MAX_POSTS = 10;

    $sql = "SELECT * FROM blogs ORDER BY Created_Time DESC";
    $result = mysqli_query($db, $sql);
    // Create Post from data from each row
    if ($result->num_rows > 0) {
      $number_of_posts = 0;
      $number_of_pages = 1;
      echo '<div class="blog_page" id="page'. $number_of_pages . '">';
      while($row = $result->fetch_assoc()) {

        #create new page when the posts-per-page has been reached
        if ($number_of_posts == $MAX_POSTS) {
          $number_of_pages += 1;
          echo '
            </div>
            <div class="blog_page" id="page'. $number_of_pages . '" hidden="hidden">
            ';
          $number_of_posts = 0;
        }
        // Build a YouTube player for each of the (up to three) video links on the post.
        // youtube_embed_html() returns '' when a field is empty or not a YouTube link,
        // so posts without videos are unaffected. (Issue #68: the old code used an
        // <embed type="video/mp4"> tag, which cannot play YouTube, so videos were blank.)
        $blog_video  = youtube_embed_html($row["Video_Link"],  $row["Title"] . ' - video 1');
        $blog_video2 = youtube_embed_html($row["Video_Link2"], $row["Title"] . ' - video 2');
        $blog_video3 = youtube_embed_html($row["Video_Link3"], $row["Title"] . ' - video 3');
        $picture_sql = "SELECT Location FROM blog_pictures WHERE Blog_Id = " . $row["Blog_Id"];
        $picture_locations = mysqli_query($db, $picture_sql);
        $blog_pictures = '<div class="d-flex flex-wrap justify-content-center">';
        if ($picture_locations->num_rows > 0) {
          while($picture = $picture_locations->fetch_assoc()) {
            $blog_pictures = $blog_pictures . '<img class="blog_picture" src="'. $picture['Location'] . '"> <br>';
          }
        }
        $blog_pictures = $blog_pictures . '</div>';

        $blog_body =
        '
        <div class="blog_post"  id="'. $row['Blog_Id'] . '">
          <h2 style="padding-top: 10px; font-weight: bold;">' . $row['Title'] . '</h2>
          <h5 style="text-align: left; margin-left: 20px;"> By: ' . $row['Author'] . '</h5>
          <p>' . $row['Created_Time'] . '</p>
          <p>' . nl2br($row['Description']) . '</p> <br>
        ';

        $blog_admin_buttons = '
        <div style="margin-top: 8px;">
          <a class="btn btn-warning btn-sm" href="modify_blog.php?id='.$row["Blog_Id"].'">Modify</a>
          <a class="btn btn-danger btn-sm" href="delete_blog.php?id='.$row["Blog_Id"].'" onclick="return confirm(\'Are you sure you would like to delete this blog post?\');">Delete</a> <br><br>
        </div>
        ';
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
          echo $blog_body.$blog_pictures.$blog_video.$blog_video2.$blog_video3.$blog_admin_buttons.'</div>';
        } else {
          echo $blog_body.$blog_pictures.$blog_video.$blog_video2.$blog_video3.'</div>';
        }
        $number_of_posts += 1;
      }
      echo '</div>';
    } else {
      echo "0 results";
    }
  }

  function fill_TOC($db) {
    $sql = "SELECT Blog_Id, Title FROM blogs ORDER BY Created_Time DESC";
    $result = mysqli_query($db, $sql);

    if ($result->num_rows > 0) {
      // Place title in TOC
      while($row = $result->fetch_assoc()) {
        $TOC_Entry = '<li class="list-group-item list-group-item-action" onclick="scrollToPost(\'' . $row['Blog_Id'] . '\')">' . $row['Title'] . '</a></li>';
        echo $TOC_Entry;
      }
    } else {
      echo "0 results";
    }
  }
  ?>