# CRUD Inventaris — Tugas Rutin 8 (Pemrograman Web)

Aplikasi CRUD inventaris barang memakai **PHP Native + PDO + MySQL**.
Mata kuliah 3KOM40115 — Pemrograman Web, FMIPA UNIMED.

## Fitur
- **Create** — form tambah produk dengan dropdown kategori & supplier
- **Read** — daftar produk dengan `JOIN` 3 tabel (products, categories, suppliers)
- **Update** — form edit yang sudah terisi data lama
- **Delete** — dengan konfirmasi (`confirm()`) dan hanya lewat `POST`
- Flash message sukses/gagal (pola Post/Redirect/Get, disimpan di session)
- ⭐ Bonus: pencarian, pagination, dan **transaction** pada delete (log aktivitas)

## Keamanan & Desain DB
- Koneksi PDO dengan **Singleton pattern** (`config/database.php`)
- Opsi PDO: `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, `EMULATE_PREPARES = false`
- **Semua query memakai prepared statements** (tidak ada concatenation)
- Input di-cast tipe (`(int)`, `(float)`) dan divalidasi di sisi server
- Semua output HTML memakai `htmlspecialchars()` (helper `e()`)
- Error detail dicatat via `error_log()`, user hanya melihat pesan generik
- Skema ternormalisasi 3NF + Foreign Key (`ON DELETE RESTRICT`)

## Struktur Proyek
```
TugasWeb-Pertemuan8-CRUD/
├── config/database.php     # Singleton PDO
├── includes/               # helper, header, footer, form bersama
├── assets/style.css
├── schema.sql              # DDL + seed data
├── index.php               # READ
├── create.php              # CREATE
├── edit.php                # UPDATE
└── delete.php              # DELETE (transaction)
```

## Langkah Import Database
1. Jalankan **Laragon / XAMPP** (Apache + MySQL).
2. Import `schema.sql`:
   - **phpMyAdmin**: tab *Import* → pilih `schema.sql` → *Go*, atau
   - **Terminal**: `mysql -u root < schema.sql`
3. Salin folder proyek ke `htdocs` (XAMPP) atau `www` (Laragon).
4. Buka `http://localhost/TugasWeb-Pertemuan8-CRUD/`.

Jika user/password MySQL berbeda, atur environment variable `DB_HOST`, `DB_NAME`,
`DB_USER`, `DB_PASS`, atau ubah nilai default di `config/database.php`.

Alternatif tanpa Apache: `php -S localhost:8000` di dalam folder proyek.

## Screenshot
> Tambahkan screenshot aplikasi Anda di sini setelah dijalankan:
> `![Daftar produk](screenshots/index.png)`
> `![Form tambah](screenshots/create.png)`
> `![Form edit](screenshots/edit.png)`
