<?php
require_once '../includes/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

  // Generate unique report number
  $conn->query("LOCK TABLES report_numbers WRITE");
  $result = $conn->query("SELECT report_no FROM report_numbers ORDER BY id DESC LIMIT 1");
  $currentReportNo = ($result && $row = $result->fetch_assoc()) ? $row['report_no'] + 1 : 1700;
  $conn->query("INSERT INTO report_numbers (report_no) VALUES ($currentReportNo)");
  $conn->query("UNLOCK TABLES");

  $reportNo = 'SER/ASS/' . $currentReportNo;

  // Upload customer signature (drawn or uploaded)
  $signaturePath = '';
  $uploadDir = realpath('../uploads/signatures') . DIRECTORY_SEPARATOR;
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

  if (!empty($_FILES['customer_sign_upload']['name'])) {
    $filename = 'cust_' . time() . '_' . basename($_FILES['customer_sign_upload']['name']);
    $target = $uploadDir . $filename;
    if (move_uploaded_file($_FILES['customer_sign_upload']['tmp_name'], $target)) {
      $signaturePath = $target;
    }
  } elseif (!empty($_POST['customer_sign_data'])) {
    $base64 = $_POST['customer_sign_data'];
    if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
      $base64 = substr($base64, strpos($base64, ',') + 1);
      $type = strtolower($type[1]);

      if (in_array($type, ['png', 'jpg', 'jpeg'])) {
        $decoded = base64_decode($base64);
        if ($decoded !== false) {
          $filename = 'cust_drawn_' . time() . '.' . $type;
          $signaturePath = $uploadDir . $filename;
          file_put_contents($signaturePath, $decoded);

          // Save drawn signature for next receipt
          if (!empty($data['save_signature']) && !empty($data['customer_id']) && is_numeric($data['customer_id'])) {
  $relative = 'uploads/signatures/' . $filename;
  $stmt = $conn->prepare("UPDATE customers SET saved_signature = ? WHERE id = ?");
  $stmt->bind_param("si", $relative, $data['customer_id']);
  $stmt->execute();
}

        }
      }
    }
  }

  // Engineer info
  $engineerId = $data['engineer_id'] ?? '';
  $engineerCustom = $data['engineer_custom_name'] ?? '';
  $engineerName = ($engineerId === 'other') ? $engineerCustom : getEngineerName($conn, $engineerId);
  $engineerSign = !empty($data['engineer_signature_path']) ? realpath($data['engineer_signature_path']) : '';
 $logoPath = 'assets/images/logo/logo-report.jpeg';

  // Start HTML
  $html = '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Service Report - Ananya Sales & Service</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; color: #000; }
    .page { height: 1200px; display: flex; flex-direction: column; justify-content: space-between; }
    .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; }
    .logo img { height: 50px; margin-bottom: 5px; }
    .company-name { color: #d35400; font-weight: bold; font-size: 20px; }
    .contact-info { font-size: 10px; margin-top: 4px; }
    .report-no { text-align: right; font-size: 12px; }
    .section { margin-top: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td, th { padding: 6px 8px; vertical-align: top; }
    .bordered td, .bordered th { border: 1px solid #333; }
    .remarks-box { min-height: 50px; border: 1px solid #000; margin-bottom: 5px; padding: 4px; }
    .footer-table td { padding-top: 25px; width: 33%; text-align: left; vertical-align: top; }
    .equipment-table td { width: 33%; }
    .customer-info-table td { width: 50%; }
    .checklist-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
    .checklist-table th, .checklist-table td {
      border: 1px solid #666; padding: 4px; font-size: 10px; text-align: center;
    }
    .checklist-table td:first-child { text-align: left; width: 70%; }
    .checked { display: inline-block; width: 10px; height: 10px; background-color: black; border: 1px solid #000; margin: auto; }
    .unchecked { display: inline-block; width: 10px; height: 10px; border: 1px solid #000; margin: auto; }
  </style>
</head>
<body>
<div class="report-wrapper">
  <div class="page">

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="../assets/images/logo/logo.jpg" alt="Logo" />
      <div class="company-name">Ananya Sales & Service</div>
      <div class="contact-info">
        702, The Gold Crest, Plot no.5, Phase 2,<br />
        Navde Colony, Navde, Panvel - 410206<br />
        Mob.: 9699555898 | 8104223378<br />
        Email: ananyasalesservice@gmail.com
      </div>
    </div>
    <div class="report-no">
      SERVICE REPORT<br />
      No.: <strong>' . $reportNo . '</strong>
    </div>
  </div>

  <!-- Customer Info -->
  <div class="section">
    <table class="customer-info-table">
      <tr>
        <td><strong>Customer Name:</strong> ' . htmlspecialchars($data["customer_name"]) . '</td>
        <td><strong>Attended By:</strong> ' . htmlspecialchars($engineerName) . '</td>
      </tr>
      <tr>
        <td><strong>Address:</strong> ' . htmlspecialchars($data["address"]) . '</td>
        <td><strong>Date:</strong> ' . date("d-m-Y", strtotime($data["service_date"])) . '</td>
      </tr>
      <tr>
        <td><strong>Phone No.:</strong> ' . htmlspecialchars($data["phone"]) . '</td>
        <td><strong>Email ID:</strong> ' . htmlspecialchars($data["email"]) . '</td>
      </tr>
    </table>
  </div>

  <!-- Nature of Visit -->
  <div class="section">
    <strong>Nature of Visit:</strong> ' . htmlspecialchars($data["nature_of_visit"]) . '
  </div>

  <!-- Equipment Info -->
  <div class="section">
    <table class="bordered equipment-table">
      <tr>
        <td><strong>Equipment:</strong> ' . htmlspecialchars($data["equipment"]) . '</td>
        <td><strong>Model No.:</strong> ' . htmlspecialchars($data["model_no"]) . '</td>
        <td><strong>Serial No:</strong> ' . htmlspecialchars($data["serial_no"]) . '</td>
      </tr>
    </table>
  </div>

  <!-- AMC Checklist + Service Rendered -->
  <div class="section">
    <table class="bordered">
      <tr>
        <td style="width: 60%;">
          <strong>AMC Checklist</strong>
          <table class="checklist-table">
            <tr><th>Item</th><th>Yes</th><th>No</th></tr>';

  foreach ($checklist as $item => $val) {
    $html .= '<tr>
      <td>' . htmlspecialchars($item) . '</td>
      <td><span class="' . ($val === 'Yes' ? 'checked' : 'unchecked') . '"></span></td>
      <td><span class="' . ($val === 'No' ? 'checked' : 'unchecked') . '"></span></td>
    </tr>';
  }

  $html .= '</table>
        </td>
        <td><strong>Service Rendered:</strong><br />' . nl2br(htmlspecialchars($data["service_rendered"])) . '</td>
      </tr>
    </table>
  </div>

  <!-- Remarks Section -->
  <div class="section">
    <div style="margin-bottom: 5px;"><strong>Our Remarks:</strong></div>
    <div class="remarks-box">' . nl2br(htmlspecialchars($data["our_remarks"])) . '</div>
    <div style="margin-top: 10px;"><strong>Spares Parts Used:</strong> ' . nl2br(htmlspecialchars($data["spare_parts"])) . '</div>
    <div style="margin-top: 10px;"><strong>Recommended:</strong> ' . nl2br(htmlspecialchars($data["recommended"])) . '</div>
    <div style="margin-top: 10px; margin-bottom: 5px;"><strong>Customer\'s Remarks:</strong></div>
    <div class="remarks-box">' . nl2br(htmlspecialchars($data["customer_remarks"])) . '</div>
  </div>

  <!-- Signatures -->
  <div class="section">
    <table class="footer-table">
      <tr>
        <td><strong>Customer\'s Name:</strong> ' . htmlspecialchars($data["customer_name"]) . '</td>
        <td><strong>Signature:</strong><br/>' .
          ($signaturePath && file_exists($signaturePath) ? '<img src="file://' . $signaturePath . '" height="40"/>' : '__________') . '</td>
        <td><strong>Engineer Signature:</strong><br/>' .
          ($engineerSign && file_exists($engineerSign) ? '<img src="file://' . $engineerSign . '" height="40"/>' : '__________') . '</td>
      </tr>
      <tr>
        <td><strong>Designation:</strong> ' . htmlspecialchars($data["designation"]) . '</td>
        <td colspan="2"><strong>Mobile No.:</strong> ' . htmlspecialchars($data["phone"]) . '</td>
      </tr>
    </table>
  </div>

  </div>
</div>
</body>
</html>
';

  // Generate PDF
  $options = new Options();
  $options->set('isRemoteEnabled', true);
  $options->set('defaultFont', 'Arial');
  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();

  // Save to file
  $receiptDir = realpath(__DIR__ . '/../receipts');
  if (!is_dir($receiptDir)) mkdir($receiptDir, 0777, true);
  $receiptFilename = "receipt_{$currentReportNo}.pdf";
  $receiptPath = $receiptDir . DIRECTORY_SEPARATOR . $receiptFilename;
  file_put_contents($receiptPath, $dompdf->output());

  // Log receipt record
  $stmt = $conn->prepare("INSERT INTO receipts (client_name, phone, email, service_type, engineer, pdf_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
  $pdfRelativePath = 'receipts/' . $receiptFilename;
  $stmt->bind_param(
    "ssssss",
    $data["customer_name"],
    $data["phone"],
    $data["email"],
    $data["nature_of_visit"],
    $engineerName,
    $pdfRelativePath
  );
  $stmt->execute();

  // Output to browser
  $dompdf->stream("service_receipt_{$currentReportNo}.pdf", ["Attachment" => false]);
  exit;
}
?>
