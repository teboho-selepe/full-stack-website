<?php
// Admin authentication middleware
session_start();

if (!isset($_SESSION['admin_email']) || $_SESSION['admin_email'] !== 'admin@gmail.com') {
    header('Location: /web/admin/login.php');
    exit();
}
?>