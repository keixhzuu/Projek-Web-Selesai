<?php
session_start();
require 'db.php';

// Cek apakah pengguna sudah otorisasi sebagai admin
if (!isset($_SESSION['authorized_admin']) || $_SESSION['authorized_admin'] !== true) {
    header("Location: auth_admin.php");
    exit;
}

// Jika pengguna sudah otorisasi, proses pembuatan akun admin dapat dilanjutkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // Simpan password tanpa hash

    // Simpan data admin ke tabel admin
    $sql = "INSERT INTO admin (email_admin, password_admin) VALUES ('$email', '$password')";

    if ($conn->query($sql)) {
        echo "Akun admin sudah dibuat.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
</head>
<body>
    <h2>Register Admin</h2>
    <form method="POST" action="">
        <input type="text" name="email" placeholder="E-mail" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
    <a href="index.php">Kembali</a>
</body>
</html>
