<?php
/*
 * gallery.php
 * Public Gallery page for Project ABCD2.
 *
 *  - Visitors see every picture and video in a grid, can filter to All / Photos / Videos,
 *    and click any item to open it full size in a lightbox (with previous/next arrows).
 *  - Admins also get an "Add to Gallery" form (pictures, video files, or a YouTube link)
 *    and a delete button on each item.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db_configuration.php';
require 'gallery_functions.php';

$page_title = 'Project ABCD2 Gallery';
include('header.php');

gallery_ensure_table($db);
$items   = gallery_get_items($db);
$isAdmin = gallery_is_admin();
$csrf    = $isAdmin ? gallery_csrf_token() : '';

// Counts shown on the filter buttons.
$imageCount = count(array_filter($items, fn($i) => $i['Media_Type'] === 'image'));
$videoCount = count($items) - $imageCount;

// One-time success/error message left by gallery_upload.php or gallery_delete.php.
$flash = $_SESSION['gallery_flash'] ?? null;
unset($_SESSION['gallery_flash']);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;600&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<link href="css/gallery.css" rel="stylesheet">

<main class="gallery-page">

  <!-- ===== Page heading ===== -->
  <header class="gallery-hero">
    <h1 class="gallery-title">Gallery</h1>
    <p class="gallery-subtitle">Photos and videos from Project ABCD events, dresses, and celebrations.</p>
  </header>

  <?php if ($flash): ?>
    <div class="alert alert-<?= gallery_e($flash['type']) ?> gallery-flash" role="alert">
      <?= gallery_e($flash['message']) ?>
    </div>
  <?php endif; ?>

  <!-- ===== Admin: upload form (hidden until "Add to Gallery" is clicked) ===== -->
  <?php if ($isAdmin): ?>
    <section class="gallery-admin">
      <button type="button" class="g-btn g-btn-primary" id="gallery-add-toggle" aria-expanded="false" aria-controls="gallery-upload-form">
        <i class="fa fa-plus"></i> Add to Gallery
      </button>

      <form id="gallery-upload-form" class="gallery-upload-card" action="gallery_upload.php"
            method="POST" enctype="multipart/form-data" hidden>
        <input type="hidden" name="csrf" value="<?= gallery_e($csrf) ?>">

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="g-title">Title</label>
            <input type="text" id="g-title" name="title" class="form-control" maxlength="100"
                   placeholder="Optional (pictures without a title show no heading)">
          </div>
          <div class="form-group col-md-6">
            <label for="g-caption">Caption</label>
            <input type="text" id="g-caption" name="caption" class="form-control" maxlength="500"
                   placeholder="Optional short description">
          </div>
        </div>

        <!-- Drag-and-drop area; clicking it opens the normal file picker. -->
        <label class="gallery-dropzone" id="gallery-dropzone" for="g-media">
          <i class="fa fa-cloud-upload"></i>
          <span class="dz-main">Drop pictures or videos here, or click to choose</span>
          <span class="dz-hint">JPG, PNG, GIF, WEBP, MP4, WEBM, MOV &middot; max <?= gallery_e(ini_get('upload_max_filesize')) ?> per file</span>
          <input type="file" id="g-media" name="media[]" multiple
                 accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/quicktime">
        </label>
        <div class="gallery-file-list" id="gallery-file-list"></div>

        <div class="form-group">
          <label for="g-video-url">or a YouTube link</label>
          <input type="url" id="g-video-url" name="video_url" class="form-control"
                 placeholder="https://www.youtube.com/watch?v=...">
        </div>

        <div class="gallery-form-actions">
          <button type="submit" class="g-btn g-btn-primary">Upload</button>
          <button type="button" class="g-btn g-btn-ghost" id="gallery-cancel">Cancel</button>
        </div>
      </form>
    </section>
  <?php endif; ?>

  <!-- ===== Filter buttons ===== -->
  <nav class="gallery-filters" role="tablist" aria-label="Filter gallery">
    <button type="button" class="g-filter is-active" data-filter="all" role="tab" aria-selected="true">
      All <span class="g-count"><?= count($items) ?></span>
    </button>
    <button type="button" class="g-filter" data-filter="image" role="tab" aria-selected="false">
      <i class="fa fa-picture-o"></i> Photos <span class="g-count"><?= $imageCount ?></span>
    </button>
    <button type="button" class="g-filter" data-filter="video" role="tab" aria-selected="false">
      <i class="fa fa-video-camera"></i> Videos <span class="g-count"><?= $videoCount ?></span>
    </button>
  </nav>

  <!-- ===== The grid ===== -->
  <?php if (empty($items)): ?>
    <div class="gallery-empty">
      <i class="fa fa-picture-o"></i>
      <p>The gallery is empty for now.<?= $isAdmin ? ' Use "Add to Gallery" to upload the first pictures and videos.' : ' Check back soon!' ?></p>
    </div>
  <?php else: ?>
    <section class="gallery-grid" id="gallery-grid">
      <?php foreach ($items as $item):
        $type      = $item['Media_Type'];
        $youtubeId = $item['Video_Url'] ? gallery_youtube_id($item['Video_Url']) : null;
        $date      = date('M j, Y', strtotime($item['Created_Time']));
        $title     = trim($item['Title']);                 // may be blank for pictures
        $label     = $title !== '' ? $title : ($type === 'image' ? 'Photo' : 'Video'); // for screen readers
        // YouTube link shown on the card: only videos get one. Use the link the admin
        // attached, or, for a YouTube-only item, the YouTube video itself.
        $ytLink    = null;
        if ($type === 'video') {
            $ytLink = !empty($item['Youtube_Link']) ? $item['Youtube_Link']
                    : ($youtubeId ? 'https://www.youtube.com/watch?v=' . $youtubeId : null);
        }
        // Admins can attach a link to uploaded video files (YouTube-only items already have one).
        $canAttachLink = $isAdmin && $type === 'video' && !$youtubeId;
      ?>
        <article class="gallery-item" data-type="<?= gallery_e($type) ?>">
          <button type="button" class="gallery-thumb"
                  data-type="<?= gallery_e($type) ?>"
                  data-src="<?= gallery_e($item['File_Path']) ?>"
                  data-youtube="<?= gallery_e($youtubeId) ?>"
                  data-title="<?= gallery_e($title) ?>"
                  data-caption="<?= gallery_e($item['Caption']) ?>"
                  aria-label="Open <?= gallery_e($label) ?>">

            <?php if ($type === 'image'): ?>
              <img src="<?= gallery_e($item['File_Path']) ?>" alt="<?= gallery_e($label) ?>" loading="lazy">
            <?php elseif ($youtubeId): ?>
              <img src="https://img.youtube.com/vi/<?= gallery_e($youtubeId) ?>/hqdefault.jpg" alt="<?= gallery_e($label) ?>" loading="lazy">
            <?php else: ?>
              <!-- "#t=0.5" makes the browser show a frame from half a second in as the preview -->
              <video src="<?= gallery_e($item['File_Path']) ?>#t=0.5" preload="metadata" muted playsinline></video>
            <?php endif; ?>

            <?php if ($type === 'video'): ?>
              <span class="g-play" aria-hidden="true"><i class="fa fa-play"></i></span>
            <?php endif; ?>
            <span class="g-badge"><?= $type === 'image' ? 'Photo' : 'Video' ?></span>
          </button>

          <div class="gallery-meta">
            <?php if ($title !== ''): ?>
              <!-- Heading only when a title was written; pictures without one show none -->
              <h3 class="g-item-title"><?= gallery_e($title) ?></h3>
            <?php endif; ?>
            <?php if ($item['Caption']): ?>
              <p class="g-item-caption"><?= gallery_e($item['Caption']) ?></p>
            <?php endif; ?>
            <div class="g-item-foot">
              <time datetime="<?= gallery_e($item['Created_Time']) ?>"><?= gallery_e($date) ?></time>
              <div class="g-foot-actions">
                <?php if ($type === 'video'): ?>
                  <?php if ($ytLink): ?>
                    <!-- Video with a link: red YouTube icon + word, opens YouTube in a new tab -->
                    <a class="g-youtube" href="<?= gallery_e($ytLink) ?>"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="Watch <?= gallery_e($label) ?> on YouTube">
                      <i class="fa fa-youtube-play" aria-hidden="true"></i> YouTube
                    </a>
                  <?php else: ?>
                    <!-- Video without a link yet: gray, not clickable, says "none" -->
                    <span class="g-youtube g-youtube-none">
                      <i class="fa fa-youtube-play" aria-hidden="true"></i> YouTube: none
                    </span>
                  <?php endif; ?>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                  <form action="gallery_delete.php" method="POST"
                        onsubmit="return confirm('Delete this item from the gallery?');">
                    <input type="hidden" name="csrf" value="<?= gallery_e($csrf) ?>">
                    <input type="hidden" name="id" value="<?= (int)$item['Gallery_Id'] ?>">
                    <button type="submit" class="g-delete" title="Delete"><i class="fa fa-trash"></i> Delete</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>

            <?php if ($canAttachLink): ?>
              <!-- Admin only, video cards only: attach, change, or remove the YouTube link.
                   <details> opens and closes on click without any JavaScript. -->
              <details class="g-link-box">
                <summary>
                  <i class="fa fa-youtube-play" aria-hidden="true"></i>
                  <?= empty($item['Youtube_Link']) ? 'Add YouTube link' : 'Change YouTube link' ?>
                </summary>
                <form action="gallery_link.php" method="POST" class="g-link-form">
                  <input type="hidden" name="csrf" value="<?= gallery_e($csrf) ?>">
                  <input type="hidden" name="id" value="<?= (int)$item['Gallery_Id'] ?>">
                  <input type="url" name="youtube_link" class="form-control form-control-sm" required
                         placeholder="https://www.youtube.com/watch?v=..."
                         value="<?= gallery_e($item['Youtube_Link'] ?? '') ?>">
                  <div class="g-link-buttons">
                    <button type="submit" class="g-link-save">Save</button>
                    <?php if (!empty($item['Youtube_Link'])): ?>
                      <!-- formnovalidate lets "Remove" submit even if the box is emptied -->
                      <button type="submit" name="remove" value="1" class="g-link-remove" formnovalidate>Remove</button>
                    <?php endif; ?>
                  </div>
                </form>
              </details>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </section>

    <p class="gallery-none-match" id="gallery-none-match" hidden>Nothing here yet in this category.</p>
  <?php endif; ?>
</main>

<!-- ===== Lightbox (full-size viewer) ===== -->
<div class="gallery-lightbox" id="gallery-lightbox" role="dialog" aria-modal="true" aria-labelledby="lb-title" hidden>
  <button type="button" class="lb-close" aria-label="Close">&times;</button>
  <button type="button" class="lb-nav lb-prev" aria-label="Previous"><i class="fa fa-chevron-left"></i></button>
  <figure class="lb-figure">
    <div class="lb-media" id="lb-media"></div>
    <figcaption>
      <h2 id="lb-title"></h2>
      <p id="lb-caption"></p>
    </figcaption>
  </figure>
  <button type="button" class="lb-nav lb-next" aria-label="Next"><i class="fa fa-chevron-right"></i></button>
</div>

<script src="js/gallery.js"></script>
</body>
</html>
