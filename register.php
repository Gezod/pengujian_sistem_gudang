<?php
session_start();
require 'config.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['register'])) {
    if (register($_POST)) {
        echo
        "<script>
            alert('User berhasil ditambahkan');
            document.location.href = 'login.php';
        </script>";
    } else {
        echo
        "<script>
            alert('User gagal ditambahkan');
            document.location.href = 'register.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #8360c3, #2ebf91);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 350px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .register-container h2 {
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

        .login-link {
            color: white;
            margin-top: 15px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Register</h2>
        <form action="" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Username" name="username" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Confirm Password" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-custom w-100" name="register">Sign Up</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login here!</a>
    </div>

</body>
</html>
