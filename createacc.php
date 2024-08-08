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
    <link rel="stylesheet" type="text/css" href="styleslain.css" />
    <title>Document</title>
</head>
<body>
<h1>Hello <?php echo htmlspecialchars($_SESSION['username']); ?>,</h1>
</body>
</html>