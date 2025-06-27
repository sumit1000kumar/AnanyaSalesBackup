<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';
require '../includes/PHPMailer/Exception.php';
require '../pdf-lib/fpdf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = $_POST['email'];
    $client_name = $_POST['client_name'];
    $service_type = $_POST['service_type'];
    $amount = $_POST['amount'];
    $payment_mode = $_POST['payment_mode'];
    $description = $_POST['description'];

    // Create PDF in memory
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,'Receipt',0,1,'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(50,10,'Client Name:',0,0);
    $pdf->Cell(0,10,$client_name,0,1);
    $pdf->Cell(50,10,'Service Type:',0,0);
    $pdf->Cell(0,10,$service_type,0,1);
    $pdf->Cell(50,10,'Amount:',0,0);
    $pdf->Cell(0,10,'Rs. ' . number_format($amount, 2),0,1);
    $pdf->Cell(50,10,'Payment Mode:',0,0);
    $pdf->Cell(0,10,$payment_mode,0,1);
    $pdf->MultiCell(0,10,'Description: ' . ($description ?: 'N/A'),0,1);
    $pdf->Ln(5);
    $pdf->Cell(0,10,'Thank you!',0,1,'C');

    $file = tempnam(sys_get_temp_dir(), 'receipt_');
    $pdf->Output('F', $file); // Save to file

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.sumitbuilds.live';  // Replace with your SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'info@sumitbuilds.live';
        $mail->Password = 'Sumit#135';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('info@sumitbuilds.live', 'Ram Dada Service');
        $mail->addAddress($to);

        $mail->Subject = 'Your Service Receipt';
        $mail->Body = 'Hi ' . $client_name . ",\n\nAttached is your receipt.\n\nThanks!";

        $mail->addAttachment($file, 'receipt.pdf');

        $mail->send();
        echo 'Receipt sent successfully!';
    } catch (Exception $e) {
        echo 'Mail Error: ' . $mail->ErrorInfo;
    }

    unlink($file); // Clean up
}
