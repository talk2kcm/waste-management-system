<?php
/**
 * PASSWORD RESET UTILITY
 * Use this to reset admin password if login fails
 * 
 * INSTRUCTIONS:
 * 1. Edit the credentials below (email and new password)
 * 2. Visit this file in your browser: http://localhost/waste-management-system/reset-password.php
 * 3. If successful, delete this file for security
 * 4. Login with your new credentials
 */

require_once(__DIR__ . '/config/database.php');

// ============================================================
// EDIT THESE VALUES
// ============================================================
$admin_email = 'admin@waste.local';
$new_password = 'Admin@123';
// ============================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $message = 'Please fill in all fields.';
        $type = 'error';
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        // Update user
        $sql = "UPDATE users SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param('ss', $password_hash, $email);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = 'Password updated successfully! You can now login.';
                    $type = 'success';
                } else {
                    $message = 'User not found with that email address.';
                    $type = 'error';
                }
            } else {
                $message = 'Error updating password: ' . $stmt->error;
                $type = 'error';
            }
            $stmt->close();
        } else {
            $message = 'Database error: ' . $conn->error;
            $type = 'error';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Waste Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        
        input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #764ba2;
        }
        
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 13px;
            color: #004085;
        }
        
        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }
        
        .default-creds {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 12px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 13px;
        }
        
        .default-creds strong {
            color: #856404;
        }
        
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Password Reset</h1>
        <p class="subtitle">Waste Management System</p>
        
        <div class="info-box">
            <strong>⚠️ Security Note:</strong>
            After using this tool to reset a password, delete this file (reset-password.php) from your server for security.
        </div>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $type === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($new_password); ?>" required>
            </div>
            
            <button type="submit">Reset Password</button>
        </form>
        
        <div class="default-creds">
            <strong>Default Credentials after reset:</strong><br>
            Email: <code>admin@waste.local</code><br>
            Password: <code>Admin@123</code>
        </div>
    </div>
</body>
</html>
