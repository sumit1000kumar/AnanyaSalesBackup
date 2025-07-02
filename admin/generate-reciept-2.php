<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../includes/db.php';

// Fetch customers
$customers = $conn->query("SELECT id, name, contact, address, designation, email FROM customers ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

// Fetch engineers
$engineers = $conn->query("SELECT id, name, signature_path FROM engineers ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
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
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
        <i class="bi bi-arrow-left me-2"></i> Dashboard
      </a>
      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['user_name'])): ?>
          <span class="text-white me-2 d-none d-sm-inline">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
          <div class="user-profile"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
          <a href="../auth/logout.php" class="logout-btn ms-2">Logout</a>
        <?php endif; ?>
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
      <form action="receipt-pdf.php" method="POST" enctype="multipart/form-data">

        <!-- Customer Info -->
        <h5 class="section-title">Customer Information</h5>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Choose Existing Customer</label>
            <select name="customer_id" class="form-select" onchange="populateCustomer(this)">
              <option value="">-- Select from saved --</option>
              <?php foreach ($customers as $cust): ?>
                <option value="<?= $cust['id'] ?>"
  data-name="<?= htmlspecialchars($cust['name']) ?>"
  data-contact="<?= htmlspecialchars($cust['contact']) ?>"
  data-address="<?= htmlspecialchars($cust['address']) ?>"
  data-designation="<?= htmlspecialchars($cust['designation']) ?>"
  data-email="<?= htmlspecialchars($cust['email']) ?>">
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
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Customer Signature (Upload)</label>
            <input type="file" name="customer_sign_upload" accept="image/*" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Engineer Signature (auto-selected)</label>
            <input type="text" name="engineer_signature_path" id="engineerSignature" class="form-control" readonly>
          </div>
        </div>

        <div class="mt-4 text-end">
          <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i> Reset</button>
          <button type="submit" class="btn btn-success"><i class="bi bi-file-earmark-pdf me-1"></i> Generate Receipt</button>
        </div>

      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="container">Built by Sumit Kumar</div>
  </footer>

</div>

<script>
  function populateCustomer(sel) {
  const opt = sel.options[sel.selectedIndex];
  document.getElementById('customer_name').value = opt.dataset.name || '';
  document.getElementById('customer_contact').value = opt.dataset.contact || '';
  document.getElementById('customer_address').value = opt.dataset.address || '';
  document.querySelector('[name="designation"]').value = opt.dataset.designation || '';
  document.querySelector('[name="email"]').value = opt.dataset.email || '';
}


  function toggleEngineerInput(sel) {
    const val = sel.value;
    document.getElementById('engineerCustom').style.display = val === 'other' ? 'block' : 'none';
    const sig = sel.options[sel.selectedIndex].dataset.signature || '';
    document.getElementById('engineerSignature').value = sig;
  }
</script>

</body>
</html>
