<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
include 'db.php';

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stylesDashboard.css" />
    <title>New Post</title>
</head>
<body>
    <h2>Create a New Post</h2>

    <!-- Formulario para subir un nuevo post -->
    <form action="upload_post.php" method="post" enctype="multipart/form-data">
        <textarea name="post_content" rows="4" cols="50" placeholder="What's on your mind?" required></textarea>
        <br>
        <input type="file" name="post_image" accept="image/*" />
        <br>
        <input type="submit" value="Upload Post" class="upload-btn" />
    </form>

    <br>

    <!-- Enlace para volver al dashboard -->
    <a href="dashboard.php" class="upload-btn">Back to Dashboard</a>

</body>
</html>
