<?php
session_start();
require 'config.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['nama'];

$query = "SELECT * FROM tb_products";
$result = mysqli_query($db, $query);

if (!$result) {
    die("Query error: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background: #343a40;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .container {
            flex-grow: 1;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }

        .card-deck {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .card-deck .card {
            width: 300px;
        }
    </style>
</head>

<body>

    <?php if (isset($_GET['success'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                <?php if ($_GET['success'] === 'tambah'): ?>
                    Swal.fire({ icon: 'success', title: 'Produk Ditambahkan!', text: 'Produk berhasil ditambahkan ke database.', timer: 2000, showConfirmButton: false });
                <?php elseif ($_GET['success'] === 'edit'): ?>
                    Swal.fire({ icon: 'success', title: 'Produk Diperbarui!', text: 'Perubahan produk berhasil disimpan.', timer: 2000, showConfirmButton: false });
                <?php elseif ($_GET['success'] === 'hapus'): ?>
                    Swal.fire({ icon: 'success', title: 'Produk Dihapus!', text: 'Produk berhasil dihapus dari database.', timer: 2000, showConfirmButton: false });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Gudang <?= htmlspecialchars($username) ?></a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-danger">Log Out</a>
            </div>
        </div>
    </nav>

    <div class="container d-flex align-items-center justify-content-center">
        <div class="card p-4 text-center mt-5" style="max-width: 400px;">
            <h2 class="fw-bold">Selamat Datang, <?= htmlspecialchars($username) ?>!</h2>
            <p class="text-muted">Anda berhasil masuk ke Gudang Sistem.</p>
        </div>
    </div>

    <div class="container text-center mt-5">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">Tambah
            Produk</button>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="simpan_produk.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="product_name" class="form-control mb-3" placeholder="Nama Produk" required>
                    <textarea name="description" class="form-control mb-3" placeholder="Deskripsi" required></textarea>
                    <input type="number" name="price" class="form-control mb-3" placeholder="Harga" required>
                    <input type="number" name="stok" class="form-control mb-3" placeholder="Stok Produk" required>
                    <input type="file" name="image" class="form-control mb-3" accept="image/*" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="container mt-5">
        <h3 class="text-center mb-4">Daftar Produk</h3>
        <div class="card-deck">
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <img src="uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($product['product_name']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                        <p class="card-text"><strong>Rp <?= number_format($product['price'], 0, ',', '.') ?></strong></p>
                        <p class="card-text">Stok: <?= htmlspecialchars($product['stok']) ?></p>
                        <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal"
                            data-bs-target="#editProductModal" data-id="<?= $product['id'] ?>"
                            data-name="<?= htmlspecialchars($product['product_name']) ?>"
                            data-description="<?= htmlspecialchars($product['description']) ?>"
                            data-price="<?= $product['price'] ?>" data-stok="<?= $product['stok'] ?>">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm mb-1" data-bs-toggle="modal"
                            data-bs-target="#deleteProductModal" data-id="<?= $product['id'] ?>">
                            Hapus
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="update_produk.php" method="POST" enctype="multipart/form-data"
                novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- ID Produk -->
                    <input type="hidden" name="product_id" id="edit_product_id">

                    <!-- Gambar Lama -->
                    <input type="hidden" name="current_image" id="edit_current_image">

                    <!-- Form Field -->
                    <input type="text" name="product_name" id="edit_product_name" class="form-control mb-3"
                        placeholder="Nama Produk" required>
                    <textarea name="description" id="edit_description" class="form-control mb-3" placeholder="Deskripsi"
                        required></textarea>
                    <input type="number" name="price" id="edit_price" class="form-control mb-3" placeholder="Harga"
                        required>
                    <input type="number" name="stok" id="edit_stok" class="form-control mb-3" placeholder="Stok Produk"
                        required>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="hapus_produk.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk ini?</p>
                    <input type="hidden" name="product_id" id="delete_product_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Pengujian & Implementasi Sistem</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const editModal = document.getElementById('editProductModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_product_id').value = button.getAttribute('data-id');
            document.getElementById('edit_product_name').value = button.getAttribute('data-name');
            document.getElementById('edit_description').value = button.getAttribute('data-description');
            document.getElementById('edit_price').value = button.getAttribute('data-price');
            document.getElementById('edit_stok').value = button.getAttribute('data-stok');

            // ✅ Tambahan: ambil nama file gambar dari <img>
            const imageSrc = button.closest('.card').querySelector('img').getAttribute('src');
            const imageName = imageSrc.split('/').pop();
            document.getElementById('edit_current_image').value = imageName;
        });


        const deleteModal = document.getElementById('deleteProductModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('delete_product_id').value = button.getAttribute('data-id');
        });
    </script>

    <?php if (isset($_GET['login']) && $_GET['login'] === 'success'): ?>
        <script>
            Swal.fire({ icon: 'success', title: 'Login Berhasil', text: 'Selamat datang kembali!', timer: 1500, showConfirmButton: false });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'gagal'): ?>
        <script>
            Swal.fire({ icon: 'error', title: 'Login Gagal', text: 'Username atau password salah.' });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
        <script>
            Swal.fire({ icon: 'success', title: 'Logout Berhasil', text: 'Kamu telah keluar dari sesi.', timer: 2000, showConfirmButton: false });
        </script>
    <?php endif; ?>

</body>

</html>