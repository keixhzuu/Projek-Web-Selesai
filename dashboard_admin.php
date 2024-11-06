<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Dapatkan `user_id` dari session
$user_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .link-list {
            list-style-type: none;
            padding: 0;
        }
        .link-list li {
            margin: 20px 0;
            text-align: center;
        }
        .link-list a {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .link-list a:hover {
            background-color: #cc0000; /* Warna merah lebih gelap untuk efek hover */
        }
        .logout {
            display: block;
            text-align: center;
            margin-top: 30px;
            font-weight: bold;
            color: #FF5733;
            text-decoration: none;
        }
        .logout:hover {
            color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="font-weight:bold;">Selamat datang, Admin!</h1>
        <p>Ini adalah halaman dashboard admin. Silakan pilih salah satu opsi di bawah ini.</p>
        <ul class="link-list">
            <li><a href="manage_user.php" class="btn btn-danger">Manajemen Pengguna</a></li>
            <li><a href="moderate_konten.php " class="btn btn-danger">Moderasi Konten</a></li>
        </ul>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</body>
</html>