<?php
/*
 * gallery_upload.php
 * Handles the admin "Add to Gallery" form on gallery.php.
 *
 * One submission can add several pictures/videos at once (each file becomes its own
 * gallery item) and/or one YouTube link. Only admins may upload.
 */
require 'db_configuration.php';
require 'gallery_functions.php';

gallery_ensure_table($db);

// Only admins posting the real form get past this point.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !gallery_is_admin()) {
    header('Location: gallery.php');
    exit;
}
// When the whole upload is bigger than post_max_size, PHP silently drops all form data.
if (empty($_POST) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    gallery_flash('danger', 'That upload is too large for the server (limit '
        . ini_get('post_max_size') . '). Try fewer or smaller files.');
    header('Location: gallery.php');
    exit;
}
if (!gallery_check_csrf($_POST['csrf'] ?? '')) {
    gallery_flash('danger', 'Your session expired. Please try the upload again.');
    header('Location: gallery.php');
    exit;
}

$title    = trim($_POST['title'] ?? '');
$caption  = trim($_POST['caption'] ?? '');
$videoUrl = trim($_POST['video_url'] ?? '');
$uploader = $_SESSION['email'] ?? '';

$added  = 0;
$errors = [];

// Make sure the upload folder exists (first run on a new machine).
if (!is_dir(GALLERY_UPLOAD_DIR)) {
    mkdir(GALLERY_UPLOAD_DIR, 0775, true);
}

// Prepared statement reused for every item: safe against SQL injection.
$stmt = $db->prepare(
    "INSERT INTO gallery (Title, Caption, Media_Type, File_Path, Video_Url, Uploaded_By, Created_Time)
     VALUES (?, ?, ?, ?, ?, ?, NOW())"
);

// ---- 1. Uploaded files -------------------------------------------------------
if (!empty($_FILES['media']['name'][0])) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $count = count($_FILES['media']['name']);

    for ($i = 0; $i < $count; $i++) {
        $originalName = $_FILES['media']['name'][$i];
        $error        = $_FILES['media']['error'][$i];
        $tmpPath      = $_FILES['media']['tmp_name'][$i];

        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = "$originalName is larger than the server allows ("
                . ini_get('upload_max_filesize') . ").";
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = "$originalName could not be uploaded (error code $error).";
            continue;
        }

        // Work out whether this is a picture or a video from the file's actual contents.
        $mime = $finfo->file($tmpPath);
        if (isset(GALLERY_IMAGE_TYPES[$mime])) {
            $mediaType = 'image';
            $ext = GALLERY_IMAGE_TYPES[$mime];
        } elseif (isset(GALLERY_VIDEO_TYPES[$mime])) {
            $mediaType = 'video';
            $ext = GALLERY_VIDEO_TYPES[$mime];
        } else {
            $errors[] = "$originalName is not a supported picture (JPG, PNG, GIF, WEBP) or video (MP4, WEBM, MOV).";
            continue;
        }

        // Save under a random name so uploads never overwrite each other.
        $destination = GALLERY_UPLOAD_DIR . uniqid('g_', true) . '.' . $ext;
        if (!move_uploaded_file($tmpPath, $destination)) {
            $errors[] = "$originalName could not be saved. Check that images/gallery is writable.";
            continue;
        }

        // If the admin left the title blank, use the file name (without extension).
        $itemTitle = $title !== '' ? $title : pathinfo($originalName, PATHINFO_FILENAME);
        $itemTitle = mb_substr($itemTitle, 0, 100);
        $noUrl = null;

        $stmt->bind_param('ssssss', $itemTitle, $caption, $mediaType, $destination, $noUrl, $uploader);
        if ($stmt->execute()) {
            $added++;
        } else {
            unlink($destination); // don't leave orphan files behind
            $errors[] = "$originalName could not be saved to the database.";
        }
    }
}

// ---- 2. YouTube link ----------------------------------------------------------
if ($videoUrl !== '') {
    $youtubeId = gallery_youtube_id($videoUrl);
    if ($youtubeId === null) {
        $errors[] = 'The video link must be a YouTube link (youtube.com or youtu.be).';
    } else {
        $itemTitle = $title !== '' ? mb_substr($title, 0, 100) : 'YouTube video';
        $mediaType = 'video';
        $noFile = null;
        // Store a clean, standard link no matter which format was pasted.
        $cleanUrl = 'https://www.youtube.com/watch?v=' . $youtubeId;

        $stmt->bind_param('ssssss', $itemTitle, $caption, $mediaType, $noFile, $cleanUrl, $uploader);
        if ($stmt->execute()) {
            $added++;
        } else {
            $errors[] = 'The video link could not be saved to the database.';
        }
    }
}

$stmt->close();

// ---- 3. Tell the admin what happened -----------------------------------------
if ($added === 0 && empty($errors)) {
    gallery_flash('warning', 'Choose at least one picture or video, or paste a YouTube link.');
} elseif (empty($errors)) {
    gallery_flash('success', $added === 1 ? 'Added 1 item to the gallery.' : "Added $added items to the gallery.");
} else {
    $message = ($added > 0 ? "Added $added item(s). " : '') . implode(' ', $errors);
    gallery_flash($added > 0 ? 'warning' : 'danger', $message);
}

header('Location: gallery.php');
exit;
?>
