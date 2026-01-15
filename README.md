# CyberHunt - E-Commerce Platform

A modern e-commerce web application built with PHP and Bootstrap 5.

## Quick Start

1. **Initialize Database**
   ```bash
   cd database
   php init.php
   ```

2. **Start PHP Server**
   ```bash
   php -S localhost:8080
   OR 
   ./start.sh
   ```

3. **Access the Application**
   Open http://localhost:8080/CyberHunt/ in your browser

4. **Create an Account**
   Register a new account to start using the platform

## Features

- **User Registration & Login** - Full authentication system
- **Product Catalog** - 50+ products across multiple categories
- **Shopping Cart** - Add, update, remove items
- **Checkout System** - Complete order placement
- **User Profiles** - Profile management with photo upload
- **Order History** - Track past orders
- **Product Reviews** - Leave and read reviews
- **Admin Panel** - Manage users, products, orders
- **Contact Form** - Customer support
- **Search Functionality** - Find products quickly
- **Coupon System** - Apply discount codes
- **Messaging System** - User-to-user messaging
- **Data Export** - Download personal data

## Technology Stack

- **Backend**: PHP 8.x
- **Frontend**: Bootstrap 5, Custom CSS
- **Database**: SQLite
- **Icons**: Bootstrap Icons
- **Fonts**: Inter (Google Fonts)

## Directory Structure

```
CyberHunt/
├── admin/              # Admin panel pages
├── assets/             # CSS, JS, images
│   ├── css/
│   └── images/
├── config/             # Configuration files
├── database/           # SQLite database & init script
├── exports/            # User data exports
├── includes/           # Header & footer templates
├── uploads/            # User uploaded files
│   └── profiles/       # Profile pictures
└── *.php               # Main application pages
```

## Pages

1. Home (`index.php`)
2. Login (`login.php`)
3. Register (`register.php`)
4. Products (`products.php`)
5. Product Detail (`product.php`)
6. Search (`search.php`)
7. Cart (`cart.php`)
8. Checkout (`checkout.php`)
9. Profile (`profile.php`)
10. Edit Profile (`edit-profile.php`)
11. Orders (`orders.php`)
12. Order Detail (`order.php`)
13. Messages (`messages.php`)
14. Settings (`settings.php`)
15. Contact (`contact.php`)
16. About (`about.php`)
17. Help (`help.php`)
18. FAQ (`faq.php`)
19. Terms (`terms.php`)
20. Privacy (`privacy.php`)
21. Shipping (`shipping.php`)
22. Returns (`returns.php`)
23. Export (`export.php`)
24. Admin Dashboard (`admin/index.php`)
25. Admin Users (`admin/users.php`)

## Coupon Codes

| Code | Discount |
|------|----------|
| WELCOME10 | 10% off |
| SAVE20 | 20% off |
| FLAT25 | $25 off |
| VIP50 | 50% off |
| FREESHIP | Free shipping |

## License

For educational purposes only.
