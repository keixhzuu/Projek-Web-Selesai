<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses hapus foto
if (isset($_POST['delete_photo_id']) && isset($_POST['pesan_admin'])) {
    $photo_id = $_POST['delete_photo_id'];
    $pesan_admin = $_POST['pesan_admin'];

    // Ambil ID pengguna dari foto yang akan dihapus
    $sql = "SELECT id_user FROM pins WHERE id_pins = $photo_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id_user'];

        // Hapus foto dari database
        $sql = "DELETE FROM pins WHERE id_pins = $photo_id";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Foto berhasil dihapus.');</script>";

            // Tambah pemberitahuan untuk pengguna dengan pesan khusus
            $pesan = $_POST['pesan_admin'];
            $sql_notify = "INSERT INTO notifikasi (user_id, pesan) VALUES ($user_id, '$pesan')";
            $conn->query($sql_notify);
        } else {
            echo "Error deleting photo: " . $conn->error;
        }
    }
}

// Menampilkan daftar foto
$sql = "SELECT id_pins, image_path, id_user, deskripsi FROM pins";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Konten</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        form{
            margin-top:30px;
            margin-bottom: 30px;
        }
        th, td {
            padding: 15px;
            text-align: center;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 600;
        }
        td img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            object-fit: cover;
        }
        tr:hover td {
            background-color: #f9f9f9;
        }
        .action-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .action-buttons label {
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .action-buttons input[type="text"] {
            width: 200px; /* Perbesar lebar input */
            padding: 8px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .delete-button, .cancel-button {
            background-color: #5bc0de;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px; /* Perbesar padding tombol */
            cursor: pointer;
            font-size: 16px; /* Perbesar ukuran teks tombol */
            font-weight: bold;
        }
        .delete-button {
            background-color: #d9534f;
        }
        .delete-button:hover {
            background-color: #c9302c;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #d9534f;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .back-button:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <h2>Daftar Foto Unggahan Pengguna</h2>
    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Deskripsi</th>
                <th>Pengguna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td><img src='" . $row['image_path'] . "' alt='Foto' /></td>
                            <td>" . htmlspecialchars($row['deskripsi']) . "</td>
                            <td>" . htmlspecialchars($row['id_user']) . "</td>
                            <td class='action-buttons'>
                                <form action='moderate_konten.php' method='POST' style='display:inline;'>
                                    <input type='hidden' name='delete_photo_id' value='" . $row['id_pins'] . "'>
                                    <label>Pesan: <input type='text' name='pesan_admin' required></label>
                                    <button type='submit' class='delete-button' onclick='return confirm(\"Hapus foto ini?\")'>Hapus</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada foto.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
        <a href="dashboard_admin.php" class="back-button">Kembali</a>
    </div>
</body>
</html>
