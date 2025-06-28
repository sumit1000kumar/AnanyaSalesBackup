<?php
session_start();
require '../includes/db.php';

// Allow both admin and user roles
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'user')) {
    header("Location: ../auth/login.php");
    exit;
}

// Handle POST request (sending a message)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = intval($_POST['complaint_id']);
    $message = trim($_POST['message']);
    $sender_id = $_SESSION['user_id'];
    
    // Verify user has access to this complaint
    if (!verifyComplaintAccess($conn, $complaint_id, $_SESSION['user_id'], $_SESSION['user_role'])) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You do not have permission to access this conversation']);
        exit;
    }
    
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO conversations (complaint_id, sender_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $complaint_id, $sender_id, $message);
        $stmt->execute();
    }
    
    // Mark all messages as read
    $stmt = $conn->prepare("UPDATE conversations SET is_read = TRUE WHERE complaint_id = ? AND sender_id != ?");
    $stmt->bind_param("ii", $complaint_id, $sender_id);
    $stmt->execute();
    
    // Return the updated conversation
    echo getConversation($conn, $complaint_id);
    exit;
}

// Handle GET request (loading messages)
if (isset($_GET['complaint_id'])) {
    $complaint_id = intval($_GET['complaint_id']);
    
    // Verify user has access to this complaint
    if (!verifyComplaintAccess($conn, $complaint_id, $_SESSION['user_id'], $_SESSION['user_role'])) {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You do not have permission to access this conversation']);
        exit;
    }
    
    // Mark messages as read
    $stmt = $conn->prepare("UPDATE conversations SET is_read = TRUE WHERE complaint_id = ? AND sender_id != ?");
    $stmt->bind_param("ii", $complaint_id, $_SESSION['user_id']);
    $stmt->execute();
    
    // Return the conversation
    echo getConversation($conn, $complaint_id);
    exit;
}

// Helper function to verify complaint access
function verifyComplaintAccess($conn, $complaint_id, $user_id, $user_role) {
    // Admins can access all complaints
    if ($user_role === 'admin') {
        return true;
    }
    
    // Users can only access their own complaints
    $stmt = $conn->prepare("SELECT id FROM complaints WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $complaint_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

// Helper function to get conversation
function getConversation($conn, $complaint_id) {
    $stmt = $conn->prepare("
        SELECT c.*, u.name, u.role 
        FROM conversations c
        JOIN users u ON c.sender_id = u.id
        WHERE c.complaint_id = ?
        ORDER BY c.created_at ASC
    ");
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    header('Content-Type: application/json');
    return json_encode($messages);
}