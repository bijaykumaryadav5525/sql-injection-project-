<?php
// VULNERABLE CODE - FOR EDUCATIONAL PURPOSES ONLY
// This code is vulnerable to time-based and boolean-based blind SQL injection

include 'db.php';

$product = null;
$error = "";
$submitted = false;

if (!empty($_GET['id'])) {
    $submitted = true;
    // VULNERABLE: Direct string concatenation without prepared statements
    $id = $_GET['id'];
    
    $SQL = "SELECT * FROM products WHERE id=$id";

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VULNERABLE - Product Details</title>
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
        .product-search {
            margin-bottom: 30px;
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
            background-color: #ff6666;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
        .product-search button:hover {
            background-color: #ff5555;
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
        .test-payloads {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            margin-top: 30px;
            font-size: 12px;
        }
        .test-payloads h4 {
            margin-top: 0;
        }
        .test-payloads p {
            margin: 5px 0;
            font-family: monospace;
            background-color: #f9f9f9;
            padding: 5px;
            border-left: 2px solid #ff9800;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning">⚠️ VULNERABLE CODE - EDUCATIONAL ONLY</div>
        
        <h1>Product Details (VULNERABLE)</h1>
        
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
        
        <div class="test-payloads">
            <h4>Test Payloads (Educational - Blind Injection):</h4>
            <p>Payload 1 - Error-based (Check for single quote):</p>
            <p>1'</p>
            <hr>
            <p>Payload 2 - Time-based Blind Injection:</p>
            <p>1 AND SLEEP(5)--</p>
            <hr>
            <p>Payload 3 - Boolean-based True Condition:</p>
            <p>1 AND 1=1</p>
            <hr>
            <p>Payload 4 - Boolean-based False Condition:</p>
            <p>1 AND 1=2</p>
            <hr>
            <p>Valid Product ID:</p>
            <p>1 (or 2, 3)</p>
        </div>
    </div>
</body>
</html>
