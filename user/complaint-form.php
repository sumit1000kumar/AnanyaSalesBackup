<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}

include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $complaint_type = filter_input(INPUT_POST, 'complaint_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // nature of visit
    $equipment = filter_input(INPUT_POST, 'equipment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // File upload handling
    $attachment_path = '';
    if (!empty($_FILES['attachment']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $max_size = 5 * 1024 * 1024;

        if (in_array($_FILES['attachment']['type'], $allowed_types) && $_FILES['attachment']['size'] <= $max_size) {
            $target_dir = "../uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $file_extension = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $file_extension;
            $attachment_path = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment_path)) {
                $attachment_path = $new_filename; // Store only filename in DB
            } else {
                $attachment_path = '';
            }
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, name, contact, email, complaint_type, equipment, attachment) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $_SESSION['user_id'], $name, $contact, $email, $complaint_type, $equipment, $attachment_path);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Complaint submitted successfully!";
        header("Location: complaint-form.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Error submitting complaint. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ananya Sales & Service | Complaint Form</title>
  <link rel="icon" href="../assets/images/favicon/favicon.ico" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <style>
    :root {
      --primary-color: #e30613;
      --secondary-color: #a9030d;
      --accent-color: #ff5964;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--dark-text);
    }

    .navbar {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .form-container {
      max-width: 800px;
      margin: 2rem auto;
      border-radius: 12px;
      background: white;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .form-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 1.5rem;
      text-align: center;
    }

    .form-body {
      padding: 2rem;
    }

    .form-label {
      font-weight: 500;
    }

    .form-control,
    .form-select {
      border-radius: 8px;
      padding: 0.75rem;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border: none;
      border-radius: 8px;
    }

    .btn-primary:hover {
      background-color: var(--secondary-color);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      background-color: var(--accent-color);
      color: white;
      font-weight: bold;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: 10px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="../index.php">
      <i class="bi bi-arrow-left me-2"></i> Dashboard
    </a>
    <div class="d-flex align-items-center">
      <span class="text-white me-2"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
      <div class="user-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></div>
      <a href="../auth/logout.php" class="btn btn-outline-light ms-3">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $_SESSION['success_message'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $_SESSION['error_message'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
  <?php endif; ?>

  <div class="form-container">
    <div class="form-header">
      <h2><i class="bi bi-megaphone me-2"></i> Submit New Complaint</h2>
      <p class="mb-0">Please fill out the form below</p>
    </div>

    <div class="form-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" name="contact" placeholder="+91..." required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="you@example.com" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Nature of Visit</label>
            <select class="form-select" name="complaint_type" required>
              <option value="" disabled selected>Select nature of visit</option>
              <option value="Installation">Installation</option>
              <option value="Service">Service</option>
              <option value="Repair">Repair</option>
              <option value="Inspection">Inspection</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Select Equipment</label>
            <select class="form-select" name="equipment" required>
  <option value="" disabled selected>Select equipment</option>
  <option value="Plasma Freezer">Plasma Freezer</option>
  <option value="Blood Storage Cabinet">Blood Storage Cabinet</option>
  <option value="Lab Centrifuge">Lab Centrifuge</option>
  <option value="Components Centrifuge">Components Centrifuge</option>
  <option value="Tube Sealer">Tube Sealer</option>
  <option value="Weighing Scale">Weighing Scale</option>
  <option value="Cryo Freezer">Cryo Freezer</option>
  <option value="Platelet Incubator">Platelet Incubator</option>
  <option value="Platelet Agitator">Platelet Agitator</option>
  <option value="Blood Collection Monitor">Blood Collection Monitor</option>
  <option value="Laminar Air Flow">Laminar Air Flow</option>
  <option value="Donor Chair">Donor Chair</option>
  <option value="Reagent Storage Cabinet">Reagent Storage Cabinet</option>
  <option value="Water Bath">Water Bath</option>
  <option value="Lab Incubator">Lab Incubator</option>
  <option value="Lab Oven">Lab Oven</option>
  <option value="Autoclave">Autoclave</option>
  <option value="Rotary Shaker">Rotary Shaker</option>
  <option value="Plasma Thawing Bath">Plasma Thawing Bath</option>
  <option value="Data Logger">Data Logger</option>
  <option value="Other">Other</option>
</select>

          </div>
          <div class="col-12">
            <label class="form-label">Attachment (Optional)</label>
            <input type="file" class="form-control" name="attachment" />
            <div class="form-text">Allowed: JPG, PNG, PDF, DOC (max 5MB)</div>
          </div>
          <div class="col-12 mt-3 d-flex justify-content-end">
            <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
            <button type="submit" class="btn btn-primary">Submit Complaint</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
