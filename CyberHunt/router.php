<?php
/**
 * CyberHunt Router
 * Handles all incoming requests and provides proper 404 handling
 * Usage: php -S localhost:8080 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

// If it's the root, serve index.php
if ($uri === '/') {
    require __DIR__ . '/index.php';
    return true;
}

// Check if file exists
if (file_exists($filePath)) {
    // If it's a PHP file, include it
    if (pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
        require $filePath;
        return true;
    }
    
    // If it's a directory, try index.php
    if (is_dir($filePath)) {
        $indexPath = rtrim($filePath, '/') . '/index.php';
        if (file_exists($indexPath)) {
            require $indexPath;
            return true;
        }
    }
    
    // Let PHP serve static files (CSS, JS, images, etc.)
    return false;
}

// Check if it's a PHP file without extension
if (file_exists($filePath . '.php')) {
    require $filePath . '.php';
    return true;
}

// 404 - Page Not Found
http_response_code(404);
require __DIR__ . '/404.php';
return true;
?>
