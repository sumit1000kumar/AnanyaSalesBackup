<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';

echo "<h3>Database Connection Test</h3>";
if ($conn->ping()) {
    echo "✓ Database connection is working<br>";
} else {
    echo "✗ Database connection failed<br>";
    exit;
}

echo "<h3>Receipts Table Check</h3>";

// Check if receipts table exists
$result = $conn->query("SHOW TABLES LIKE 'receipts'");
if ($result->num_rows > 0) {
    echo "✓ Receipts table exists<br>";
} else {
    echo "✗ Receipts table does not exist<br>";
    exit;
}

// Count receipts
$result = $conn->query("SELECT COUNT(*) as count FROM receipts");
$row = $result->fetch_assoc();
echo "Total receipts in database: " . $row['count'] . "<br>";

// Show recent receipts
echo "<h3>Recent Receipts</h3>";
$result = $conn->query("SELECT * FROM receipts ORDER BY created_at DESC LIMIT 5");
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Client Name</th><th>PDF Path</th><th>Created At</th><th>File Exists</th></tr>";
    while($row = $result->fetch_assoc()) {
        $filePath = __DIR__ . '/' . $row['pdf_path'];
        $fileExists = file_exists($filePath) ? '✓' : '✗';
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['pdf_path']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td>" . $fileExists . " (" . $filePath . ")</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No receipts found in database.<br>";
}

echo "<h3>Directory Structure Check</h3>";

// Check if receipts directory exists
$receiptsDir = __DIR__ . '/receipts';
if (is_dir($receiptsDir)) {
    echo "✓ Receipts directory exists: " . $receiptsDir . "<br>";
    $files = scandir($receiptsDir);
    echo "Files in receipts directory: " . count($files) - 2 . "<br>"; // -2 for . and ..
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- " . $file . "<br>";
        }
    }
} else {
    echo "✗ Receipts directory does not exist: " . $receiptsDir . "<br>";
}

// Check report_numbers table
echo "<h3>Report Numbers Table Check</h3>";
$result = $conn->query("SELECT * FROM report_numbers ORDER BY id DESC LIMIT 5");
if ($result->num_rows > 0) {
    echo "Report numbers in database:<br>";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . ", Report No: " . $row['report_no'] . "<br>";
    }
} else {
    echo "No report numbers found.<br>";
}
?>
