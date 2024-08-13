<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $post_content = $_POST['post_content'];

    $target_dir = "post_images/";

    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == UPLOAD_ERR_OK) {
        // Asegúrate de que los nombres de los archivos sean únicos
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["post_image"]["name"]);

        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
            $post_image = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    } else {
        $post_image = null; 
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
