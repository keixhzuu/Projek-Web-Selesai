<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Memeriksa apakah login sebagai admin atau user
    $sql_admin = "SELECT id_admin, email_admin, password_admin FROM admin WHERE email_admin = '$email' AND password_admin = '$password'";
    $sql_user = "SELECT id_user, email_user, password_user FROM user WHERE email_user = '$email' AND password_user = '$password'";
    
    $result_admin = $conn->query($sql_admin);
    $result_user = $conn->query($sql_user);

    if ($result_admin->num_rows > 0) {
        $admin = $result_admin->fetch_assoc();
        $_SESSION['admin_id'] = $admin['id_admin']; // Simpan admin ID dalam session
        header("Location: dashboard_admin.php");
        exit;
    } elseif ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $_SESSION['user_id'] = $user['id_user']; // Simpan user ID dalam session
        header("Location: dashboard_user.php");
        exit;
    } else {
        $message = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru atau Login</title>
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
    .terms {
        font-size: 12px;
        color: #888;
        margin-top: 15px;
    }
</style>

    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
        <img src="logo_nomin.png" alt="logo" style="width: 60px; height: 60px; margin-bottom: 10px;">
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

    <!-- Form Login -->
    <div id="loginForm">
        <h2 class="h4">Login ke akun Anda</h2>
        <form method="POST">
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Kata sandi" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="terms">
            Dengan login, Anda menyetujui <a href="#">Persyaratan Layanan</a> dan <a href="#">Kebijakan Privasi</a>.
        </p>
        <a href="register_tampilan.php" class="btn btn-outline-secondary btn-block mt-3">Belum punya akun? Daftar</a>
        <a href="register_admin.php" class="btn btn-outline-secondary btn-block mt-3">Registrasi sebagai admin</a>
        </div>
    </div>

    </div>
</body>
</html>