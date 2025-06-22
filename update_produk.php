<?php
session_start();
require 'config.php'; 

// Cek login
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    die("User ID tidak ditemukan. Silakan login kembali.");
}

// Ambil data dari form
$product_id = intval($_POST['product_id']);
$product_name = htmlspecialchars($_POST['product_name']);
$description = htmlspecialchars($_POST['description']);
$price = floatval($_POST['price']);
$stok = intval($_POST['stok']); // ✅ Ambil stok dari form
$image_name = $_POST['current_image'] ?? ''; // Jika tidak ada, tetap aman

// Proses upload gambar baru jika ada
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($image_file_type, $valid_extensions)) {
        die("Format gambar tidak valid. Hanya JPG, JPEG, PNG, atau GIF yang diperbolehkan.");
    }

    $target_dir = "uploads/";
    $image_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        die("Gagal mengupload gambar.");
    }
}

// Update ke database
$sql = "UPDATE tb_products SET product_name = ?, description = ?, price = ?, stok = ?, image = ? WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("ssdisi", $product_name, $description, $price, $stok, $image_name, $product_id);

if ($stmt->execute()) {
    header("Location: index.php?success=edit"); 
    exit;
} else {
    echo "Gagal mengupdate data produk: " . $stmt->error;
}
?>
