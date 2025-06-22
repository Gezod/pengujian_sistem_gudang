<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['product_id']);
    
    // Hapus gambar jika ada
    $result = mysqli_query($db, "SELECT image FROM tb_products WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
    if ($data && file_exists("uploads/" . $data['image'])) {
        unlink("uploads/" . $data['image']);
    }

    // Hapus data dari database
    $query = "DELETE FROM tb_products WHERE id = $id";
    mysqli_query($db, $query);
}

header("Location: index.php?success=hapus");
exit;
