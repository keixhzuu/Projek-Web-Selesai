<?php
include 'db.php'; // Menghubungkan ke database
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $kategori = $_POST['kategori'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Periksa apakah pengguna sudah login dan memiliki ID pengguna di session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Ambil ID pengguna dari session

        // Upload file ke folder 'uploads'
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO pins (title, deskripsi, kategori, image_path, id_user) VALUES ('$title', '$description', '$kategori', '$target_file', '$user_id')";
            $result = $conn->query($sql); // Pastikan koneksi menggunakan variabel `$conn`

            if ($result) {
                header("Location: dashboard_user.php");
                exit;
            } else {
                header("Location: dashboard_user.php?status=gagal");
                exit;
            }

        } else {
            echo "Gagal mengunggah gambar.";
        }
    } else {
        echo "Pengguna tidak ditemukan. Pastikan Anda sudah login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
</head>
<body>
    <h1>Unggah Gambar</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Judul:</label>
        <input type="text" name="title" required><br><br>
        
        <label>Deskripsi:</label>
        <textarea name="description"></textarea><br><br>
        
        <label>Kategori:</label>
        <input type="text" name="kategori" required><br><br>
        
        <label>Gambar:</label>
        <input type="file" name="image" required><br><br>
        
        <input type="submit" value="Unggah">
    </form>
    <br>
    <button onclick="window.location.href='index.php'">Kembali</button>
</body>
</html>