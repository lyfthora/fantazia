<?php
session_start();
include 'db.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Recuperar la información del usuario logueado
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT profile_picture FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();

// Si el usuario no ha subido una foto, puedes mostrar una imagen por defecto
if (!$profile_picture) {
    $profile_picture = 'img/default-profile.png'; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?></h2>
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" />
    <p>This is your profile page.</p>
</body>
</html>