<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password){
        die("Pass not same retard");
    }

// recordar descomentar.
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (username, email, password) VALUES (?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo "Registro exitoso.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
}


?>