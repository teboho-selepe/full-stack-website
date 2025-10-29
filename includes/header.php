<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$name = $_SESSION['name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Path Tutors</title>
    <link rel="stylesheet" href="/web/assets/style.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<header>
    
    <a href="/web/index.php" class="logo">
        <img src="/web/assets/logo.png" alt="SmartPath Tutoring Logo">
    </a>


    <nav>
        <a href="/web/index.php">Home</a>
        <a href="/web/pages/about.php">About</a>
        <a href="/web/pages/contact.php">Contact</a>
    </nav>

    <div class="user-auth">
        <?php if (!empty($name)): ?>
        <div class="profile-box">
            <div class="avatar-cicle"><?= strtoupper($name[0]) ?></div>
            <div class="dropdown">
                <a href="#">My Account</a>
                <a href="/web/controllers/logout.php">Logout</a>
            </div>
        </div>
        <?php else: ?>
        <button type="button" class="login-btn-modal">Login</button>
        <?php endif; ?>
    </div>
</header>
