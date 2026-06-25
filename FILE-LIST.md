# SQL Injection Project - Complete File List

## Project Files

### Core Application Files

1. **db.php** (Database Connection)
   - Purpose: Central database connection file
   - Contains: PDO database configuration
   - Usage: Included in all PHP files
   - Status: Uses prepared statement example

2. **login-vulnerable.php** (Demo 1)
   - Purpose: Demonstrate authentication bypass vulnerability
   - Vulnerability Type: Direct string concatenation
   - Attack Method: SQL OR injection, Comment injection
   - Impact: Complete authentication bypass
   - Payloads: ' OR '1'='1, admin'#

3. **login-patched.php** (Fixed Version 1)
   - Purpose: Secure login using prepared statements
   - Security Method: PDO prepared statements with placeholders
   - Attack Prevention: Separates code from data
   - Status: Blocks all tested payloads

4. **search-vulnerable.php** (Demo 2)
   - Purpose: Demonstrate UNION-based SQL injection
   - Vulnerability Type: GET parameter without validation
   - Attack Method: UNION SELECT to extract data
   - Impact: Full database credential extraction
   - Payloads: 0 UNION SELECT columns FROM users

5. **search-patched.php** (Fixed Version 2)
   - Purpose: Secure search using input validation
   - Security Method: filter_var() with FILTER_VALIDATE_INT
   - Attack Prevention: Forces input to be integer only
   - Status: Blocks UNION injection attacks

6. **product-vulnerable.php** (Demo 3)
   - Purpose: Demonstrate blind SQL injection
   - Vulnerability Type: Time-based and Boolean-based blind
   - Attack Method: SLEEP() and conditional logic
   - Impact: Information disclosure through timing/response
   - Payloads: 1 AND SLEEP(5), 1 AND 1=1/1=2

7. **product-patched.php** (Fixed Version 3)
   - Purpose: Secure product lookup using validation
   - Security Method: filter_var() with FILTER_VALIDATE_INT
   - Attack Prevention: Integer-only input enforcement
   - Status: Blocks blind injection attacks

### Database Files

8. **database.sql** (Database Schema)
   - Purpose: Create and populate test database
   - Contains: Table definitions and test data
   - Tables: users, products
   - Test Users: admin, john, sarah
   - Test Products: 3 sample products
   - Usage: Import into MySQL using phpMyAdmin or CLI

### Documentation Files

9. **README.md** (Main Documentation)
   - Purpose: Complete project overview and reference
   - Sections: 
     * Project overview
     * Setup instructions
     * Attack demonstrations with payloads
     * Mitigation techniques
     * Learning outcomes
     * References
     * Legal disclaimer
   - Length: Comprehensive guide
   - Audience: Students, Developers, Security Professionals

10. **SETUP.md** (Installation Guide)
    - Purpose: Step-by-step installation instructions
    - Covers: Windows, macOS, Linux
    - Includes: Quick start (5 minutes)
    - Includes: Detailed setup
    - Includes: Troubleshooting guide
    - Includes: Testing instructions
    - Includes: Verification steps

11. **.gitignore** (Git Configuration)
    - Purpose: Exclude sensitive files from repository
    - Excludes: IDE files, logs, database files
    - Excludes: Sensitive config files
    - Usage: Automatically used by git

12. **FILE-LIST.md** (This File)
    - Purpose: Describe all project files
    - Helps: Understanding project structure
    - Useful: For documentation purposes

---

## File Organization

```
sql-injection-project/
│
├── Core Files (7 PHP files)
│   ├── db.php
│   ├── login-vulnerable.php
│   ├── login-patched.php
│   ├── search-vulnerable.php
│   ├── search-patched.php
│   ├── product-vulnerable.php
│   └── product-patched.php
│
├── Database Files (1 SQL file)
│   └── database.sql
│
├── Documentation (5 files)
│   ├── README.md
│   ├── SETUP.md
│   ├── FILE-LIST.md (this file)
│   ├── .gitignore
│   └── LICENSE (optional)
│
└── (Optional)
    ├── screenshots/ (images of attacks)
    ├── payloads.txt (attack payloads)
    └── notes/ (additional notes)
```

---

## Quick Reference

### For Attackers (Learning)

1. Start with: `login-vulnerable.php`
   - Test payloads: ' OR '1'='1

2. Try next: `search-vulnerable.php`
   - Test payloads: 0 UNION SELECT 1,username,password,role,email FROM users--

3. Then try: `product-vulnerable.php`
   - Test payloads: 1 AND SLEEP(5)--

### For Defenders (Learning)

1. Compare vulnerable with patched versions:
   - Look at: db.php vs login code
   - Understand: PDO prepared statements

2. Study mitigation in search-patched.php:
   - Learn: filter_var() usage
   - Practice: input validation

3. Apply in product-patched.php:
   - Implement: FILTER_VALIDATE_INT
   - Prevent: Blind SQL injection

### For Penetration Testers

1. Use sqlmap against vulnerable pages:
   ```bash
   sqlmap -u "http://localhost:8000/login-vulnerable.php" \
     --data="username=admin&password=test" --dbs
   ```

2. Test patched pages:
   ```bash
   sqlmap -u "http://localhost:8000/login-patched.php" \
     --data="username=admin&password=test" --dbs
   ```

3. Verify patches work:
   - Should return: "NOT VULNERABLE"

---

## Test Data

### User Credentials
```
admin / admin123
john / john123
sarah / sarah123
```

### Product IDs
```
ID 1: Laptop Pro
ID 2: Wireless Mouse
ID 3: USB Cable
```

---

## Key Attack Payloads

### Authentication Bypass
- `' OR '1'='1` - Universal bypass
- `admin'#` - Targeted bypass
- `' OR 1=1--` - Comment-based bypass

### UNION Injection
- `1 ORDER BY 5--` - Column detection
- `0 UNION SELECT 1,2,3,4,5--` - Column mapping
- `0 UNION SELECT 1,username,password,role,email FROM users--` - Data extraction

### Blind Injection
- `1 AND SLEEP(5)--` - Time-based detection
- `1 AND 1=1` - Boolean true
- `1 AND 1=2` - Boolean false

---

## Implementation Summary

| Attack Type | Vulnerable Page | Patched Page | Defense Method |
|------------|-----------------|--------------|-----------------|
| Auth Bypass | login-vulnerable | login-patched | PDO Prepared Statements |
| UNION Injection | search-vulnerable | search-patched | Input Validation (int) |
| Blind Injection | product-vulnerable | product-patched | Integer Cast (int) |

---

## Learning Path

1. **Beginner** (Day 1)
   - Read: README.md introduction
   - Run: SETUP.md installation
   - Try: Test valid login

2. **Intermediate** (Day 2-3)
   - Study: login-vulnerable.php code
   - Test: Authentication bypass payloads
   - Compare: With login-patched.php
   - Learn: PDO prepared statements

3. **Advanced** (Day 4-5)
   - Study: search-vulnerable.php code
   - Test: UNION injection payloads
   - Analyze: search-patched.php defenses
   - Learn: Input validation techniques

4. **Expert** (Day 6+)
   - Study: product-vulnerable.php code
   - Test: Blind injection payloads
   - Test: With sqlmap automation
   - Learn: Multiple defense layers
   - Implement: Defense-in-depth strategy

---

## File Statistics

- **Total Files**: 12
- **PHP Files**: 7
- **SQL Files**: 1
- **Documentation Files**: 4
- **Total Lines of Code**: ~2000+
- **Total Lines of Documentation**: ~3000+
- **Security Demonstrations**: 3 attack types
- **Mitigation Examples**: 3 techniques

---

## Before Using This Project

✓ Read the disclaimer in README.md
✓ Understand it's for education only
✓ Ensure you have authorization to test
✓ Follow responsible disclosure practices
✓ Never use on production systems
✓ Respect privacy and data protection

---

## Support Files

If you need additional files:

1. **payloads.txt** - All attack payloads listed
   - Can request: Add to project
   
2. **screenshots/** - Attack demonstration images
   - Can add: Before/after vulnerability testing
   
3. **notes/** - Additional learning materials
   - Can create: For specific topics

---

## Version Information

- **Project Version**: 1.0
- **Created**: May 2026
- **Last Updated**: May 15, 2026
- **PHP Version**: 7.4+
- **MySQL Version**: 5.7+
- **Status**: Educational Use Only

---

**All files are ready to use. Start with SETUP.md for installation!**
