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
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 9px; padding: 10px; }
    .container { max-width: 100%; border: 2px solid #333; padding: 10px; background-color: #e8e8e8; }
    .header { background-color: #fff; padding: 10px; border-bottom: 1px solid #333; position: relative; }
    .company-logo { display: inline-block; vertical-align: top; margin-right: 10px; }
    .logo-triangle { width: 0; height: 0; border-left: 20px solid #d32f2f; border-top: 12px solid transparent; border-bottom: 12px solid transparent; display: inline-block; margin-right: 5px; }
    .company-name { color: #d32f2f; font-size: 16px; font-weight: bold; display: inline-block; vertical-align: middle; }
    .company-info { font-size: 8px; color: #333; margin-top: 3px; line-height: 1.2; }
    .service-header { position: absolute; top: 10px; right: 10px; text-align: right; font-size: 9px; color: #333; }
    .service-number { font-size: 14px; font-weight: bold; margin-top: 3px; }
    .form-body { padding: 10px; }
    .form-row { display: flex; margin-bottom: 5px; }
    .form-group { flex: 1; margin-right: 10px; }
    .form-group:last-child { margin-right: 0; }
    .form-label { font-size: 8px; color: #333; margin-bottom: 2px; display: block; }
    .form-value { border-bottom: 1px solid #333; padding: 2px 0; min-height: 12px; font-size: 9px; }
    .nature-visit { margin: 8px 0; font-size: 9px; color: #333; }
    .equipment-section { border: 1px solid #333; margin: 8px 0; }
    .equipment-header { display: flex; border-bottom: 1px solid #333; background-color: #d8d8d8; }
    .equipment-col { flex: 1; padding: 5px; border-right: 1px solid #333; font-size: 9px; font-weight: bold; text-align: center; }
    .equipment-col:last-child { border-right: none; }
    .equipment-row { display: flex; min-height: 25px; }
    .equipment-cell { flex: 1; padding: 5px; border-right: 1px solid #333; font-size: 9px; background-color: #fff; }
    .equipment-cell:last-child { border-right: none; }
    .checklist-section { display: flex; margin: 8px 0; }
    .checklist-left { flex: 1; margin-right: 10px; }
    .checklist-right { flex: 1; }
    .checklist-title { font-size: 9px; font-weight: bold; margin-bottom: 5px; color: #333; }
    .checklist-item { display: flex; margin-bottom: 4px; align-items: center; font-size: 8px; }
    .checklist-bullet { width: 4px; height: 4px; background-color: #333; border-radius: 50%; margin-right: 5px; flex-shrink: 0; }
    .checklist-text { flex: 1; color: #333; }
    .checkbox-group { display: flex; margin-left: 8px; }
    .checkbox-item { margin-right: 10px; display: flex; align-items: center; font-size: 8px; }
    .checkbox { width: 10px; height: 10px; border: 1px solid #333; margin-right: 3px; background-color: #fff; }
    .checkbox.checked { background-color: #333; }
    .section-title { font-size: 9px; font-weight: bold; margin: 8px 0 4px 0; color: #333; }
    .large-input { width: 100%; border: none; border-bottom: 1px solid #333; background-color: transparent; padding: 2px 0; font-size: 9px; margin-bottom: 5px; min-height: 12px; }
    .signature-section { margin-top: 10px; border-top: 1px solid #333; padding-top: 8px; }
    .signature-row { display: flex; margin-bottom: 5px; }
    .signature-col { flex: 1; margin-right: 10px; }
    .signature-col:last-child { margin-right: 0; }
    .signature-line { border-bottom: 1px solid #333; height: 20px; margin-top: 2px; position: relative; }
    .signature-img { max-height: 25px; max-width: 80px; position: absolute; bottom: 0; }
  </style>

  <div class="container">
    <div class="header">
      <div class="company-logo">
        <div class="logo-triangle"></div>
        <span class="company-name">Ananya Sales & Service</span>
      </div>
      <div class="company-info">
        702, The Gold Crest, Plot no.5, Phase 2, Navde Colony,<br>
        Navde, Panvel - 410206 | Mob: 9699555898 / 8104223378<br>
        Email: devaaldar@gmail.com | ananyasalesservice@gmail.com
      </div>
      <div class="service-header">
        SERVICE REPORT : SER/ASS<br>
        <div class="service-number">No.: ' . rand(1000, 9999) . '</div>
      </div>
    </div>

    <div class="form-body">
      <div class="form-row">
        <div class="form-group">
          <div class="form-label">Customer Name</div>
          <div class="form-value">' . $data['customer_name'] . '</div>
        </div>
        <div class="form-group">
          <div class="form-label">Designation</div>
          <div class="form-value">' . $data['designation'] . '</div>
        </div>
        <div class="form-group">
          <div class="form-label">Attended By</div>
          <div class="form-value">' . $engineerName . '</div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <div class="form-label">Address</div>
          <div class="form-value">' . $data['address'] . '</div>
        </div>
        <div class="form-group">
          <div class="form-label">Date</div>
          <div class="form-value">' . $data['service_date'] . '</div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <div class="form-label">Phone No.</div>
          <div class="form-value">' . $data['phone'] . '</div>
        </div>
        <div class="form-group">
          <div class="form-label">Email ID</div>
          <div class="form-value">' . $data['email'] . '</div>
        </div>
        <div class="form-group">
          <div class="form-label">Nature of Visit</div>
          <div class="form-value">' . $data['nature_of_visit'] . '</div>
        </div>
      </div>

      <div class="equipment-section">
        <div class="equipment-header">
          <div class="equipment-col">Equipment</div>
          <div class="equipment-col">Model No.</div>
          <div class="equipment-col">Serial No.</div>
        </div>
        <div class="equipment-row">
          <div class="equipment-cell">' . $data['equipment'] . '</div>
          <div class="equipment-cell">' . $data['model_no'] . '</div>
          <div class="equipment-cell">' . $serialNo . '</div>
        </div>
      </div>

      <div class="checklist-section">
        <div class="checklist-left">
          <div class="checklist-title">AMC Checklist</div>';
          
          $checklistItems = [
            "Compressor Current", "Condenser Fan Motor", "All Electric Connections",
            "Instrument Temp", "Blower Motor", "Heater", "Carbon Brush", 
            "Centrifuge Rpm", "Recorder", "Agitator Motor", "Cleaning"
          ];
          
          foreach ($checklistItems as $item) {
            $value = $checklist[$item] ?? 'No';
            $html .= '
            <div class="checklist-item">
              <div class="checklist-bullet"></div>
              <div class="checklist-text">' . $item . '</div>
              <div class="checkbox-group">
                <div class="checkbox-item">
                  <div class="checkbox ' . ($value === 'Yes' ? 'checked' : '') . '"></div>
                  <span>Yes</span>
                </div>
                <div class="checkbox-item">
                  <div class="checkbox ' . ($value === 'No' ? 'checked' : '') . '"></div>
                  <span>No</span>
                </div>
              </div>
            </div>';
          }
          
          $html .= '
        </div>
        <div class="checklist-right">
          <div class="checklist-title">Service Rendered</div>
          <div class="large-input">' . $data['service_rendered'] . '</div>
          <div class="section-title">Our Remarks</div>
          <div class="large-input">' . $data['our_remarks'] . '</div>
          <div class="section-title">Spare Parts Used</div>
          <div class="large-input">' . $data['spare_parts'] . '</div>
        </div>
      </div>

      <div class="section-title">Customer\'s Remarks</div>
      <div class="large-input">' . $data['customer_remarks'] . '</div>

      <div class="signature-section">
        <div class="signature-row">
          <div class="signature-col">
            <div class="form-label">Customer\'s Name</div>
            <div class="signature-line">' . $data['customer_name'] . '</div>
          </div>
          <div class="signature-col">
            <div class="form-label">Signature</div>
            <div class="signature-line">';
            if ($signaturePath && file_exists($signaturePath)) {
              $html .= '<img src="../' . $signaturePath . '" class="signature-img" />';
            }
            $html .= '</div>
          </div>
          <div class="signature-col">
            <div class="form-label">Engineer Signature</div>
            <div class="signature-line">';
            if ($engineerSign && file_exists('../' . $engineerSign)) {
              $html .= '<img src="../' . $engineerSign . '" class="signature-img" />';
            }
            $html .= '</div>
          </div>
        </div>
        <div class="signature-row">
          <div class="signature-col">
            <div class="form-label">Designation</div>
            <div class="signature-line">' . $data['designation'] . '</div>
          </div>
          <div class="signature-col">
            <div class="form-label">Mobile No.</div>
            <div class="signature-line">' . $data['phone'] . '</div>
          </div>
          <div class="signature-col">
            <div class="form-label">Date</div>
            <div class="signature-line">' . $data['service_date'] . '</div>
          </div>
        </div>
      </div>
    </div>
  </div>';

  // Generate and Save PDF
  $options = new Options();
  $options->set('isRemoteEnabled', true);
  $options->set('defaultPaperSize', 'A4');
  $options->set('defaultPaperOrientation', 'portrait');
  
  $dompdf = new Dompdf($options);
  $dompdf->loadHtml($html);
  $dompdf->render();

  $filename = 'Service_Receipt_'.time().'.pdf';
  $filepath = '../receipts/'.$filename;
  file_put_contents($filepath, $dompdf->output());

  // Save to DB
  $client_name = $data['customer_name'];
  $phone = $data['phone'];
  $email = $data['email'];
  $service_type = $data['nature_of_visit'];
  $engineer = $engineerName;
  $description = $data['our_remarks'];
  $pdf_path = 'receipts/'.$filename;

  $stmt = $conn->prepare("INSERT INTO receipts (client_name, phone, email, service_type, engineer, description, pdf_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $client_name, $phone, $email, $service_type, $engineer, $description, $pdf_path);
  $stmt->execute();
  $stmt->close();

  header("Location: receipt-list.php?success=true");
  exit;
}
?>