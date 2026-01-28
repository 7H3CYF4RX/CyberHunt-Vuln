<?php
/**
 * CyberHunt - Database Configuration
 * A modern e-commerce platform
 */

// Database configuration
define('DB_TYPE', 'sqlite'); // Using SQLite for portability
define('DB_PATH', __DIR__ . '/../database/cyberhunt.db');

// Site configuration
define('SITE_NAME', 'CyberHunt');
define('SITE_URL', 'http://localhost');
define('SITE_VERSION', '2.1.4');

// Session configuration - only start if not already active
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Error reporting (hide from display for production-like experience)
error_reporting(E_ALL);
ini_set('display_errors', 0);

/**
 * Database connection class
 */
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $this->pdo = new PDO('sqlite:' . DB_PATH);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    // Intentionally unsafe query method
    public function rawQuery($sql) {
        return $this->pdo->query($sql);
    }
    
    // Safe query method with prepared statements
    public function safeQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}

/**
 * Helper functions
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

// Generate CSRF token (intentionally weak implementation)
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = md5(time()); // Weak token generation
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}
?>
