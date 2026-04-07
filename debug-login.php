<?php
/**
 * DEBUG LOGIN SCRIPT
 * Checks what's wrong with authentication
 * DELETE THIS FILE AFTER TESTING!
 */

require_once(__DIR__ . '/config/database.php');
require_once(__DIR__ . '/includes/init.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Debug</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        h1 { color: #333; margin-bottom: 20px; }
        .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .section h2 { color: #2ecc71; margin-top: 0; font-size: 18px; margin-bottom: 10px; }
        .section p { margin: 8px 0; color: #333; }
        code { background: #f0f0f0; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .test-area { background: #ecf0f1; padding: 15px; margin-top: 10px; border-radius: 5px; }
        table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #2ecc71; color: white; }
        tr:hover { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Login Debug Information</h1>
        
        <!-- Database Connection Test -->
        <div class="section">
            <h2>1. Database Connection</h2>
            <?php
            if ($conn->connect_error) {
                echo '<p class="error">❌ Connection Failed: ' . htmlspecialchars($conn->connect_error) . '</p>';
            } else {
                echo '<p class="success">✅ Connected to database: ' . DB_NAME . '</p>';
            }
            ?>
        </div>

        <!-- Check Users Table -->
        <div class="section">
            <h2>2. Users in Database</h2>
            <?php
            $sql = "SELECT user_id, email, first_name, last_name, role, status, password_hash FROM users";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                echo '<table>';
                echo '<tr><th>ID</th><th>Email</th><th>Name</th><th>Role</th><th>Status</th><th>Password Hash</th></tr>';
                
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
                    echo '<td><code>' . htmlspecialchars($row['email']) . '</code></td>';
                    echo '<td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                    echo '<td><code style="font-size:11px;">' . substr(htmlspecialchars($row['password_hash']), 0, 30) . '...</code></td>';
                    echo '</tr>';
                }
                
                echo '</table>';
            } else {
                echo '<p class="error">❌ No users found in database</p>';
            }
            ?>
        </div>

        <!-- Password Verification Test -->
        <div class="section">
            <h2>3. Password Verification Test</h2>
            <div class="test-area">
                <p><strong>Testing password:</strong> <code>Admin@123</code></p>
                
                <?php
                $test_email = 'admin@waste.local';
                $test_password = 'Admin@123';
                
                // Get admin user
                $sql = "SELECT password_hash FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $test_email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $hash = $user['password_hash'];
                    
                    echo '<p><strong>Database Hash:</strong> <code style="font-size:11px;">' . htmlspecialchars($hash) . '</code></p>';
                    
                    // Test password_verify
                    if (password_verify($test_password, $hash)) {
                        echo '<p class="success">✅ Password verification PASSED!</p>';
                    } else {
                        echo '<p class="error">❌ Password verification FAILED!</p>';
                        echo '<p>The hash in the database does not match the password "Admin@123"</p>';
                        
                        // Generate new hash
                        $new_hash = password_hash($test_password, PASSWORD_BCRYPT, ['cost' => 10]);
                        echo '<p><strong>Generated new hash:</strong></p>';
                        echo '<p><code style="font-size:11px;">' . htmlspecialchars($new_hash) . '</code></p>';
                        echo '<p>Use this SQL to fix it:</p>';
                        echo '<p><code style="font-size:11px;">UPDATE users SET password_hash = \'' . htmlspecialchars($new_hash) . '\' WHERE email = \'admin@waste.local\';</code></p>';
                    }
                } else {
                    echo '<p class="error">❌ User not found: ' . htmlspecialchars($test_email) . '</p>';
                }
                
                $stmt->close();
                ?>
            </div>
        </div>

        <!-- Login Simulation -->
        <div class="section">
            <h2>4. Login Simulation Test</h2>
            <div class="test-area">
                <?php
                $email = 'admin@waste.local';
                $password = 'Admin@123';
                
                // Step 1: Find user
                $sql = "SELECT user_id, first_name, last_name, email, role, password_hash, status FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    echo '<p>✅ User found: ' . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . '</p>';
                    echo '<p>Email: <code>' . htmlspecialchars($user['email']) . '</code></p>';
                    echo '<p>Role: ' . htmlspecialchars($user['role']) . '</p>';
                    echo '<p>Status: ' . htmlspecialchars($user['status']) . '</p>';
                    
                    // Step 2: Check status
                    if ($user['status'] !== 'active') {
                        echo '<p class="error">❌ Account is ' . htmlspecialchars($user['status']) . '</p>';
                    } else {
                        echo '<p>✅ Account is active</p>';
                        
                        // Step 3: Verify password
                        if (password_verify($password, $user['password_hash'])) {
                            echo '<p class="success">✅ Password is correct - LOGIN WOULD SUCCEED!</p>';
                        } else {
                            echo '<p class="error">❌ Password is incorrect - LOGIN FAILS</p>';
                        }
                    }
                } else {
                    echo '<p class="error">❌ User not found</p>';
                }
                
                $stmt->close();
                ?>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="section">
            <h2>5. Next Steps</h2>
            <p>1. Review the results above</p>
            <p>2. If password verification FAILED, copy the SQL command from section 3</p>
            <p>3. Paste it in phpMyAdmin → SQL tab → Go</p>
            <p>4. Then try logging in again</p>
            <p>5. <strong>⚠️ DELETE THIS FILE (debug-login.php) after testing for security!</strong></p>
        </div>
    </div>
</body>
</html>
