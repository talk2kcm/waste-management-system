<?php
/**
 * LOGIN PAGE
 * Handles user authentication
 * 
 * Demo Credentials:
 * Admin: admin@waste.local / Admin@123
 * Staff: john@waste.local / Admin@123
 * Resident: jane@waste.local / Admin@123
 */

require_once(__DIR__ . '/includes/init.php');

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'dashboard.php');
    exit();
}

$page_title = 'Login';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Query database for user
        $sql = "SELECT user_id, first_name, last_name, email, role, password_hash, status FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Check if user account is active
                if ($user['status'] !== 'active') {
                    $error = 'Your account is ' . $user['status'] . '. Please contact administrator.';
                } else if (verifyPassword($password, $user['password_hash'])) {
                    // Password is correct - set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    // Log activity
                    logActivity($conn, 'User Login', 'users', $user['user_id']);
                    
                    // Redirect to dashboard
                    header('Location: ' . BASE_URL . 'dashboard.php');
                    exit();
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'Invalid email or password.';
            }
            $stmt->close();
        }
    }
}

?>
<?php require_once('includes/header.php'); ?>

<div class="login-container">
    <div class="login-box">
        <h2>Waste Management System</h2>
        <p class="subtitle">Login to your account</p>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo escape($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        
        <div class="login-info">
            <p><strong>Demo Credentials:</strong></p>
            <p>Admin: <code>admin@waste.local</code> / <code>Admin@123</code></p>
            <p>Staff: <code>john@waste.local</code> / <code>Admin@123</code></p>
            <p>Resident: <code>jane@waste.local</code> / <code>Admin@123</code></p>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>
