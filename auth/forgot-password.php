<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes manually
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
require '../includes/PHPMailer/src/Exception.php';
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiry

        // Save token to DB
        $update = $conn->prepare("UPDATE users SET reset_token=?, reset_token_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        // Email sending setup
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2; // Add this line
$mail->Debugoutput = 'html'; // Optional: show debug output as HTML

            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; // From Mailtrap
            $mail->SMTPAuth   = true;
            $mail->Username   = '653e31a20734e7';   // Replace
            $mail->Password   = '6d0a10a903ad2e';   // Replace
            $mail->Port       = 2525;

            // Looking to send emails in production? Check out our Email API/SMTP product!
// $phpmailer = new PHPMailer();
// $phpmailer->isSMTP();
// $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
// $phpmailer->SMTPAuth = true;
// $phpmailer->Port = 2525;
// $phpmailer->Username = '653e31a20734e7';
// $phpmailer->Password = '6d0a10a903ad2e';

            $mail->setFrom('noreply@yourdomain.com', 'Complaint Management System');
            $mail->addAddress($email);

            $resetLink = "http://localhost/Ram_dada_dashboard/auth/reset-password.php?token=$token";

            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            $mail->Body    = "Hello,<br><br>Click the link below to reset your password:<br>
                             <a href='$resetLink'>$resetLink</a><br><br>This link will expire in 1 hour.";

            $mail->send();
            $_SESSION['success'] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $error = "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4" style="max-width: 400px; width: 100%;">
        <h4 class="mb-3">Forgot Password</h4>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if (isset($_SESSION['success'])) { echo "<div class='alert alert-success'>{$_SESSION['success']}</div>"; unset($_SESSION['success']); } ?>
        <form method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
