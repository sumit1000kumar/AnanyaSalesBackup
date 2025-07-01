<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}

include '../includes/db.php';

// Add Customer
if (isset($_POST['add_customer'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $designation = mysqli_real_escape_string($conn, $_POST['designation']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $contact = mysqli_real_escape_string($conn, $_POST['contact']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);

  // Check for duplicate mobile number
  $check_query = "SELECT id FROM customers WHERE contact = '$contact'";
  $check_result = mysqli_query($conn, $check_query);

  if (mysqli_num_rows($check_result) > 0) {
    $error = "A customer with this mobile number already exists.";
  } else {
    $insert_query = "INSERT INTO customers (name, designation, address, contact, email) 
                     VALUES ('$name', '$designation', '$address', '$contact', '$email')";
    if (mysqli_query($conn, $insert_query)) {
      $success = "Customer added successfully!";
    } else {
      $error = "Database error: " . mysqli_error($conn);
    }
  }
}

// Delete Customer
if (isset($_GET['delete_id'])) {
  $delete_id = intval($_GET['delete_id']);
  $del_query = "DELETE FROM customers WHERE id = $delete_id";
  if (mysqli_query($conn, $del_query)) {
    $success = "Customer deleted successfully!";
  } else {
    $error = "Failed to delete customer.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Management | Ananya Sales and Service</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    :root {
      --primary-color: #e30613;
      --accent-color: #ff5964;
      --light-bg: #f8f9fa;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: var(--primary-color);
    }

    .user-profile {
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
      margin-left: 10px;
    }

    .main-footer {
      background-color: #000;
      color: white;
      border-top: 1px solid #dee2e6;
    }

    .card {
      box-shadow: var(--card-shadow);
      border-radius: 12px;
    }

    .btn-delete {
      color: #dc3545;
      border: none;
      background: none;
    }

    .btn-delete:hover {
      color: #a9030d;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
        <i class="bi bi-shield-lock me-2"></i>
        <span>Ananya Sales and Service</span>
      </a>
      <div class="d-flex align-items-center">
        <span class="text-white me-2 d-none d-sm-inline">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <div class="user-profile"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
        <a href="../auth/logout.php" class="logout-btn">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container my-5">
    <h3 class="mb-4">Manage Customers</h3>

    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Add Customer Form -->
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">Add New Customer</div>
      <div class="card-body">
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Customer Name</label>
              <input type="text" name="name" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Designation</label>
              <input type="text" name="designation" class="form-control" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" />
            </div>
            <div class="col-12">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control" rows="2" required></textarea>
            </div>
          </div>
          <div class="mt-4">
            <button type="submit" name="add_customer" class="btn btn-success">
              <i class="bi bi-plus-circle me-1"></i> Add Customer
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Customer List Table -->
    <div class="card">
      <div class="card-header bg-dark text-white">Customer List</div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Designation</th>
              <th>Contact</th>
              <th>Email</th>
              <th>Address</th>
              <th>Added On</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC");
            $sn = 1;
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$sn}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['designation']}</td>
                      <td>{$row['contact']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['address']}</td>
                      <td>{$row['created_at']}</td>
                      <td>
                        <a href='?delete_id={$row['id']}' onclick='return confirm(\"Delete this customer?\")' class='btn-delete'>
                          <i class='bi bi-trash'></i>
                        </a>
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
