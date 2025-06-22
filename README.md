# 📦 Sistem Gudang Produk Berbasis Web

Proyek ini merupakan aplikasi manajemen gudang sederhana yang memungkinkan pengguna untuk menambahkan, melihat, mengedit, dan menghapus data produk melalui antarmuka web menggunakan **PHP + MySQL** dan **Bootstrap 5**.

---

## 👥 Kelompok 6 - Pengujian & Implementasi Sistem

| Nama Lengkap                       | NIM          |
|------------------------------------|--------------|
| Refangga Lintar P                  | 1204220137   |
| Ramadhani Vanva F                  | 1204220068   |
| Alief Sukma D                      | 1204220030   |
| Stephanie Debora A                 | 1204220134   |
| Zahrotunnisa Nuril Firdaust        | 1204238079   |
| Dimas Rifki Nuramadani             | 1204238096   |

---

## 🧠 Penjelasan Flowchart Login

Pada proses login sistem, digunakan logika percabangan sebagai berikut:

1. Pengguna membuka halaman login dan mengisi email serta password.
2. Sistem mengecek apakah data yang dimasukkan valid.
3. Jika tidak valid, sistem menampilkan pesan error.
4. Jika valid, sistem akan mengecek role pengguna:
   - Jika `dosen`, akan diarahkan ke halaman dosen.
   - Jika `admin`, akan diarahkan ke halaman admin.

### 🔢 Komponen Flowchart:
- **Node (simpul)**: 10
- **Edge (alur/panah)**: 11

### 🧮 Cyclomatic Complexity (VG)
\[
VG = E - N + 2P = 11 - 10 + 2(1) = 3
\]
Artinya terdapat **3 jalur logika independen** yang perlu diuji.

---

## 🛠️ Fitur Sistem

- 🔐 Login dengan validasi session.
- 📄 Halaman dashboard untuk menampilkan produk.
- ➕ Tambah produk baru (nama, deskripsi, harga, stok, gambar).
- ✏️ Edit produk dengan modal popup.
- 🗑️ Hapus produk dengan konfirmasi.
- 🎨 UI menggunakan Bootstrap 5.
- 🔔 Notifikasi real-time menggunakan SweetAlert2.

---

## 📁 Struktur Folder

```bash
.
├── config.php
├── login.php
├── dashboard.php
├── simpan_produk.php
├── update_produk.php
├── hapus_produk.php
├── uploads/
└── README.md
