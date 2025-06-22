<?php
session_start();
require 'config.php';  // Memasukkan file config.php

// Cek jika form login disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Ambil data user dari DB
    $stmt = $db->prepare("SELECT id, password FROM tb_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Login sukses, simpan session
        $_SESSION['login'] = true;
        $_SESSION['nama'] = $username;
        $_SESSION['user_id'] = $user['id']; // ✅ Untuk simpan produk
        header("Location: index.php?login=success");
        exit;
    } else {
        // Gagal login, redirect ke halaman login dengan pesan error
        header("Location: login.php?error=gagal");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(to right, #8360c3, #2ebf91);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 350px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container h2 {
            color: white;
            margin-bottom: 20px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-custom {
            background-color: #ff4b5c;
            color: white;
            border: none;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #ff1c3d;
        }

        .register-link {
            color: white;
            margin-top: 15px;
            display: block;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Username" name="username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom w-100" name="login">Sign In</button>
        </form>
        <a href="register.php" class="register-link">Don't have an account? Register here!</a>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'gagal'): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Username atau password salah.',
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Logout Berhasil',
                text: 'Kamu telah keluar dari sesi.',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

</body>

</html>