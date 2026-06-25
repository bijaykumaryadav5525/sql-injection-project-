# Setup and Installation Guide

## Quick Start (5 Minutes)

### Windows Users

1. **Download & Install**
   - Download XAMPP from https://www.apachefriends.org/
   - Run the installer and install to default location
   - Start Apache and MySQL from XAMPP Control Panel

2. **Copy Files**
   ```
   Copy project folder to: C:\xampp\htdocs\sql-injection-project\
   ```

3. **Create Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create new database: `injectable_db`
   - Import `database.sql` file
   - Click Import

4. **Access Application**
   ```
   http://localhost/sql-injection-project/login-vulnerable.php
   ```

### macOS Users

1. **Install Homebrew** (if not installed)
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. **Install PHP & MySQL**
   ```bash
   brew install php
   brew install mysql
   brew services start mysql
   ```

3. **Configure Database**
   ```bash
   mysql -u root
   CREATE DATABASE injectable_db;
   ```

4. **Import Schema**
   ```bash
   mysql -u root injectable_db < database.sql
   ```

5. **Start PHP Server**
   ```bash
   cd /path/to/project
   php -S localhost:8000
   ```

6. **Access Application**
   ```
   http://localhost:8000/login-vulnerable.php
   ```

### Linux Users (Ubuntu/Debian)

1. **Install Required Packages**
   ```bash
   sudo apt-get update
   sudo apt-get install php php-mysql mysql-server
   sudo systemctl start mysql
   ```

2. **Create Database**
   ```bash
   sudo mysql -u root
   CREATE DATABASE injectable_db;
   EXIT;
   ```

3. **Import Schema**
   ```bash
   sudo mysql -u root injectable_db < database.sql
   ```

4. **Update db.php Credentials**
   ```php
   // If MySQL requires sudo
   $user = 'root';
   $password = '';
   ```

5. **Start PHP Server**
   ```bash
   cd /path/to/project
   php -S localhost:8000
   ```

6. **Access Application**
   ```
   http://localhost:8000/login-vulnerable.php
   ```

---

## Detailed Setup Guide

### Step 1: Database Configuration

#### Option A: Using Command Line

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE injectable_db;

# Use the database
USE injectable_db;

# Import schema
SOURCE /path/to/database.sql;

# Verify tables
SHOW TABLES;
SELECT * FROM users;
SELECT * FROM products;
```

#### Option B: Using phpMyAdmin

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on "Databases"
3. Enter database name: `injectable_db`
4. Click "Create"
5. Select the new database
6. Click on "Import"
7. Upload `database.sql`
8. Click "Import"

### Step 2: Configure PHP Connection

Edit `db.php` with your credentials:

```php
<?php
$host = 'localhost';      // MySQL host
$db = 'injectable_db';    // Database name
$user = 'root';           // MySQL username
$password = '';           // MySQL password (blank for default)
$charset = 'utf8mb4';

// ... rest of connection code
?>
```

### Step 3: Set Proper Permissions

```bash
# Linux/macOS
chmod 755 .
chmod 644 *.php
chmod 644 *.sql
chmod 644 *.md

# Or more restrictive
chmod 600 db.php
```

### Step 4: Verify Installation

Visit each page and verify they work:

1. **Vulnerable Login**
   - URL: `http://localhost:8000/login-vulnerable.php`
   - Try: Username `admin`, Password `admin123`
   - Should display: Admin user details

2. **Patched Login**
   - URL: `http://localhost:8000/login-patched.php`
   - Try: Username `' OR '1'='1`, Password anything
   - Should display: "Incorrect username or password"

3. **Vulnerable Search**
   - URL: `http://localhost:8000/search-vulnerable.php`
   - Try: ID = `1`
   - Should display: Product details

4. **Patched Search**
   - URL: `http://localhost:8000/search-patched.php`
   - Try: ID = `0 UNION SELECT...`
   - Should display: "No results found"

5. **Vulnerable Product**
   - URL: `http://localhost:8000/product-vulnerable.php`
   - Try: ID = `1 AND SLEEP(5)--`
   - Should: Take 5 seconds to load

6. **Patched Product**
   - URL: `http://localhost:8000/product-patched.php`
   - Try: ID = `1 AND SLEEP(5)--`
   - Should: Load instantly

---

## Troubleshooting

### Issue: "Connection failed: No such file or directory"

**Solution**: MySQL server not running
```bash
# Start MySQL
sudo systemctl start mysql          # Linux
brew services start mysql           # macOS
# Use XAMPP Control Panel           # Windows
```

### Issue: "SQLSTATE[HY000] [1045] Access denied"

**Solution**: Wrong MySQL credentials in `db.php`
```bash
# Verify MySQL user
mysql -u root -p
# or
mysql -u root
```

### Issue: "Table 'injectable_db.users' doesn't exist"

**Solution**: Database not imported
```bash
# Reimport schema
mysql -u root injectable_db < database.sql

# Verify
mysql -u root injectable_db
SHOW TABLES;
```

### Issue: "PHP command not found"

**Solution**: PHP not installed or not in PATH
```bash
# Check PHP installation
php -v

# Install PHP
sudo apt-get install php           # Linux
brew install php                   # macOS
```

### Issue: Port 8000 already in use

**Solution**: Use different port
```bash
php -S localhost:8001
# or
php -S localhost:9000
```

---

## Testing Attacks

### Using Browser DevTools

1. Open Login Page
2. Press F12 to open DevTools
3. Go to "Console" tab
4. Try SQL injection payloads in form fields

### Using curl

```bash
# Test vulnerable login
curl -X POST http://localhost:8000/login-vulnerable.php \
  -d "username=' OR '1'='1&password=test"

# Test vulnerable search
curl "http://localhost:8000/search-vulnerable.php?id=1 OR 1=1"
```

### Using SQLmap

```bash
# Install sqlmap
git clone --depth 1 https://github.com/sqlmapproject/sqlmap.git sqlmap-dev
cd sqlmap-dev

# Test vulnerable login
python3 sqlmap.py -u "http://localhost:8000/login-vulnerable.php" \
  --data="username=admin&password=test" --dbs --batch

# Test vulnerable search
python3 sqlmap.py -u "http://localhost:8000/search-vulnerable.php?id=1" \
  --dbs --batch
```

---

## File Structure After Setup

```
sql-injection-project/
├── db.php                    # Database connection
├── database.sql              # Database schema and data
├── login-vulnerable.php      # Vulnerable login (Attack Demo 1)
├── login-patched.php        # Fixed login (PDO prepared statements)
├── search-vulnerable.php    # Vulnerable search (Attack Demo 2)
├── search-patched.php       # Fixed search (Input validation)
├── product-vulnerable.php   # Vulnerable product (Attack Demo 3)
├── product-patched.php      # Fixed product (Integer validation)
├── README.md                # Full documentation
├── SETUP.md                 # This file
├── .gitignore              # Git ignore rules
└── images/                 # (Optional) Screenshots
```

---

## Database Schema Overview

### users table
```
id | username | email              | password | role
1  | admin    | admin@securelab.com| admin123 | admin
2  | john     | john@example.com   | john123  | user
3  | sarah    | sarah@example.com  | sarah123 | user
```

### products table
```
id | name          | description                    | category    | price
1  | Laptop Pro    | High performance laptop        | Electronics | 134999
2  | Wireless Mouse| Ergonomic wireless mouse       | Accessories | 1299
3  | USB Cable     | Universal USB 3.0 cable       | Cables      | 299
```

---

## Next Steps

1. ✓ Access vulnerable pages and understand the attacks
2. ✓ Try the suggested test payloads
3. ✓ Study the vulnerable vs patched code
4. ✓ Learn the mitigation techniques
5. ✓ Try using SQLmap for automated testing
6. ✓ Read the full report in the project directory

---

## Getting Help

1. **Code Questions**: Read inline comments in PHP files
2. **SQL Questions**: Check database.sql file
3. **Attack Details**: See README.md Attack Demonstrations section
4. **Security Theory**: Visit OWASP.org

---

**Good luck with your learning! Remember: These attacks are real and organizations lose millions every year to SQL injection. Use this knowledge responsibly!**
