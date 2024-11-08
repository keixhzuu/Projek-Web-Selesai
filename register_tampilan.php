<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ttl = $_POST['ttl'];
    $role = $_POST['role'];

    if ($role == 'user') {
        // Insert into the user table if the role is 'user'
        $sql = "INSERT INTO user (email_user, password_user, ttl) VALUES ('$email', '$password', '$ttl')";
        
        if ($conn->query($sql)) {
            $message = "Akun pengguna telah dibuat.";
        } else {
            $message = "Error: " . $conn->error;
        }
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
        .form-group label {
        display: block;
        text-align: left;
        font-size: 0.9rem; 
        color: #555; 
        margin-bottom: 5px;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
        <img src="logo_nomin.png" alt="logo" style="width: 60px; height: 60px; margin-bottom: 10px;">
        </div>

        <!-- Alert for Success Message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <div id="registerForm">
            <h2 class="h4">Buat akun pribadi baru</h2>
            <p class="text-muted">Dapatkan ide-ide baru untuk dicoba</p>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" placeholder="Buat kata sandi" name="password" required>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="ttl" required>
                </div>
                <input type="hidden" name="role" value="user">
                <button type="submit" class="btn btn-primary btn-block">Lanjutkan</button>
            </form>
            <p class="terms">
                Dengan melanjutkan, berarti Anda menyetujui <a href="#">Persyaratan Layanan</a> Pinterest dan menyatakan <br> bahwa Anda telah membaca <a href="#">Kebijakan Privasi</a>.
            </p>
            <a href="index.php" class="btn btn-outline-secondary btn-block mt-3">Sudah punya akun? Login</a>
            </div>
    </div>

    <!-- jQuery and Bootstrap JS for the dismissible alert -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
