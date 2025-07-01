<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../pdf-lib/fpdf.php';
require_once __DIR__ . '/../includes/db.php';

function getEngineerName($conn, $id) {
    if (!$id || $id === 'other') return '';
    $stmt = $conn->prepare("SELECT name FROM engineers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    return $name ?: '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $checklist = $_POST['checklist'] ?? [];

    // Save customer signature
    $customerSignPath = '';
    if (!empty($_FILES['customer_sign_upload']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/signatures/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $filename = 'cust_' . time() . '_' . basename($_FILES['customer_sign_upload']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['customer_sign_upload']['tmp_name'], $targetFile)) {
            $customerSignPath = $targetFile;
        }
    }

    // Get engineer name
    $engineerId = $data['engineer_id'] ?? '';
    $engineerCustom = $data['engineer_custom_name'] ?? '';
    $attended_by = ($engineerId === 'other') ? $engineerCustom : getEngineerName($conn, $engineerId);

    $engineerSignPath = $data['engineer_signature_path'] ?? '';

    // Generate PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Header
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Ananya Sales & Service', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5, '702, The Gold Crest, Plot no.5, Phase 2,', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Navde Colony, Navde, Panvel - 410206, Maharashtra', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Mob.: 9699555898 | 8104223378', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Email: devaaldar@gmail.com | ananyasalesnservice@gmail.com', 0, 1, 'C');
    $pdf->Ln(5); $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); $pdf->Ln(5);

    // Customer Info
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, 'Customer Information', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, "Name: " . ($data['customer_name'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Designation: " . ($data['designation'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Address: " . ($data['address'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Phone: " . ($data['phone'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Email: " . ($data['email'] ?? ''), 0, 1);
    $pdf->Ln(5);
    $pdf->Cell(0, 7, "Service Date: " . ($data['service_date'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Attended By: " . $attended_by, 0, 1);

    // Visit & Equipment
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, 'Nature of Visit: ' . ($data['nature_of_visit'] ?? ''), 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 7, "Equipment: " . ($data['equipment'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Model No.: " . ($data['model_no'] ?? ''), 0, 1);
    $pdf->Cell(0, 7, "Serial No.: " . ($data['serial_no'] ?? ''), 0, 1);

    // Checklist
    if (!empty($checklist)) {
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 7, 'AMC Checklist', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        foreach ($checklist as $item => $value) {
            $pdf->Cell(0, 6, "- " . ucfirst(str_replace('_', ' ', $item)) . ": $value", 0, 1);
        }
    }

    // Remarks
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, 'Our Remarks:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 7, $data['our_remarks'] ?? '-');

    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, 'Spare Parts Used:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 7, $data['spare_parts'] ?? '-');

    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 7, "Customer's Remarks:", 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 7, $data['customer_remarks'] ?? '-');

    // Signatures
    $pdf->Ln(10);
    $y = $pdf->GetY();

    if ($customerSignPath && file_exists($customerSignPath)) {
        $pdf->Image($customerSignPath, 30, $y, 50);
        $pdf->SetY($y + 25);
        $pdf->Cell(60, 5, 'Customer Signature', 0, 0, 'C');
    }

    if ($engineerSignPath && file_exists($engineerSignPath)) {
        $pdf->SetY($y);
        $pdf->Image($engineerSignPath, 120, $y, 50);
        $pdf->SetY($y + 25);
        $pdf->SetX(120);
        $pdf->Cell(60, 5, 'Engineer Signature', 0, 0, 'C');
    }

    // Save PDF
    $pdfDir = __DIR__ . '/../receipts/';
    if (!is_dir($pdfDir)) mkdir($pdfDir, 0777, true);
    $pdfFilename = 'Service_Receipt_' . time() . '.pdf';
    $pdfFullPath = $pdfDir . $pdfFilename;
    $pdf->Output('F', $pdfFullPath);  // Save to file

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO receipts (client_name, phone, email, service_type, engineer, description, pdf_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $client_name   = $data['customer_name'];
$phone         = $data['phone'];
$email         = $data['email'];
$service_type  = $data['nature_of_visit'];
$engineer      = $attended_by;
$description   = $data['our_remarks'];
$pdf_path      = 'receipts/' . $pdfFilename;

$stmt->bind_param(
    "sssssss",
    $client_name,
    $phone,
    $email,
    $service_type,
    $engineer,
    $description,
    $pdf_path
);

    $stmt->execute();

    // Redirect or confirmation
    header("Location: receipt-list.php?success=1");
    exit;
}
?>
