<?php
include 'db.php';
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$id_pins = $_GET['id'];

// ambil path
$sql = "SELECT image_path FROM pins WHERE id_pins = $id_pins";
$hasil = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($hasil);
 
// hapus gambar di folder uploads
if ($data && file_exists($data['image_path'])) {
    unlink($data['image_path']);
}

// Query untuk menghapus data berdasarkan ID gambar (id_pins) dan user_id yang login
$sql = "DELETE FROM pins WHERE id_pins = $id_pins AND id_user = $user_id";
$hasil = mysqli_query($conn, $sql);

// Mengecek apakah query berhasil dijalankan
if ($hasil) {
    header("Location: dashboard_user.php?status=sukses"); // Redirect setelah berhasil hapus
} else {
    echo "Gagal menghapus data.";
}
?>
