<?php
session_start();
include 'db.php';

// Cek apakah pengguna sudah login dan memiliki `user_id` di sesi
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Dapatkan `user_id` dari session
$user_id = $_SESSION['user_id'];

// Hitung notifikasi yang belum dibaca
$sql_unread = "SELECT COUNT(*) AS unread_count FROM notifikasi WHERE user_id = $user_id AND status = 0"; // pastikan kolom status ada
$result_unread = $conn->query($sql_unread);
$unread_count = 0; // Inisialisasi jumlah notifikasi yang belum dibaca

if ($result_unread && $row_unread = $result_unread->fetch_assoc()) {
    $unread_count = $row_unread['unread_count']; // Ambil jumlah notifikasi yang belum dibaca
}

// Dapatkan `user_id` dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data profil pengguna dari database
$sql_user = "SELECT email_user, ttl FROM user WHERE id_user = $user_id";
$result_user = $conn->query($sql_user);

// Cek apakah data pengguna ada
$user_data = $result_user->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        /* Sidebar styling */
        .sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            width: 60px;
            padding-top: 10px;
            border-right: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        /* Icon styling */
        .icon {
            position: relative;
            margin: 20px 0;
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }

        /* Notification dot styling */
        .notification-dot {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            font-size: 12px;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Profile container styling */
        .profile-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
            margin-left: 80px; /* Adjust for sidebar width */
        }

        .profile-info h3 {
            margin: 0;
            color: #333;
        }

        .profile-info p {
            color: #666;
            font-size: 0.9em;
            margin: 5px 0;
        }

        .btn-logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard_user.php" class="icon" title="Home">
            <img src="logo_nomin.png" alt="logo" style="width: 40px; height: 40px; margin-bottom: 10px;">
        </a>
        <a href="cari.php" class="icon" title="Explore">
            <i class="bi bi-compass"></i>
        </a>
        <a href="upload.php" class="icon" title="Add">
            <i class="bi bi-plus-square"></i>
        </a>
        <a href="notifikasi.php" class="icon notification-icon" title="Notifications" onclick="toggleNotificationPanel()">
            <i class="bi bi-bell"></i>
            <?php if ($unread_count > 0): ?> 
                <span class="notification-dot"><?php echo $unread_count; ?></span> 
            <?php endif; ?> 
        </a>
        <a href="profil.php" class="icon" title="More">
            <i class="bi bi-person-fill"></i>
        </a>
    </div>

    <!-- Profile Container -->
    <div class="profile-container">
        <?php if ($user_data): ?>
            <img src="JAEMIN.jpeg" alt="jaemin" style="width: 110px; height: 110px; margin-bottom: 10px;">
            <!-- Tampilkan Informasi Pengguna -->
            <div class="profile-info">
                <h3><?= htmlspecialchars($user_data['email_user']); ?></h3>
                <p>Tanggal Lahir: <?= htmlspecialchars($user_data['ttl']); ?></p>
            </div>
            <a href="logout.php" class="btn btn-danger btn-logout">Logout</a>
        <?php else: ?>
            <p>Profil tidak ditemukan.</p>
        <?php endif; ?>
    </div>

</body>
</html>