<?php
$pageTitle = 'Admin - Users';
require_once __DIR__ . '/../config/database.php';

// Broken access control
if (!isLoggedIn()) {
    redirect('/login.php');
}

$db = getDB();

// Handle user deletion - vulnerable to CSRF (no token check)
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    $db->exec("DELETE FROM users WHERE id = $userId"); // SQLi + CSRF
    $_SESSION['success_message'] = 'User deleted successfully.';
    redirect('/admin/users.php');
}

// Handle user edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $role = $_POST['role'];
    $balance = $_POST['balance'];
    
    // SQL injection in update
    $db->exec("UPDATE users SET role = '$role', balance = $balance WHERE id = $userId");
    $_SESSION['success_message'] = 'User updated successfully.';
    redirect('/admin/users.php');
}

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users WHERE 1=1";
if (!empty($search)) {
    $query .= " AND (username LIKE '%$search%' OR email LIKE '%$search%')"; // SQLi
}
$query .= " ORDER BY created_at DESC";

$users = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin - CyberHunt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .sidebar { width: 280px; min-height: 100vh; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); position: fixed; left: 0; top: 0; padding: 20px; }
        .main-content { margin-left: 280px; padding: 30px; background: #f8f9fc; min-height: 100vh; }
        .sidebar-link { display: flex; align-items: center; padding: 12px 20px; color: rgba(255,255,255,0.7); border-radius: 10px; margin-bottom: 8px; transition: all 0.3s; text-decoration: none; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="/" class="d-block mb-4 text-decoration-none">
            <h4 class="text-white fw-bold"><i class="bi bi-crosshair me-2"></i>CyberHunt</h4>
        </a>
        <nav class="mt-4">
            <a href="/admin/" class="sidebar-link"><i class="bi bi-speedometer2"></i>Dashboard</a>
            <a href="/admin/users.php" class="sidebar-link active"><i class="bi bi-people"></i>Users</a>
            <a href="/admin/products.php" class="sidebar-link"><i class="bi bi-box"></i>Products</a>
            <a href="/admin/orders.php" class="sidebar-link"><i class="bi bi-cart"></i>Orders</a>
            <a href="/" class="sidebar-link"><i class="bi bi-house"></i>Back to Site</a>
        </nav>
    </div>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Manage Users</h3>
            <form class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Balance</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="/uploads/profiles/<?php echo $user['profile_pic'] ?? 'default.jpg'; ?>" 
                                         class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;"
                                         onerror="this.src='/assets/images/default-avatar.svg'">
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'secondary'; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                            <td>$<?php echo number_format($user['balance'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo $user['id']; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit User: <?php echo htmlspecialchars($user['username']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Role</label>
                                                <select name="role" class="form-select">
                                                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Balance</label>
                                                <input type="number" name="balance" class="form-control" step="0.01" value="<?php echo $user['balance']; ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
