<?php include 'db.php'; 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambahkan Pin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Basic styling for the body */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            padding: 20px;
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

        /* Centering the sidebar icons vertically */
        .sidebar .icon:last-child {
            margin-bottom: auto;
        }
        
        /* Main container styling */
        .upload-container {
            width: 100%;
            max-width: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            box-sizing: border-box;
        }
        
        /* Form heading */
        .upload-form h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        /* Form styling */
        .upload-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Form group styling */
        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            color: #333;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        /* Input focus and hover styling */
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #0073e6;
            outline: none;
        }

        /* Styling for the upload box */
        .upload-box {
            max-width: 100%;
            border: 2px dashed #ddd;
            border-radius: 8px;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #666;
            cursor: pointer;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            box-sizing: border-box;
        }

        .upload-box:hover {
            border-color: #bbb;
            background-color: #f3f3f3;
        }

        /* Hidden file input */
        #image {
            display: none;
        }

        /* Image preview styling */
        .preview-image {
            display: none;
            max-width: 100%;
            border-radius: 8px;
            margin-top: 15px;
        }

        /* Button styling */
        .submit-btn {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background-color: #0073e6;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }

        .submit-btn:hover {
            background-color: #005bb5;
        }

        /* File upload instructions */
        .description {
            font-size: 12px;
            color: #777;
            margin-top: 8px;
            text-align: center;
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
            <a href="profil.php" class="icon" title="Profil">
                <i class="bi bi-person-fill"></i>
            </a>
        </div>

    <!-- Main container -->
    <div class="upload-container">
        <form class="upload-form" action="upload_action.php" method="post" enctype="multipart/form-data">
            <h1>Buat Pin</h1>

            <!-- Title input -->
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" id="title" name="title" placeholder="Tambahkan judul" required>
            </div>

            <!-- Description input -->
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" placeholder="Tambahkan deskripsi " rows="4"></textarea>
            </div>

            <!-- Category dropdown -->
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori">
                    <option value="orang">Manusia</option>
                    <option value="makan dan minum">Makanan dan Minuman</option>
                    <option value="imajinasi">Imajinasi</option>
                    <option value="hewan">Hewan</option>
                    <option value="tanaman">Tanaman</option>
                    <option value="pacar">Pacar</option>
                    <option value="style">Style</option>
                    <option value="selebriti">Selebriti</option>
                    <option value="seni">Seni</option>
                    <option value="hiburan">Hiburan</option>
                    <!-- More options as needed -->
                </select>
            </div>

            <!-- Drag-and-drop upload area using label for direct connection -->
            <label class="upload-box" for="image">
                Pilih file atau seret dan jatuhkan di sini
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" required>
                <img id="preview" class="preview-image" alt="Preview Image">
                <p class="description">Sebaiknya gunakan file .jpg berkualitas tinggi berukuran kurang dari 20 MB atau file .mp4 kurang dari 200 MB.</p>
            </label>

            <!-- Submit button -->
            <button type="submit" class="btn btn-danger">Simpan Pin</button>
        </form>
    </div>

    <script>
        // Image preview function
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show image preview
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>