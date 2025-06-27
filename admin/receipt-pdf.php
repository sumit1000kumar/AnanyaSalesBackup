<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../pdf-lib/fpdf.php';
require_once __DIR__ . '/../includes/db.php'; // Include your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $_POST['client_name'];
    $service_type = $_POST['service_type'];
    $amount = $_POST['amount'];
    $payment_mode = $_POST['payment_mode'];
    $description = $_POST['description'];

    // ✅ 1. Save to receipts table
    $stmt = $conn->prepare("INSERT INTO receipts (client_name, service_type, amount, payment_mode, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $client_name, $service_type, $amount, $payment_mode, $description);
    $stmt->execute();
    $stmt->close();

    // ✅ 2. Generate the PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Logo
    $logoPath = __DIR__ . '/../assets/images/logo.png';
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 10, 10, 40);
    }

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

    $pdf->Cell(50,10,'Description:',0,1);
    $pdf->MultiCell(0,10,$description ?: 'N/A',0,1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial','I',10);
    $pdf->Cell(0,10,'Authorized Signature',0,1,'R');
    $pdf->Ln(5);
    $pdf->Cell(0,10,'Thank you for your business!',0,1,'C');

    // ✅ 3. Output PDF to browser
    $pdf->Output('I', 'receipt_' . time() . '.pdf');
    exit;
}
