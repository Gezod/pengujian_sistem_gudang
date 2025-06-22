<?php
$db = mysqli_connect('localhost', 'root', '', 'pengujian_2');

function register($data)
{
    global $db;

    $username = strtolower(stripslashes($data['username']));
    $password = mysqli_real_escape_string($db, $data['password']);
    $confirmPassword = mysqli_real_escape_string($db, $data['confirmPassword']);

    $query = mysqli_query($db, "SELECT username FROM tb_users WHERE username = '$username'");

    if (mysqli_fetch_assoc($query)) {
        echo
        "<script>
            alert('Username sudah ada!');
        </script>";
        return false;
    }

    if ($password !== $confirmPassword) {
        echo
        "<script>
            alert('Password dan Confirm Password tidak sama!');
        </script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($db, "INSERT INTO tb_users VALUES ('', '$username', '$password')");

    return mysqli_affected_rows($db);
}

function login($data)
{
    global $db;

    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);

    $query = mysqli_query($db, "SELECT * FROM tb_users WHERE username = '$username'");
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        if (password_verify($password, $row['password'])) {
            
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $row['id']; 
            $_SESSION['nama'] = $row['username'];

            return true;
        }
        echo
        "<script>
            alert('Password Salah!');
        </script>";
        return false;
    }
    echo
    "<script>
        alert('Username tidak ditemukan!');
    </script>";
    return false;
}

