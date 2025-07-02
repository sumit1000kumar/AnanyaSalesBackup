<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../auth/login.php");
  exit;
}
include '../includes/db.php';

$calendarEvents = [];
$today = date('Y-m-d');
$currentMonth = date('m');
$currentYear = date('Y');
$dueThisMonth = 0;

$calendarQuery = $conn->query("SELECT hospital_name, address, next_due_date FROM hospital_reminders");
while ($row = $calendarQuery->fetch_assoc()) {
  $dueDate = $row['next_due_date'];
  $daysLeft = (strtotime($dueDate) - strtotime($today)) / (60 * 60 * 24);

  if (date('m', strtotime($dueDate)) == $currentMonth && date('Y', strtotime($dueDate)) == $currentYear) {
    $dueThisMonth++;
  }

  if ($daysLeft <= 3) {
    $color = '#ff4d4f'; // Urgent
  } elseif ($daysLeft <= 10) {
    $color = '#ffc107'; // Upcoming
  } else {
    $color = '#28a745'; // Safe
  }

  $calendarEvents[] = [
    'title' => $row['hospital_name'],
    'start' => $dueDate,
    'description' => $row['address'],
    'backgroundColor' => $color,
    'borderColor' => $color,
    'textColor' => '#fff'
  ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Monthly Reminders | Ananya Sales & Service</title>

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet"/>

  <!-- FullCalendar -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

  <style>
    :root {
      --primary-color: #e30613;
      --secondary-color: #a9030d;
      --light-bg: #f8f9fa;
    }

    body {
      background-color: var(--light-bg);
    }

    .page-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-radius: 0 0 20px 20px;
      padding: 2rem 0;
      margin-bottom: 2rem;
      text-align: center;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border: none;
    }

    .btn-primary:hover {
      background-color: var(--secondary-color);
    }

    .form-section {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }

    /* Calendar Styling */
    #calendar {
      background-color: white;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .fc .fc-toolbar-title {
      color: var(--primary-color);
      font-size: 1.25rem;
      font-weight: bold;
    }

    .fc .fc-button {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .fc .fc-button:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }

    .fc .fc-daygrid-event {
      font-size: 0.8rem;
      padding: 2px 4px;
      border-radius: 4px;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
      .form-section, .card {
        padding: 1rem;
      }

      .page-header h2 {
        font-size: 1.5rem;
      }

      .fc .fc-toolbar {
        flex-direction: column;
        gap: 10px;
      }

      .table {
        font-size: 0.85rem;
      }

      .form-label {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
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

  <div class="page-header">
    <h2><i class="bi bi-calendar-event me-2"></i>Monthly Hospital Service Reminders</h2>
    <p class="lead">Track and manage upcoming service due dates</p>
  </div>

  <div class="container">

    <!-- Alert Section -->
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= ($_GET['success'] === 'deleted') ? 'Reminder deleted successfully!' : 'Reminder added successfully!' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?> <!-- ✅ This was missing -->

<?php if (isset($_GET['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php
      switch ($_GET['error']) {
        case 'missing':
          echo '❌ Please fill in all the fields.';
          break;
        case 'db':
          echo '❌ Database error occurred.';
          break;
        default:
          echo '❌ Unexpected error.';
      }
    ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>



    <!-- Summary -->
    <div class="alert alert-info text-center fw-semibold">
      📅 <strong><?= $dueThisMonth ?></strong> hospital(s) have due dates in <strong><?= date('F Y') ?></strong>
    </div>

    <!-- Form -->
    <div class="form-section">
      <h5 class="mb-3 fw-semibold">Add New Reminder</h5>
      <form method="POST" action="save-hospital-reminder.php">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Hospital Name</label>
            <input type="text" name="hospital_name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Last Service Date</label>
            <input type="date" name="last_service_date" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Next Due Date</label>
            <input type="date" name="next_due_date" class="form-control" required>
          </div>
        </div>
        <div class="mt-3 text-end">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add Reminder
          </button>
        </div>
      </form>
    </div>

    <!-- Calendar -->
    <div class="card mb-4">
      <div class="card-header bg-white fw-bold">Hospital Due Date Calendar</div>
      <div class="card-body">
        <div id="calendar"></div>
      </div>
    </div>

    <!-- Table -->
    <div class="card mb-5">
      <div class="card-header bg-white fw-bold">Upcoming Reminders</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Hospital Name</th>
                <th>Address</th>
                <th>Last Service</th>
                <th>Next Due</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $res = $conn->query("SELECT * FROM hospital_reminders ORDER BY next_due_date ASC");
              if ($res && $res->num_rows > 0):
                $i = 1;
                while ($row = $res->fetch_assoc()):
              ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                  <td><?= htmlspecialchars($row['address']) ?></td>
                  <td><?= date('d M Y', strtotime($row['last_service_date'])) ?></td>
                  <td><?= date('d M Y', strtotime($row['next_due_date'])) ?></td>
                  <td>
                    <form method="POST" action="delete-hospital-reminder.php" onsubmit="return confirm('Are you sure you want to delete this reminder?');">
                      <input type="hidden" name="reminder_id" value="<?= $row['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; else: ?>
                <tr><td colspan="6" class="text-center py-3 text-muted">No reminders found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const calendarEl = document.getElementById('calendar');
      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: ''
        },
        events: <?= json_encode($calendarEvents) ?>,
        eventDidMount: function(info) {
          new bootstrap.Tooltip(info.el, {
            title: info.event.extendedProps.description,
            placement: 'top',
            trigger: 'hover',
            container: 'body'
          });
        }
      });
      calendar.render();
    });
  </script>
</body>
</html>
