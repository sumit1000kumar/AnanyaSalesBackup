<?php
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $admin_code_input = $_POST['admin_code'] ?? '';
    $secretAdminCode = "Sumit@123";

    if ($role === 'admin' && $admin_code_input !== $secretAdminCode) {
        $error = "Invalid admin access code. Please enter the correct code to register as admin.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Registration successful! Please login.';
            header("Location: login.php");
            exit;
        } else {
            $error = 'This email is already registered. Please use a different email or login.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Complaint Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
      --success-color: #28a745;
      --danger-color: #dc3545;
      --light-bg: #f8f9fa;
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
    
    .register-container {
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
    }
    
    .register-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .register-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 2rem;
      text-align: center;
    }
    
    .register-body {
      padding: 2rem;
      background: white;
    }
    
    .form-control {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #dee2e6;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-register {
      background-color: var(--primary-color);
      border: none;
      padding: 0.75rem;
      border-radius: 8px;
      font-weight: 500;
      letter-spacing: 0.5px;
      transition: all 0.3s;
    }
    
    .btn-register:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
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
    }
    
    .input-icon input {
      padding-left: 45px;
    }
    
    .password-strength {
      height: 4px;
      background: #e9ecef;
      margin-top: 0.25rem;
      border-radius: 2px;
      overflow: hidden;
    }
    
    .password-strength-bar {
      height: 100%;
      width: 0;
      background: var(--danger-color);
      transition: width 0.3s;
    }
    
    .divider {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
      color: #6c757d;
    }
    
    .divider::before, .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #dee2e6;
    }
    
    .divider::before {
      margin-right: 1rem;
    }
    
    .divider::after {
      margin-left: 1rem;
    }
    
    @media (max-width: 576px) {
      .register-header {
        padding: 1.5rem;
      }
      
      .register-body {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="register-container">
    <div class="register-card">
      <div class="register-header">
        <h2><i class="bi bi-person-plus me-2"></i>Create Account</h2>
        <p class="mb-0">Join our community today</p>
      </div>
      
      <div class="register-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <form method="POST" id="registrationForm">
          <div class="mb-3 input-icon">
            <i class="bi bi-person"></i>
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
          </div>
          
          <div class="mb-3 input-icon">
            <i class="bi bi-envelope"></i>
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
          </div>
          
          <div class="mb-3 input-icon">
            <i class="bi bi-lock"></i>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required minlength="6">
            <div class="password-strength">
              <div class="password-strength-bar" id="passwordStrength"></div>
            </div>
            <small class="text-muted">Minimum 6 characters</small>
          </div>
          
          <div class="mb-3">
            <label for="roleSelect" class="form-label">Register As</label>
            <select name="role" id="roleSelect" class="form-select" required onchange="toggleAdminCode(this.value)">
              <option value="user" selected>Regular User</option>
              <option value="admin">Administrator</option>
            </select>
          </div>
          
          <div class="mb-3 d-none" id="adminCodeBlock">
            <div class="input-icon">
              <i class="bi bi-shield-lock"></i>
              <label for="admin_code" class="form-label">Admin Access Code</label>
              <input type="password" id="admin_code" name="admin_code" class="form-control" placeholder="Enter admin access code">
              <small class="text-muted">Contact system administrator for the code</small>
            </div>
          </div>
          
          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label" for="terms">
              I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
            </label>
          </div>
          
          <button type="submit" class="btn btn-register btn-primary w-100 mb-3">
            <i class="bi bi-person-plus me-2"></i> Create Account
          </button>
          
          <div class="divider">or</div>
          
          <p class="text-center mb-0">Already have an account? <a href="login.php" class="text-primary">Sign in</a></p>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle admin code field
  function toggleAdminCode(role) {
    const adminCodeBlock = document.getElementById('adminCodeBlock');
    adminCodeBlock.classList.toggle('d-none', role !== 'admin');
    if (role === 'admin') {
      document.getElementById('admin_code').focus();
    }
  }

  // Password strength indicator
  document.getElementById('password').addEventListener('input', function(e) {
    const strengthBar = document.getElementById('passwordStrength');
    const password = e.target.value;
    let strength = 0;
    
    if (password.length > 0) strength += 20;
    if (password.length >= 6) strength += 20;
    if (password.match(/[A-Z]/)) strength += 20;
    if (password.match(/[0-9]/)) strength += 20;
    if (password.match(/[^A-Za-z0-9]/)) strength += 20;
    
    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = strength < 40 ? '#dc3545' : 
                                      strength < 80 ? '#ffc107' : '#28a745';
  });

  // Auto-focus first field
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('name').focus();
  });
</script>
</body>
</html>