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

// Tampilkan pesan error jika ada status 'gagal' di URL
if (isset($_GET['status']) && $_GET['status'] === 'gagal') {
    echo 'Gagal Upload';
}

// Menampilkan gambar milik pengguna yang login dari tabel 'pins'
$sql_pins = "SELECT * FROM pins WHERE id_user = $user_id";
$result_pins = $conn->query($sql_pins);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nominterest Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f4f4f4;
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

        /* Notification dot */
        .notification-dot {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            padding: 2px 5px;
        }

        /* Content styling */
        .content {
            margin-left: 80px;
            padding: 20px;
            width: calc(100% - 80px);
        }

        /* Gallery styling */
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* Reduced gap for a more compact layout */
            justify-content: start;
        }

        .gallery-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            width: 180px; /* Adjusted width */
            margin-bottom: 10px; /* Added margin for spacing */
            cursor: pointer;
        }

        .gallery-item img {
            width: 100%;
            height: 200px; /* Fixed height for uniformity */
            object-fit: cover; /* Ensure image doesn't stretch */
        }

        .gallery-item h2 {
            font-weight: bold;
            color: #333;
            font-size: 1.2em; /* Ukuran judul */
        }

        .gallery-item h3 {
            color: #888; /* Warna abu-abu untuk kategori */
            font-size: 0.8em; /* Ukuran kecil untuk kategori */
        }

        .gallery-item p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 5px 15px; /* Smaller padding for compact buttons */
            font-size: 0.8em; /* Smaller font size */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 3px; /* Smaller margins for compact layout */
            cursor: pointer;
        }

        .btn-edit {
            background-color: #f44336; /* Red color */
        }

        .btn-delete {
            background-color: #f44336; /* Red color */
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
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
        <a href="notifikasi.php" class="icon notification-icon" title="Notifikasi" onclick="toggleNotificationPanel()">
            <i class="bi bi-bell"></i>
            <?php if ($unread_count > 0): ?> 
                <span class="notification-dot"><?php echo $unread_count; ?></span> 
            <?php endif; ?> 
        </a>
        <a href="profil.php" class="icon" title="Akun">
            <i class="bi bi-person-fill"></i>
        </a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h1 style="font-weight:bold;">Galeri Gambar</h1><br>
        <div class="gallery">
            <?php if ($result_pins && $result_pins->num_rows > 0): ?>
                <?php while ($row = $result_pins->fetch_assoc()): ?>
                    <div class="gallery-item" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="showImageModal('<?= htmlspecialchars($row['image_path']); ?>', '<?= htmlspecialchars($row['title']); ?>')">
                        <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="Pin Image">
                        <h2><?= htmlspecialchars($row['title']); ?></h2>
                        <h3><?= htmlspecialchars($row['kategori']); ?></h3>
                        <p><?= htmlspecialchars($row['deskripsi']); ?></p>
                        <a href="edit.php?id=<?= urlencode($row['id_pins']); ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?= urlencode($row['id_pins']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');" class="btn btn-delete">Hapus</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada gambar.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for Full Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel" style="font-weight: bold;">Gambar Penuh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Full Image" style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Script to Load Image in Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showImageModal(imagePath, title) {
            document.getElementById('modalImage').src = imagePath;
            document.getElementById('imageModalLabel').textContent = title;
        }
    </script>
</body>
</html>
