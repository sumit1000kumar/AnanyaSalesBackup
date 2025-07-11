<?php
// $host = 'shareddb-y.hosting.stackcp.net';
// $user = 'bloodBank_New-313539ee82';
// $password = 'Latest#123'; 
// $database = 'bloodBank_New-313539ee82';

// $conn = new mysqli($host, $user, $password, $database);

// for local testing
$conn = new mysqli("localhost", "root", "", "ram_dada");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
