<?php
session_start();
include '../db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/index.php");
    exit();
}

// Verifica que el post_id y el comentario estén disponibles
if (isset($_POST['post_id']) && isset($_POST['comment_text'])) {
    $post_id = $_POST['post_id'];
    $comment_text = $_POST['comment_text'];
    $username = $_SESSION['username']; 

   
    $stmt = $conn->prepare("
        INSERT INTO comments (post_id, username, comment_text, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param('iss', $post_id, $username, $comment_text);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$conn->close();
?>