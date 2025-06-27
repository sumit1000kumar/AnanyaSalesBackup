<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } elseif ($_SESSION['user_role'] === 'user') {
        header("Location: user/user-dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome | Complaint Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 text-center">
  <div class="card shadow mx-auto" style="max-width: 600px;">
    <div class="card-body p-5">
      <h1 class="mb-4">Complaint Management System</h1>
      <p class="lead mb-4">Welcome! Please login or register to continue.</p>
      <a href="auth/login.php" class="btn btn-primary btn-lg me-2">Login</a>
      <a href="auth/register.php" class="btn btn-outline-primary btn-lg">Register</a>
    </div>
  </div>
</div>

</body>
</html>
