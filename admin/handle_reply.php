<?php
require_once __DIR__ . '/../config.php';

// Create message_replies table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS message_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    reply_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES contact_messages(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text']) && isset($_POST['message_id'])) {
    $message_id = (int)$_POST['message_id'];
    $reply_text = trim($_POST['reply_text']);
    
    if (!empty($reply_text)) {
        $stmt = $conn->prepare("INSERT INTO message_replies (message_id, reply_text) VALUES (?, ?)");
        $stmt->bind_param("is", $message_id, $reply_text);
        
        if ($stmt->execute()) {
            header('Location: messages.php?reply_sent=1');
        } else {
            header('Location: messages.php?error=1');
        }
        $stmt->close();
    }
}

$conn->close();
exit();
?>