<?php
session_start();
include '../db.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$post_id = $_GET['id'];


$stmt = $conn->prepare("SELECT p.post_content, p.post_image, p.created_at, u.username, u.profile_picture 
                        FROM posts p 
                        JOIN usuarios u ON p.username = u.username 
                        WHERE p.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($post_content, $post_image, $created_at, $username, $profile_picture);
$stmt->fetch();
$stmt->close();


if (!$profile_picture) {
    $profile_picture = '../img/default-profile.png';
} else {
    $profile_picture = '../register/uploads/' . basename($profile_picture);
}

// Procesar el comentario enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    $comment_user = $_SESSION['username']; // Asumiendo que el nombre de usuario está en la sesión

    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, username, comment_text, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $post_id, $comment_user, $comment);
        $stmt->execute();
        $stmt->close();
    }

    // Redirigir para evitar el reenvío del formulario al actualizar la página
    header("Location: post_detail.php?id=" . $post_id);
    exit();
}

// Recuperar los comentarios del post
$comments = [];
$stmt = $conn->prepare("SELECT c.comment_text, c.created_at, u.username, u.profile_picture 
                        FROM comments c 
                        JOIN usuarios u ON c.username = u.username 
                        WHERE c.post_id = ? ORDER BY c.created_at DESC");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/styles/stylesPostDetails.css" />
    <title>Post Detail</title>
</head>
<body>
    <div class="post-detail-container">
        <div class="post-header">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-pic" />
            <div class="user-info">
                <h3 class="username"><?php echo htmlspecialchars($username); ?></h3>
            </div>
            <div class="profile-button-container">
                <a href="dashboard.php?username=<?php echo urlencode($username); ?>" class="profile-btn">Back to Profile</a>
            </div>
        </div>
        <div class="post-content">
            <img src="<?php echo htmlspecialchars('post_images/' . basename($post_image)); ?>" alt="Post Image" class="post-image" />
            <p class="post-text"><?php echo htmlspecialchars($post_content); ?></p>
            <p class="post-date"><?php echo htmlspecialchars(date("g:i A • m.d.y", strtotime($created_at))); ?></p>
        </div>
        <div class="comment-section">
            <div class="write-comment-container">
                <form id="commentForm" method="POST" action="">
                    <div class="write-comment">
                        <input type="text" name="comment" class="comment-input" placeholder="Write your comment..." required>
                        <button type="submit" class="comment-btn">Post</button>
                    </div>
                </form>
            </div>
         
            <div class="comments">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <img src="<?php echo htmlspecialchars('../register/uploads/' . basename($comment['profile_picture'] ?: 'default-profile.png')); ?>" alt="Comment Profile Picture" class="comment-profile-pic" />
                        <div class="comment-content">
                            <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong></p>
                            <div class="comment-meta">
                                <p><?php echo htmlspecialchars(date("g:i A • m.d.y", strtotime($comment['created_at']))); ?></p>
                            </div>
                            <div class="comment-text">
                                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
