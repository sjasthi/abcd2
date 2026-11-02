<?php
/*
 * delete_the_nomination.php
 * Issue #62 - handles the POST from delete_nomination.php.
 *
 * Deletes the nomination and sends the admin back to Manage Nominations with a
 * status flag (Deleted=Success / Deleted=Failed) so a message is shown there,
 * the same way Modify Nomination reports Updated=Success.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only admins may delete nominations (previously this endpoint had no check).
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

include_once 'db_configuration.php';

// Only act on a real form submission with a valid integer id.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: manageNominations.php');
    exit();
}

$id = (int)$_POST['id'];
$status = 'Failed';

if ($id > 0) {
    $stmt = $db->prepare("DELETE FROM nominations WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $status = 'Success';
    }
    $stmt->close();
}

header('Location: manageNominations.php?Deleted=' . $status);
exit();
