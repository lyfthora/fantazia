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
    <link rel="stylesheet" type="text/css" href="stylesNewPost.css" />
    <title>New Post</title>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn">back to account</a>
        <button class="create-post-btn">★ create a post ★</button>
        <form action="upload_post.php" method="post" enctype="multipart/form-data">
            <div class="upload-container">
                <div class="upload-icon">↑</div>
                <p>upload an<br>image</p>
                <input type="file" name="post_image" accept="image/*" style="display: none;" id="post_image" />
            </div>
            <textarea name="post_content" rows="4" cols="50" placeholder="What's on your mind?" required style="display: none;"></textarea>
            <input type="submit" value="Upload Post" class="upload-btn" style="display: none;" />
        </form>
    </div>

    <script>
        document.querySelector('.create-post-btn').addEventListener('click', function() {
            document.querySelector('textarea').style.display = 'block';
            document.querySelector('.upload-btn').style.display = 'block';
        });

        document.querySelector('.upload-container').addEventListener('click', function() {
            document.getElementById('post_image').click();
        });
    </script>
</body>
</html>