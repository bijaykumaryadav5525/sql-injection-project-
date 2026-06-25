<?php
// VULNERABLE CODE - FOR EDUCATIONAL PURPOSES ONLY
// This code is intentionally vulnerable to demonstrate SQL injection attacks

session_start();
include 'db.php';

$error = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // VULNERABLE: Direct string concatenation without prepared statements
    $username = $_POST['username'];
    $password = $_POST['password'];

    // This is the vulnerable SQL query
    $SQL = "SELECT * FROM users WHERE username='$username' AND password='$password'";

    try {
        $result = $conn->query($SQL);
        
        if ($result && $result->rowCount() > 0) {
            $user = $result->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            $success = true;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } catch (Exception $e) {
        $error = "SQL Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VULNERABLE - Login</title>
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
        .warning {
            background-color: #ffcccc;
            color: #cc0000;
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
            background-color: #ff6666;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #ff5555;
        }
        .test-payloads {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 12px;
        }
        .test-payloads h4 {
            margin-top: 0;
        }
        .test-payloads p {
            margin: 5px 0;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning">⚠️ VULNERABLE CODE - EDUCATIONAL ONLY</div>
        
        <h1>LOGIN (VULNERABLE)</h1>
        
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
        
        <div class="test-payloads">
            <h4>Test Payloads (Educational):</h4>
            <p><strong>Username:</strong> ' OR '1'='1</p>
            <p><strong>Password:</strong> anything</p>
            <hr>
            <p><strong>Username:</strong> admin'#</p>
            <p><strong>Password:</strong> anything</p>
            <hr>
            <p><strong>Valid Login:</strong></p>
            <p><strong>Username:</strong> admin</p>
            <p><strong>Password:</strong> admin123</p>
        </div>
    </div>
</body>
</html>
