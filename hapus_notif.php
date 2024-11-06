<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Cek apakah `id` notifikasi tersedia di URL
if (isset($_GET['id'])) {
    $notification_id = intval($_GET['id']);

    // Pastikan notifikasi yang dihapus adalah milik pengguna yang sedang login
    $user_id = $_SESSION['user_id'];
    $sql = "DELETE FROM notifikasi WHERE id = $notification_id AND user_id = $user_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman dashboard dengan pesan sukses
        header("Location: dashboard_user.php?status=notif_deleted");
    } else {
        // Redirect kembali ke halaman dashboard dengan pesan gagal
        header("Location: dashboard_user.php?status=notif_failed");
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>