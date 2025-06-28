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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome | Complaint Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .welcome-container {
      flex: 1;
      display: flex;
      align-items: center;
    }
    
    .welcome-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      max-width: 500px;
    }
    
    .welcome-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 2rem;
      border-radius: 12px 12px 0 0 !important;
    }
    
    .btn-welcome {
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
      min-width: 150px;
    }
    
    .btn-welcome:hover {
      transform: translateY(-2px);
    }
    
    /* Footer Styles - Can be moved to separate CSS file */
    .main-footer {
      background-color: #212529;
      color: white;
      padding: 1rem 0;
      text-align: center;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="welcome-container">
    <div class="container">
      <div class="card shadow mx-auto welcome-card">
        <div class="card-header welcome-header text-center">
          <h2 class="mb-1">Complaint Management System</h2>
          <p class="mb-0 opacity-75">Efficient complaint resolution platform</p>
        </div>
        <div class="card-body p-4 text-center">
          <div class="d-flex flex-wrap justify-content-center gap-3 my-4">
            <a href="auth/login.php" class="btn btn-primary btn-welcome">
              <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </a>
            <a href="auth/register.php" class="btn btn-outline-primary btn-welcome">
              <i class="bi bi-person-plus me-2"></i>Register
            </a>
          </div>
          <p class="text-muted mb-0">Manage and resolve complaints efficiently</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer (Reusable Component) -->
  <footer class="main-footer">
    <div class="container">
      <span>Built by Sumit Kumar</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>