<?php
session_start();
include '../db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/index.php");
    exit();
}

// Obtener todos los posts junto con el nombre de usuario y la foto de perfil
$stmt = $conn->prepare("SELECT p.id, p.post_content, p.post_image, p.created_at, u.username, u.profile_picture 
                        FROM posts p 
                        JOIN usuarios u ON p.username = u.username 
                        ORDER BY p.id DESC");
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/stylesAllPost.css" />
    <title>All Posts</title>
</head>

<body>
    <div class="posts-container">
        <?php if (empty($posts)) { ?>
            <p>No posts available.</p>
        <?php } else { ?>
            <?php foreach ($posts as $post) { 
                // Ruta de la foto de perfil
                $profile_picture = $post['profile_picture'] ? '../register/uploads/' . basename($post['profile_picture']) : '../img/default-profile.png';
                if (!file_exists($profile_picture)) {
                    $profile_picture = '../img/default-profile.png';
                }
                
                // Ruta de la imagen del post
                $post_image_path = 'post_images/' . basename($post['post_image']);
            ?>
                <div class="post">
                    <div class="post-header">
                        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="post-profile-picture" />
                        <p class="post-username"><?php echo htmlspecialchars($post['username']); ?></p>
                    </div>
                    <div class="post-content">
                        <?php if (!empty($post['post_image'])) { ?>
                            <img src="<?php echo htmlspecialchars($post_image_path); ?>" alt="Post Image" class="post-image" />
                        <?php } ?>
                        <p><?php echo htmlspecialchars($post['post_content']); ?></p>
                        <p class="post-date"><?php echo htmlspecialchars(date("g:i A • m.d.y", strtotime($post['created_at']))); ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>

</html>
