<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include '../includes/db.php';

// ADD ENGINEER
if (isset($_POST['add_engineer'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);

  if ($_FILES['signature']['error'] === 0) {
    $uploadDir = '../uploads/engineers/';
    $filename = uniqid() . '_' . basename($_FILES['signature']['name']);
    $uploadPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['signature']['tmp_name'], $uploadPath)) {
      $relativePath = 'uploads/engineers/' . $filename;
      $query = "INSERT INTO engineers (name, signature_path) VALUES ('$name', '$relativePath')";
      if (mysqli_query($conn, $query)) {
        $success = "Engineer added successfully!";
      } else {
        $error = "Database error: " . mysqli_error($conn);
      }
    } else {
      $error = "Failed to upload signature.";
    }
  } else {
    $error = "File upload error.";
  }
}

// DELETE ENGINEER
if (isset($_POST['delete_engineer'])) {
  $engineerId = intval($_POST['delete_engineer_id']);

  // Fetch file path
  $getFileQuery = "SELECT signature_path FROM engineers WHERE id = $engineerId";
  $fileResult = mysqli_query($conn, $getFileQuery);
  if ($fileResult && mysqli_num_rows($fileResult) > 0) {
    $fileData = mysqli_fetch_assoc($fileResult);
    $filePath = '../' . $fileData['signature_path'];

    // Delete from DB
    $deleteQuery = "DELETE FROM engineers WHERE id = $engineerId";
    if (mysqli_query($conn, $deleteQuery)) {
      if (file_exists($filePath)) {
        unlink($filePath);
      }
      $success = "Engineer deleted successfully.";
    } else {
      $error = "Error deleting engineer: " . mysqli_error($conn);
    }
  } else {
    $error = "Engineer not found.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Engineer Management | Ananya Sales and Service</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    :root {
      --primary-color: #e30613;
      --secondary-color: #a9030d;
      --accent-color: #ff5964;
      --success-color: #198754;
      --warning-color: #f59e0b;
      --info-color: #0ea5e9;
      --danger-color: #ef4444;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: var(--primary-color);
    }

    .logout-btn {
      font-size: 0.9rem;
      color: white;
      background: transparent;
      border: 1px solid white;
      padding: 4px 10px;
      border-radius: 6px;
      text-decoration: none;
      margin-left: 10px;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .main-footer {
      background-color: #000;
      border-top: 1px solid #dee2e6;
      width: 100%;
    }

    .main-footer .text-muted {
      color: white !important;
    }

    .card {
      box-shadow: var(--card-shadow);
      border-radius: 12px;
    }

    .card-header {
      font-weight: 600;
    }

    .table img {
      border: 1px solid #dee2e6;
      border-radius: 6px;
      padding: 2px;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
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

  <!-- Page Content -->
  <div class="container my-5">
    <h3 class="mb-4">Manage Engineers</h3>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Engineer Form -->
    <div class="card mb-4">
      <div class="card-header text-white" style="background-color: var(--primary-color); border: 1px solid var(--primary-color);">Add New Engineer</div>
      <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="name" class="form-label">Engineer Name</label>
            <input type="text" name="name" class="form-control" required />
          </div>
          <div class="mb-3">
            <label for="signature" class="form-label">Signature Image</label>
            <input type="file" name="signature" class="form-control" accept="image/*" required />
          </div>
          <button type="submit" name="add_engineer" class="btn btn-success">Add Engineer</button>
        </form>
      </div>
    </div>

    <!-- Engineer List Table -->
    <div class="card">
      <div class="card-header bg-dark text-white">Engineers List</div>
      <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Signature</th>
              <th>Added On</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM engineers ORDER BY id DESC");
            $sn = 1;
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$sn}</td>
                      <td>{$row['name']}</td>
                      <td><img src='../{$row['signature_path']}' alt='Signature' height='40'></td>
                      <td>{$row['created_at']}</td>
                      <td>
                        <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this engineer?\");'>
                          <input type='hidden' name='delete_engineer_id' value='{$row['id']}' />
                          <button type='submit' name='delete_engineer' class='btn btn-sm btn-danger'>
                            <i class='bi bi-trash'></i> Delete
                          </button>
                        </form>
                      </td>
                    </tr>";
              $sn++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="main-footer mt-auto py-3">
    <div class="container text-center">
      <span class="text-muted">Built by Sumit Kumar</span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
