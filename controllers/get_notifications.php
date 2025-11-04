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

// Using `contact_messages` table (columns: id, name, email, module, message, created_at)
// We will fetch messages where the `email` column matches the logged-in user's email.
// Prepare statement to avoid SQL injection
if ($stmt = $conn->prepare("SELECT id, name, module, message, created_at FROM contact_messages WHERE email = ? ORDER BY created_at DESC LIMIT 100")) {
    $stmt->bind_param('s', $recipient);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $resp['notifications'][] = [
                'id' => $row['id'],
                'sender' => $row['name'],
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
