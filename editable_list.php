<?php
session_start();
include_once 'db_configuration.php';
header('Content-Type: application/json');

// Only admins may edit
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Not allowed']);
    exit;
}

// Column names can't be bound as parameters, so restrict them to a fixed list
$allowedColumns = ['name', 'tag_line', 'category', 'type', 'state_name', 'key_words'];

$column = $_POST['column'] ?? '';
$id     = (int)($_POST['id'] ?? 0);
$value  = trim($_POST['value'] ?? '');

if (!in_array($column, $allowedColumns, true) || $id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Bad request']);
    exit;
}

// Prepared statement: the value is sent separately from the SQL, so it can't inject anything
$stmt = $db->prepare("UPDATE dresses SET `$column` = ? WHERE id = ?");
$stmt->bind_param('si', $value, $id);
$ok = $stmt->execute();

echo json_encode(['ok' => $ok]);