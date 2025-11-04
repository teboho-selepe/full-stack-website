<?php
// Endpoint to accept messages sent from notification dropdown (AJAX)
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config.php';

$resp = ['success' => false, 'notification' => null, 'error' => null];

if (empty($_SESSION['email'])) {
    http_response_code(401);
    $resp['error'] = 'Not authenticated';
    echo json_encode($resp);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $resp['error'] = 'Invalid method';
    echo json_encode($resp);
    exit;
}

$sender_name = $_SESSION['name'] ?? '';
$sender_email = $_SESSION['email'];
$message = trim($_POST['message'] ?? '');
$module = trim($_POST['module'] ?? '');

if ($message === '') {
    $resp['error'] = 'Message cannot be empty';
    echo json_encode($resp);
    exit;
}

// Insert into contact_messages as a new user message
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, module, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    $resp['error'] = 'Database prepare failed';
    echo json_encode($resp);
    exit;
}
$stmt->bind_param('ssss', $sender_name, $sender_email, $module, $message);

if (!$stmt->execute()) {
    $resp['error'] = 'Failed to save message';
    echo json_encode($resp);
    exit;
}

$newId = $stmt->insert_id;
$created_at = date('Y-m-d H:i:s');

// Return the created notification object for immediate UI update
$resp['success'] = true;
$resp['notification'] = [
    'id' => (string)$newId,
    'sender' => $sender_name,
    'module' => $module,
    'message' => $message,
    'created_at' => $created_at,
    'type' => 'user'
];

echo json_encode($resp);
exit;

?>