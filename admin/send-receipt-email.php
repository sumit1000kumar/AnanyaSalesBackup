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
        $pdfPath = "../" . $receipt['pdf_path']; // Assuming stored as "receipts/filename.pdf"

        if (empty($email)) {
            header("Location: receipt-list.php?mail=error&reason=" . urlencode("Email is empty."));
            exit;
        }

        if (!file_exists($pdfPath)) {
            header("Location: receipt-list.php?mail=error&reason=" . urlencode("PDF file not found."));
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            // SMTP config
            $mail->isSMTP();
            // $mail->Host       = 'sandbox.smtp.mailtrap.io';
            // $mail->SMTPAuth   = true;
            // $mail->Username   = '653e31a20734e7';
            // $mail->Password   = '6d0a10a903ad2e';
            // $mail->SMTPSecure = 'tls';
            // $mail->Port       = 587;
            

            // // Email settings
            // $mail->setFrom('info@ananyasaleservice.in', 'Ananya Sales & Service');
            $mail->Host       = 'smtp.sumitbuilds.live'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bank@sumitbuilds.live';
            $mail->Password   = 'ramdada@123';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            // Email settings
            $mail->setFrom('bank@sumitbuilds.live', 'Ananya Sales & Service');
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
            header("Location: receipt-list.php?mail=error&reason=" . urlencode($mail->ErrorInfo));
            exit;
        }

    } else {
        header("Location: receipt-list.php?mail=error&reason=" . urlencode("Receipt not found."));
        exit;
    }
} else {
    header("Location: receipt-list.php?mail=error&reason=" . urlencode("Invalid request."));
    exit;
}
?>
