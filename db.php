<?php
$host = 'localhost';
$db = 'nominterest';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
$conn->connect_error ? die("Koneksi gagal: " . $conn->connect_error) : '';
?>
