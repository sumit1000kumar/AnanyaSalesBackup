<?php
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/user-dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Complaint Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --primary-light: #e0e7ff;
      --secondary-color: #3a0ca3;
      --success-color: #4BB543;
      --danger-color: #FF3333;
      --light-bg: #f8f9fa;
      --text-muted: #6c757d;
      --border-radius: 12px;
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      margin: 0;
      display: grid;
      place-items: center; /* This centers everything both horizontally and vertically */
      padding: 1rem;
    }
    
    .login-card {
      border: none;
      border-radius: var(--border-radius);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      width: 100%;
      max-width: 450px;
    }
    
    .login-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 1.75rem;
      text-align: center;
    }
    
    .login-header h2 {
      font-weight: 600;
      margin-bottom: 0.5rem;
      font-size: clamp(1.5rem, 4vw, 1.75rem);
    }
    
    .login-body {
      padding: 1.75rem;
      background: white;
    }
    
    .form-control {
      border-radius: var(--border-radius);
      padding: 0.75rem 1rem;
      border: 1px solid #e0e0e0;
      transition: all 0.2s ease;
      font-size: 0.95rem;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
    }
    
    .btn-login {
      background-color: var(--primary-color);
      border: none;
      padding: 0.75rem;
      border-radius: var(--border-radius);
      font-weight: 500;
      transition: all 0.2s ease;
      width: 100%;
    }
    
    .btn-login:hover {
      background-color: var(--secondary-color);
      transform: translateY(-1px);
    }
    
    .input-icon {
      position: relative;
    }
    
    .input-icon i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: var(--primary-color);
      font-size: 1.1rem;
    }
    
    .input-icon input {
      padding-left: 45px;
    }
    
    .divider {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
      color: var(--text-muted);
    }
    
    .divider::before, .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #e0e0e0;
    }
    
    .divider::before {
      margin-right: 1rem;
    }
    
    .divider::after {
      margin-left: 1rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .login-header {
        padding: 1.5rem;
      }
      
      .login-body {
        padding: 1.5rem;
      }
    }
    
    @media (max-width: 576px) {
      body {
        padding: 0.75rem;
      }
      
      .login-header {
        padding: 1.25rem;
      }
      
      .login-body {
        padding: 1.25rem;
      }
      
      .form-control {
        padding: 0.7rem 0.9rem;
        font-size: 0.9rem;
      }
      
      .input-icon i {
        left: 12px;
        font-size: 1rem;
      }
      
      .input-icon input {
        padding-left: 40px;
      }
    }
    
    @media (max-width: 400px) {
      .login-header h2 {
        font-size: 1.4rem;
      }
      
      .login-header p {
        font-size: 0.9rem;
      }
      
      .form-label {
        font-size: 0.9rem;
      }
      
      .form-check-label, .text-primary {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-card shadow">
    <div class="login-header">
      <h2><i class="bi bi-shield-lock me-2"></i>Welcome Back</h2>
      <p class="mb-0">Sign in to continue</p>
    </div>
    
    <div class="login-body">
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center">
          <i class="bi bi-check-circle-fill me-2"></i>
          <div><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div><?= $error ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <form method="POST">
        <div class="mb-3 input-icon">
          <i class="bi bi-envelope" style="position:relative; bottom: -45px;"></i>
          <label for="email" class="form-label">Email Address</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
        </div>
        
        <div class="mb-3 input-icon">
          <i class="bi bi-lock" style="position:relative; bottom: -45px;"></i>
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Your password" required>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
          </div>
          <a href="forgot-password.php" class="text-primary text-decoration-none">Forgot password?</a>
        </div>
        
        <button type="submit" class="btn btn-login btn-primary mb-3">
          <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
        </button>
        
        <div class="divider">or</div>
        
        <p class="text-center mb-0">Don't have an account? <a href="register.php" class="text-primary text-decoration-none">Sign up</a></p>
      </form>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto-focus email field on page load
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('email').focus();
    });

    // Add smooth form transitions
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = '';
      });
    });
  </script>
</body>
</html>