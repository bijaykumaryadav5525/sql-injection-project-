<?php
// PATCHED CODE - Using PDO Prepared Statements
// This is the secure version that prevents SQL injection

session_start();
include 'db.php';

$error = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SECURE: Using prepared statements with placeholders
    $SQL = "SELECT id, username, email, password, role FROM users WHERE username = ?";

    try {
        // Prepare the statement
        $stmt = $conn->prepare($SQL);
        
        // Execute with parameter binding
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password (in real application, use password_verify with hashed passwords)
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                $success = true;
                header("Location: dashboard-patched.php");
                exit;
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            $error = "Incorrect username or password.";
        }
    } catch (PDOException $e) {
        $error = "SQL Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATCHED - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .success-banner {
            background-color: #ccffcc;
            color: #00aa00;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .error {
            background-color: #ffcccc;
            color: #cc0000;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #66cc66;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #55bb55;
        }
        .info-box {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 12px;
            border-left: 4px solid #2196F3;
        }
        .info-box h4 {
            margin-top: 0;
            color: #1976D2;
        }
        .info-box p {
            margin: 5px 0;
        }
        .test-credentials {
            background-color: #f3e5f5;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-banner">✓ PATCHED CODE - SQL Injection Protected</div>
        
        <h1>LOGIN (SECURE)</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <input type="submit" value="LOGIN">
        </form>
        
        <div class="info-box">
            <h4>🔒 Security Features:</h4>
            <p>✓ PDO Prepared Statements</p>
            <p>✓ Parameter Binding</p>
            <p>✓ Input Separation from SQL</p>
            <p>✓ SQL Injection Protected</p>
        </div>
        
        <div class="test-credentials">
            <strong>Valid Credentials:</strong><br>
            Username: admin<br>
            Password: admin123
        </div>
    </div>
</body>
</html>
