<?php
session_start();
include 'db.php'; // Incluye el archivo de conexión a la base de datos

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $post_content = $_POST['post_content'];

  
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["post_image"]["name"]);

        // Verifica si el directorio de subida existe, si no lo crea
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
            $post_image = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        $post_image = null; // No hay imagen
    }

    // Inserta el post en la base de datos
    $stmt = $conn->prepare("INSERT INTO posts (username, post_content, post_image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $post_content, $post_image);

    if ($stmt->execute()) {
        header("Location: dashboard.php"); // Redirige al dashboard después de subir el post
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
