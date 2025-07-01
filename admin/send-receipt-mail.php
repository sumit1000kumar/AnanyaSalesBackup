<?php
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
require '../includes/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['receipt_id'])) {
    $receipt_id = intval($_POST['receipt_id']);

    // Fetch the receipt data
    $stmt = $conn->prepare("SELECT * FROM receipts WHERE id = ?");
    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $receipt = $result->fetch_assoc();
        $email = $receipt['email'];
        $client_name = $receipt['client_name'];
        $pdfPath = "../receipts/" . $receipt['pdf_path']; // adjust for server path

        if (!empty($email) && file_exists($pdfPath)) {
            $mail = new PHPMailer(true);

            try {
                // SMTP config
                $mail->isSMTP();
                $mail->Host       = 'sandbox.smtp.mailtrap.io';     // ✅ your SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username   = '653e31a20734e7';       // ✅ your email
                $mail->Password   = '6d0a10a903ad2e';        // ✅ your password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                // Email
                $mail->setFrom('info@ananyasaleservice.in', 'Ananya Sales & Service');
                $mail->addAddress($email, $client_name);
                $mail->addAttachment($pdfPath);

                $mail->isHTML(true);
                $mail->Subject = 'Your Service Receipt from Ananya Sales & Service';
                $mail->Body = "
                    Dear {$client_name},<br><br>
                    Please find attached your service receipt.<br><br>
                    Regards,<br>
                    <strong>Ananya Sales & Service</strong>
                ";

                $mail->send();
                header("Location: receipt-list.php?mail=success");
                exit;

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Email is empty or PDF file is missing.";
        }
    } else {
        echo "Receipt not found.";
    }
} else {
    echo "Invalid request.";
}
?>
