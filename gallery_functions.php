<?php
/*
 * gallery_functions.php
 * Shared helpers for the Gallery feature (gallery.php, gallery_upload.php, gallery_delete.php).
 *
 * The gallery stores one row per item in the `gallery` table. An item is either:
 *   - an image file uploaded to images/gallery/
 *   - a video file uploaded to images/gallery/ (mp4 / webm / mov)
 *   - a YouTube link (no file is stored, only the URL)
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Folder (relative to the project root) where uploaded gallery files are saved.
const GALLERY_UPLOAD_DIR = 'images/gallery/';

// Allowed file types, keyed by the MIME type PHP detects from the file contents
// (we never trust the browser-supplied type or the file extension alone).
const GALLERY_IMAGE_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
const GALLERY_VIDEO_TYPES = [
    'video/mp4'       => 'mp4',
    'video/webm'      => 'webm',
    'video/quicktime' => 'mov',
];

/**
 * Creates the gallery table the first time the feature is used, so the page works
 * on a fresh XAMPP install without importing sql/gallery.sql by hand.
 */
function gallery_ensure_table($db) {
    $sql = "CREATE TABLE IF NOT EXISTS `gallery` (
        `Gallery_Id`  INT(11) NOT NULL AUTO_INCREMENT,
        `Title`       VARCHAR(100) NOT NULL,
        `Caption`     VARCHAR(500) DEFAULT NULL,
        `Media_Type`  ENUM('image','video') NOT NULL,
        `File_Path`   VARCHAR(255) DEFAULT NULL,
        `Video_Url`   VARCHAR(255) DEFAULT NULL,
        `Uploaded_By` VARCHAR(100) DEFAULT NULL,
        `Created_Time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`Gallery_Id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    mysqli_query($db, $sql);
}

/** True when the logged-in user is an admin (same check admin.php uses). */
function gallery_is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/** Returns a per-session token used to protect the upload and delete forms. */
function gallery_csrf_token() {
    if (empty($_SESSION['gallery_csrf'])) {
        $_SESSION['gallery_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['gallery_csrf'];
}

function gallery_check_csrf($token) {
    return isset($_SESSION['gallery_csrf']) && is_string($token)
        && hash_equals($_SESSION['gallery_csrf'], $token);
}

/**
 * Pulls the 11-character video id out of any common YouTube link format:
 * youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID, youtube.com/shorts/ID.
 * Returns null if the link is not a YouTube link.
 */
function gallery_youtube_id($url) {
    $pattern = '~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~';
    if (preg_match($pattern, $url, $m)) {
        return $m[1];
    }
    return null;
}

/** Fetches all gallery items, newest first. */
function gallery_get_items($db) {
    $items = [];
    $result = mysqli_query($db, "SELECT * FROM gallery ORDER BY Created_Time DESC, Gallery_Id DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

/** Stores a one-time message shown at the top of gallery.php after a redirect. */
function gallery_flash($type, $message) {
    $_SESSION['gallery_flash'] = ['type' => $type, 'message' => $message];
}

/** Short HTML-escape helper used throughout the gallery templates. */
function gallery_e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
