<?php
// Returns JSON list of messages/notifications for the logged in user
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config.php';

// Simple response helper
$resp = ['success' => false, 'notifications' => [], 'error' => null];

if (empty($_SESSION['email'])) {
    http_response_code(401);
    $resp['error'] = 'Not authenticated';
    echo json_encode($resp);
    exit;
}

$recipient = $_SESSION['email'];

// Get both messages and replies
$sql = "SELECT 
    source_id as id,
    sender_name,
    module,
    message,
    created_at,
    type
FROM (
    SELECT 
        m.id as source_id,
        m.name as sender_name,
        m.module,
        m.message,
        m.created_at,
        'user' as type
    FROM contact_messages m
    WHERE m.email = ?
    
    UNION ALL
    
    SELECT 
        CONCAT(m.id, '_reply') as source_id,
        'Admin' as sender_name,
        m.module,
        r.reply_text as message,
        r.created_at,
        'admin' as type
    FROM contact_messages m
    INNER JOIN message_replies r ON m.id = r.message_id
    WHERE m.email = ?
) combined
ORDER BY created_at DESC
LIMIT 100";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ss", $recipient, $recipient);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $resp['notifications'][] = [
                'id' => $row['id'],
                'sender' => $row['sender_name'],
                'module' => $row['module'],
                'message' => $row['message'],
                'created_at' => $row['created_at']
            ];
        }
        $resp['success'] = true;
        echo json_encode($resp);
        exit;
    } else {
        $resp['error'] = 'Failed to execute query';
        echo json_encode($resp);
        exit;
    }
} else {
    $resp['error'] = 'Database error: could not prepare statement';
    echo json_encode($resp);
    exit;
}

?>
