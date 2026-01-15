<?php
require_once __DIR__ . '/config/database.php';

if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

$db = getDB();

// Handle sending message - BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $receiver = $_POST['receiver'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? ''; // Stored XSS - not sanitized
    
    // Find receiver by username
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$receiver]);
    $receiverUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($receiverUser) {
        $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $receiverUser['id'], $subject, $message]);
        $_SESSION['success_message'] = 'Message sent successfully!';
    } else {
        $_SESSION['error_message'] = 'User not found.';
    }
    
    header('Location: /messages.php');
    exit;
}

// Get inbox messages
$inbox = $db->query("SELECT m.*, u.username as sender_name, u.profile_pic as sender_pic 
                     FROM messages m 
                     JOIN users u ON m.sender_id = u.id 
                     WHERE m.receiver_id = " . $_SESSION['user_id'] . " 
                     ORDER BY m.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Get sent messages
$sent = $db->query("SELECT m.*, u.username as receiver_name 
                    FROM messages m 
                    JOIN users u ON m.receiver_id = u.id 
                    WHERE m.sender_id = " . $_SESSION['user_id'] . " 
                    ORDER BY m.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Messages';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Messages</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Messages</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Compose Message -->
        <div class="col-lg-4 mb-4">
            <div class="card p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-pencil-square me-2"></i>New Message</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">To (Username)</label>
                        <input type="text" name="receiver" class="form-control" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Message subject" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Write your message..." required></textarea>
                    </div>
                    <button type="submit" name="send_message" class="btn btn-primary w-100">
                        <i class="bi bi-send me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Messages -->
        <div class="col-lg-8">
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#inbox">
                        <i class="bi bi-inbox me-1"></i>Inbox 
                        <span class="badge bg-primary ms-1"><?php echo count($inbox); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#sentMessages">
                        <i class="bi bi-send me-1"></i>Sent
                    </a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Inbox -->
                <div class="tab-pane fade show active" id="inbox">
                    <?php if (empty($inbox)): ?>
                        <div class="card p-4 text-center text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2 mb-0">No messages in your inbox</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($inbox as $msg): ?>
                        <div class="card p-3 mb-3 <?php echo !$msg['is_read'] ? 'border-primary' : ''; ?>">
                            <div class="d-flex align-items-start">
                                <img src="/uploads/profiles/<?php echo $msg['sender_pic'] ?? 'default.jpg'; ?>" 
                                     class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;"
                                     onerror="this.src='/assets/images/default-avatar.svg'">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($msg['sender_name']); ?></h6>
                                            <small class="text-muted"><?php echo date('M d, Y g:i A', strtotime($msg['created_at'])); ?></small>
                                        </div>
                                        <?php if (!$msg['is_read']): ?>
                                            <span class="badge bg-primary">New</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="fw-semibold mb-1 mt-2"><?php echo htmlspecialchars($msg['subject']); ?></p>
                                    <!-- Stored XSS - message not sanitized -->
                                    <p class="text-muted mb-0"><?php echo $msg['message']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Sent -->
                <div class="tab-pane fade" id="sentMessages">
                    <?php if (empty($sent)): ?>
                        <div class="card p-4 text-center text-muted">
                            <i class="bi bi-send fs-1"></i>
                            <p class="mt-2 mb-0">No sent messages</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($sent as $msg): ?>
                        <div class="card p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1"><strong>To:</strong> <?php echo htmlspecialchars($msg['receiver_name']); ?></p>
                                    <p class="fw-semibold mb-1"><?php echo htmlspecialchars($msg['subject']); ?></p>
                                    <p class="text-muted mb-0 small"><?php echo htmlspecialchars($msg['message']); ?></p>
                                </div>
                                <small class="text-muted"><?php echo date('M d', strtotime($msg['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
