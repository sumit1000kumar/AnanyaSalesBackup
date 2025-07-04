<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../includes/PHPMailer/src/Exception.php';
require '../includes/PHPMailer/src/PHPMailer.php';
require '../includes/PHPMailer/src/SMTP.php';
include '../includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP config
    $mail->isSMTP();
    $mail->Host       = 'smtp.sumitbuilds.live';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'bank@sumitbuilds.live';
    $mail->Password   = 'ramdada@123';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Sender
    $mail->setFrom('bank@sumitbuilds.live', 'Ananya Sales & Service');
    $mail->isHTML(true);

    // Fetch reminders due tomorrow
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    $stmt = $conn->prepare("SELECT hospital_name, address, next_due_date FROM hospital_reminders WHERE next_due_date = ?");
    $stmt->bind_param("s", $tomorrow);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No reminders due tomorrow.";
        exit;
    }

    // Email content
    $subject = "Hospital Service Reminder - Due on " . date('d M Y', strtotime($tomorrow));

    $body = "<h3>Hospital Service Reminders</h3><p>The following hospitals are due for service tomorrow:</p><ul>";
    while ($row = $result->fetch_assoc()) {
        $body .= "<li><strong>{$row['hospital_name']}</strong>, {$row['address']} (Due: " . date('d M Y', strtotime($row['next_due_date'])) . ")</li>";
    }
    $body .= "</ul>";

    $recipients = [
        'sumitkumar9012004@gmail.com' => 'Admin One',
        'youremail@example.com' => 'Admin Two' 
    ];

    foreach ($recipients as $email => $name) {
        $mail->addAddress($email, $name);
    }

    $mail->Subject = $subject;
    $mail->Body    = $body;

    // Send
    $mail->send();

    // Log
    $logStmt = $conn->prepare("INSERT INTO email_logs (subject, recipients, body, status) VALUES (?, ?, ?, 'sent')");
    $recipientList = implode(', ', array_keys($recipients));
    $logStmt->bind_param("sss", $subject, $recipientList, $body);
    $logStmt->execute();

    echo "Reminder email sent successfully.";
} catch (Exception $e) {
    // Log failure
    $error = $mail->ErrorInfo ?? 'Unknown error';
    $recipientList = isset($recipients) ? implode(', ', array_keys($recipients)) : 'N/A';
    $failLog = $conn->prepare("INSERT INTO email_logs (subject, recipients, body, status, error) VALUES (?, ?, ?, 'failed', ?)");
    $failLog->bind_param("ssss", $subject, $recipientList, $body, $error);
    $failLog->execute();

    echo "Mailer Error: {$mail->ErrorInfo}";
}
?>
