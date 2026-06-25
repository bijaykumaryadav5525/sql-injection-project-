<?php
// PATCHED CODE - Using Integer Validation
// This is the secure version that prevents blind SQL injection

include 'db.php';

$product = null;
$error = "";
$submitted = false;

if (!empty($_GET['id'])) {
    $submitted = true;
    
    // SECURE: Validate and cast input to integer
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    if ($id !== false) {
        // Now the input is guaranteed to be an integer
        $SQL = "SELECT * FROM products WHERE id=" . $id;

        try {
            $result = $conn->query($SQL);
            
            if ($result && $result->rowCount() > 0) {
                $product = $result->fetch(PDO::FETCH_ASSOC);
            } else {
                $product = null;
            }
        } catch (PDOException $e) {
            $error = "SQL Error: " . $e->getMessage();
        }
    } else {
        $error = "Invalid input - Please enter a valid product ID";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATCHED - Product Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
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
        .product-search {
            margin-bottom: 30px;
        }
        .product-search label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .product-search input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .product-search button {
            background-color: #66cc66;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
        .product-search button:hover {
            background-color: #55bb55;
        }
        .product-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
            border: 1px solid #ddd;
        }
        .product-details h2 {
            margin-top: 0;
            color: #333;
        }
        .product-details p {
            margin: 10px 0;
        }
        .product-details strong {
            color: #333;
        }
        .not-found {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .info-box {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 30px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="success-banner">✓ PATCHED CODE - SQL Injection Protected</div>
        
        <h1>Product Details (SECURE)</h1>
        
        <div class="product-search">
            <form method="GET" action="">
                <label for="id">Enter Product ID:</label>
                <input type="text" id="id" name="id" placeholder="Enter product ID (try: 1)">
                <button type="submit">SEARCH</button>
            </form>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($submitted && $product): ?>
            <div class="product-details">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><strong>Product ID:</strong> <?php echo htmlspecialchars($product['id']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                <p><strong>Price:</strong> Rs. <?php echo htmlspecialchars($product['price']); ?></p>
            </div>
        <?php elseif ($submitted && !$product): ?>
            <div class="not-found">Product not found.</div>
        <?php endif; ?>
        
        <div class="info-box">
            <h4>🔒 Security Features:</h4>
            <p>✓ Input Validation with filter_var()</p>
            <p>✓ FILTER_VALIDATE_INT enforces integer-only input</p>
            <p>✓ Time-based Blind Injection Blocked</p>
            <p>✓ Boolean-based Blind Injection Blocked</p>
            <p>✓ Error-based Injection Blocked</p>
            <p>✓ Type Safety Enforced</p>
        </div>
    </div>
</body>
</html>
