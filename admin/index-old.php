<?php
// Show PHP errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../includes/db.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['complaint_id']);
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = "Status updated successfully!";
    header("Location: index.php");
    exit;
}

// Get filter values from GET
$filter_status = $_GET['status'] ?? '';
$search_term = $_GET['search'] ?? '';

// Build query with filters
$query = "SELECT * FROM complaints WHERE 1";

if (!empty($filter_status)) {
    $query .= " AND status = '" . $conn->real_escape_string($filter_status) . "'";
}

if (!empty($search_term)) {
    $term = $conn->real_escape_string($search_term);
    $query .= " AND (name LIKE '%$term%' OR contact LIKE '%$term%' OR complaint_type LIKE '%$term%')";
}

$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Complaint Management | Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
      --success-color: #2ecc71;
      --warning-color: #f39c12;
      --info-color: #3498db;
      --danger-color: #e74c3c;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .dashboard-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 0 0 20px 20px;
      box-shadow: 0 4px 20px rgba(67, 97, 238, 0.15);
      margin-bottom: 2rem;
      padding: 2rem 0;
    }
    
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
    }
    
    .table-responsive {
      border-radius: 12px;
      overflow: hidden;
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead th {
      background-color: var(--primary-color);
      color: white;
      border-bottom: none;
      font-weight: 500;
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(67, 97, 238, 0.05);
    }
    
    .badge {
      padding: 0.5em 0.75em;
      font-weight: 500;
      font-size: 0.85em;
    }
    
    .badge-new {
      background-color: var(--warning-color);
      color: white;
    }
    
    .badge-progress {
      background-color: var(--info-color);
      color: white;
    }
    
    .badge-resolved {
      background-color: var(--success-color);
      color: white;
    }
    
    .status-select {
      min-width: 140px;
    }
    
    .search-box {
      position: relative;
    }
    
    .search-box .bi {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: var(--primary-color);
    }
    
    .search-box input {
      padding-left: 40px;
    }
    
    .filter-btn {
      min-width: 100px;
    }
    
    .attachment-btn {
      min-width: 80px;
    }

    /* Mobile Card Styles */
    .complaint-card {
      border: 1px solid #e0e0e0;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      background: white;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
    
    .complaint-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f0f0f0;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }
    
    .complaint-id {
      font-weight: bold;
      color: var(--primary-color);
    }
    
    .complaint-date {
      font-size: 0.8rem;
      color: #666;
    }
    
    .complaint-status {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 500;
      margin-top: 5px;
    }
    
    .complaint-details {
      margin-bottom: 10px;
    }
    
    .detail-row {
      display: flex;
      margin-bottom: 8px;
    }
    
    .detail-label {
      font-weight: 600;
      color: var(--primary-color);
      min-width: 100px;
    }
    
    .detail-value {
      flex: 1;
    }
    
    .mobile-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
      flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
      /* Hide desktop table on mobile */
      .table-responsive {
        display: none;
      }
      
      /* Show mobile cards on mobile */
      .mobile-complaints-view {
        display: block;
      }
      
      /* Adjust filter layout */
      .filter-container .col-md-3, 
      .filter-container .col-md-5,
      .filter-container .col-md-2 {
        width: 100%;
        margin-bottom: 10px;
      }
    }
    
    @media (min-width: 769px) {
      /* Hide mobile view on desktop */
      .mobile-complaints-view {
        display: none;
      }
    }
  </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/admin/dashboard.php">
      <i class="bi bi-shield-lock me-2"></i>
      <span>Admin Panel</span>
    </a>
    <div class="d-flex align-items-center">
      <a href="dashboard.php" class="btn btn-outline-light me-3">
        <i class="bi bi-speedometer2 me-1"></i> Dashboard
      </a>
      <span class="text-white me-3 d-none d-sm-inline">Welcome, Admin</span>
      <a href="../auth/logout.php" class="btn btn-outline-light">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>
  </div>
</nav>

<!-- Dashboard Header -->
<div class="dashboard-header">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Complaint Management</h1>
    <p class="lead opacity-75">View and manage all submitted complaints</p>
  </div>
</div>

<!-- Main Content -->
<div class="container mb-5">
  <!-- Success Message -->
  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>
      <?= $_SESSION['success_message'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <!-- Filters Card -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" class="row g-3 align-items-center filter-container">
        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="New" <?= $filter_status === 'New' ? 'selected' : '' ?>>New</option>
            <option value="Under Progress" <?= $filter_status === 'Under Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Resolved" <?= $filter_status === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
          </select>
        </div>
        
        <div class="col-md-5 search-box">
          <i class="bi bi-search"></i>
          <input type="text" name="search" class="form-control" 
                 placeholder="Search by name, contact or type..." 
                 value="<?= htmlspecialchars($search_term) ?>">
        </div>
        
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100 filter-btn">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
        </div>
        
        <div class="col-md-2">
          <a href="index.php" class="btn btn-outline-secondary w-100">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Desktop Table View -->
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Type</th>
          <th>Status</th>
          <th>Date</th>
          <th>Attachment</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['contact']) ?></td>
              <td><?= htmlspecialchars($row['complaint_type']) ?></td>
              <td>
                <span class="badge 
                  <?= $row['status'] === 'Resolved' ? 'badge-resolved' : 
                     ($row['status'] === 'Under Progress' ? 'badge-progress' : 'badge-new') ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
              <td><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
              <td>
                <?php if (!empty($row['attachment'])): ?>
                  <a href="../uploads/<?= $row['attachment'] ?>" target="_blank" class="btn btn-sm btn-outline-primary attachment-btn">
                    <i class="bi bi-paperclip me-1"></i> View
                  </a>
                <?php else: ?>
                  <span class="text-muted">None</span>
                <?php endif; ?>
              </td>
              <td>
                <form method="POST" class="d-flex gap-2 align-items-center">
                  <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                  <select name="status" class="form-select form-select-sm status-select">
                    <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                    <option value="Under Progress" <?= $row['status'] === 'Under Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= $row['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                  </select>
                  <button type="submit" name="update_status" class="btn btn-sm btn-primary">
                    <i class="bi bi-check-lg"></i>
                    <span class="d-none d-md-inline">Update</span>
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">
              <i class="bi bi-exclamation-circle fs-4 d-block mb-2"></i>
              No complaints found matching your criteria
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <!-- Mobile Cards View -->
  <div class="mobile-complaints-view">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php 
      // Reset pointer to beginning for mobile view
      $result->data_seek(0); 
      while ($row = $result->fetch_assoc()): ?>
        <div class="complaint-card">
          <div class="complaint-card-header">
            <span class="complaint-id">#<?= $row['id'] ?></span>
            <span class="complaint-date"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></span>
          </div>
          
          <div class="complaint-details">
            <div class="detail-row">
              <span class="detail-label">Name:</span>
              <span class="detail-value"><?= htmlspecialchars($row['name']) ?></span>
            </div>
            
            <div class="detail-row">
              <span class="detail-label">Contact:</span>
              <span class="detail-value"><?= htmlspecialchars($row['contact']) ?></span>
            </div>
            
            <div class="detail-row">
              <span class="detail-label">Type:</span>
              <span class="detail-value"><?= htmlspecialchars($row['complaint_type']) ?></span>
            </div>
            
            <div class="detail-row">
              <span class="detail-label">Status:</span>
              <span class="detail-value">
                <span class="complaint-status 
                  <?= $row['status'] === 'Resolved' ? 'badge-resolved' : 
                     ($row['status'] === 'Under Progress' ? 'badge-progress' : 'badge-new') ?>">
                  <?= $row['status'] ?>
                </span>
              </span>
            </div>
            
            <?php if (!empty($row['attachment'])): ?>
              <div class="detail-row">
                <span class="detail-label">Attachment:</span>
                <span class="detail-value">
                  <a href="../uploads/<?= $row['attachment'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-paperclip me-1"></i> View
                  </a>
                </span>
              </div>
            <?php endif; ?>
          </div>
          
          <div class="mobile-actions">
            <form method="POST" class="d-flex gap-2 align-items-center flex-grow-1">
              <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
              <select name="status" class="form-select form-select-sm flex-grow-1">
                <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                <option value="Under Progress" <?= $row['status'] === 'Under Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Resolved" <?= $row['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
              </select>
              <button type="submit" name="update_status" class="btn btn-sm btn-primary">
                <i class="bi bi-check-lg"></i>
              </button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center py-4 text-muted">
        <i class="bi bi-exclamation-circle fs-4 d-block mb-2"></i>
        No complaints found matching your criteria
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Footer -->
<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>  
  // Enhance the user experience
  document.addEventListener('DOMContentLoaded', function() {
    // Focus search input when search icon is clicked (mobile)
    document.querySelector('.bi-search').addEventListener('click', function() {
      document.querySelector('[name="search"]').focus();
    });
    
    // Confirm before changing status to resolved
    const statusForms = document.querySelectorAll('form[method="POST"]');
    statusForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const selectedStatus = this.querySelector('select').value;
        if (selectedStatus === 'Resolved') {
          if (!confirm('Are you sure you want to mark this complaint as resolved?')) {
            e.preventDefault();
          }
        }
      });
    });
  });
</script>

</body>
</html>