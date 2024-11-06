<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Hitung notifikasi yang belum dibaca untuk pengguna yang sedang login
$sql = "SELECT COUNT(*) AS unread_count FROM notifikasi WHERE user_id = $user_id AND status = 0";
$result = $conn->query($sql);
$unread_count = 0;

if ($result && $row = $result->fetch_assoc()) {
    $unread_count = $row['unread_count'];
}

// Menghapus notifikasi jika ID notifikasi tersedia di URL
if (isset($_GET['id'])) {
    $notification_id = intval($_GET['id']);

    // Memastikan notifikasi yang dihapus adalah milik pengguna yang sedang login
    $sql = "DELETE FROM notifikasi WHERE id = $notification_id AND user_id = $user_id";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman notifikasi dengan pesan sukses
        header("Location: notifikasi.php?status=notif_deleted");
    } else {
        // Redirect kembali ke halaman notifikasi dengan pesan gagal
        header("Location: notifikasi.php?status=notif_failed");
    }
    exit;
}

// Mengambil notifikasi untuk pengguna yang sedang login
$sql = "SELECT id, pesan, create_at FROM notifikasi WHERE user_id = $user_id ORDER BY create_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
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
            z-index: 1;
        }

        /* Main content area */
        .content {
            margin-left: 80px; /* Provides space for the sidebar */
            padding: 20px;
            width: 100%;
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

        h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .notifications-container {
            max-width: 600px;
            margin: auto;
        }

        .notification {
            background: white;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification p {
            margin: 0;
        }

        /* Styling for date and time to be gray and subtle */
        .notification small {
            color: #888; /* Light gray color */
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .notification img {
        max-width: 100px; /* Lebar maksimum gambar */
        max-height: 100px; /* Tinggi maksimum gambar */
        border-radius: 5px; /* Sudut gambar melengkung */
        margin-top: 5px; /* Jarak atas gambar */
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard_user.php" class="icon" title="Rumah">
            <img src="logo_nomin.png" alt="logo" style="width: 40px; height: 40px; margin-bottom: 10px;">
        </a>
        <a href="cari.php" class="icon" title="Cari">
            <i class="bi bi-compass"></i>
        </a>
        <a href="upload.php" class="icon" title="Tambahkan">
            <i class="bi bi-plus-square"></i>
        </a>
        <a href="notifikasi.php" class="icon notification-icon" title="Notifikasi">
            <i class="bi bi-bell"></i>
            <?php if ($unread_count > 0): ?> 
                <span class="notification-dot"><?php echo $unread_count; ?></span> 
            <?php endif; ?> 
        </a>
        <a href="profil.php" class="icon" title="Profil">
            <i class="bi bi-person-fill"></i>
        </a>
    </div>

    <div class="content">
        <h2>Notifikasi Anda</h2>
        <div class="notifications-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='notification'>
        <div>
            <p>" . htmlspecialchars($row['pesan']) . "</p>
            <small>" . htmlspecialchars($row['create_at']) . "</small>
            <br>";
            // Menampilkan foto jika ada
            if (!empty($row['foto'])) {
                echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Preview Foto' style='max-width: 100px; max-height: 100px; margin-top: 5px;'>";
            }
            echo "  </div>
            <a href='notifikasi.php?id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Hapus notifikasi ini?\")'>Hapus</a>
        </div>";
                }
            } else {
                echo "<div class='notification'>Tidak ada notifikasi.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>