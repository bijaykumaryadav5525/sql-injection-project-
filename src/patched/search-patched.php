<?php
// PATCHED CODE - Using Input Validation with Integer Cast
// This is the secure version that prevents SQL injection

include 'db.php';

$results = [];
$error = "";
$submitted = false;

if (!empty($_GET['id'])) {
    $submitted = true;
    
    // SECURE: Validate and cast input to integer
    $id_val = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    if ($id_val !== false) {
        // Now the input is guaranteed to be an integer
        $query = "SELECT id, name, description, category, price FROM products WHERE id = " . $id_val;

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
    } else {
        $error = "Invalid input - Please enter a valid number";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATCHED - Search</title>
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
        .search-box {
            margin-bottom: 30px;
        }
        .search-box label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
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
            background-color: #66cc66;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }
        .search-box button:hover {
            background-color: #55bb55;
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
        
        <h1>Find a User (SECURE)</h1>
        
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
        
        <div class="info-box">
            <h4>🔒 Security Features:</h4>
            <p>✓ Input Validation with filter_var()</p>
            <p>✓ FILTER_VALIDATE_INT ensures integer-only input</p>
            <p>✓ UNION Injection Blocked</p>
            <p>✓ Schema Enumeration Protected</p>
            <p>✓ Type Safety Enforced</p>
        </div>
    </div>
</body>
</html>
