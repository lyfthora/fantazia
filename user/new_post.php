<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
include '../db.php';

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/stylesNewPost.css" />
    <title>New Post</title>
</head>

<body>
    <div class="container">
        <a href="dashboard.php" class="back-btn">back to profile</a>
        <!-- Botón sin funcionalidad -->
        <button class="create-post-btn">★ create a post ★</button>
        <form action="upload_post.php" method="post" enctype="multipart/form-data">
            <div class="upload-container" id="uploadContainer">
                <div class="upload-content">
                    <p>upload an<br>image</p>
                </div>
                <input type="file" name="post_image" accept="image/*" id="post_image" />
                <img id="preview" src="#" alt="Preview" style="display: none; max-width: 100%; max-height: 305px;" />
            </div>
            <textarea name="post_content" rows="4" cols="50" placeholder="Write write write..." required
                style="display: none;"></textarea>
            <input type="submit" value="Upload Post" class="upload-btn" style="display: none;" />
        </form>
    </div>
    <img src="../img/gif/distance2.gif" class="distance">
    <footer>
        <p class="note"><b>NOTE:</b> All love lain. All love lain. All love lain. All love lain.</p>
        <a href="../lainalone.html" class="help-link">Help & Guidelines</a>
    </footer>

    <script>
        document.getElementById('post_image').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                var img = document.getElementById('preview');
                img.src = e.target.result;
                img.style.display = 'block';

                document.querySelector('.upload-content').style.display = 'none';

                document.querySelector('textarea').style.display = 'block';
                document.querySelector('.upload-btn').style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                console.log("No file selected");
            }
        });
    </script>
</body>

</html>