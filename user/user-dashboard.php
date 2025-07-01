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
  <!-- Required Meta Tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Portal for Complaint Management System">
  <meta name="author" content="Ananya Sales & Service">

  <!-- Page Title -->
  <title>Ananya Sales & Service | Complaint Form</title>

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

  <!-- Inline Styles -->
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
  <header class="dashboard-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <img src="../assets/images/logo/logo.jpg" alt="Logo" style="height:40px; vertical-align:middle; margin-right:10px; padding: 5px; border-radius: 10px; background-color: white;">
          <h1 class="h4 mb-0 d-inline align-middle">Ananya Sales and Service</h1>
        </div>
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
    <th>Equipment</th>
    <th>Status</th>
    <th>Date</th>
    <th>Actions</th>
  </tr>
</thead>

              <tbody>
                <?php $count = 1; while($row = $result->fetch_assoc()): 
                  $stmt = $conn->prepare("SELECT COUNT(*) as unread FROM conversations WHERE complaint_id = ? AND sender_id != ? AND is_read = FALSE");
                  $stmt->bind_param("ii", $row['id'], $_SESSION['user_id']);
                  $stmt->execute();
                  $unread = $stmt->get_result()->fetch_assoc()['unread'];
                ?>
                <tr>
                  <td data-label="#"><?= $count++ ?></td>
                  <td data-label="Type"><?= htmlspecialchars($row['complaint_type']) ?></td>
                  <td data-label="Equipment"><?= htmlspecialchars($row['equipment']) ?></td>

                  <td data-label="Status">
                    <?php
                      $status = strtolower($row['status']);
                      $badgeClass = match ($status) {
                        'resolved' => 'success',
                        'under progress' => 'warning',
                        'pending', '' => 'secondary',
                        default => 'info',
                      };
                    ?>
                    <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($row['status']) ?></span>
                  </td>
                  <td data-label="Date">
                    <?php
                      date_default_timezone_set("Asia/Kolkata");
                      echo date('d M Y, h:i A', strtotime($row['created_at']));
                    ?>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-info chat-btn <?= $unread > 0 ? 'has-unread' : '' ?>" onclick="openChat(<?= $row['id'] ?>, 'Admin')">
                      <i class="bi bi-chat-left-text"></i>
                    </button>
                  </td>
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

      <!-- Chat Modal -->
      <div id="chatModal" class="chat-modal">
        <div class="chat-dialog">
          <div class="chat-header">
            <h5 class="mb-0" id="chatTitle">Conversation</h5>
            <button class="close-btn" onclick="closeChat()">&times;</button>
          </div>
          <div class="chat-body" id="chatBody"></div>
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
    </div>
  </main>

  <footer class="text-center mt-5 py-3 bg-light">
    <?php include '../includes/footer.php'; ?>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
// Chat system variables
let currentComplaintId = null;
let chatPollingInterval = null;
let isChatOpen = false;
let lastMessageTime = null;

// Open chat modal
function openChat(complaintId, userName) {
    currentComplaintId = complaintId;
    document.getElementById('chatTitle').textContent = `Conversation with ${userName}`;
    document.getElementById('chatModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    isChatOpen = true;
    
    // Initial load with loading indicator
    loadMessages(true);
    
    // Start polling for new messages every 2 seconds
    chatPollingInterval = setInterval(() => loadMessages(), 2000);
}

// Close chat modal
function closeChat() {
    document.getElementById('chatModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    isChatOpen = false;
    
    // Clear polling interval
    if (chatPollingInterval) {
        clearInterval(chatPollingInterval);
        chatPollingInterval = null;
    }
    
    currentComplaintId = null;
    lastMessageTime = null;
}

// Load messages for current complaint
function loadMessages(initialLoad = false) {
    if (!currentComplaintId || !isChatOpen) return;
    
    const chatBody = document.getElementById('chatBody');
    
    // Only show loading indicator on initial load
    if (initialLoad) {
        chatBody.innerHTML = `
            <div class="text-center py-3">
                <i class="bi bi-arrow-repeat spinner"></i> Loading messages...
            </div>
        `;
    }

    fetch(`../admin/chat.php?complaint_id=${currentComplaintId}${lastMessageTime ? `&last_update=${lastMessageTime}` : ''}`, {
        credentials: 'include' // Include cookies for session
    })
    .then(response => {
        if (!response.ok) {
            // Try to get error message from response
            return response.json().then(err => {
                throw new Error(err.error || 'Network response was not ok');
            }).catch(() => {
                throw new Error(`Server returned status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(messages => {            
        if (messages && messages.error) {
            showError(messages.error);
            return;
        }
        
        if (!Array.isArray(messages)) {
            throw new Error('Invalid response format from server');
        }
        
        // Only update if new messages exist or it's initial load
        if (initialLoad || messages.length > 0) {
            // Update last message time if we have messages
            if (messages.length > 0) {
                lastMessageTime = messages[messages.length-1].created_at;
            }
            
            updateChatUI(messages);
        }
    })
    .catch(error => {
        console.error('Error loading messages:', error);
        showError(error.message);
    });
}

function updateChatUI(messages) {
    const chatBody = document.getElementById('chatBody');
    
    chatBody.innerHTML = '';
    
    if (messages.length === 0) {
        chatBody.innerHTML = `
            <div class="text-center text-muted py-3">
                No messages yet. Start the conversation!
            </div>
        `;
        return;
    }
    
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
}

function showError(message) {
    const chatBody = document.getElementById('chatBody');
    chatBody.innerHTML = `
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i> 
            ${message}
            <button onclick="loadMessages(true)" class="btn btn-sm btn-outline-danger ms-2">
                <i class="bi bi-arrow-repeat"></i> Retry
            </button>
        </div>
    `;
}

// Format time for display
function formatTime(timestamp) {
    try {
        const date = new Date(timestamp);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    } catch (e) {
        console.error('Error formatting time:', e);
        return '';
    }
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
        // Show sending indicator
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i>';
        sendBtn.disabled = true;
        
        fetch('../admin/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `complaint_id=${currentComplaintId}&message=${encodeURIComponent(message)}`,
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || 'Failed to send message');
                });
            }
            return response.json();
        })
        .then(messages => {
            if (messages && messages.error) {
                throw new Error(messages.error);
            }
            messageInput.value = '';
            // Force a refresh of messages after sending
            loadMessages(true);
        })
        .catch(error => {
            console.error('Error sending message:', error);
            showError(error.message);
        })
        .finally(() => {
            sendBtn.innerHTML = '<i class="bi bi-send"></i>';
            sendBtn.disabled = false;
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

// Add spinner animation style
const style = document.createElement('style');
style.textContent = `
    .spinner {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>
