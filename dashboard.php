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

// Recupera los posts del usuario
$stmt = $conn->prepare("SELECT post_content, post_image FROM posts WHERE username = ?");
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
    <title>Dashboard</title>
    <style>
        .upload-btn {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }

        .post img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
    <p>Your profile picture:</p>
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #ccc;" />
    
    <!-- Formulario para subir un nuevo post -->
    <h3>Upload a Post</h3>
    <form action="upload_post.php" method="post" enctype="multipart/form-data">
        <textarea name="post_content" rows="4" cols="50" placeholder="What's on your mind?" required></textarea>
        <br>
        <input type="file" name="post_image" accept="image/*" />
        <br>
        <input type="submit" value="Upload Post" class="upload-btn" />
    </form>

    <!-- Mostrar los posts del usuario -->
    <h3>Your Posts:</h3>
    <?php if (empty($posts)) { ?>
        <p>You have not posted anything yet.</p>
    <?php } else { ?>
        <?php foreach ($posts as $post) { ?>
            <div class="post">
                <p><?php echo htmlspecialchars($post['post_content']); ?></p>
                <?php if (!empty($post['post_image'])) { ?>
                    <img src="<?php echo htmlspecialchars($post['post_image']); ?>" alt="Post Image" />
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
</body>
</html>
