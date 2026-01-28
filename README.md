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

## 🎟️ Coupon Codes

| Code | Discount | Notes |
|------|----------|-------|
| `WELCOME10` | 10% off | Popup after login |
| `GRAND20` | 20% off | Shown in banner |
| `FLAT50` | $50 off | Hidden |

**Vulnerability:** Alternate between coupons to stack unlimited discounts!

---

## 🔥 Vulnerabilities Included

### Coverage

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

## 👥 Contributors

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/MRG6OOT">
        <b>@MRG6OOT</b>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/Sensei-Glitch99">
        <b>@Sensei-Glitch99</b>
      </a>
    </td>
  </tr>
</table>

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
## STILL IN DEVELOPMENT - MAY CONTAIN ERRORS

# HAPPY HACKING 

Lab By
MUHAMMED FARHAN
