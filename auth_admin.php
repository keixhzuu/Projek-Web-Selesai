<?php
session_start();

// Password otorisasi admin (bisa disimpan di database atau file konfigurasi yang aman)
$admin_auth_password = 'adminsecret';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_password = $_POST['auth_password'];
    
    // Memeriksa apakah password otorisasi benar
    if ($input_password === $admin_auth_password) {
        // Jika benar, beri izin akses ke halaman registrasi admin
        $_SESSION['authorized_admin'] = true;
        header("Location: register_admin.php");
        exit;
    } else {
        $error_message = "Password otorisasi salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otorisasi Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #f7f7f7;
    }
    .container {
        max-width: 400px;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        background-color: #fff;
        text-align: center;
    }
    .logo img {
        width: 50px;
        margin-bottom: 20px;
    }
    .btn-primary {
        background-color: #e60023;
        border: none;
    }
    .btn-primary:hover {
        background-color: #d4001f;
    }
</style>

    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
        <img src="logo_nomin.png" alt="logo" style="width: 60px; height: 60px; margin-bottom: 10px;">
        </div>

    <!-- Form Login -->
    <div id="loginForm">
        <h2 class="h4">Otorisasi Admin</h2>
        <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Kata sandi" name="auth_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
        <a href="index.php" class="btn btn-outline-secondary btn-block mt-3">Kembali ke Login</a>
        </div>
    </div>

    </div>
</body>
</html>