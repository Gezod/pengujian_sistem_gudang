<?php
session_start();
require 'config.php'; // Pastikan file config.php dimuat dengan benar

// Cek login
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    die("User ID tidak ditemukan. Silakan login kembali.");
}

// Ambil data dari form
$product_name = htmlspecialchars($_POST['product_name']);
$description = htmlspecialchars($_POST['description']);
$price = floatval($_POST['price']);
$stok = intval($_POST['stok']); // ✅ Tambahkan ambil stok dari form

// Ambil user_id dari session
$user_id = $_SESSION['user_id']; // Pastikan user_id ada dalam session

// Proses upload gambar
$image_name = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Cek apakah ekstensi file valid
    if (!in_array($image_file_type, $valid_extensions)) {
        die("Format gambar tidak valid. Hanya JPG, JPEG, PNG, atau GIF yang diperbolehkan.");
    }
    
    // Proses upload gambar
    $target_dir = "uploads/";
    $image_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    // Pastikan folder uploads/ ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        die("Gagal mengupload gambar.");
    }
} else {
    $image_name = ''; // Jika tidak ada gambar, set default kosong
}

// Simpan ke database
$sql = "INSERT INTO tb_products (user_id, product_name, description, price, image, stok) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $db->prepare($sql);  // Gunakan $db di sini, sesuai dengan config.php
$stmt->bind_param("issdsi", $user_id, $product_name, $description, $price, $image_name, $stok); // perhatikan urutan dan tipe data

if ($stmt->execute()) {
    header("Location: index.php?success=tambah");
    exit;
} else {
    echo "Gagal menyimpan data produk: " . $stmt->error;
}
?>
