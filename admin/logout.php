<?php
// Admin logout
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Log activity if user is logged in
if (isAdmin()) {
    logActivity('logout', 'User logged out');
}

// Destroy session
destroyAdminSession();

// Redirect to login page
header('Location: login.php?message=logged_out');
exit;
?>
