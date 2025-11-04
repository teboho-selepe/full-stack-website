<?php
require_once 'auth_check.php';
require_once __DIR__ . '/../config.php';

// Handle message deletion if requested
if (isset($_POST['delete']) && isset($_POST['message_id'])) {
    $id = (int)$_POST['message_id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages - SmartPath</title>
    <link rel="stylesheet" href="/web/assets/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .admin-header {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            color: #003366;
            font-size: 1.5rem;
            margin: 0;
        }
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .messages-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .message-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        .sender-info h3 {
            margin: 0;
            color: #003366;
        }
        .sender-info p {
            margin: 0.25rem 0;
            color: #666;
            font-size: 0.9rem;
        }
        .message-actions {
            display: flex;
            gap: 0.5rem;
        }
        .delete-btn {
            padding: 0.25rem 0.5rem;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .message-body {
            color: #333;
            line-height: 1.5;
        }
        .module-tag {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #e9ecef;
            color: #495057;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        
        /* Reply section styles */
        .reply-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #dee2e6;
        }
        
        .reply-section h4 {
            color: #003366;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .reply-form {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .reply-form textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            resize: vertical;
        }
        
        .reply-btn {
            align-self: flex-start;
            padding: 0.5rem 1rem;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }
        
        .reply-btn:hover {
            background: #218838;
        }
        
        .previous-replies {
            margin-top: 1.5rem;
        }
        
        .reply-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 0.75rem;
        }
        
        .reply-text {
            color: #212529;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .reply-time {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        body {
            background: #f8f9fa;
            margin: 0;
            min-height: 100vh;
            padding-top: 80px; /* Account for main site header */
        }
        .admin-header {
            position: fixed;
            left: 0;
            right: 0;
            z-index: 98;
        }
        .messages-container {
            margin-top: 70px; /* Space for admin header */
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1>Message Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="messages-container">
        <?php if (empty($messages)): ?>
            <div class="empty-state">
                <h2>No messages yet</h2>
                <p>When users send messages through the contact form, they'll appear here.</p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div class="sender-info">
                            <h3><?= htmlspecialchars($message['name']) ?></h3>
                            <p>Email: <?= htmlspecialchars($message['email']) ?></p>
                            <p>Sent: <?= date('F j, Y g:i a', strtotime($message['created_at'])) ?></p>
                        </div>
                        <div class="message-actions">
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                                <button type="submit" name="delete" class="delete-btn">
                                    <i class='bx bx-trash'></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="message-body">
                        <?= nl2br(htmlspecialchars($message['message'])) ?>
                    </div>
                    <?php if (!empty($message['module'])): ?>
                        <div class="module-tag">
                            Module: <?= htmlspecialchars($message['module']) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Reply section -->
                    <div class="reply-section">
                        <h4>Reply to Message</h4>
                        <form action="handle_reply.php" method="POST" class="reply-form">
                            <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                            <textarea name="reply_text" rows="4" placeholder="Type your reply here..." required></textarea>
                            <button type="submit" class="reply-btn">
                                <i class='bx bx-send'></i> Send Reply
                            </button>
                        </form>
                        
                        <?php
                        // Fetch existing replies for this message
                        $reply_stmt = $conn->prepare("SELECT reply_text, created_at FROM message_replies WHERE message_id = ? ORDER BY created_at DESC");
                        $reply_stmt->bind_param("i", $message['id']);
                        $reply_stmt->execute();
                        $replies = $reply_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                        if (!empty($replies)):
                        ?>
                        <div class="previous-replies">
                            <h4>Previous Replies</h4>
                            <?php foreach ($replies as $reply): ?>
                            <div class="reply-item">
                                <div class="reply-text"><?= nl2br(htmlspecialchars($reply['reply_text'])) ?></div>
                                <div class="reply-time">Sent: <?= date('F j, Y g:i a', strtotime($reply['created_at'])) ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; 
                        $reply_stmt->close();
                        ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>