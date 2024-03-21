<?php

  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }

  function fill_blog($db) {
    $MAX_POSTS = 5;

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
        if ($row["Video_Link"] != NULL) {
          $blog_link = '<a class="blog_link" href=' . $row["Video_Link"] . '> Video </a> </div>';
        } else {
          $blog_link = '</div>';
        }
        $picture_sql = "SELECT Location FROM blog_pictures WHERE Blog_Id = " . $row["Blog_Id"];
        $picture_locations = mysqli_query($db, $picture_sql);
        $blog_pictures = '';
        if ($picture_locations->num_rows > 0) {
          while($picture = $picture_locations->fetch_assoc()) {
            $blog_pictures = $blog_pictures . '<img class="blog_picture" src="'. $picture['Location'] . '"> <br>';
          }
        }
        $blog_body =
        '
        <div class="blog_post"  id="'. $row['Blog_Id'] . '">
          <h2 style="padding-top: 10px; font-weight: bold;">' . $row['Title'] . '</h2>
          <h5 style="text-align: left; padding-left: 10px;"> By: ' . $row['Author'] . '</h5>
          <p>' . $row['Created_Time'] . '</p>
          <p>' . nl2br($row['Description']) . '</p> <br>
        ';
        echo $blog_body.$blog_pictures.$blog_link;
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
        $TOC_Entry = '<li><a onclick="scrollToPost(\'' . $row['Blog_Id'] . '\')">' . $row['Title'] . '</a></li>';
        echo $TOC_Entry;
      }
    } else {
      echo "0 results";
    }
  }
  ?>