<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reminder_id'])) {
  $id = intval($_POST['reminder_id']);
  $stmt = $conn->prepare("DELETE FROM hospital_reminders WHERE id = ?");
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    // header("Location: hospital-reminders.php?success=1");
    header("Location: hospital-reminders.php?success=deleted");

    exit;
  } else {
    header("Location: hospital-reminders.php?error=db");
    exit;
  }
}

header("Location: hospital-reminders.php?error=missing");
exit;
