# TK IT Quantum School — Fondasi Proyek (Tahap 1)

## Perbaikan Bug Penting (baca dulu jika upgrade dari zip sebelumnya)

Jika sebelumnya Anda mendapat **500 Internal Server Error** saat mengakses situs:
- Penyebabnya: `.htaccess` di folder utama punya salah ketik (`\#` alih-alih `#`) yang membuat Apache gagal memuat konfigurasi. Sudah diperbaiki.
- Folder `uploads/` dipindahkan ke dalam `public/uploads/` — sebelumnya file upload tersimpan di luar `public/` sehingga gambar tidak bisa tampil di browser. Folder ini sekarang diberi `.htaccess` yang mencegah eksekusi PHP di dalamnya demi keamanan.
- Semua `.htaccess` yang bergantung pada `mod_rewrite` dibungkus `<IfModule mod_rewrite.c>` supaya tidak membuat Apache crash jika modul tersebut nonaktif.

**Jika meng-extract ulang di atas instalasi lama**, hapus dulu folder `uploads/` lama di root project (bukan yang di dalam `public/`) agar tidak ada folder ganda.

Ini adalah **fondasi** dari website resmi sekolah "TK IT Quantum School": struktur folder, database lengkap (ERD-nya tercermin dari relasi tabel), core Custom MVC, keamanan dasar, dan satu modul contoh (Beranda + Berita) sebagai pola rujukan untuk modul-modul lain.

## Login Default

**Catatan restore database:** fitur restore mem-parsing file `.sql` sederhana (pisah per titik-koma) tanpa dependency eksternal — cocok untuk dump standar seperti `database/tkitquantum.sql`. Untuk dump yang berisi `DELIMITER` khusus (stored procedure/trigger kompleks), gunakan phpMyAdmin secara manual.

| Username     | Password    | Role       |
|--------------|-------------|------------|
| superadmin   | Aragi@2026  | superadmin |
| admin        | Aragi@2026  | admin      |

URL login: `http://localhost/tkitquantum/public/auth/login`
**Wajib ganti password setelah login pertama kali** (menu "Ganti Password" di sidebar).

## Cara Install (XAMPP)

1. Copy folder `tkitquantum/` ke `C:\xampp\htdocs\` (atau `htdocs/` di macOS/Linux).
2. Buka phpMyAdmin → buat database baru, lalu import `database/tkitquantum.sql`
   (query `CREATE DATABASE` sudah termasuk di file, jadi bisa langsung diimport tanpa buat database manual).
3. Sesuaikan kredensial di `config/database.php` jika perlu (default: user `root`, password kosong).
4. Sesuaikan `base_url` di `config/app.php` bila lokasi folder berbeda.
5. Akses via browser: `http://localhost/tkitquantum/public/`
6. Pastikan module Apache `mod_rewrite` aktif (default aktif di XAMPP) agar `.htaccess` bekerja.

## Struktur Folder

```
tkitquantum/
├── config/          -> database.php, app.php (satu-satunya tempat ubah koneksi DB)
├── core/             -> App (router), Controller, Model, Database, Security
├── controllers/       -> satu file per controller (co: HomeController.php)
├── models/            -> satu file per model (co: BeritaModel.php)
├── views/
│   ├── layouts/main.php  -> layout global (navbar, footer, Bootstrap 5)
│   ├── home/              -> view publik
│   ├── admin/              -> (tahap berikut) dashboard admin
│   ├── auth/                -> (tahap berikut) login
│   └── errors/                -> 403.php, 404.php, 500.php
├── public/            -> DOCUMENT ROOT (arahkan Apache/hosting ke sini)
│   ├── index.php        -> front controller
│   ├── .htaccess          -> pretty URL
│   └── uploads/            -> file upload (guru, tendik, berita, galeri, ppdb, branding)
├── database/tkitquantum.sql
└── storage/logs, storage/backup
```

## Pola Routing

`/nama-controller/method/param1` → memanggil `NamaControllerController@method($param1)`
Kosong → `HomeController@index`

## Keamanan yang Sudah Diimplementasikan di Tahap 1

- PDO Prepared Statement di seluruh query (lihat `core/Model.php`)
- `password_hash` / `password_verify` (siap dipakai di modul Auth tahap berikutnya)
- CSRF token helper (`Security::csrfToken()`, `Security::csrfField()`, `Controller::validateCsrf()`)
- Validasi & sanitasi upload berbasis MIME asli + nama file acak (`Security::handleUpload()`)
- Security headers, session hardening, auto logout idle, regenerasi session ID
- RBAC dasar (`Controller::requireAuth()`, `requireRole()`)
- Activity log (`Security::logActivity()`)
- Halaman error 403/404/500 custom
- `.htaccess` memblokir akses langsung ke file sensitif & direktori di luar `public/`

## Modul CMS (Berita, Pengumuman, Agenda, Galeri)

Diakses dari sidebar dashboard admin. Pola URL-nya:

- `/adminberita`, `/adminberita/tambah`, `/adminberita/edit/{id}`, `/adminberita/hapus/{id}`
- `/adminpengumuman`, `/adminpengumuman/tambah`, `/adminpengumuman/edit/{id}`, `/adminpengumuman/hapus/{id}`
- `/adminagenda`, `/adminagenda/tambah`, `/adminagenda/edit/{id}`, `/adminagenda/hapus/{id}`
- `/admingaleri` (tab Foto & Video, upload dan hapus langsung dari halaman ini)

**Catatan penting untuk pengembangan modul berikutnya:** karena router mencocokkan segmen URL persis dengan nama method (case-sensitive, tanpa tanda hubung), gunakan nama method camelCase tanpa hyphen dan pastikan link di view menggunakan nama yang sama persis (contoh: method `hapusFoto()` → URL `/admingaleri/hapusFoto/5`, BUKAN `/admingaleri/hapus-foto/5`).

## Roadmap Tahap Berikutnya

Karena scope aslinya sangat besar, saya sarankan lanjut bertahap:

1. ~~**Modul Auth & RBAC dasar**~~ ✅ Selesai — login, rate limiting, lock akun, ganti password, session hardening.
2. ~~**Dashboard Admin**~~ ✅ Selesai — layout admin + Chart.js statistik.
3. ~~**Modul CMS**~~ ✅ Selesai — Berita, Pengumuman, Agenda, Galeri Foto & Video (CRUD lengkap + CKEditor + upload aman + DataTables + SweetAlert2), sudah tampil di halaman publik.
4. ~~**Modul Guru & Tenaga Kependidikan**~~ ✅ Selesai — CRUD lengkap di dashboard (`/adminguru`, `/admintendik`) + halaman publik (`/guru`, `/tendik`) dengan data dummy realistis.
5. ~~**Modul PPDB Online**~~ ✅ Selesai — info + countdown + FAQ (`/ppdb`), form pendaftaran (`/ppdb/daftar`), cek status (`/ppdb/cekStatus`), nomor pendaftaran otomatis, kuota otomatis menutup pendaftaran, dan panel admin (`/adminppdb`) untuk pengaturan, verifikasi pendaftar, dan kelola FAQ.
6. ~~**Modul Logo & Branding**~~ ✅ Selesai — kelola logo (utama/login/navbar/footer), favicon, foto kepsek, banner hero, warna website, sosial media, dan embed Google Maps dari dashboard (`/adminbranding`) tanpa ubah kode. Sudah terhubung ke navbar, footer, halaman login, hero, dan section Lokasi di beranda.
7. ~~**Backup/Restore Database, Audit Log lanjutan**~~ ✅ Selesai — backup manual ke file `.sql` murni PDO (tanpa `shell_exec`), unduh/hapus riwayat backup, restore dari file upload (transaksional, dibatasi 20MB), dan halaman Log Aktivitas (`/adminbackup/logAktivitas`) menampilkan 500 aktivitas terbaru. Khusus role **superadmin**.
8. ~~**Data dummy realistis penuh** untuk semua modul~~ ✅ Selesai — ditambahkan CRUD untuk Struktur Organisasi (`/adminstruktur`), Prestasi (`/adminprestasi`), Ekstrakurikuler (`/adminekskul`), Fasilitas (`/adminfasilitas`), Testimoni Alumni (`/admintestimoni`), Kalender Akademik (`/adminkalender`), dan Profil Sekolah — sejarah/visi/misi/sambutan (`/adminprofil`). Semua tampil di halaman publik `/profil` dan `/akademik`, serta sebagian di beranda.

## Ringkasan Cakupan Saat Ini

Seluruh 18 poin "Output yang Diharapkan" di brief awal sudah terimplementasi kecuali:
- Use Case Diagram, Activity Diagram, dan ERD dalam bentuk diagram visual (struktur relasi sudah tercermin penuh di `database/tkitquantum.sql`, tapi belum digambar sebagai diagram)
- Wireframe formal (desain langsung diimplementasikan ke kode berjalan, bukan mockup terpisah)
- Modul Mata Pelajaran & Jadwal Pelajaran CRUD (tabel `mata_pelajaran` sudah ada di database, belum ada antarmuka CRUD-nya)

Beri tahu saya jika ingin melengkapi salah satu dari tiga poin di atas, atau ingin saya lakukan review keamanan/QA menyeluruh terhadap seluruh proyek.

Beri tahu saya modul mana yang ingin dikerjakan lebih dulu — saya akan bangun secara penuh (controller, model, view, CRUD, validasi) satu per satu supaya kualitasnya terjaga.
