<?php
session_start();
include 'db.php'; // Incluye el archivo de conexión a la base de datos

// Verifica si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Recupera la información del usuario logueado
$username = $_SESSION['username'];

// Recupera la foto de perfil del usuario
$stmt = $conn->prepare("SELECT profile_picture FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Si no hay foto de perfil, usa una imagen por defecto
if (!$profile_picture) {
    $profile_picture = 'img/default-profile.png';
}

// Recupera los posts del usuario (incluye 'id')
$stmt = $conn->prepare("SELECT id, post_content, post_image FROM posts WHERE username = ?");
$stmt->bind_param("s", $username);
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
    <link rel="stylesheet" type="text/css" href="stylesDashboard.css" />
    <title>Dashboard</title>
</head>
<body>
    <div class="profile-container">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile" />
        <div class="profile-info">
            <p class="username"><?php echo htmlspecialchars($username); ?></p>
            
        </div>
    </div>

    <div class="new-post-btn">
        <form action="new_post.php" method="get">
            <input type="submit" value="new post +" class="upload-btn" />
        </form>
    </div>

    <div class="posts-container">
    <?php if (empty($posts)) { ?>
        <p>You have not posted anything yet.</p>
    <?php } else { ?>
        <?php foreach ($posts as $post) { ?>
            <div class="post">
                <a href="post_detail.php?id=<?php echo htmlspecialchars($post['id']); ?>">
                    <img src="<?php echo htmlspecialchars($post['post_image']); ?>" alt="Post Image" />
                </a>
            </div>
        <?php } ?>
    <?php } ?>
    </div>
</body>
</html>
