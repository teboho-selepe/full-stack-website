<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $module = trim($_POST['module']);
    $message = trim($_POST['message']);


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit;
    }

    
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, module, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $module, $message);

    if ($stmt->execute()) {
        echo "
        <script>
            window.onload = function() {
                document.getElementById('successModal').style.display = 'flex';
            };
        </script>
        ";
        require_once "../pages/contact.php"; 
    } else {
        
        echo "<script>alert('Something went wrong. Please try again later.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();

} else {
    
    header('Location: ../pages/contact.php');
    exit;
}

?>
