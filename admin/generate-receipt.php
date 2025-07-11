<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../includes/db.php';

// Fetch customers with error handling
$customerQuery = "SELECT id, name, contact, address, designation, email, saved_signature FROM customers ORDER BY name ASC";

$customerResult = $conn->query($customerQuery);

switch (true) {
  case !$customerResult:
    die("Customer Query Error: " . $conn->error);
  default:
    $customers = $customerResult->fetch_all(MYSQLI_ASSOC);
}

// Fetch engineers with error handling
$engineerQuery = "SELECT id, name, signature_path FROM engineers ORDER BY name ASC";
$engineerResult = $conn->query($engineerQuery);

switch (true) {
  case !$engineerResult:
    die("Engineer Query Error: " . $conn->error);
  default:
    $engineers = $engineerResult->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Generate Service Receipt</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #e30613;
      --secondary-color: #a9030d;
      --accent-color: #ff5964;
    }
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .navbar {
      background-color: var(--primary-color);
    }
    .navbar .nav-link, .navbar-brand, .navbar .text-white {
      color: white !important;
    }
    .navbar .user-profile {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--accent-color);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
    }
    .logout-btn {
      font-size: 0.9rem;
      color: white;
      border: 1px solid white;
      padding: 4px 10px;
      border-radius: 6px;
      text-decoration: none;
      margin-left: 10px;
    }
    .logout-btn:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
      margin-bottom: -10px;
      text-align: center;
    }
    .form-section {
      background: white;
      padding: 30px;
      border-radius: 0 0 10px 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .section-title {
      color: var(--primary-color);
      font-weight: bold;
      border-bottom: 1px solid #ddd;
      margin-bottom: 15px;
      padding-bottom: 5px;
    }
    footer.main-footer {
      background-color: rgb(6, 7, 7);
      border-top: 1px solid #dee2e6;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: auto;
    }
  </style>
</head>
<body>
<div class="wrapper">

  <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-shield-lock me-2"></i> Admin Panel
        </a>
        <div class="d-flex align-items-center">
          <a href="dashboard.php" class="btn btn-outline-light me-2">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
          <a href="../auth/logout.php" class="btn btn-outline-light">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </div>
      </div>
    </nav>

  <!-- Header -->
  <div class="container mt-4">
    <div class="header">
      <h2><i class="bi bi-receipt-cutoff me-2"></i>Generate Service Receipt</h2>
    </div>

    <!-- Form -->
    <div class="form-section">
      <form id="receiptForm" action="receipt-pdf.php" method="POST" enctype="multipart/form-data">
        <!-- Customer Info -->
        <h5 class="section-title">Customer Information</h5>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Choose Existing Customer</label>
            <select name="customer_id" class="form-select" onchange="populateCustomer(this)">
              <option value="">-- Select from saved --</option>
              <?php foreach ($customers as $cust): ?>
                <!-- <option value="<?= $cust['id'] ?>"
                  data-name="<?= htmlspecialchars($cust['name']) ?>"
                  data-contact="<?= htmlspecialchars($cust['contact']) ?>"
                  data-address="<?= htmlspecialchars($cust['address']) ?>"
                  data-designation="<?= htmlspecialchars($cust['designation']) ?>"
                  data-email="<?= htmlspecialchars($cust['email']) ?>">
                  <?= htmlspecialchars($cust['name']) ?> (<?= $cust['contact'] ?>)
                </option> -->
                <option value="<?= $cust['id'] ?>"
  data-name="<?= htmlspecialchars($cust['name']) ?>"
  data-contact="<?= htmlspecialchars($cust['contact']) ?>"
  data-address="<?= htmlspecialchars($cust['address']) ?>"
  data-designation="<?= htmlspecialchars($cust['designation']) ?>"
  data-email="<?= htmlspecialchars($cust['email']) ?>"
  data-signature="<?= htmlspecialchars($cust['saved_signature'] ?? '') ?>">
  <?= htmlspecialchars($cust['name']) ?> (<?= $cust['contact'] ?>)
</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6"><input name="customer_name" id="customer_name" class="form-control" placeholder="Customer Name" required></div>
          <div class="col-md-6"><input name="designation" class="form-control" placeholder="Designation"></div>
          <div class="col-md-6"><input name="address" id="customer_address" class="form-control" placeholder="Address"></div>
          <div class="col-md-6"><input name="phone" id="customer_contact" class="form-control" placeholder="Phone" required></div>
          <div class="col-md-6"><input name="email" type="email" class="form-control" placeholder="Email"></div>
        </div>

        <hr class="my-4"/>

        <!-- Equipment -->
        <h5 class="section-title">Service & Equipment Details</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <select name="nature_of_visit" class="form-select" required>
              <option value="">Nature of Visit</option>
              <option>Installation</option>
              <option>Warranty</option>
              <option>Paid Service</option>
              <option>Service Contract</option>
              <option>Demo</option>
              <option>Validation</option>
              <option>Inspection</option>
              <option>F.S.</option>
            </select>
          </div>
          <div class="col-md-6"><input name="equipment" class="form-control" placeholder="Equipment"></div>
          <div class="col-md-6"><input name="model_no" class="form-control" placeholder="Model No."></div>
          <div class="col-md-6"><input name="serial_no" class="form-control" placeholder="Serial No."></div>
        </div>

        <hr class="my-4"/>

        <!-- AMC Checklist -->
        <h5 class="section-title">AMC Checklist</h5>
        <div class="row g-3">
          <?php
          $checklist = [
            "Compressor Current", "Condenser Fan Motor", "All Electric Connections",
            "Instrument Temp", "Blower Motor", "Heater",
            "Carbon Brush", "Centrifuge Rpm", "Recorder",
            "Agitator Motor", "Cleaning"
          ];
          foreach ($checklist as $item): ?>
            <div class="col-md-4">
              <label class="form-label"><?= $item ?></label>
              <select name="checklist[<?= $item ?>]" class="form-select" required>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          <?php endforeach; ?>
        </div>

        <hr class="my-4"/>

        <!-- Remarks -->
        <h5 class="section-title">Remarks</h5>
        <div class="mb-3"><textarea name="our_remarks" class="form-control" rows="2" placeholder="Our Remarks"></textarea></div>
        <div class="mb-3"><textarea name="spare_parts" class="form-control" rows="2" placeholder="Spare Parts Used"></textarea></div>
        <div class="mb-3"><textarea name="recommended" class="form-control" rows="2" placeholder="Recommended (if any)"></textarea></div>
        <div class="mb-3"><textarea name="customer_remarks" class="form-control" rows="2" placeholder="Customer's Remarks"></textarea></div>

        <hr class="my-4"/>

        <!-- Service Report -->
        <h5 class="section-title">Service Report</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Attended By (Engineer)</label>
            <select name="engineer_id" id="engineerDropdown" class="form-select" onchange="toggleEngineerInput(this)">
              <option value="">Select Engineer</option>
              <?php foreach ($engineers as $eng): ?>
                <option value="<?= $eng['id'] ?>"
                  data-name="<?= htmlspecialchars($eng['name']) ?>"
                  data-signature="<?= htmlspecialchars($eng['signature_path']) ?>">
                  <?= htmlspecialchars($eng['name']) ?>
                </option>
              <?php endforeach; ?>
              <option value="other">Other (manual entry)</option>
            </select>
          </div>
          <div class="col-md-6" id="engineerCustom" style="display:none;">
            <input type="text" name="engineer_custom_name" class="form-control" placeholder="Enter engineer name manually">
          </div>
          <div class="col-md-6"><input type="date" name="service_date" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
          <div class="col-md-6"><input name="service_rendered" class="form-control" placeholder="Service Rendered"></div>
        </div>

        <hr class="my-4"/>

        <!-- Signatures -->
        <h5 class="section-title">Signatures</h5>
        <!-- <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Customer Signature (Upload)</label>
            <input type="file" name="customer_sign_upload" accept="image/*" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Engineer Signature (auto-selected)</label>
            <input type="text" name="engineer_signature_path" id="engineerSignature" class="form-control" readonly>
          </div>
        </div> -->
        <!-- ✅ Customer Signature Drawing Tool -->
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Customer Signature (Upload or Draw)</label>
    <input type="file" name="customer_sign_upload" accept="image/*" class="form-control mb-2">

    <!-- Canvas for drawing -->
    <canvas id="signaturePad" width="300" height="120" style="border:1px solid #ccc; border-radius:5px;"></canvas>
    <input type="hidden" name="customer_sign_data" id="customer_sign_data">
    <div class="mt-2">
  <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSignature()">Clear</button>
  <label class="ms-3">
    <input type="checkbox" name="save_signature" value="1"> Save for next receipt
  </label>
  <button type="button" class="btn btn-sm btn-outline-primary ms-3" onclick="loadPreviousSignature()">Load Previous Signature</button>
</div>

  </div>

  <div class="col-md-6">
    <label class="form-label">Engineer Signature (auto-selected)</label>
    <input type="text" name="engineer_signature_path" id="engineerSignature" class="form-control" readonly>
  </div>
</div>

        <div class="mt-4 text-end">
  <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Reset</button>
  <button type="button" class="btn btn-primary" onclick="previewReport()"><i class="bi bi-eye me-1"></i> Preview Report</button>
</div>


      </form>
    </div>
  </div>

  <!-- Reciept Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="previewModalLabel"><i class="bi bi-receipt me-2"></i>Service Receipt Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Preview content will go here -->
        <div id="previewContent" class="px-2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
        <button type="submit" class="btn btn-success" form="receiptForm"><i class="bi bi-check-circle me-1"></i> Confirm & Generate</button>
      </div>
    </div>
  </div>
</div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="container">Built by Sumit Kumar</div>
  </footer>
</div>

<!-- signature pad -->
<!-- <script>
  const canvas = document.getElementById("signaturePad");
  const ctx = canvas.getContext("2d");
  let drawing = false;

  canvas.addEventListener("mousedown", () => drawing = true);
  canvas.addEventListener("mouseup", () => drawing = false);
  canvas.addEventListener("mouseout", () => drawing = false);
  canvas.addEventListener("mousemove", draw);

  function draw(e) {
    if (!drawing) return;
    const rect = canvas.getBoundingClientRect();
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";
    ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
  }

  function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById("customer_sign_data").value = "";
  }

  // Save canvas data on form submit
  document.getElementById("receiptForm").addEventListener("submit", function(e) {
    const signatureData = canvas.toDataURL("image/png");
    if (signatureData.includes("data:image/png;base64")) {
      document.getElementById("customer_sign_data").value = signatureData;
    }
  });
</script> -->
<script>
  const canvas = document.getElementById("signaturePad");
  const ctx = canvas.getContext("2d");
  let drawing = false;

  // Mouse events
  canvas.addEventListener("mousedown", startDrawing);
  canvas.addEventListener("mouseup", stopDrawing);
  canvas.addEventListener("mouseout", stopDrawing);
  canvas.addEventListener("mousemove", draw);

  // Touch events
  canvas.addEventListener("touchstart", (e) => startDrawing(e.touches[0]), false);
  canvas.addEventListener("touchend", stopDrawing);
  canvas.addEventListener("touchcancel", stopDrawing);
  canvas.addEventListener("touchmove", (e) => {
    draw(e.touches[0]);
    e.preventDefault(); // Prevent scrolling while drawing
  }, { passive: false });

  function getCanvasPos(e) {
    const rect = canvas.getBoundingClientRect();
    return {
      x: e.clientX - rect.left,
      y: e.clientY - rect.top
    };
  }

  function startDrawing(e) {
    drawing = true;
    ctx.beginPath();
    const pos = getCanvasPos(e);
    ctx.moveTo(pos.x, pos.y);
  }

  function stopDrawing() {
    drawing = false;
    ctx.beginPath();
  }

  function draw(e) {
    if (!drawing) return;
    const pos = getCanvasPos(e);
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.strokeStyle = "#000";
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
  }

  function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById("customer_sign_data").value = "";
  }

  // Save signature to hidden input
  document.getElementById("receiptForm").addEventListener("submit", function(e) {
    const signatureData = canvas.toDataURL("image/png");
    if (signatureData.includes("data:image/png;base64")) {
      document.getElementById("customer_sign_data").value = signatureData;
    }
  });
</script>

<script>
  function populateCustomer(sel) {
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('customer_name').value = opt.dataset.name || '';
  document.getElementById('customer_contact').value = opt.dataset.contact || '';
  document.getElementById('customer_address').value = opt.dataset.address || '';
  document.querySelector('[name="designation"]').value = opt.dataset.designation || '';
  document.querySelector('[name="email"]').value = opt.dataset.email || '';

  // Load saved signature if available
  const savedSignPath = opt.dataset.signature;
  if (savedSignPath) {
    const img = new Image();
    img.crossOrigin = 'Anonymous'; // For base64 conversion
    img.onload = function () {
      const canvas = document.getElementById("signaturePad");
      const ctx = canvas.getContext("2d");
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

      // Set base64 data to hidden input
      document.getElementById("customer_sign_data").value = canvas.toDataURL("image/png");
    };
    img.src = "../" + savedSignPath;
  } else {
    clearSignature();
  }
}

function loadPreviousSignature() {
  const customerSelect = document.querySelector('[name="customer_id"]');
  const selectedOption = customerSelect.options[customerSelect.selectedIndex];
  const savedSignPath = selectedOption.dataset.signature;

  if (!savedSignPath) {
    alert("No saved signature found for this customer.");
    return;
  }

  const img = new Image();
  img.crossOrigin = 'Anonymous'; // required for base64 conversion
  img.onload = function () {
    const canvas = document.getElementById("signaturePad");
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    // Store base64 in hidden input for submission
    document.getElementById("customer_sign_data").value = canvas.toDataURL("image/png");
  };
  img.src = "../" + savedSignPath;
}


  function toggleEngineerInput(sel) {
    const val = sel.value;
    document.getElementById('engineerCustom').style.display = val === 'other' ? 'block' : 'none';
    const sig = sel.options[sel.selectedIndex].dataset.signature || '';
    document.getElementById('engineerSignature').value = sig;
  }
</script>

<!-- recipet preview -->
 <script>
function previewReport() {
  const form = document.getElementById('receiptForm');
  const data = new FormData(form);

  let previewHtml = `
    <h6>Customer Info</h6>
    <ul>
      <li><strong>Name:</strong> ${data.get('customer_name')}</li>
      <li><strong>Designation:</strong> ${data.get('designation')}</li>
      <li><strong>Address:</strong> ${data.get('address')}</li>
      <li><strong>Phone:</strong> ${data.get('phone')}</li>
      <li><strong>Email:</strong> ${data.get('email')}</li>
    </ul>

    <h6>Service & Equipment</h6>
    <ul>
      <li><strong>Nature of Visit:</strong> ${data.get('nature_of_visit')}</li>
      <li><strong>Equipment:</strong> ${data.get('equipment')}</li>
      <li><strong>Model No.:</strong> ${data.get('model_no')}</li>
      <li><strong>Serial No.:</strong> ${data.get('serial_no')}</li>
    </ul>

    <h6>AMC Checklist</h6>
    <ul>
  `;

  document.querySelectorAll('select[name^="checklist"]').forEach(select => {
    previewHtml += `<li><strong>${select.name.replace('checklist[','').replace(']','')}:</strong> ${select.value}</li>`;
  });

  previewHtml += `
    </ul>

    <h6>Remarks</h6>
    <ul>
      <li><strong>Our Remarks:</strong> ${data.get('our_remarks')}</li>
      <li><strong>Spare Parts Used:</strong> ${data.get('spare_parts')}</li>
      <li><strong>Customer's Remarks:</strong> ${data.get('customer_remarks')}</li>
    </ul>

    <h6>Service Report</h6>
    <ul>
      <li><strong>Engineer:</strong> ${data.get('engineer_id') || data.get('engineer_custom_name')}</li>
      <li><strong>Service Date:</strong> ${data.get('service_date')}</li>
      <li><strong>Service Rendered:</strong> ${data.get('service_rendered')}</li>
    </ul>
  `;

  document.getElementById('previewContent').innerHTML = previewHtml;

  const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
  previewModal.show();
}
</script>

<!-- ✅ Required for modal, dropdowns, etc. -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
