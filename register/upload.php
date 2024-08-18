<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $username = $_SESSION['username'];
        $profile_picture = $target_file;

        
        $stmt = $conn->prepare("UPDATE usuarios SET profile_picture = ? WHERE username = ?");
        $stmt->bind_param("ss", $profile_picture, $username);

        if ($stmt->execute()) {
           
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            echo "Error saving to database: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$conn->close();
?>
