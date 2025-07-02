<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Receipt Management Panel">
  <meta name="author" content="Ananya Sales & Service">

  <title>Ananya Sales & Service | Receipt List</title>

  <!-- Favicon -->
  <link rel="icon" href="../assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="../assets/images/favicon/site.webmanifest">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet"/>

  <style>
    :root {
      --primary-color: #e30613;
      --secondary-color: #a9030d;
      --success-color: #198754;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .dashboard-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 0 0 20px 20px;
      margin-bottom: 2rem;
      padding: 2rem 0;
    }

    .table thead th {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-view, .btn-email {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-view:hover, .btn-email:hover {
      background-color: var(--secondary-color);
    }

    .empty-state {
      padding: 2rem;
      text-align: center;
      color: #888;
    }
  </style>
</head>

<body>
  <div class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
          <i class="bi bi-arrow-left me-2"></i> Admin Panel
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
    <header class="dashboard-header text-center">
      <div class="container">
        <h1 class="fw-bold"><i class="bi bi-receipt me-2"></i>Receipt Management</h1>
        <p class="lead opacity-75">View and manage all generated receipts</p>
      </div>
    </header>

    <!-- Alerts -->
    <?php if (isset($_GET['mail'])): ?>
      <div class="container mt-3">
        <?php if ($_GET['mail'] === 'success'): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            Email sent successfully to the client.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif ($_GET['mail'] === 'error'): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Failed to send email.
            <?php if (!empty($_GET['reason'])): ?>
              <br><strong>Reason:</strong> <?= htmlspecialchars($_GET['reason']) ?>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-fill">
      <div class="container mb-5">
        <div class="card">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Service</th>
                    <th>Engineer</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $result = $conn->query("SELECT * FROM receipts ORDER BY created_at DESC");
                  if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()): ?>
                      <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['client_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['service_type'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['engineer'] ?? '') ?></td>
                        <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                        <td>
                          <div class="d-flex gap-2">
                            <a href="../<?= htmlspecialchars($row['pdf_path']) ?>" target="_blank" class="btn btn-sm btn-view">
                              <i class="bi bi-eye-fill me-1"></i> View
                            </a>
                            <form action="send-receipt-email.php" method="POST">
                              <input type="hidden" name="receipt_id" value="<?= $row['id'] ?>">
                              <button type="submit" class="btn btn-sm btn-email">
                                <i class="bi bi-envelope-fill me-1"></i> Email
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile;
                  else: ?>
                    <tr>
                      <td colspan="8">
                        <div class="empty-state">
                          <i class="bi bi-inbox-fill fs-3"></i>
                          <h5>No receipts found</h5>
                          <p>Start creating receipts to manage them here.</p>
                        </div>
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Optional: Auto-hide alert after 5s -->
  <script>
    setTimeout(() => {
      const alert = document.querySelector('.alert');
      if (alert) alert.classList.remove('show');
    }, 5000);
  </script>
</body>
</html>
