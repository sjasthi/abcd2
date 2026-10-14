<?php
/*
 * gallery_delete.php
 * Removes one gallery item (and its uploaded file, if any). Admins only.
 */
require 'db_configuration.php';
require 'gallery_functions.php';

gallery_ensure_table($db);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !gallery_is_admin()
        || !gallery_check_csrf($_POST['csrf'] ?? '')) {
    header('Location: gallery.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);

// Look the item up first so we know which file to delete from disk.
$stmt = $db->prepare("SELECT File_Path FROM gallery WHERE Gallery_Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$item) {
    gallery_flash('warning', 'That gallery item no longer exists.');
    header('Location: gallery.php');
    exit;
}

$stmt = $db->prepare("DELETE FROM gallery WHERE Gallery_Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

// Only delete files that live inside the gallery folder (never anything else on disk).
$path = $item['File_Path'];
if ($path && strpos($path, GALLERY_UPLOAD_DIR) === 0 && strpos($path, '..') === false && is_file($path)) {
    unlink($path);
}

gallery_flash('success', 'Gallery item deleted.');
header('Location: gallery.php');
exit;
?>
