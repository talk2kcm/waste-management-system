<?php
/**
 * LOGOUT PAGE
 * Destroys user session and redirects to login
 */

require_once(__DIR__ . '/includes/init.php');

// Check if user is logged in
if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    
    // Log the logout activity
    logActivity($conn, 'User Logout', 'users', $user_id);
    
    // Destroy session
    session_destroy();
}

// Redirect to login page
header('Location: ' . BASE_URL . 'login.php');
exit();
?>
