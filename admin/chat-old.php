<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = intval($_POST['complaint_id']);
    $message = trim($_POST['message']);
    $sender_id = $_SESSION['user_id'];
    
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
    echo json_encode($messages);
    exit;
}

if (isset($_GET['complaint_id'])) {
    $complaint_id = intval($_GET['complaint_id']);
    
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
    
    // Mark messages as read
    $stmt = $conn->prepare("UPDATE conversations SET is_read = TRUE WHERE complaint_id = ? AND sender_id != ?");
    $stmt->bind_param("ii", $complaint_id, $_SESSION['user_id']);
    $stmt->execute();
    
    header('Content-Type: application/json');
    echo json_encode($messages);
    exit;
}