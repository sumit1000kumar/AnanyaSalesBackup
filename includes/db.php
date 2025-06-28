<?php
$host = 'sdb-80.hosting.stackcp.net';
$user = 'bloodBank-35303835c8d6';
$password = 'db0y9tsdbw'; 
$database = 'bloodBank-35303835c8d6';

$conn = new mysqli($host, $user, $password, $database);

// for local testing
// $conn = new mysqli("localhost", "root", "", "ram_dada");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
