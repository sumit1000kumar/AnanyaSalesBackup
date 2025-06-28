<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
  header("Location: auth/login.php");
  exit;
}

require '../includes/db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM complaints WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard | Complaint Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --primary-light: #e0e7ff;
      --secondary-color: #3a0ca3;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .dashboard-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 1.5rem 0;
      margin-bottom: 2rem;
      box-shadow: var(--card-shadow);
    }
    
    .dashboard-container {
      flex: 1;
      padding: 0 1rem;
    }
    
    .welcome-card {
      background: white;
      border-radius: 10px;
      box-shadow: var(--card-shadow);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .complaint-card {
      background: white;
      border-radius: 10px;
      box-shadow: var(--card-shadow);
      padding: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .table-responsive {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead {
      background-color: var(--primary-color);
      color: white;
    }
    
    .badge {
      font-weight: 500;
      padding: 0.5em 0.75em;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
      .dashboard-header {
        padding: 1.25rem 0;
      }
      
      .welcome-card, .complaint-card {
        padding: 1.25rem;
      }
    }
    
    @media (max-width: 768px) {
      .dashboard-header {
        padding: 1rem 0;
      }
      
      .welcome-card, .complaint-card {
        padding: 1rem;
      }
      
      .table thead {
        display: none;
      }
      
      .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
      }
      
      .table tr {
        margin-bottom: 1rem;
        border-bottom: 2px solid #dee2e6;
      }
      
      .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border-bottom: 1px solid #dee2e6;
      }
      
      .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 1rem;
        width: calc(50% - 1rem);
        padding-right: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--primary-color);
      }
      
      .table td:last-child {
        border-bottom: 0;
      }
    }
    
    @media (max-width: 576px) {
      .dashboard-header {
        padding: 0.75rem 0;
      }
      
      .welcome-card h2 {
        font-size: 1.5rem;
      }
      
      .complaint-card h4 {
        font-size: 1.25rem;
      }
      
      .table td {
        padding-left: 40%;
      }
      
      .table td::before {
        width: calc(40% - 1rem);
      }
    }
  </style>
</head>
<body>
  <header class="dashboard-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">Complaint Management System</h1>
        <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>
    </div>
  </header>

  <main class="dashboard-container">
    <div class="container">
      <!-- Welcome Card -->
      <div class="welcome-card">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
          <div class="mb-3 mb-md-0">
            <h2 class="mb-1">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h2>
            <p class="text-muted mb-0">Track and manage your complaints</p>
          </div>
          <a href="./complaint-form.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Submit New Complaint
          </a>
        </div>
      </div>

      <!-- Complaints Section -->
      <div class="complaint-card">
        <h4 class="mb-4">Your Complaint History</h4>
        
        <?php if ($result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Date Submitted</th>
                </tr>
              </thead>
              <tbody>
                <?php $count = 1; while($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td data-label="#"><?= $count++ ?></td>
                    <td data-label="Type"><?= htmlspecialchars($row['complaint_type']) ?></td>
                    <td data-label="Description"><?= htmlspecialchars($row['description']) ?></td>
                    <td data-label="Status">
                      <?php
                        $badgeClass = match(strtolower($row['status'])) {
                          'resolved' => 'success',
                          'under progress' => 'warning',
                          'pending', '' => 'secondary',
                          default => 'info'
                        };
                      ?>
                      <span class="badge bg-<?= $badgeClass ?>">
                        <?= ucfirst($row['status']) ?>
                      </span>
                    </td>
                    <td data-label="Date Submitted"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            You haven't submitted any complaints yet.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <?php include '../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>