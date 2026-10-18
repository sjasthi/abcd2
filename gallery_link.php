<?php
/*
 * gallery_link.php
 * Saves (or removes) the YouTube link attached to one gallery item.
 *
 * Used by the small "Add YouTube link" box on each video card, which only admins see.
 * Once a link is saved, gallery.php shows a red YouTube icon and the word "YouTube" on
 * that card; clicking it opens the video on YouTube in a new tab.
 *
 * POST fields:
 *   csrf          - the form token from gallery.php
 *   id            - Gallery_Id of the item
 *   youtube_link  - the link to save (any YouTube format)
 *   remove        - present when the admin clicked "Remove"
 */
require 'db_configuration.php';
require 'gallery_functions.php';

gallery_ensure_table($db); // also adds the Youtube_Link column on older databases

// Only admins posting the real form get past this point.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !gallery_is_admin()) {
    header('Location: gallery.php');
    exit;
}
if (!gallery_check_csrf($_POST['csrf'] ?? '')) {
    gallery_flash('danger', 'Your session expired. Please try again.');
    header('Location: gallery.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    gallery_flash('danger', 'That gallery item could not be found.');
    header('Location: gallery.php');
    exit;
}

// YouTube links belong on video cards only.
$check = $db->prepare('SELECT Media_Type FROM gallery WHERE Gallery_Id = ?');
$check->bind_param('i', $id);
$check->execute();
$found = $check->get_result()->fetch_assoc();
$check->close();
if (!$found || $found['Media_Type'] !== 'video') {
    gallery_flash('danger', 'YouTube links can only be added to videos.');
    header('Location: gallery.php');
    exit;
}

if (isset($_POST['remove'])) {
    // "Remove" button: clear the link.
    $link = null;
    $message = 'YouTube link removed.';
} else {
    $pasted = trim($_POST['youtube_link'] ?? '');
    $youtubeId = gallery_youtube_id($pasted);
    if ($youtubeId === null) {
        gallery_flash('danger', 'Please paste a YouTube link (youtube.com or youtu.be).');
        header('Location: gallery.php');
        exit;
    }
    // Save one clean, standard link no matter which format was pasted.
    $link = 'https://www.youtube.com/watch?v=' . $youtubeId;
    $message = 'YouTube link saved.';
}

// Prepared statement: safe against SQL injection.
$stmt = $db->prepare('UPDATE gallery SET Youtube_Link = ? WHERE Gallery_Id = ?');
$stmt->bind_param('si', $link, $id);
if ($stmt->execute() && $stmt->affected_rows >= 0) {
    gallery_flash('success', $message);
} else {
    gallery_flash('danger', 'The YouTube link could not be saved.');
}
$stmt->close();

header('Location: gallery.php');
exit;
?>
