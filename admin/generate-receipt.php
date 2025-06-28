<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Receipt | Payment System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3a0ca3;
      --success-color: #28a745;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .admin-navbar {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .receipt-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 12px 12px 0 0;
      padding: 1.5rem;
      margin-bottom: -1px;
    }
    
    .form-card {
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
    }
    
    .form-label {
      font-weight: 500;
      color: #495057;
      margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
      border-radius: 8px;
      padding: 0.75rem 1rem;
      border: 1px solid #dee2e6;
      transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-generate {
      background-color: var(--success-color);
      border: none;
      padding: 0.75rem 2rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s;
      letter-spacing: 0.5px;
      color: #ffffff;
    }
    
    .btn-generate:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }
    
    .input-group-text {
      background-color: #f8f9fa;
      border-radius: 8px 0 0 8px;
    }
    
    .currency-input {
      border-radius: 0 8px 8px 0 !important;
    }
    
    @media (max-width: 768px) {
      .receipt-header {
        border-radius: 0;
      }
      
      .form-card {
        border-radius: 0;
        box-shadow: none;
      }
    }
  </style>
</head>
<body>

<!-- Admin Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark admin-navbar">
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

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <!-- Form Header -->
      <div class="receipt-header text-center">
        <h2><i class="bi bi-receipt-cutoff me-2"></i>Generate Payment Receipt</h2>
        <p class="mb-0 opacity-75">Fill in the details to create a professional receipt</p>
      </div>
      
      <!-- Receipt Form -->
      <div class="form-card">
        <div class="card-body p-4">
          <form action="receipt-pdf.php" method="POST">
            <!-- Client Information -->
            <div class="mb-4">
              <h5 class="mb-3 text-primary"><i class="bi bi-person-circle me-2"></i>Client Information</h5>
              <div class="row g-3">
                <div class="col-md-12">
                  <label for="client_name" class="form-label">Full Name</label>
                  <input type="text" id="client_name" name="client_name" class="form-control" placeholder="Enter client's full name" required>
                </div>
              </div>
            </div>
            
            <!-- Payment Details -->
            <div class="mb-4">
              <h5 class="mb-3 text-primary"><i class="bi bi-credit-card me-2"></i>Payment Details</h5>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="service_type" class="form-label">Service Type</label>
                  <input type="text" id="service_type" name="service_type" class="form-control" placeholder="e.g. Consultation, Repair" required>
                </div>
                
                <div class="col-md-6">
                  <label for="amount" class="form-label">Amount</label>
                  <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input type="number" id="amount" name="amount" class="form-control currency-input" placeholder="0.00" step="0.01" min="0" required>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <label for="payment_mode" class="form-label">Payment Method</label>
                  <select id="payment_mode" name="payment_mode" class="form-select" required>
                    <option value="" disabled selected>Select payment method</option>
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Net Banking">Net Banking</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                  </select>
                </div>
                
                <div class="col-md-6">
                  <label for="payment_date" class="form-label">Payment Date</label>
                  <input type="date" id="payment_date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
              </div>
            </div>
            
            <!-- Additional Information -->
            <div class="mb-4">
              <h5 class="mb-3 text-primary"><i class="bi bi-card-text me-2"></i>Additional Details</h5>
              <div class="mb-3">
                <label for="description" class="form-label">Service Description</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter details about the service provided"></textarea>
              </div>
              
              <div class="mb-3">
                <label for="terms" class="form-label">Terms & Conditions</label>
                <textarea id="terms" name="terms" class="form-control" rows="2" placeholder="Enter any terms or notes">Thank you for your business. All payments are non-refundable.</textarea>
              </div>
            </div>
            
            <!-- Form Actions -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
              <button type="reset" class="btn btn-outline-secondary me-md-2">
                <i class="bi bi-x-circle me-1"></i> Clear Form
              </button>
              <button type="submit" class="btn btn-generate">
                <i class="bi bi-file-earmark-pdf me-1"></i> Generate Receipt
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Auto-focus first field
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('client_name').focus();
    
    // Format amount field on blur
    const amountField = document.getElementById('amount');
    amountField.addEventListener('blur', function() {
      if (this.value && !isNaN(this.value)) {
        this.value = parseFloat(this.value).toFixed(2);
      }
    });
  });
</script>
</body>
</html>