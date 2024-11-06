<?php
session_start();
require 'db.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses hapus pengguna
if (isset($_GET['delete_user_id'])) {
    $user_id = $_GET['delete_user_id'];
    $sql = "DELETE FROM user WHERE id_user = $user_id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pengguna berhasil dihapus.');</script>";
    } else {
        echo "<script>alert('Gagal menghapus pengguna: " . $conn->error . "');</script>";
    }
}

// Menampilkan daftar pengguna
$sql = "SELECT id_user, email_user, password_user, ttl FROM user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pribadi Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <style>
        /* CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .table-container {
            width: 90%;
            max-width: 800px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-title {
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #e60023;
            color: white;
            font-weight: bold;
        }

        td {
            color: #555;
        }

        .action-link {
            text-decoration: none;
            color: #e60023;
            font-weight: bold;
        }

        .action-link:hover {
            color: #d00020;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e60023;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .back-button:hover {
            background-color: #d00020;
        }
    </style>
</head>
<body>

    <div class="table-container">
        <h2 class="table-title">Data Pribadi Pengguna</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Tanggal Lahir (TTL)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id_user']) . "</td>
                                <td>" . htmlspecialchars($row['email_user']) . "</td>
                                <td>" . htmlspecialchars($row['password_user']) . "</td>
                                <td>" . htmlspecialchars($row['ttl']) . "</td>
                                <td><a href='manage_user.php?delete_user_id=" . urlencode($row['id_user']) . "' class='action-link' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pengguna ini?\")'><i class='bi bi-trash'></i> Hapus</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada pengguna.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="dashboard_admin.php" class="back-button">Kembali ke Dashboard Admin</a>
    </div>

</body>
</html>