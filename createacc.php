<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stylesAcc-Creation.css" />
    <title>Document</title>
</head>

<body>
<h2>Hello <?php echo htmlspecialchars($_SESSION['username']); ?>,<br> welcome</h2>
    <h1>Would you like to upload a profile picture?</h1>

    <!-- Formulario para la carga de la imagen -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
        >
        <img src="img/gif/LAINHADN3.gif" class="pic" id="upload-trigger" alt="Click to upload" />

        <img id="preview" src="" alt="Image Preview" style="display: none; width: 200px; height: 200px; margin-top: 20px;" />

        <input type="file" name="profile_picture" id="file-input" accept="image/*" style="display: none;" />
        
        <p class="abajopagina"><b>No, Skip this shit</b></p>
        <br>
        <input  class="upload" type="submit" value="Upload" />
    </form>

    <script>
        document.getElementById('upload-trigger').addEventListener('click', function() {
            document.getElementById('file-input').click();
        });

        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Muestra la imagen en la vista previa
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    // Esconde el GIF
                    document.getElementById('upload-trigger').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>