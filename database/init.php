<?php
/**
 * CyberHunt - Database Initialization Script
 * Creates database schema and populates with 100 users
 */

$dbPath = __DIR__ . '/cyberhunt.db';

// Remove existing database
if (file_exists($dbPath)) {
    unlink($dbPath);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$pdo->exec("
    -- Users table
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100),
        phone VARCHAR(20),
        address TEXT,
        city VARCHAR(50),
        country VARCHAR(50),
        bio TEXT,
        profile_pic VARCHAR(255) DEFAULT 'default.jpg',
        role VARCHAR(20) DEFAULT 'user',
        balance DECIMAL(10,2) DEFAULT 1000.00,
        secret_question VARCHAR(255),
        secret_answer VARCHAR(255),
        reset_token VARCHAR(100),
        reset_expiry DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        last_login DATETIME,
        is_active INTEGER DEFAULT 1
    );

    -- Products table
    CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(200) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        sale_price DECIMAL(10,2),
        category VARCHAR(50),
        stock INTEGER DEFAULT 100,
        image VARCHAR(255),
        rating DECIMAL(3,2) DEFAULT 0,
        reviews_count INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    -- Orders table
    CREATE TABLE orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        order_number VARCHAR(50) UNIQUE,
        total_amount DECIMAL(10,2),
        status VARCHAR(20) DEFAULT 'pending',
        shipping_address TEXT,
        payment_method VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    -- Order items table
    CREATE TABLE order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER,
        product_id INTEGER,
        quantity INTEGER,
        price DECIMAL(10,2),
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );

    -- Reviews table
    CREATE TABLE reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        product_id INTEGER,
        rating INTEGER,
        title VARCHAR(200),
        comment TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );

    -- Cart table
    CREATE TABLE cart (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        product_id INTEGER,
        quantity INTEGER DEFAULT 1,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    );

    -- Messages table
    CREATE TABLE messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        sender_id INTEGER,
        receiver_id INTEGER,
        subject VARCHAR(200),
        message TEXT,
        is_read INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id)
    );

    -- Contact submissions
    CREATE TABLE contact_submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(100),
        email VARCHAR(100),
        subject VARCHAR(200),
        message TEXT,
        ip_address VARCHAR(45),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    -- Activity logs
    CREATE TABLE activity_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        action VARCHAR(100),
        details TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    -- Password reset tokens
    CREATE TABLE password_resets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        token VARCHAR(100),
        expiry DATETIME,
        used INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    -- User files
    CREATE TABLE user_files (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        filename VARCHAR(255),
        original_name VARCHAR(255),
        file_type VARCHAR(50),
        file_size INTEGER,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    -- Coupons table
    CREATE TABLE coupons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code VARCHAR(50) UNIQUE,
        discount_type VARCHAR(20),
        discount_value DECIMAL(10,2),
        min_order DECIMAL(10,2) DEFAULT 0,
        max_uses INTEGER DEFAULT 100,
        used_count INTEGER DEFAULT 0,
        expiry_date DATE,
        is_active INTEGER DEFAULT 1
    );
");

// Generate 100 users with realistic data
$firstNames = ['James', 'Mary', 'John', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda', 'William', 'Elizabeth', 
    'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Charles', 'Karen',
    'Christopher', 'Nancy', 'Daniel', 'Lisa', 'Matthew', 'Betty', 'Anthony', 'Margaret', 'Mark', 'Sandra',
    'Donald', 'Ashley', 'Steven', 'Kimberly', 'Paul', 'Emily', 'Andrew', 'Donna', 'Joshua', 'Michelle',
    'Kenneth', 'Dorothy', 'Kevin', 'Carol', 'Brian', 'Amanda', 'George', 'Melissa', 'Edward', 'Deborah'];

$lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
    'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
    'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson',
    'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores'];

$cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 
    'Dallas', 'San Jose', 'Austin', 'Jacksonville', 'Fort Worth', 'Columbus', 'Charlotte', 'Seattle', 'Denver',
    'Boston', 'Nashville', 'Portland'];

$countries = ['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany'];

$bios = [
    'Tech enthusiast and avid online shopper. Love finding great deals!',
    'Professional developer with a passion for e-commerce innovation.',
    'Small business owner exploring digital marketplace solutions.',
    'Digital marketing specialist focused on consumer behavior.',
    'Freelance designer who appreciates good UX in shopping platforms.',
    'Entrepreneur always on the lookout for quality products.',
    'Software engineer with an interest in secure online transactions.',
    'Content creator reviewing the best online shopping experiences.',
    'Data analyst tracking e-commerce trends and patterns.',
    'Customer service professional who values seamless shopping.'
];

$secretQuestions = [
    'What is your mother\'s maiden name?',
    'What was the name of your first pet?',
    'What city were you born in?',
    'What is your favorite movie?',
    'What was your childhood nickname?'
];

$stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, phone, address, city, country, bio, role, balance, secret_question, secret_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Create admin user
$stmt->execute([
    'admin',
    'admin@cyberhunt.local',
    md5('CyberCrews@123'), // Weak password hashing
    'System Administrator',
    '+1-555-0100',
    '100 Admin Street',
    'New York',
    'United States',
    'CyberHunt Platform Administrator',
    'admin',
    99999.99,
    'What is your favorite security tool?',
    'burpsuite'
]);

// Create test user
$stmt->execute([
    'testuser',
    'test@cyberhunt.local',
    md5('password123'),
    'Test User Account',
    '+1-555-0101',
    '123 Test Avenue',
    'Los Angeles',
    'United States',
    'This is a test account for demonstration purposes.',
    'user',
    5000.00,
    'What is your favorite color?',
    'blue'
]);

// Generate 98 more users
for ($i = 3; $i <= 100; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $city = $cities[array_rand($cities)];
    $country = $countries[array_rand($countries)];
    
    $username = strtolower($firstName . $lastName . rand(1, 99));
    $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@example.com');
    $password = md5('user' . $i . '123'); // Predictable passwords
    $fullName = $firstName . ' ' . $lastName;
    $phone = '+1-555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    $address = rand(100, 9999) . ' ' . $lastNames[array_rand($lastNames)] . ' ' . ['Street', 'Avenue', 'Boulevard', 'Drive', 'Lane'][rand(0, 4)];
    $bio = $bios[array_rand($bios)];
    $balance = rand(100, 10000) + (rand(0, 99) / 100);
    $secretQuestion = $secretQuestions[array_rand($secretQuestions)];
    $secretAnswer = strtolower(['blue', 'fluffy', 'new york', 'inception', 'buddy'][array_rand([0, 1, 2, 3, 4])]);
    
    $stmt->execute([
        $username,
        $email,
        $password,
        $fullName,
        $phone,
        $address,
        $city,
        $country,
        $bio,
        'user',
        $balance,
        $secretQuestion,
        $secretAnswer
    ]);
}

// Insert products
$products = [
    ['Wireless Bluetooth Headphones Pro', 'Premium noise-canceling headphones with 40-hour battery life. Features advanced ANC technology, premium drivers for crystal-clear audio, and ultra-comfortable memory foam ear cushions. Perfect for music lovers and professionals alike.', 299.99, 249.99, 'Electronics', 'headphones.svg'],
    ['Smart Watch Series X', 'Advanced fitness tracking smartwatch with heart rate monitor, GPS, and AMOLED display. Track your workouts, monitor your sleep, and stay connected with smart notifications.', 399.99, 349.99, 'Electronics', 'smartwatch.svg'],
    ['Mechanical Gaming Keyboard RGB', 'Professional gaming keyboard with Cherry MX switches, per-key RGB lighting, and aircraft-grade aluminum frame. Includes dedicated media controls and USB passthrough.', 149.99, 129.99, 'Electronics', 'keyboard.svg'],
    ['Ultra HD 4K Webcam', 'Crystal clear 4K video calls with auto-focus, HDR, and noise-canceling microphones. Perfect for remote work, streaming, and content creation.', 179.99, 159.99, 'Electronics', 'webcam.svg'],
    ['Premium Laptop Stand Aluminum', 'Ergonomic laptop stand crafted from premium aluminum. Improves posture and airflow. Compatible with all laptops up to 17 inches.', 79.99, 69.99, 'Accessories', 'laptop_stand.svg'],
    ['Wireless Charging Pad Fast', 'Fast wireless charging pad compatible with all Qi-enabled devices. Sleek design with LED indicator and overheat protection.', 49.99, 39.99, 'Electronics', 'charger.svg'],
    ['Noise Canceling Earbuds Pro', 'True wireless earbuds with active noise cancellation, transparency mode, and 30-hour battery life with charging case.', 199.99, 179.99, 'Electronics', 'headphones.svg'],
    ['USB-C Hub 7-in-1', 'Premium aluminum USB-C hub with HDMI 4K, SD card reader, USB 3.0 ports, and 100W power delivery. Perfect for MacBook and laptops.', 89.99, 79.99, 'Accessories', 'usb_hub.svg'],
    ['Gaming Mouse Wireless', 'High-precision wireless gaming mouse with 25K DPI sensor, 70-hour battery life, and customizable RGB lighting.', 129.99, 109.99, 'Electronics', 'mouse.svg'],
    ['Portable SSD 1TB External', 'Ultra-fast portable SSD with read speeds up to 1050MB/s. Compact design, shock resistant, and compatible with PC, Mac, and gaming consoles.', 149.99, 129.99, 'Storage', 'ssd.svg'],
    ['Smart Home Speaker', 'Voice-controlled smart speaker with premium sound quality, smart home integration, and virtual assistant support.', 99.99, 89.99, 'Electronics', 'speaker.svg'],
    ['LED Desk Lamp Smart', 'Adjustable LED desk lamp with wireless charging base, multiple color temperatures, and touch controls.', 69.99, 59.99, 'Home Office', 'desk_lamp.svg'],
    ['Ergonomic Office Chair Pro', 'Premium ergonomic office chair with lumbar support, adjustable armrests, and breathable mesh back. Built for all-day comfort.', 499.99, 449.99, 'Furniture', 'office_chair.svg'],
    ['Mechanical Pencil Set Premium', 'Professional drafting set with multiple lead sizes, erasers, and carrying case. Perfect for artists and engineers.', 34.99, 29.99, 'Stationery', 'notebook.svg'],
    ['Leather Messenger Bag', 'Handcrafted genuine leather messenger bag with padded laptop compartment. Fits up to 15-inch laptops.', 189.99, 169.99, 'Bags', 'laptop_stand.svg'],
    ['Wireless Presenter Remote', 'Professional wireless presenter with laser pointer, volume controls, and 100ft range. Perfect for presentations.', 49.99, 44.99, 'Accessories', 'mouse.svg'],
    ['Monitor Arm Dual', 'Heavy-duty dual monitor arm with full motion adjustment. Supports monitors up to 32 inches and 20 lbs each.', 129.99, 119.99, 'Accessories', 'monitor.svg'],
    ['Cable Management Kit', 'Complete cable management solution with cable sleeves, clips, and labels. Keep your workspace organized.', 29.99, 24.99, 'Accessories', 'usb_hub.svg'],
    ['Blue Light Blocking Glasses', 'Stylish computer glasses that reduce eye strain and improve sleep. Available in multiple frame styles.', 39.99, 34.99, 'Accessories', 'webcam.svg'],
    ['Desk Organizer Wooden', 'Elegant wooden desk organizer with compartments for pens, phones, and accessories. Handcrafted from sustainable bamboo.', 44.99, 39.99, 'Home Office', 'notebook.svg'],
    ['Wireless Keyboard Compact', 'Slim wireless keyboard with quiet keys, multi-device support, and 2-year battery life.', 79.99, 69.99, 'Electronics', 'keyboard.svg'],
    ['Privacy Screen Filter 24inch', 'Anti-spy privacy screen filter that limits viewing angle to protect sensitive information.', 59.99, 54.99, 'Accessories', 'monitor.svg'],
    ['Microphone USB Condenser', 'Professional USB condenser microphone for podcasting, streaming, and recording. Includes pop filter and shock mount.', 149.99, 134.99, 'Electronics', 'microphone.svg'],
    ['Ring Light LED 18inch', 'Professional LED ring light with tripod stand, phone holder, and remote control. Perfect for content creators.', 89.99, 79.99, 'Electronics', 'desk_lamp.svg'],
    ['Wrist Rest Gel Premium', 'Ergonomic gel wrist rest that reduces strain during typing. Non-slip base and cooling gel technology.', 24.99, 21.99, 'Accessories', 'mouse_pad.svg'],
    ['Screen Cleaning Kit', 'Professional screen cleaning kit with microfiber cloths and streak-free cleaning solution. Safe for all screens.', 14.99, 12.99, 'Accessories', 'monitor.svg'],
    ['Document Scanner Portable', 'Compact portable scanner with WiFi connectivity. Scan documents directly to cloud storage.', 199.99, 179.99, 'Electronics', 'tablet.svg'],
    ['Footrest Adjustable', 'Ergonomic adjustable footrest with massage surface. Improves posture and reduces leg fatigue.', 49.99, 44.99, 'Furniture', 'office_chair.svg'],
    ['Whiteboard Magnetic Large', 'Large magnetic whiteboard with aluminum frame. Includes markers, eraser, and mounting hardware.', 129.99, 119.99, 'Home Office', 'monitor.svg'],
    ['Power Strip Surge Protector', 'Smart power strip with USB ports, surge protection, and individual outlet switches.', 39.99, 34.99, 'Electronics', 'charger.svg'],
    ['Laptop Cooling Pad', 'Powerful laptop cooling pad with 5 fans, adjustable height, and RGB lighting. Keeps your laptop cool under load.', 49.99, 44.99, 'Accessories', 'laptop_stand.svg'],
    ['Digital Drawing Tablet', 'Professional drawing tablet with 8192 pressure levels, tilt support, and customizable express keys.', 249.99, 229.99, 'Electronics', 'tablet.svg'],
    ['Webcam Cover Slide', 'Ultra-thin webcam cover that protects your privacy. Works with laptops, tablets, and external webcams.', 9.99, 7.99, 'Accessories', 'webcam.svg'],
    ['Desk Mat Extended', 'Extra-large desk mat with premium stitched edges. Provides smooth surface for mouse and protects desk.', 34.99, 29.99, 'Accessories', 'mouse_pad.svg'],
    ['Network Switch 8-Port', 'Gigabit network switch with 8 ports, metal housing, and plug-and-play setup.', 39.99, 34.99, 'Electronics', 'usb_hub.svg'],
    ['Ethernet Cable Cat8 10ft', 'High-speed Cat8 ethernet cable with gold-plated connectors. Supports 40Gbps speeds.', 19.99, 16.99, 'Accessories', 'charger.svg'],
    ['USB Flash Drive 256GB', 'High-speed USB 3.1 flash drive with metal housing and keychain loop.', 34.99, 29.99, 'Storage', 'flash_drive.svg'],
    ['Desk Clock Digital', 'Modern digital desk clock with LED display, alarm, and temperature display.', 29.99, 26.99, 'Home Office', 'smartwatch.svg'],
    ['Pen Holder Cup', 'Elegant pen holder crafted from stainless steel. Modern design complements any workspace.', 19.99, 17.99, 'Stationery', 'notebook.svg'],
    ['Sticky Notes Premium Pack', 'Premium sticky notes in multiple sizes and colors. 1000 sheets total with strong adhesive.', 14.99, 12.99, 'Stationery', 'notebook.svg'],
    ['Notebook Leather Bound', 'Premium leather-bound notebook with 200 pages of acid-free paper. Includes ribbon bookmark.', 29.99, 26.99, 'Stationery', 'notebook.svg'],
    ['Business Card Holder', 'Professional business card holder in brushed aluminum. Holds up to 50 cards.', 19.99, 17.99, 'Accessories', 'phone_case.svg'],
    ['Photo Frame Digital WiFi', 'WiFi-enabled digital photo frame with 10-inch HD display. Share photos directly from your phone.', 129.99, 119.99, 'Electronics', 'tablet.svg'],
    ['Plant Pot Smart Self-Watering', 'Smart plant pot with water level indicator and self-watering system. Perfect for office plants.', 34.99, 31.99, 'Home Office', 'desk_lamp.svg'],
    ['Air Purifier Desktop', 'Compact desktop air purifier with HEPA filter. Removes 99.97% of particles.', 79.99, 71.99, 'Home Office', 'speaker.svg'],
    ['Humidifier USB Mini', 'Mini USB humidifier with LED night light. Perfect for desk or bedside.', 24.99, 21.99, 'Home Office', 'charger.svg'],
    ['Stress Ball Set', 'Set of 3 premium stress balls in different densities. Helps reduce stress and improve grip strength.', 14.99, 12.99, 'Accessories', 'mouse.svg'],
    ['Eye Mask Sleep Bluetooth', 'Bluetooth sleep mask with built-in headphones. Block light while listening to relaxing sounds.', 39.99, 35.99, 'Accessories', 'headphones.svg'],
    ['Coffee Mug Insulated', 'Premium insulated coffee mug that keeps drinks hot for 6 hours. Spill-proof lid included.', 29.99, 26.99, 'Home Office', 'desk_lamp.svg'],
    ['Water Bottle Smart', 'Smart water bottle with temperature display and hydration reminders. Keeps drinks cold for 24 hours.', 39.99, 35.99, 'Accessories', 'charger.svg']
];

$productStmt = $pdo->prepare("INSERT INTO products (name, description, price, sale_price, category, image, rating, reviews_count, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($products as $product) {
    $productStmt->execute([
        $product[0],
        $product[1],
        $product[2],
        $product[3],
        $product[4],
        $product[5],
        round(rand(35, 50) / 10, 1), // Random rating 3.5-5.0
        rand(10, 500), // Random review count
        rand(10, 200) // Random stock
    ]);
}

// Insert sample reviews
$reviewStmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, title, comment) VALUES (?, ?, ?, ?, ?)");

$reviewTitles = [
    'Excellent product!', 'Highly recommended', 'Great value for money', 'Exactly what I needed',
    'Very satisfied', 'Good quality', 'Fast shipping', 'Works perfectly', 'Love it!', 'Best purchase ever'
];

$reviewComments = [
    'This product exceeded my expectations. The quality is outstanding and it arrived quickly.',
    'I have been using this for a month now and it works flawlessly. Highly recommend to anyone looking for a reliable product.',
    'Great value for the price. The build quality is impressive and it does exactly what it is supposed to do.',
    'After extensive research, I chose this product and I am so glad I did. It is perfect for my needs.',
    'The packaging was excellent and the product was exactly as described. Very happy with my purchase.',
    'I was skeptical at first but this product proved me wrong. It is now an essential part of my daily workflow.',
    'Customer service was helpful and the product quality is top-notch. Would buy again.',
    'This is my second purchase from this brand and they continue to impress. Excellent quality.',
    'Fast delivery and the product works great. No complaints at all. Five stars!',
    'Perfect for home office setup. The ergonomics are well thought out and it looks premium.'
];

for ($i = 0; $i < 100; $i++) {
    $reviewStmt->execute([
        rand(1, 100), // Random user
        rand(1, 50), // Random product
        rand(3, 5), // Rating 3-5
        $reviewTitles[array_rand($reviewTitles)],
        $reviewComments[array_rand($reviewComments)]
    ]);
}

// Insert sample orders
$orderStmt = $pdo->prepare("INSERT INTO orders (user_id, order_number, total_amount, status, shipping_address, payment_method) VALUES (?, ?, ?, ?, ?, ?)");

$statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
$paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'crypto'];

for ($i = 0; $i < 50; $i++) {
    $userId = rand(1, 100);
    $orderNumber = 'ORD-' . date('Y') . '-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);
    $totalAmount = rand(50, 1000) + (rand(0, 99) / 100);
    $status = $statuses[array_rand($statuses)];
    $shippingAddress = rand(100, 9999) . ' Random Street, City, Country 12345';
    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
    
    $orderStmt->execute([$userId, $orderNumber, $totalAmount, $status, $shippingAddress, $paymentMethod]);
}

// Insert coupons
$couponStmt = $pdo->prepare("INSERT INTO coupons (code, discount_type, discount_value, min_order, expiry_date) VALUES (?, ?, ?, ?, ?)");

$coupons = [
    ['WELCOME10', 'percentage', 10, 50, '2026-12-31'],
    ['SAVE20', 'percentage', 20, 100, '2026-06-30'],
    ['FLAT25', 'fixed', 25, 75, '2026-03-31'],
    ['VIP50', 'percentage', 50, 200, '2026-12-31'],
    ['FREESHIP', 'fixed', 15, 0, '2026-12-31']
];

foreach ($coupons as $coupon) {
    $couponStmt->execute($coupon);
}

echo "Database initialized successfully!\n";
echo "Created 100 users\n";
echo "Created 50 products\n";
echo "Created 100 reviews\n";
echo "Created 50 orders\n";
echo "Created 5 coupons\n";
echo "\nYou can now register a new account at the site.\n";
?>
