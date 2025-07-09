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

  // Compact HTML for single page
  $html = '
<style>
  body { font-family: Arial, sans-serif; font-size: 10px; margin: 20px; }
  .header { text-align: center; margin-bottom: 10px; }
  .header h2 { margin: 0; font-size: 18px; }
  .section { margin-top: 10px; }
  .section h4 { border-bottom: 1px solid #999; margin-bottom: 4px; font-size: 12px; }
  table { width: 100%; border-collapse: collapse; margin-top: 5px; }
  td, th { padding: 4px; vertical-align: top; }
  .bordered td, .bordered th { border: 1px solid #ccc; }
  .signatures img { height: 40px; }
</style>

<div class="header">
  
  <h2><img src="../assets/images/logo/logo.jpg"  style="height:20px; vertical-align: middle;">Ananya Sales and Service</h2>
  <div>Gold Crest, Plot No. 5, Navde - 410208</div>
  <div>Contact: +91 98765 43210 | Email: service@ananyasales.com</div>
  <hr />
  <strong>Service Receipt</strong><br/>
  <small>Date: ' . date("d-m-Y", strtotime($data["service_date"])) . '</small>
</div>

<div class="section">
  <h4>Customer Information</h4>
  <table>
    <tr><td><strong>Name:</strong> ' . $data["customer_name"] . '</td><td><strong>Designation:</strong> ' . $data["designation"] . '</td></tr>
    <tr><td><strong>Contact:</strong> ' . $data["phone"] . '</td><td><strong>Email:</strong> ' . $data["email"] . '</td></tr>
    <tr><td colspan="2"><strong>Address:</strong> ' . $data["address"] . '</td></tr>
  </table>
</div>

<div class="section">
  <h4>Service & Equipment Details</h4>
  <table>
    <tr><td><strong>Nature of Visit:</strong> ' . $data["nature_of_visit"] . '</td><td><strong>Equipment:</strong> ' . $data["equipment"] . '</td></tr>
    <tr><td><strong>Model No:</strong> ' . $data["model_no"] . '</td><td><strong>Serial No:</strong> ' . $data["serial_no"] . '</td></tr>
  </table>
</div>

<div class="section">
  <h4>AMC Checklist</h4>
  <table class="bordered">';
  foreach ($checklist as $item => $val) {
    $html .= '<tr><td><strong>' . htmlspecialchars($item) . '</strong></td><td>' . htmlspecialchars($val) . '</td></tr>';
  }
  $html .= '</table>
</div>

<div class="section">
  <h4>Remarks</h4>
  <table>
    <tr><td><strong>Our Remarks:</strong> ' . nl2br($data["our_remarks"]) . '</td></tr>
    <tr><td><strong>Spare Parts Used:</strong> ' . nl2br($data["spare_parts"]) . '</td></tr>
    <tr><td><strong>Customer\'s Remarks:</strong> ' . nl2br($data["customer_remarks"]) . '</td></tr>
  </table>
</div>

<div class="section">
  <h4>Service Report</h4>
  <table>
    <tr><td><strong>Engineer:</strong> ' . $engineerName . '</td><td><strong>Service Rendered:</strong> ' . $data["service_rendered"] . '</td></tr>
    <tr><td colspan="2"><strong>Service Date:</strong> ' . date("d-m-Y", strtotime($data["service_date"])) . '</td></tr>
  </table>
</div>

<div class="section">
  <h4>Signatures</h4>
  <table class="signatures" style="margin-top:10px;">
    <tr>
      <td><strong>Customer Signature:</strong><br>';
        if ($signaturePath) {
          $html .= '<img src="' . $signaturePath . '" height="40"/>';
        } else {
          $html .= 'N/A';
        }
$html .= '</td>
      <td><strong>Engineer Signature:</strong><br>';
        if (!empty($engineerSign)) {
          $html .= '<img src="' . $engineerSign . '" height="40"/>';
        } else {
          $html .= 'N/A';
        }
$html .= '</td>
    </tr>
  </table>
</div>
';

  // Generate PDF
  $options = new Options();
  $options->set('defaultFont', 'Arial');
  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->setPaper('A4', 'portrait');
  $dompdf->render();
  $dompdf->stream("service_receipt.pdf", ["Attachment" => false]);
  exit;
}
?>
