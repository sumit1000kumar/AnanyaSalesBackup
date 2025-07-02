<?php
require_once '../includes/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Helper: get engineer name by ID
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

  // Upload customer signature
  $signaturePath = '';
  if (!empty($_FILES['customer_sign_upload']['name'])) {
    $uploadDir = '../uploads/signatures/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $filename = 'cust_' . time() . '_' . basename($_FILES['customer_sign_upload']['name']);
    $target = $uploadDir . $filename;
    if (move_uploaded_file($_FILES['customer_sign_upload']['tmp_name'], $target)) {
      $signaturePath = $target;
    }
  }

  // Get engineer info
  $engineerId = $data['engineer_id'] ?? '';
  $engineerCustom = $data['engineer_custom_name'] ?? '';
  $engineerName = ($engineerId === 'other') ? $engineerCustom : getEngineerName($conn, $engineerId);
  $engineerSign = $data['engineer_signature_path'] ?? '';
  $serialNo = $data['serial_no'] ?? 'SN' . rand(1000, 9999);

  // Generate HTML content
  $html = '
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    .container { padding: 20px; border: 1px solid #000; }
    .header { text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 10px; }
    .contact { text-align: center; font-size: 11px; margin-bottom: 10px; }
    .section-title { font-weight: bold; margin-top: 15px; border-bottom: 1px solid #000; font-size: 13px; }
    .checkbox-grid { display: grid; grid-template-columns: repeat(3, 1fr); font-size: 11px; gap: 4px; }
    .checkbox-item { border: 1px solid #000; padding: 4px; }
    .signature-block { margin-top: 30px; display: flex; justify-content: space-between; }
    .signature { width: 45%; text-align: center; }
    .footer { margin-top: 20px; font-size: 10px; text-align: center; }
    img.signature-img { height: 60px; margin-top: 5px; }
  </style>

  <div class="container">
    <div class="header">Ananya Sales & Service</div>
    <div class="contact">
      702, The Gold Crest, Plot No.5, Phase 2, Navde Colony, Navde, Panvel - 410206<br>
      Mob: 9699555898 / 8104223378 | Email: devaaldar@gmail.com
    </div>

    <div class="section-title">Customer Details</div>
    <p><strong>Name:</strong> ' . $data['customer_name'] . '</p>
    <p><strong>Designation:</strong> ' . $data['designation'] . '</p>
    <p><strong>Address:</strong> ' . $data['address'] . '</p>
    <p><strong>Phone:</strong> ' . $data['phone'] . ' | <strong>Email:</strong> ' . $data['email'] . '</p>

    <div class="section-title">Service Info</div>
    <p><strong>Nature of Visit:</strong> ' . $data['nature_of_visit'] . '</p>
    <p><strong>Equipment:</strong> ' . $data['equipment'] . '</p>
    <p><strong>Model No.:</strong> ' . $data['model_no'] . '</p>
    <p><strong>Serial No.:</strong> ' . $serialNo . '</p>

    <div class="section-title">AMC Checklist</div>
    <div class="checkbox-grid">';
      foreach ($checklist as $key => $val) {
        $html .= '<div class="checkbox-item"><strong>' . $key . ':</strong> ' . $val . '</div>';
      }
  $html .= '</div>

    <div class="section-title">Remarks</div>
    <p><strong>Our Remarks:</strong> ' . $data['our_remarks'] . '</p>
    <p><strong>Spare Parts:</strong> ' . $data['spare_parts'] . '</p>
    <p><strong>Customer Remarks:</strong> ' . $data['customer_remarks'] . '</p>

    <div class="section-title">Service Report</div>
    <p><strong>Attended By:</strong> ' . $engineerName . '</p>
    <p><strong>Date:</strong> ' . $data['service_date'] . '</p>
    <p><strong>Service Rendered:</strong> ' . $data['service_rendered'] . '</p>

    <div class="signature-block">
      <div class="signature">
        <p><strong>Customer Signature</strong></p>';
        if ($signaturePath && file_exists($signaturePath)) {
          $html .= '<img src="../' . $signaturePath . '" class="signature-img" />';
        } else {
          $html .= '<p>Not available</p>';
        }
  $html .= '<p>' . $data['customer_name'] . '</p>
      </div>

      <div class="signature">
        <p><strong>Engineer Signature</strong></p>';
        if ($engineerSign && file_exists('../' . $engineerSign)) {
          $html .= '<img src="../' . $engineerSign . '" class="signature-img" />';
        } else {
          $html .= '<p>Not available</p>';
        }
  $html .= '<p>' . $engineerName . '</p>
      </div>
    </div>

    <div class="footer">
      Receipt generated on: ' . date("d M Y H:i:s") . '
    </div>
  </div>
  ';

  // Generate and Save PDF
  $dompdf = new Dompdf((new Options())->set('isRemoteEnabled', true));
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4');
  $dompdf->render();

  $filename = 'Service_Receipt_' . time() . '.pdf';
  $filepath = '../receipts/' . $filename;
  file_put_contents($filepath, $dompdf->output());

  // ✅ Assign to variables BEFORE bind_param
  $client_name = $data['customer_name'];
  $phone = $data['phone'];
  $email = $data['email'];
  $service_type = $data['nature_of_visit'];
  $engineer = $engineerName;
  $description = $data['our_remarks'];
  $pdf_path = 'receipts/' . $filename;

  // Save to DB
  $stmt = $conn->prepare("INSERT INTO receipts 
    (client_name, phone, email, service_type, engineer, description, pdf_path) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $client_name, $phone, $email, $service_type, $engineer, $description, $pdf_path);
  $stmt->execute();
  $stmt->close();

  header("Location: receipt-list.php?success=true");
  exit;
}
?>
