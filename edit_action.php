<?php
include 'db.php';
session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = intval($_GET['id']); // Ensure ID is an integer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $target_dir = "uploads/";

    // Initialize SQL query without updating image_path
    $sql = "UPDATE pins SET title = '$title', deskripsi = '$description', kategori = '$kategori'";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_file = $target_dir . basename($image);

        // Try to upload the new image file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql .= ", image_path = '$target_file'"; // Add image_path update to query if image upload succeeds
        } else {
            die("Gagal mengunggah gambar.");
        }
    }

    $sql .= " WHERE id_pins = $id";

    // Execute the query and check the result
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard_user.php");
        exit; // Stop script execution after redirect
    } else {
        echo "Gagal memperbarui gambar! " . mysqli_error($conn); // Display SQL error
    }
} else {
    die("Method tidak diizinkan.");
}