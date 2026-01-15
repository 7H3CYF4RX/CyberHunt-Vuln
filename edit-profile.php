<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

$db = getDB();
$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $country = $_POST['country'] ?? '';
    $bio = $_POST['bio'] ?? ''; // Stored XSS - bio is not sanitized
    
    // Handle profile picture upload - Vulnerable to unrestricted file upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/profiles/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = $_FILES['profile_pic']['name'];
        $targetPath = $uploadDir . $fileName; // No sanitization of filename
        
        // Only checking extension superficially - can be bypassed
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
            $db->exec("UPDATE users SET profile_pic = '$fileName' WHERE id = " . $_SESSION['user_id']);
        }
    }
    
    // Update user - vulnerable to stored XSS in bio field
    $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ?, city = ?, country = ?, bio = ? WHERE id = ?");
    $stmt->execute([$full_name, $email, $phone, $address, $city, $country, $bio, $_SESSION['user_id']]);
    
    $_SESSION['success_message'] = 'Profile updated successfully!';
    header('Location: /profile.php');
    exit;
}

$pageTitle = 'Edit Profile';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Edit Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/profile.php">Profile</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="fw-bold mb-4">Update Your Information</h4>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <!-- Profile Picture -->
                    <div class="text-center mb-4">
                        <img src="/uploads/profiles/<?php echo $user['profile_pic'] ?? 'default.jpg'; ?>" 
                             alt="Profile" class="rounded-circle mb-3" id="previewImage"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #667eea;"
                             onerror="this.src='/assets/images/default-avatar.svg'">
                        <div>
                            <label for="profile_pic" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-camera me-1"></i>Change Photo
                            </label>
                            <input type="file" name="profile_pic" id="profile_pic" class="d-none" accept="image/*">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="tel" name="phone" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Country</label>
                            <select name="country" class="form-select">
                                <option value="">Select Country</option>
                                <option value="United States" <?php echo ($user['country'] ?? '') === 'United States' ? 'selected' : ''; ?>>United States</option>
                                <option value="Canada" <?php echo ($user['country'] ?? '') === 'Canada' ? 'selected' : ''; ?>>Canada</option>
                                <option value="United Kingdom" <?php echo ($user['country'] ?? '') === 'United Kingdom' ? 'selected' : ''; ?>>United Kingdom</option>
                                <option value="Australia" <?php echo ($user['country'] ?? '') === 'Australia' ? 'selected' : ''; ?>>Australia</option>
                                <option value="Germany" <?php echo ($user['country'] ?? '') === 'Germany' ? 'selected' : ''; ?>>Germany</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <input type="text" name="address" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Bio</label>
                        <textarea name="bio" class="form-control" rows="4" 
                                  placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        <small class="text-muted">This will be displayed on your public profile.</small>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                        <a href="/profile.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            
            <!-- Change Password Section -->
            <div class="card p-4 mt-4">
                <h4 class="fw-bold mb-4">Change Password</h4>
                <form method="POST" action="/change-password.php">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_pic').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
