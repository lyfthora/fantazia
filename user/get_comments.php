<?php
include '../db.php';

// Desactivar la visualización de errores para evitar que interfieran con la salida JSON
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$post_id = $_GET['post_id'] ?? 0;

if ($post_id == 0) {
    echo json_encode([]);
    exit();
}

// Consulta para obtener los comentarios del post
$stmt = $conn->prepare("
    SELECT c.comment_text AS comment_content, c.created_at, u.username, u.profile_picture
    FROM comments c
    JOIN usuarios u ON c.username = u.username
    WHERE c.post_id = ?
    ORDER BY c.created_at ASC
");

$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

header('Content-Type: application/json');
echo json_encode($comments);

$stmt->close();
$conn->close();
?>