<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Receipts | Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
      --success-color: #28a745;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
      --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
    
    .btn-view {
      background-color: var(--primary-color);
      color: white;
      border: none;
      transition: all 0.3s;
    }
    
    .btn-view:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
    }
    
    .amount-cell {
      font-weight: 600;
      color: var(--success-color);
    }
    
    .empty-state {
      padding: 2rem;
      text-align: center;
    }
    
    .empty-state i {
      font-size: 2.5rem;
      color: #adb5bd;
      margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
      .table-responsive {
        border-radius: 8px;
      }
      
      .table thead {
        display: none;
      }
      
      .table tr {
        display: block;
        margin-bottom: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }
      
      .table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
      }
      
      .table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--primary-color);
        margin-right: 1rem;
      }
      
      .table td:last-child {
        border-bottom: none;
      }
    }
  </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <i class="bi bi-shield-lock me-2"></i>
      <span>Admin Panel</span>
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

<!-- Dashboard Header -->
<div class="dashboard-header">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Receipt Management</h1>
    <p class="lead opacity-75">View and manage all generated receipts</p>
  </div>
</div>

<!-- Main Content -->
<div class="container mb-5">
  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Client Name</th>
              <th>Service</th>
              <th>Amount</th>
              <th>Payment Mode</th>
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
                  <td data-label="ID"><?= $row['id'] ?></td>
                  <td data-label="Client Name"><?= htmlspecialchars($row['client_name']) ?></td>
                  <td data-label="Service"><?= htmlspecialchars($row['service_type']) ?></td>
                  <td data-label="Amount" class="amount-cell">₹<?= number_format($row['amount'], 2) ?></td>
                  <td data-label="Payment Mode"><?= $row['payment_mode'] ?></td>
                  <td data-label="Date"><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
                  <td data-label="Actions">
                    <form action="receipt-pdf.php" method="POST" target="_blank">
                      <input type="hidden" name="client_name" value="<?= htmlspecialchars($row['client_name']) ?>">
                      <input type="hidden" name="service_type" value="<?= htmlspecialchars($row['service_type']) ?>">
                      <input type="hidden" name="amount" value="<?= $row['amount'] ?>">
                      <input type="hidden" name="payment_mode" value="<?= $row['payment_mode'] ?>">
                      <input type="hidden" name="description" value="<?= htmlspecialchars($row['description']) ?>">
                      <button type="submit" class="btn btn-sm btn-view">
                        <i class="bi bi-eye-fill me-1"></i> View
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7">
                  <div class="empty-state">
                    <i class="bi bi-receipt"></i>
                    <h5>No receipts found</h5>
                    <p class="text-muted">Generate your first receipt to see it listed here</p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>