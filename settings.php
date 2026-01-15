<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

$db = getDB();
$user = getCurrentUser();

// Handle settings update - No CSRF protection - BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_notifications'])) {
        // Simulated notification settings
        $_SESSION['success_message'] = 'Notification preferences updated!';
    }
    
    if (isset($_POST['delete_account'])) {
        // Account deletion with SQL injection in confirmation
        $confirm = $_POST['confirm_delete'] ?? '';
        if ($confirm === 'DELETE') {
            $db->exec("DELETE FROM users WHERE id = " . $_SESSION['user_id']);
            session_destroy();
            header('Location: /');
            exit;
        }
    }
    
    if (isset($_POST['update_security'])) {
        $twoFactor = $_POST['two_factor'] ?? '0';
        // Insecure direct object reference / mass assignment
        $db->exec("UPDATE users SET is_active = $twoFactor WHERE id = " . $_SESSION['user_id']);
        $_SESSION['success_message'] = 'Security settings updated!';
    }
    
    header('Location: /settings.php');
    exit;
}

$pageTitle = 'Account Settings';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Account Settings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card p-3">
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#general" data-bs-toggle="tab">
                        <i class="bi bi-gear me-2"></i>General
                    </a>
                    <a class="nav-link" href="#notifications" data-bs-toggle="tab">
                        <i class="bi bi-bell me-2"></i>Notifications
                    </a>
                    <a class="nav-link" href="#security" data-bs-toggle="tab">
                        <i class="bi bi-shield-lock me-2"></i>Security
                    </a>
                    <a class="nav-link" href="#privacy" data-bs-toggle="tab">
                        <i class="bi bi-eye-slash me-2"></i>Privacy
                    </a>
                    <a class="nav-link text-danger" href="#danger" data-bs-toggle="tab">
                        <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- General -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card p-4">
                        <h5 class="fw-bold mb-4">General Settings</h5>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Language</label>
                            <select class="form-select">
                                <option selected>English (US)</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Timezone</label>
                            <select class="form-select">
                                <option selected>UTC-08:00 Pacific Time</option>
                                <option>UTC-05:00 Eastern Time</option>
                                <option>UTC+00:00 London</option>
                                <option>UTC+05:30 India</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Currency</label>
                            <select class="form-select">
                                <option selected>USD ($)</option>
                                <option>EUR (€)</option>
                                <option>GBP (£)</option>
                                <option>INR (₹)</option>
                            </select>
                        </div>
                        
                        <button type="button" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
                
                <!-- Notifications -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card p-4">
                        <h5 class="fw-bold mb-4">Notification Preferences</h5>
                        <form method="POST" action="">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailOrders" checked>
                                <label class="form-check-label" for="emailOrders">Email me about order updates</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailPromos" checked>
                                <label class="form-check-label" for="emailPromos">Email me about promotions and deals</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailNews">
                                <label class="form-check-label" for="emailNews">Email me the newsletter</label>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="smsAlerts">
                                <label class="form-check-label" for="smsAlerts">SMS alerts for deliveries</label>
                            </div>
                            <button type="submit" name="update_notifications" class="btn btn-primary">Save Preferences</button>
                        </form>
                    </div>
                </div>
                
                <!-- Security -->
                <div class="tab-pane fade" id="security">
                    <div class="card p-4 mb-4">
                        <h5 class="fw-bold mb-4">Security Settings</h5>
                        <form method="POST" action="">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="two_factor" id="twoFactor" value="1">
                                <label class="form-check-label" for="twoFactor">Enable Two-Factor Authentication</label>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="loginAlerts" checked>
                                <label class="form-check-label" for="loginAlerts">Alert me of new logins</label>
                            </div>
                            <button type="submit" name="update_security" class="btn btn-primary">Update Security</button>
                        </form>
                    </div>
                    
                    <div class="card p-4">
                        <h5 class="fw-bold mb-3">Change Password</h5>
                        <a href="/edit-profile.php#password" class="btn btn-outline-primary">Change Password</a>
                    </div>
                </div>
                
                <!-- Privacy -->
                <div class="tab-pane fade" id="privacy">
                    <div class="card p-4">
                        <h5 class="fw-bold mb-4">Privacy Settings</h5>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="publicProfile" checked>
                            <label class="form-check-label" for="publicProfile">Make my profile public</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="showOrders">
                            <label class="form-check-label" for="showOrders">Show my purchase history on profile</label>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="dataTracking" checked>
                            <label class="form-check-label" for="dataTracking">Allow personalized recommendations</label>
                        </div>
                        <a href="/export.php" class="btn btn-outline-primary me-2">Download My Data</a>
                        <button type="button" class="btn btn-primary">Save Privacy Settings</button>
                    </div>
                </div>
                
                <!-- Danger Zone -->
                <div class="tab-pane fade" id="danger">
                    <div class="card p-4 border-danger">
                        <h5 class="fw-bold text-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h5>
                        <p class="text-muted mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Type <code>DELETE</code> to confirm</label>
                                <input type="text" name="confirm_delete" class="form-control" placeholder="DELETE">
                            </div>
                            <button type="submit" name="delete_account" class="btn btn-danger">
                                <i class="bi bi-trash me-2"></i>Delete My Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
