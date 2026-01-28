<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

$db = getDB();
$user = getCurrentUser();

// Handle export request - Path traversal vulnerability
// Must be BEFORE any HTML output
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    
    // Simulated filesystem root (transparent to user)
    $simulatedRoot = __DIR__ . '/fake_root';
    
    // Handle path traversal with ../ sequences
    if (strpos($file, '../') !== false) {
        $normalized = $file;
        // Remove leading ../ sequences: ../../../etc/passwd -> etc/passwd
        while (strpos($normalized, '../') === 0) {
            $normalized = substr($normalized, 3);
        }
        // Try to find the file in simulated root
        $targetPath = $simulatedRoot . '/' . $normalized;
        if (file_exists($targetPath)) {
            header('Content-Type: text/plain');
            header('Content-Disposition: inline; filename="' . basename($file) . '"');
            echo file_get_contents($targetPath);
            exit;
        }
    }
    
    // If path starts with /, treat it as absolute path
    if (strpos($file, '/') === 0) {
        $targetPath = $simulatedRoot . $file;
        if (file_exists($targetPath)) {
            header('Content-Type: text/plain');
            header('Content-Disposition: inline; filename="' . basename($file) . '"');
            echo file_get_contents($targetPath);
            exit;
        }
    }
    
    // Try from exports directory
    $filePath = __DIR__ . '/exports/' . $file;
    if (file_exists($filePath)) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        echo file_get_contents($filePath);
        exit;
    }
}

// Generate export
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['export_type'] ?? 'profile';
    
    $exportDir = __DIR__ . '/exports/';
    if (!is_dir($exportDir)) {
        mkdir($exportDir, 0777, true);
    }
    
    switch ($type) {
        case 'profile':
            $data = $user;
            break;
        case 'orders':
            $data = $db->query("SELECT * FROM orders WHERE user_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'all':
            $data = [
                'profile' => $user,
                'orders' => $db->query("SELECT * FROM orders WHERE user_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_ASSOC),
                'reviews' => $db->query("SELECT * FROM reviews WHERE user_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_ASSOC)
            ];
            break;
    }
    
    $filename = 'export_' . $_SESSION['user_id'] . '_' . time() . '.json';
    file_put_contents($exportDir . $filename, json_encode($data, JSON_PRETTY_PRINT));
    
    $_SESSION['export_file'] = $filename;
    $_SESSION['success_message'] = 'Export generated successfully!';
}

$pageTitle = 'Export Data';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Export My Data</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Export Data</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="fw-bold mb-4"><i class="bi bi-download me-2"></i>Export Your Data</h4>
                <p class="text-muted mb-4">
                    Download a copy of your personal data. This includes your profile information, 
                    order history, and reviews.
                </p>
                
                <form method="POST" action="">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">What would you like to export?</label>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="export_type" value="profile" id="exportProfile" checked>
                            <label class="form-check-label" for="exportProfile">
                                <strong>Profile Only</strong>
                                <small class="text-muted d-block">Your account information and settings</small>
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="export_type" value="orders" id="exportOrders">
                            <label class="form-check-label" for="exportOrders">
                                <strong>Orders Only</strong>
                                <small class="text-muted d-block">Your complete order history</small>
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="export_type" value="all" id="exportAll">
                            <label class="form-check-label" for="exportAll">
                                <strong>Everything</strong>
                                <small class="text-muted d-block">Profile, orders, reviews, and all associated data</small>
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-arrow-down me-2"></i>Generate Export
                    </button>
                </form>
                
                <?php if (isset($_SESSION['export_file'])): ?>
                <hr class="my-4">
                <div class="alert alert-success">
                    <h5 class="alert-heading"><i class="bi bi-check-circle me-2"></i>Export Ready!</h5>
                    <p class="mb-3">Your data export has been generated successfully.</p>
                    <a href="/export.php?file=<?php echo $_SESSION['export_file']; ?>" class="btn btn-success">
                        <i class="bi bi-download me-2"></i>Download Export
                    </a>
                </div>
                <?php unset($_SESSION['export_file']); endif; ?>
            </div>
            
            <!-- Previous Exports -->
            <div class="card p-4 mt-4">
                <h5 class="fw-bold mb-3">Previous Exports</h5>
                <p class="text-muted small">
                    Enter the filename to download a previous export:
                </p>
                <form method="GET" action="" class="d-flex gap-2">
                    <input type="text" name="file" class="form-control" placeholder="e.g., export_1_1234567890.json">
                    <button type="submit" class="btn btn-outline-primary">Download</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
