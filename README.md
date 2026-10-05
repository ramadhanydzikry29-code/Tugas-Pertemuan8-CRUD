# CRUD Inventaris — Tugas Rutin 8 (Pemrograman Web)

Aplikasi CRUD Inventaris sederhana yang dibuat untuk memenuhi Tugas Rutin 8 Pemrograman Web.

## Langkah Import Database

1. Jalankan **XAMPP**.
2. Aktifkan **Apache** dan **MySQL**.
3. Buka **phpMyAdmin** melalui browser:
   `http://localhost/phpmyadmin/`
4. Buat database baru dengan nama:
   `inventaris_db`
5. Pilih database `inventaris_db`.
6. Klik menu **Import**.
7. Pilih file `schema.sql` yang terdapat di dalam project.
8. Klik **Import** atau **Go**.
9. Pastikan tabel berikut berhasil dibuat:
   - `categories`
   - `suppliers`
   - `products`
   - `activity_logs`

## Menjalankan Aplikasi

Setelah database berhasil di-import, buka:

`http://localhost/TugasWeb-Pertemuan8-CRUD/`

## Fitur Aplikasi

- Menampilkan daftar produk
- Menambahkan produk
- Mengedit produk
- Menghapus produk
- Pencarian produk
- Pagination
- Validasi input
- Pencatatan aktivitas penghapusan pada `activity_logs`

## Screenshot

### Daftar Produk

<img width="1600" height="819" alt="image" src="https://github.com/user-attachments/assets/543556f9-4c94-4ed8-bebe-d4fc44e55323" />


### Tambah Produk

<img width="1488" height="840" alt="image" src="https://github.com/user-attachments/assets/f943e1a5-f864-4f27-8c3f-5e364e1b052b" />


### Edit Produk

<img width="1378" height="699" alt="image" src="https://github.com/user-attachments/assets/d6edfaa4-65fc-436b-a2b6-56bd41349c4c" />
