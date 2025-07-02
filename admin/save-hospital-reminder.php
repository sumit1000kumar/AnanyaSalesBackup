<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $hospital_name = trim($_POST['hospital_name']);
  $address = trim($_POST['address']);
  $last_service_date = $_POST['last_service_date'];
  $next_due_date = $_POST['next_due_date'];

  if ($hospital_name && $address && $last_service_date && $next_due_date) {
    $stmt = $conn->prepare("INSERT INTO hospital_reminders (hospital_name, address, last_service_date, next_due_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $hospital_name, $address, $last_service_date, $next_due_date);

    if ($stmt->execute()) {
      header("Location: hospital-reminders.php?success=1");
      exit;
    } else {
      header("Location: hospital-reminders.php?error=db");
      exit;
    }
  } else {
    header("Location: hospital-reminders.php?error=missing");
    exit;
  }
} else {
  header("Location: hospital-reminders.php");
  exit;
}
?>
