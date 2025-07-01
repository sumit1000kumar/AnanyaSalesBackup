<?php
require '../includes/db.php';
session_start();

// 1. Get the token from the URL
$token = $_GET['token'] ?? '';

// 2. Validate the token and check expiry
$stmt = $conn->prepare("SELECT id, reset_token_expiry FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || strtotime($user['reset_token_expiry']) < time()) {
    die("Invalid or expired token.");
}

// 3. Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_token_expiry=NULL WHERE id=?");
        $update->bind_param("si", $hashedPassword, $user['id']);
        $update->execute();

        $_SESSION['success'] = "Your password has been reset successfully.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
  <div class="card p-4" style="max-width: 400px; width: 100%;">
    <h4 class="mb-3">Set New Password</h4>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      
      <div class="mb-3">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-success w-100">Update Password</button>
    </form>
  </div>
</body>
</html>
