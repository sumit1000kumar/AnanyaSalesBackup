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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ananya Sales and Service | Admin Dashboard</title>

  <!-- Favicons -->
  <link rel="icon" href="../assets/images/favicon/favicon.ico" type="image/x-icon" />
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/favicon/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon/favicon-16x16.png" />
  <link rel="manifest" href="../assets/images/favicon/site.webmanifest" />

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Custom Styles -->
  <style>
    :root {
      --primary-color: #e30613;
      --secondary-color: #a9030d;
      --accent-color: #ff5964;
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
      box-shadow: 0 4px 20px rgba(227, 6, 19, 0.15);
      padding: 2rem 0;
      margin-bottom: 2rem;
    }

    .dashboard-card {
      border: none;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: var(--card-shadow);
      background: white;
      height: 100%;
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

    .main-footer {
      background-color: rgb(6, 7, 7);
      border-top: 1px solid #dee2e6;
      width: 100%;
    }

    .main-footer .text-muted {
      color: white !important;
    }
  </style>
</head>

<body>
  <div class="wrapper d-flex flex-column min-vh-100">

    <!-- Navbar -->
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
    <div class="dashboard-header">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="d-flex align-items-center mb-3 mb-md-0">
          <img src="../assets/images/logo/logo.jpg" alt="Logo" width="50" class="me-3" style="border-radius: 10px; background-color: white; padding: 5px;">
          <h2 class="mb-0 fw-bold">Ananya Sales and Service</h2>
        </div>
        <div class="text-end">
          <h4 class="fw-semibold mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
          <p class="mb-0 text-white-50">Manage complaints, service receipts & operations</p>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="flex-fill">
      <div class="container mb-5">
        <div class="row g-4">

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

          <!-- Generate Service Receipt -->
          <div class="col-md-6 col-lg-4">
            <a href="generate-receipt.php" class="text-decoration-none">
              <div class="dashboard-card">
                <div class="card-body">
                  <div class="card-icon"><i class="bi bi-receipt-cutoff"></i></div>
                  <h5 class="card-title">Generate Service Receipt</h5>
                  <p class="card-description">Create service payment receipts</p>
                </div>
              </div>
            </a>
          </div>

          <!-- View Service Receipts -->
          <div class="col-md-6 col-lg-4">
            <a href="receipt-list.php" class="text-decoration-none">
              <div class="dashboard-card">
                <div class="card-body">
                  <div class="card-icon"><i class="bi bi-archive"></i></div>
                  <h5 class="card-title">View Service Receipts</h5>
                  <p class="card-description">Access receipt history</p>
                </div>
              </div>
            </a>
          </div>

          <!-- Manage Customers -->
          <div class="col-md-6 col-lg-4">
            <a href="customer-manage.php" class="text-decoration-none">
              <div class="dashboard-card">
                <div class="card-body">
                  <div class="card-icon"><i class="bi bi-person-lines-fill"></i></div>
                  <h5 class="card-title">Manage Customers</h5>
                  <p class="card-description">Add, edit, or delete customer details</p>
                </div>
              </div>
            </a>
          </div>

          <!-- Manage Engineers -->
          <div class="col-md-6 col-lg-4">
            <a href="engineer-manage.php" class="text-decoration-none">
              <div class="dashboard-card">
                <div class="card-body">
                  <div class="card-icon"><i class="bi bi-people-gear"></i></div>
                  <h5 class="card-title">Manage Engineers</h5>
                  <p class="card-description">Add, edit, or delete engineer info & signatures</p>
                </div>
              </div>
            </a>
          </div>

        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="main-footer mt-auto">
      <div class="container py-3">
        <div class="text-center">
          <span class="text-muted">Built by Sumit Kumar</span>
        </div>
      </div>
    </footer>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
