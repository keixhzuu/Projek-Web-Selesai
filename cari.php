<?php 
include 'db.php'; 
session_start();
include 'db.php';

// Cek apakah pengguna sudah login dan memiliki `user_id` di sesi
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Dapatkan `user_id` dari sesi
$user_id = $_SESSION['user_id'];

$sql = "SELECT COUNT(*) AS unread_count FROM notifikasi WHERE user_id = $user_id AND status = 0";
$result = $conn->query($sql);
$unread_count = 0;

if ($result && $row = $result->fetch_assoc()) {
    $unread_count = $row['unread_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Photos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
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

        .search-container {
            text-align: center;
            margin: 20px;
            padding-left: 80px; /* Offset for sidebar */
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .search-container button {
            padding: 10px;
            background-color: #e60023;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #c2001f;
        }

        .photo-results {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: start;
            margin-left: 80px;
            padding: 20px; /* Added padding for spacing */
        }

        .photo-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: visible; /* Allow dropdowns to extend beyond the card */
            text-align: center;
            width: 180px;
            position: relative;
            padding-bottom: 10px; /* Add padding to avoid close edges */
        }

        .photo-item:hover {
            transform: scale(1.05);
        }

        .photo-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .photo-item h3 {
            font-size: 1em;
            color: #333;
            font-weight: bold;
            margin: 0;
        }

        /* Dropdown menu for details */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            padding: 8px;
            border-radius: 8px;
            z-index: 10; /* Make sure it appears on top */
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
        }

        /* Display the dropdown on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content p {
            margin: 0;
            color: #333;
            font-size: 0.9em;
        }

        .three-dots-icon {
            font-size: 16px;
            color: #666;
            cursor: pointer;
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

    <div class="search-container">
        <form method="GET" action="">
            <input type="text" name="keyword" placeholder="Cari Foto" required>
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="photo-results">
        <?php
        require 'db.php';
        
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            
            // Escape keyword untuk menghindari SQL injection
            $keyword = mysqli_real_escape_string($conn, $keyword);
            
            // Query untuk mencari berdasarkan title atau description
            $sql = "SELECT * FROM pins WHERE title LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' OR kategori LIKE '%$keyword%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($photo = $result->fetch_assoc()) {
                    echo "<div class='photo-item'>";
                    echo "<img src='" . htmlspecialchars($photo['image_path']) . "' alt='" . htmlspecialchars($photo['title']) . "'>";
                    echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 10px;'>";
                    echo "<h3>" . htmlspecialchars($photo['title']) . "</h3>";
                    
                    // Three-dot icon with dropdown for details
                    echo "<div class='dropdown'>";
                    echo "<span class='three-dots-icon'>&#x22EE;</span>"; // Three-dot icon
                    echo "<div class='dropdown-content'>";
                    echo "<p><strong>Kategori:</strong> " . htmlspecialchars($photo['kategori']) . "</p>";
                    echo "<p><strong>Deskripsi:</strong> " . htmlspecialchars($photo['deskripsi']) . "</p>";
                    echo "</div>"; // End dropdown-content
                    echo "</div>"; // End dropdown

                    echo "</div>"; // End title and icon container
                    echo "</div>"; // End photo-item
                }
            } else {
                echo "<p>No results found for '$keyword'</p>";
            }
        }
        ?>
    </div>
</body>
</html>