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
      --success-color: #4BB543;
      --warning-color: #FFCC00;
      --danger-color: #FF3333;
      --light-bg: #f8f9fa;
      --text-muted: #6c757d;
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 0;
      margin: 0;
    }
    
    .registration-container {
      flex: 1;
      display: flex;
      align-items: center;
      padding: 1rem;
    }
    
    .registration-card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
    }
    
    .registration-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 1.5rem;
      text-align: center;
    }
    
    .registration-body {
      padding: 1.5rem;
      background: white;
    }
    
    .form-control {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      border: 1px solid #e0e0e0;
      transition: all 0.2s ease;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
    }
    
    .btn-register {
      background-color: var(--primary-color);
      border: none;
      padding: 0.75rem;
      border-radius: 10px;
      font-weight: 500;
      letter-spacing: 0.3px;
      transition: all 0.2s ease;
      width: 100%;
    }
    
    .btn-register:hover {
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
    
    .input-icon input, .input-icon select {
      padding-left: 45px;
    }
    
    .password-strength {
      height: 5px;
      background: #e9ecef;
      margin-top: 0.5rem;
      border-radius: 3px;
      overflow: hidden;
    }
    
    .password-strength-bar {
      height: 100%;
      width: 0;
      transition: width 0.3s ease, background-color 0.3s ease;
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
      .registration-header {
        padding: 1.25rem;
      }
      
      .registration-body {
        padding: 1.25rem;
      }
    }
    
    @media (max-width: 576px) {
      body {
        padding: 0.5rem;
      }
      
      .registration-header h2 {
        font-size: 1.5rem;
      }
      
      .form-control {
        padding: 0.65rem 0.9rem;
        font-size: 0.95rem;
      }
      
      .input-icon i {
        left: 12px;
        font-size: 1rem;
      }
      
      .input-icon input, .input-icon select {
        padding-left: 40px;
      }
      
      .btn-register {
        padding: 0.65rem;
        font-size: 0.95rem;
      }
    }
    
    @media (max-width: 400px) {
      .registration-header h2 {
        font-size: 1.3rem;
      }
      
      .registration-header p {
        font-size: 0.9rem;
      }
      
      .form-label {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
<div class="registration-container">
  <div class="container">
    <div class="registration-card shadow">
      <div class="registration-header">
        <h2><i class="bi bi-person-plus me-2"></i>Create Account</h2>
        <p class="mb-0">Join our platform in just a minute</p>
      </div>
      
      <div class="registration-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?= $error ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        
        <form method="POST" id="registrationForm">
          <div class="mb-3 input-icon">
            <i class="bi bi-person" style="position:relative; bottom: -45px;"></i>
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Your full name" required>
          </div>
          
          <div class="mb-3 input-icon">
            <i class="bi bi-envelope" style="position:relative; bottom: -45px;"></i>
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
          </div>
          
          <div class="mb-3 input-icon">
            <i class="bi bi-lock" style="position:relative; bottom: -45px;"></i>
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="At least 6 characters" required minlength="6">
            <div class="password-strength mt-2">
              <div class="password-strength-bar" id="passwordStrength"></div>
            </div>
            <!-- <small class="text-muted" style="color: #000;">Minimum 6 characters</small> -->
          </div>
          
          <div class="mb-3 input-icon">
            <i class="bi bi-person-badge" style="position:relative; bottom: -40px;"></i>
            <label for="roleSelect" class="form-label">Account Type</label>
            <select name="role" id="roleSelect" class="form-select" required onchange="toggleAdminCode(this.value)">
              <option value="user" selected>Regular User</option>
              <option value="admin">Administrator</option>
            </select>
          </div>
          
          <div class="mb-3 d-none" id="adminCodeBlock">
            <div class="input-icon">
              <i class="bi bi-shield-lock" style="position:relative; bottom: -45px;"></i>
              <label for="admin_code" class="form-label">Admin Access Code</label>
              <input type="password" id="admin_code" name="admin_code" class="form-control" placeholder="Enter secret code">
            </div>
          </div>
          
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="terms" required>
            <label class="form-check-label" for="terms">
              I agree to the <a href="#" class="text-primary">Terms</a> and <a href="#" class="text-primary">Privacy Policy</a>
            </label>
          </div>
          
          <button type="submit" class="btn btn-register btn-primary mb-3">
            <i class="bi bi-person-plus me-2"></i> Create Account
          </button>
          
          <div class="divider">or</div>
          
          <p class="text-center mb-0">Already have an account? <a href="login.php" class="text-primary">Sign in</a></p>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Toggle admin code field - Fixed version
  function toggleAdminCode(role) {
    const adminCodeBlock = document.getElementById('adminCodeBlock');
    if (role === 'admin') {
      adminCodeBlock.classList.remove('d-none');
      document.getElementById('admin_code').focus();
    } else {
      adminCodeBlock.classList.add('d-none');
    }
  }

  // Initialize the admin code block visibility on page load
  document.addEventListener('DOMContentLoaded', function() {
    // Set initial state based on current selection
    const roleSelect = document.getElementById('roleSelect');
    toggleAdminCode(roleSelect.value);
    
    // Auto-focus first field
    document.getElementById('name').focus();
  });

  // Password strength indicator
  document.getElementById('password').addEventListener('input', function(e) {
    const strengthBar = document.getElementById('passwordStrength');
    const password = e.target.value;
    let strength = 0;
    
    // Length check
    if (password.length > 0) strength += 20;
    if (password.length >= 6) strength += 20;
    
    // Complexity checks
    if (password.match(/[A-Z]/)) strength += 20;
    if (password.match(/[0-9]/)) strength += 20;
    if (password.match(/[^A-Za-z0-9]/)) strength += 20;
    
    // Update strength bar
    strengthBar.style.width = strength + '%';
    
    // Change color based on strength
    if (strength < 40) {
      strengthBar.style.backgroundColor = 'var(--danger-color)';
    } else if (strength < 80) {
      strengthBar.style.backgroundColor = 'var(--warning-color)';
    } else {
      strengthBar.style.backgroundColor = 'var(--success-color)';
    }
  });
</script>
</body>
</html>