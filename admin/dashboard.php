<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | Complaint Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4cc9f0;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      --card-shadow-hover: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
      margin-bottom: 2rem;
      padding: 2rem 0;
    }

    .dashboard-card {
      border: none;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: var(--card-shadow);
      height: 100%;
      background: white;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--card-shadow-hover);
    }

    .card-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }

    .card-body {
      padding: 2rem;
      text-align: center;
    }

    .card-title {
      font-weight: 600;
      color: #333;
    }

    .card-description {
      color: #6c757d;
      font-size: 0.9rem;
    }

    .user-profile {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--accent-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }

    .logout-btn {
      font-size: 0.9rem;
      color: white;
      background: transparent;
      border: 1px solid white;
      padding: 4px 10px;
      border-radius: 6px;
      text-decoration: none;
      margin-left: 10px;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.15);
    }
  </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <i class="bi bi-shield-lock me-2"></i>
      <span>Admin Panel</span>
    </a>
    <div class="d-flex align-items-center">
      <span class="text-white me-2 d-none d-sm-inline">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
      <div class="user-profile"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
      <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard Header -->
<div class="dashboard-header text-center">
  <div class="container">
    <h1 class="display-5 fw-bold mb-3"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
    <p class="lead opacity-75">Manage complaints, receipts, and system operations</p>
  </div>
</div>

<!-- Main Content -->
<div class="container mb-5">
  <div class="row g-4">

    <!-- Complaint Form -->
    <div class="col-md-6 col-lg-4">
      <a href="../user/complaint-form.php" class="text-decoration-none">
        <div class="dashboard-card">
          <div class="card-body">
            <div class="card-icon"><i class="bi bi-file-earmark-plus"></i></div>
            <h5 class="card-title">Submit Complaint</h5>
            <p class="card-description">Create new complaint tickets</p>
          </div>
        </div>
      </a>
    </div>

    <!-- View Complaints -->
    <div class="col-md-6 col-lg-4">
      <a href="index.php" class="text-decoration-none">
        <div class="dashboard-card">
          <div class="card-body">
            <div class="card-icon"><i class="bi bi-list-ul"></i></div>
            <h5 class="card-title">View Complaints</h5>
            <p class="card-description">Manage all submitted complaints</p>
          </div>
        </div>
      </a>
    </div>

    <!-- Generate Receipt -->
    <div class="col-md-6 col-lg-4">
      <a href="generate-receipt.php" class="text-decoration-none">
        <div class="dashboard-card">
          <div class="card-body">
            <div class="card-icon"><i class="bi bi-receipt-cutoff"></i></div>
            <h5 class="card-title">Generate Receipt</h5>
            <p class="card-description">Create payment receipts</p>
          </div>
        </div>
      </a>
    </div>

    <!-- View Receipts -->
    <div class="col-md-6 col-lg-4">
      <a href="receipt-list.php" class="text-decoration-none">
        <div class="dashboard-card">
          <div class="card-body">
            <div class="card-icon"><i class="bi bi-archive"></i></div>
            <h5 class="card-title">View Receipts</h5>
            <p class="card-description">Access receipt history</p>
          </div>
        </div>
      </a>
    </div>

  </div>
</div>

<!-- Footer -->
<footer class="bg-light py-4 mt-5">
  <div class="container text-center text-muted">
    <p class="mb-0">Complaint Management System &copy; <?= date('Y') ?></p>
    <small>v1.0.0</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
