<?php
session_start(); 
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Password are diff..";
        header("Location: create.php");
        exit();
    }

    // Recordar descomentar.
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    try {

        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: createacc.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            $_SESSION['error'] = "Username or email already exists..";
        } else {
            $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        }
        header("Location: create.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
