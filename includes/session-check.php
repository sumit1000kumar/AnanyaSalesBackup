<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  // Not logged in or not admin
  header("Location: ../auth/login.php");
  exit;
}
?>
