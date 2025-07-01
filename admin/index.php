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

// Export to CSV
if (isset($_GET['export'])) {
    $export_result = $conn->query($query);
    
    if ($export_result && $export_result->num_rows > 0) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="complaints_export.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'Contact', 'Nature of Visit', 'Equipment', 'Status']); // CSV Header

        while ($row = $export_result->fetch_assoc()) {
            fputcsv($output, [
                $row['id'],
                $row['name'],
                $row['contact'],
                $row['complaint_type'],
                $row['equipment'],
                $row['status']
            ]);
        }

        fclose($output);
        exit;
    } else {
        $_SESSION['success_message'] = "No complaints found to export.";
        header("Location: index.php");
        exit;
    }
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required Meta Tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Portal for Complaint Management System">
  <meta name="author" content="Ananya Sales & Service">

  <!-- Page Title -->
  <title>Ananya Sales & Service | Complaints</title>

  <!-- Favicon -->
  <link rel="icon" href="../assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="../assets/images/favicon/site.webmanifest">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
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
    /* chat system */
    .chat-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      margin-left: 5px;
    }
    
    .chat-btn.has-unread {
      position: relative;
    }
    
    .chat-btn.has-unread::after {
      content: '';
      position: absolute;
      top: -3px;
      right: -3px;
      width: 12px;
      height: 12px;
      background-color: #dc3545;
      border-radius: 50%;
      border: 2px solid white;
    }
    
    .chat-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 1050;
    }
    
    .chat-dialog {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 90%;
      max-width: 600px;
      max-height: 80vh;
      background: white;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
      display: flex;
      flex-direction: column;
    }
    
    .chat-header {
      padding: 15px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: var(--primary-color);
      color: white;
      border-radius: 10px 10px 0 0;
    }
    
    .chat-body {
      flex: 1;
      overflow-y: auto;
      padding: 15px;
      background-color: #f9f9f9;
    }
    
    .chat-message {
      margin-bottom: 15px;
      padding: 10px 15px;
      border-radius: 8px;
      max-width: 80%;
      position: relative;
      word-wrap: break-word;
    }
    
    .admin-message {
      background-color: #e3f2fd;
      margin-left: auto;
      border-top-right-radius: 0;
    }
    
    .user-message {
      background-color: #f1f1f1;
      margin-right: auto;
      border-top-left-radius: 0;
    }
    
    .message-sender {
      font-weight: 600;
      font-size: 0.85rem;
      margin-bottom: 5px;
      color: var(--primary-color);
    }
    
    .message-time {
      font-size: 0.75rem;
      color: #666;
      text-align: right;
      margin-top: 5px;
    }
    
    .chat-footer {
      padding: 15px;
      border-top: 1px solid #eee;
      background-color: white;
      border-radius: 0 0 10px 10px;
    }
    
    .chat-input {
      border-radius: 20px;
      padding: 10px 15px;
      resize: none;
    }
    
    .send-btn {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-left: 10px;
    }
    
    .close-btn {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
    }
    
    /* Mobile specific styles */
    @media (max-width: 768px) {
      .chat-dialog {
        width: 95%;
        height: 85vh;
        max-height: none;
      }
      
      .chat-message {
        max-width: 90%;
      }
    }

   :root {
  --primary-color: #e30613;          /* Main red */
  --secondary-color: #a9030d;        /* Darker red */
  --accent-color: #ff5964;           /* For user avatars or highlights */
  --success-color: #198754;          /* Bootstrap green (unchanged) */
  --warning-color: #f59e0b;          /* Amber */
  --info-color: #0ea5e9;             /* Light blue */
  --danger-color: #ef4444;           /* Bright red */
  --light-bg: #f8f9fa;               /* Light background */
  --dark-text: #212529;
  --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
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


<!-- Main Content -->
<div class="container mb-5 mt-3">
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
        
        <div class="col-md-4 col-sm-6 search-box">

          <i class="bi bi-search"></i>
          <input type="text" name="search" class="form-control" 
                 placeholder="Search by name, contact or type..." 
                 value="<?= htmlspecialchars($search_term) ?>">
        </div>
        
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100 filter-btn" style="background-color: var(--primary-color); border-color: var(--primary-color);">
            <i class="bi bi-funnel me-1"></i> Filter
          </button>
        </div>
        
       <div class="col-md-1">
  <a href="index.php" class="btn btn-outline-secondary w-100" title="Reset">
    <i class="bi bi-arrow-counterclockwise"></i>
  </a>
</div>

        <div class="col-md-1">
  <button type="submit" name="export" class="btn btn-success w-100" title="Export to Excel">
    <i class="bi bi-file-earmark-excel"></i>
  </button>
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
    <th>Nature of Visit</th>
    <th>Equipment</th>
    <th>Status</th>
    <th>Attachment</th>
    <th>Actions</th>
  </tr>
</thead>

    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): 
          // Check for unread messages
          $stmt = $conn->prepare("
            SELECT COUNT(*) as unread 
            FROM conversations 
            WHERE complaint_id = ? 
            AND sender_id != ? 
            AND is_read = FALSE
          ");
          $stmt->bind_param("ii", $row['id'], $_SESSION['user_id']);
          $stmt->execute();
          $unread = $stmt->get_result()->fetch_assoc()['unread'];
        ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
<td><?= htmlspecialchars($row['contact'] ?? '') ?></td>
<td><?= htmlspecialchars($row['complaint_type'] ?? '') ?></td>
<td><?= htmlspecialchars($row['equipment'] ?? '') ?></td>



            <td>
              <span class="badge 
                <?= $row['status'] === 'Resolved' ? 'badge-resolved' : 
                   ($row['status'] === 'Under Progress' ? 'badge-progress' : 'badge-new') ?>">
                <?= $row['status'] ?>
              </span>
            </td>
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
              <div class="d-flex align-items-center">
                <form method="POST" class="d-flex gap-2 align-items-center me-2">
                  <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                  <select name="status" class="form-select form-select-sm status-select">
                    <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                    <option value="Under Progress" <?= $row['status'] === 'Under Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= $row['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                  </select>
                  <button type="submit" name="update_status" class="btn btn-sm btn-primary" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                    <i class="bi bi-check-lg"></i>
                    <span class="d-none d-md-inline">Update</span>
                  </button>
                </form>
                <button class="btn btn-sm btn-info chat-btn <?= $unread > 0 ? 'has-unread' : '' ?>" 
                        onclick="openChat(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')">
                  <i class="bi bi-chat-left-text"></i>
                </button>
              </div>
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
    $result->data_seek(0); 
    while ($row = $result->fetch_assoc()): 
      // Check for unread messages
      $stmt = $conn->prepare("
        SELECT COUNT(*) as unread 
        FROM conversations 
        WHERE complaint_id = ? 
        AND sender_id != ? 
        AND is_read = FALSE
      ");
      $stmt->bind_param("ii", $row['id'], $_SESSION['user_id']);
      $stmt->execute();
      $unread = $stmt->get_result()->fetch_assoc()['unread'];
    ?>
      <div class="complaint-card">
        <div class="complaint-card-header">
          <span class="complaint-id">#<?= $row['id'] ?></span>
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
  <span class="detail-label">Nature:</span>
  <span class="detail-value"><?= htmlspecialchars($row['complaint_type']) ?></span>
</div>

<div class="detail-row">
  <span class="detail-label">Equipment:</span>
  <span class="detail-value"><?= htmlspecialchars($row['equipment']) ?></span>
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
          <button class="btn btn-sm btn-info chat-btn <?= $unread > 0 ? 'has-unread' : '' ?>" 
                  onclick="openChat(<?= $row['id'] ?>, '<?= htmlspecialchars($row['name']) ?>')">
            <i class="bi bi-chat-left-text"></i>
          </button>
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

<!-- Chat Modal -->
<div id="chatModal" class="chat-modal">
  <div class="chat-dialog">
    <div class="chat-header">
      <h5 class="mb-0" id="chatTitle">Conversation</h5>
      <button class="close-btn" onclick="closeChat()">&times;</button>
    </div>
    <div class="chat-body" id="chatBody">
      <!-- Messages will be loaded here -->
    </div>
    <div class="chat-footer">
      <div class="d-flex align-items-center">
        <textarea id="messageInput" class="form-control chat-input" placeholder="Type your message..." rows="1"></textarea>
        <button id="sendBtn" class="btn btn-primary send-btn">
          <i class="bi bi-send"></i>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Footer -->
 <div class="footer" style="margin-top: 50px; padding: 20px; background-color: #f8f9fa; text-align: center;">
   <?php include '../includes/footer.php'; ?>
 </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script>  
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
</script> -->
<script>
// Chat system variables
let currentComplaintId = null;
let chatRefreshInterval = null;

// Open chat modal
function openChat(complaintId, userName) {
    currentComplaintId = complaintId;
    document.getElementById('chatTitle').textContent = `Conversation with ${userName}`;
    document.getElementById('chatModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    loadMessages();
    
    // Start refreshing messages every 3 seconds
    chatRefreshInterval = setInterval(loadMessages, 3000);
}

// Close chat modal
function closeChat() {
    document.getElementById('chatModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    clearInterval(chatRefreshInterval);
    currentComplaintId = null;
}

// Load messages for current complaint
function loadMessages() {
    if (!currentComplaintId) return;
    
    fetch(`chat.php?complaint_id=${currentComplaintId}`)
        .then(response => response.json())
        .then(messages => {
            const chatBody = document.getElementById('chatBody');
            chatBody.innerHTML = '';
            
            messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${msg.role === 'admin' ? 'admin-message' : 'user-message'}`;
                
                messageDiv.innerHTML = `
                    <div class="message-sender">${msg.name} (${msg.role})</div>
                    <div>${msg.message}</div>
                    <div class="message-time">${formatTime(msg.created_at)}</div>
                `;
                
                chatBody.appendChild(messageDiv);
            });
            
            // Scroll to bottom
            chatBody.scrollTop = chatBody.scrollHeight;
        });
}

// Format time for display
function formatTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Send message
document.getElementById('sendBtn').addEventListener('click', sendMessage);
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (message && currentComplaintId) {
        fetch('chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `complaint_id=${currentComplaintId}&message=${encodeURIComponent(message)}`
        })
        .then(response => response.json())
        .then(messages => {
            messageInput.value = '';
            loadMessages();
        });
    }
}

// Auto-resize textarea
document.getElementById('messageInput').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('chatModal')) {
        closeChat();
    }
});

// [Previous JavaScript code remains the same]
</script>
</body>
</html>