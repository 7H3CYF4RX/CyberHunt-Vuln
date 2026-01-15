<?php
/**
 * CyberHunt API - User Data Export
 * Vulnerable to XXE and Path Traversal
 */

header('Content-Type: application/json');
require_once __DIR__ . '/config/database.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'export':
        // Path traversal vulnerability
        $format = $_GET['format'] ?? 'json';
        $userId = $_GET['user_id'] ?? '';
        
        if (empty($userId)) {
            echo json_encode(['error' => 'User ID required']);
            exit;
        }
        
        $db = getDB();
        $user = $db->query("SELECT * FROM users WHERE id = $userId")->fetch(PDO::FETCH_ASSOC); // SQLi
        
        if ($format === 'xml') {
            header('Content-Type: application/xml');
            $xml = new SimpleXMLElement('<user/>');
            foreach ($user as $key => $value) {
                $xml->addChild($key, htmlspecialchars($value));
            }
            echo $xml->asXML();
        } else {
            echo json_encode($user);
        }
        break;
        
    case 'import':
        // XXE vulnerability
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $xmlData = file_get_contents('php://input');
            
            // Vulnerable XML parsing - external entities enabled
            libxml_disable_entity_loader(false);
            $doc = new DOMDocument();
            $doc->loadXML($xmlData, LIBXML_NOENT | LIBXML_DTDLOAD);
            
            $userData = simplexml_import_dom($doc);
            
            echo json_encode(['status' => 'success', 'data' => (array)$userData]);
        }
        break;
        
    case 'fetch':
        // SSRF vulnerability
        $url = $_GET['url'] ?? '';
        
        if (!empty($url)) {
            // No URL validation - allows internal network access
            $content = file_get_contents($url);
            echo json_encode(['content' => $content]);
        } else {
            echo json_encode(['error' => 'URL required']);
        }
        break;
        
    case 'file':
        // Path traversal for file download
        $file = $_GET['name'] ?? '';
        
        if (!empty($file)) {
            $basePath = __DIR__ . '/exports/';
            $filePath = $basePath . $file; // No path sanitization
            
            if (file_exists($filePath)) {
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                readfile($filePath);
            } else {
                // Try with path traversal
                if (file_exists($file)) {
                    readfile($file);
                } else {
                    echo json_encode(['error' => 'File not found: ' . $file]);
                }
            }
        }
        break;
        
    case 'users':
        // Mass assignment / Information disclosure
        $db = getDB();
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        
        // Returns all user data including sensitive fields
        $users = $db->query("SELECT * FROM users LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['users' => $users]);
        break;
        
    case 'update':
        // Mass assignment vulnerability
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isLoggedIn()) {
                echo json_encode(['error' => 'Not authenticated']);
                exit;
            }
            
            $db = getDB();
            $userId = $data['user_id'] ?? $_SESSION['user_id'];
            
            // Allows updating any field including role, balance, etc.
            unset($data['user_id']);
            
            $sets = [];
            foreach ($data as $key => $value) {
                $sets[] = "$key = '$value'"; // SQLi in each field
            }
            
            if (!empty($sets)) {
                $sql = "UPDATE users SET " . implode(', ', $sets) . " WHERE id = $userId";
                $db->exec($sql);
                echo json_encode(['status' => 'success']);
            }
        }
        break;
        
    default:
        echo json_encode([
            'api' => 'CyberHunt API',
            'version' => '1.0',
            'endpoints' => [
                'export' => '/api.php?action=export&user_id=1&format=json',
                'import' => '/api.php?action=import (POST XML)',
                'fetch' => '/api.php?action=fetch&url=http://example.com',
                'file' => '/api.php?action=file&name=report.pdf',
                'users' => '/api.php?action=users&limit=10&offset=0',
                'update' => '/api.php?action=update (POST JSON)'
            ]
        ]);
}
?>
