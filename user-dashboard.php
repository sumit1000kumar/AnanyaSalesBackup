<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
  header("Location: auth/login.php");
  exit;
}

require 'includes/db.php';

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
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h2>
    <a href="auth/logout.php" class="btn btn-outline-secondary">Logout</a>
  </div>

  <div class="mb-4">
    <a href="user/complaint-form.php" class="btn btn-primary">➕ Submit New Complaint</a>
  </div>

  <h4>Your Complaint History</h4>
  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive mt-3">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Complaint Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date Submitted</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $count++ ?></td>
              <td><?= htmlspecialchars($row['complaint_type']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td>
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
              <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info mt-3">You have not submitted any complaints yet.</div>
  <?php endif; ?>
</div>

</body>
</html>
