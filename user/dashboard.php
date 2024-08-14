<?php
session_start();
include '../db.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}


$username = $_SESSION['username'];

// foto de perfil del usuario
$stmt = $conn->prepare("SELECT profile_picture FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();


if (!$profile_picture) {
    $profile_picture = '../img/default-profile.png';
} else {

    $profile_picture = '../register/uploads/' . basename($profile_picture);


    if (!file_exists($profile_picture)) {
        $profile_picture = '../img/default-profile.png';
    }
}


$stmt = $conn->prepare("SELECT id, post_content, post_image FROM posts WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
        <div class="footer-buttons">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/styles/stylesDashboard.css" />
    <title>Dashboard</title>
</head>

<body>
    <div class="profile-container">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile" />
        <div class="profile-info">
            <p class="username"><?php echo htmlspecialchars($username); ?></p>
        </div>
        <form action="../login/logout.php" method="post">
            <input type="submit" value="Log Out" class="logout-btn" />
        </form>
    </div>

    <div class="new-post-btn">
        <form action="new_post.php" method="get">
            <input type="submit" value="new post" class="upload-btn" />
        </form>
    </div>

    <div class="posts-container">
        <?php if (empty($posts)) { ?>
            <p>You have not posted anything yet.</p>
        <?php } else { ?>
            <?php foreach ($posts as $post) {
                $post_image_path = '../register/uploads/' . htmlspecialchars($post['post_image']);
            ?>
                <div class="post">
                    <a href="post_detail.php?id=<?php echo htmlspecialchars($post['id']); ?>">
                        <img src="<?php echo htmlspecialchars($post['post_image']); ?>" alt="Post Image" />
                    </a>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
<!-- Footer con los botones para ver todos los posts y el perfil -->
<footer class="dashboard-footer">
        <div class="footer-buttons">
            <form action="all_post.php" method="get">
                <input type="submit" value="View All Posts" class="footer-btn" />
            </form>
            <form action="profile.php" method="get">
                <input type="submit" value="View Profile" class="footer-btn" />
            </form>
        </div>
    </footer>

</html>