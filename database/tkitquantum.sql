-- =====================================================================
-- ARAGI SCHOOL - Database Schema
-- Engine: MySQL 8+ / MariaDB 10.4+
-- Charset: utf8mb4
-- =====================================================================



-- ============================
-- 1. USERS & RBAC
-- ============================
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(50) NOT NULL UNIQUE,   -- superadmin, admin, operator, guru, tendik
    deskripsi VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(100) NOT NULL UNIQUE,       -- e.g. berita.create, ppdb.manage
    deskripsi VARCHAR(255) NULL
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- password_hash()
    nama_lengkap VARCHAR(150) NOT NULL,
    foto VARCHAR(255) NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    last_login DATETIME NULL,
    failed_attempts TINYINT DEFAULT 0,
    locked_until DATETIME NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    aktivitas VARCHAR(255) NOT NULL,
    modul VARCHAR(100) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================
-- 2. PENGATURAN & BRANDING
-- ============================
CREATE TABLE pengaturan_website (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(150) NOT NULL DEFAULT 'TK IT Quantum School',
    tagline VARCHAR(255) NULL,
    alamat TEXT NULL,
    email VARCHAR(100) NULL,
    telepon VARCHAR(30) NULL,
    logo VARCHAR(255) NULL,
    favicon VARCHAR(255) NULL,
    logo_login VARCHAR(255) NULL,
    logo_navbar VARCHAR(255) NULL,
    logo_footer VARCHAR(255) NULL,
    foto_kepsek VARCHAR(255) NULL,
    banner_hero VARCHAR(255) NULL,
    warna_primary VARCHAR(20) DEFAULT '#FFC107',
    warna_secondary VARCHAR(20) DEFAULT '#FFF8E1',
    warna_accent VARCHAR(20) DEFAULT '#FF9800',
    facebook VARCHAR(255) NULL,
    instagram VARCHAR(255) NULL,
    youtube VARCHAR(255) NULL,
    tiktok VARCHAR(255) NULL,
    maps_embed TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE profil_sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sejarah TEXT NULL,
    visi TEXT NULL,
    misi TEXT NULL,
    tujuan TEXT NULL,
    sambutan_kepsek TEXT NULL,
    nama_kepsek VARCHAR(150) NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE struktur_organisasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    jabatan VARCHAR(150) NOT NULL,
    foto VARCHAR(255) NULL,
    urutan INT DEFAULT 0,
    parent_id INT NULL,
    FOREIGN KEY (parent_id) REFERENCES struktur_organisasi(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================
-- 3. GURU & TENAGA KEPENDIDIKAN
-- ============================
CREATE TABLE guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    nip VARCHAR(30) NULL UNIQUE,
    nama_lengkap VARCHAR(150) NOT NULL,
    foto VARCHAR(255) NULL,
    mata_pelajaran VARCHAR(150) NULL,
    pendidikan_terakhir VARCHAR(100) NULL,
    jabatan VARCHAR(100) NULL,
    email VARCHAR(100) NULL,
    no_hp VARCHAR(30) NULL,
    riwayat_singkat TEXT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE tenaga_kependidikan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    nip VARCHAR(30) NULL UNIQUE,
    nama_lengkap VARCHAR(150) NOT NULL,
    foto VARCHAR(255) NULL,
    jabatan VARCHAR(100) NULL,          -- Kepala TU, Staff TU, Bendahara, Operator, Pustakawan, dll
    pendidikan_terakhir VARCHAR(100) NULL,
    email VARCHAR(100) NULL,
    no_hp VARCHAR(30) NULL,
    deskripsi_singkat TEXT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================
-- 4. AKADEMIK
-- ============================
CREATE TABLE mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_mapel VARCHAR(150) NOT NULL,
    kode VARCHAR(20) NULL,
    deskripsi TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE kalender_akademik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NULL,
    keterangan TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE prestasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    kategori ENUM('akademik','non-akademik') DEFAULT 'akademik',
    tingkat VARCHAR(100) NULL,          -- sekolah, kota, provinsi, nasional, internasional
    tahun YEAR NULL,
    foto VARCHAR(255) NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE ekstrakurikuler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    pembina VARCHAR(150) NULL,
    foto VARCHAR(255) NULL,
    deskripsi TEXT NULL,
    jadwal VARCHAR(150) NULL
) ENGINE=InnoDB;

CREATE TABLE fasilitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    foto VARCHAR(255) NULL,
    deskripsi TEXT NULL
) ENGINE=InnoDB;

-- ============================
-- 5. CMS: BERITA, PENGUMUMAN, AGENDA, GALERI
-- ============================
CREATE TABLE kategori_berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NULL,
    user_id INT NULL,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    thumbnail VARCHAR(255) NULL,
    ringkasan VARCHAR(500) NULL,
    isi LONGTEXT NOT NULL,
    status ENUM('draft','publish') DEFAULT 'draft',
    dilihat INT DEFAULT 0,
    tanggal_publish DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori_berita(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    isi_singkat VARCHAR(500) NULL,
    isi LONGTEXT NOT NULL,
    lampiran VARCHAR(255) NULL,
    status ENUM('draft','publish') DEFAULT 'draft',
    tanggal_publish DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE agenda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    tanggal_mulai DATETIME NOT NULL,
    tanggal_selesai DATETIME NULL,
    lokasi VARCHAR(200) NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE galeri_kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE galeri_foto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NULL,
    judul VARCHAR(200) NULL,
    file VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES galeri_kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE galeri_video (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    url_youtube VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE testimoni_alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    angkatan VARCHAR(20) NULL,
    profesi VARCHAR(150) NULL,
    foto VARCHAR(255) NULL,
    isi_testimoni TEXT NOT NULL,
    status ENUM('draft','publish') DEFAULT 'publish',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================
-- 6. PPDB (Penerimaan Peserta Didik Baru)
-- ============================
CREATE TABLE ppdb_pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun_ajaran VARCHAR(20) NOT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'nonaktif',
    kuota INT NOT NULL DEFAULT 0,
    tanggal_mulai DATE NULL,
    tanggal_selesai DATE NULL,
    biaya_pendaftaran DECIMAL(10,2) DEFAULT 0,
    informasi TEXT NULL,
    persyaratan TEXT NULL,
    alur_pendaftaran TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE ppdb_faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pertanyaan VARCHAR(255) NOT NULL,
    jawaban TEXT NOT NULL,
    urutan INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE ppdb_pendaftar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ppdb_id INT NOT NULL,
    no_pendaftaran VARCHAR(30) NOT NULL UNIQUE,
    nik VARCHAR(20) NOT NULL,
    nisn VARCHAR(20) NULL,
    nama VARCHAR(150) NOT NULL,
    tempat_lahir VARCHAR(100) NULL,
    tanggal_lahir DATE NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    agama VARCHAR(30) NULL,
    alamat TEXT NULL,
    no_hp VARCHAR(30) NULL,
    email VARCHAR(100) NULL,
    asal_sekolah VARCHAR(150) NULL,
    nama_ayah VARCHAR(150) NULL,
    nama_ibu VARCHAR(150) NULL,
    pekerjaan_ortu VARCHAR(100) NULL,
    penghasilan_ortu VARCHAR(100) NULL,
    file_kk VARCHAR(255) NULL,
    file_akta VARCHAR(255) NULL,
    file_foto VARCHAR(255) NULL,
    file_rapor VARCHAR(255) NULL,
    status ENUM('menunggu','diverifikasi','diterima','ditolak') DEFAULT 'menunggu',
    catatan_admin TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ppdb_id) REFERENCES ppdb_pengaturan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================
-- 7. STATISTIK & PENGUNJUNG
-- ============================
CREATE TABLE statistik_kunjungan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NULL,
    halaman VARCHAR(255) NULL,
    user_agent VARCHAR(255) NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE statistik_sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_siswa INT DEFAULT 0,
    total_guru INT DEFAULT 0,
    total_tendik INT DEFAULT 0,
    total_ekskul INT DEFAULT 0,
    total_prestasi INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================
-- 8. KONTAK
-- ============================
CREATE TABLE pesan_kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subjek VARCHAR(200) NULL,
    pesan TEXT NOT NULL,
    status ENUM('baru','dibaca') DEFAULT 'baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =====================================================================
-- SEED DATA
-- =====================================================================

INSERT INTO roles (nama_role, deskripsi) VALUES
('superadmin', 'Akses penuh seluruh sistem'),
('admin', 'Mengelola konten dan PPDB'),
('operator', 'Operator sekolah - input berita & pengumuman'),
('guru', 'Akun guru'),
('tendik', 'Akun tenaga kependidikan');

-- Password default untuk KEDUA akun di bawah: Aragi@2026
-- (Ganti password ini segera setelah login pertama kali via menu "Ganti Password")
INSERT INTO users (role_id, username, email, password, nama_lengkap, status) VALUES
(1, 'superadmin', 'superadmin@tkitquantum.sch.id', '$2b$10$qHacySZLhDMdo5NdvqMc3emdnEIJnYmydv0TeT7nTm7tMfwmFCiga', 'Super Administrator', 'aktif'),
(2, 'admin', 'admin@tkitquantum.sch.id', '$2b$10$qHacySZLhDMdo5NdvqMc3emdnEIJnYmydv0TeT7nTm7tMfwmFCiga', 'Admin Sekolah', 'aktif');

INSERT INTO pengaturan_website (nama_sekolah, tagline, alamat, email, telepon, warna_primary, warna_secondary, warna_accent, instagram) VALUES
('TK IT Quantum School', 'Bahagia Bermain, Bahagia Belajar, Bahagia Bertumbuh Bersama Si Kecil!',
'Jl. Sei Mencirim No. 1, Kampung Lalang, Desa Medan Krio, Kec. Sunggal, Kab. Deli Serdang, Sumatera Utara 20351',
'yudha.quantum2014@gmail.com', '0813-xxxx-xxxx', '#4FC3F7', '#FFF9E5', '#FF6FA5', 'https://www.instagram.com/tkitquantum.school/');

INSERT INTO profil_sekolah (sejarah, visi, misi, tujuan, sambutan_kepsek, nama_kepsek) VALUES
('TK IT Quantum School berada di bawah naungan Yayasan Pendidikan Quantum School, berlokasi di Jl. Sei Mencirim No. 1, Medan Krio, Kecamatan Sunggal, Kabupaten Deli Serdang. Sekolah ini hadir sebagai taman kanak-kanak Islam Terpadu yang memadukan pembelajaran akademik, keagamaan, dan pembentukan karakter sejak usia dini melalui suasana belajar yang ceria, aman, dan menyenangkan.',
'Menjadi taman kanak-kanak Islam Terpadu terdepan yang membentuk anak-anak cerdas, ceria, berakhlak mulia, mandiri, dan siap melangkah ke jenjang pendidikan berikutnya.',
'1. Menanamkan nilai-nilai keislaman sejak usia dini dengan cara yang menyenangkan.\n2. Mengembangkan potensi anak melalui bermain sambil belajar (learning by playing).\n3. Membangun kemandirian, kepercayaan diri, dan keterampilan sosial anak.\n4. Menyediakan lingkungan belajar yang aman, ceria, dan ramah anak.',
'Menghasilkan generasi anak usia dini yang sehat, cerdas, kreatif, mandiri, dan berakhlakul karimah sebagai bekal memasuki pendidikan dasar.',
'Selamat datang di TK IT Quantum School! Kami berkomitmen menghadirkan pengalaman belajar terbaik bagi si kecil melalui suasana kelas yang hangat, penuh warna, dan penuh kasih sayang, agar setiap anak tumbuh menjadi pribadi yang cerdas dan berakhlak mulia.', 'Kepala TK IT Quantum School');

INSERT INTO statistik_sekolah (total_siswa, total_guru, total_tendik, total_ekskul, total_prestasi) VALUES
(120, 12, 6, 5, 16);

INSERT INTO ppdb_pengaturan (tahun_ajaran, status, kuota, tanggal_mulai, tanggal_selesai, informasi, persyaratan, alur_pendaftaran) VALUES
('2026/2027', 'aktif', 80, '2026-06-01', '2026-08-31',
 'Pendaftaran Peserta Didik Baru TK IT Quantum School Tahun Ajaran 2026/2027 resmi dibuka. Yuk, daftarkan si kecil untuk tumbuh bersama kami!',
 '- Fotokopi Kartu Keluarga\n- Fotokopi Akta Kelahiran\n- Pas Foto berwarna anak ukuran 3x4\n- Fotokopi Kartu Imunisasi/KIA\n- Usia sesuai ketentuan jenjang (Play Group/TK A/TK B)',
 '1. Isi formulir pendaftaran online melalui website\n2. Unggah seluruh dokumen persyaratan\n3. Simpan nomor pendaftaran yang diberikan sistem\n4. Tunggu proses verifikasi oleh panitia PPDB\n5. Cek status kelulusan melalui menu "Cek Status Pendaftaran"');

INSERT INTO ppdb_faq (pertanyaan, jawaban, urutan) VALUES
('Apakah pendaftaran PPDB dikenakan biaya?', 'Pendaftaran PPDB online TK IT Quantum School tidak dipungut biaya apapun.', 1),
('Berapa lama proses verifikasi berkas?', 'Proses verifikasi berkas membutuhkan waktu 1-3 hari kerja setelah pendaftaran dikirim.', 2),
('Apakah bisa mendaftar tanpa NISN?', 'Bisa, kolom NISN bersifat opsional dan dapat dilengkapi menyusul.', 3),
('Bagaimana jika kuota sudah penuh?', 'Jika kuota telah penuh, tombol pendaftaran akan otomatis dinonaktifkan dan status akan berubah menjadi "Kuota Telah Penuh".', 4);

INSERT INTO kategori_berita (nama_kategori, slug) VALUES
('Akademik', 'akademik'), ('Prestasi', 'prestasi'), ('Kegiatan', 'kegiatan'), ('Pengumuman Umum', 'umum');

INSERT INTO berita (kategori_id, user_id, judul, slug, ringkasan, isi, status, tanggal_publish) VALUES
(3, 2, 'TK IT Quantum School Raih Juara 1 Olimpiade Sains Tingkat Provinsi', 'tk-it-quantum-school-raih-juara-1-olimpiade-sains-tingkat-provinsi',
 'Tim olimpiade sains TK IT Quantum School berhasil membawa pulang medali emas.',
 '<p>Tim olimpiade sains TK IT Quantum School berhasil meraih Juara 1 pada ajang Olimpiade Sains Tingkat Provinsi yang diselenggarakan bulan ini. Prestasi ini menjadi bukti nyata komitmen sekolah dalam mengembangkan potensi akademik siswa.</p>',
 'publish', NOW()),
(1, 2, 'Penerapan Kurikulum Merdeka Berbasis Teknologi Tahun Ajaran Baru', 'penerapan-kurikulum-merdeka-berbasis-teknologi-tahun-ajaran-baru',
 'TK IT Quantum School terus berinovasi dengan mengintegrasikan teknologi dalam pembelajaran.',
 '<p>Memasuki tahun ajaran baru, TK IT Quantum School menerapkan kurikulum merdeka yang diperkaya dengan pemanfaatan teknologi digital di setiap mata pelajaran.</p>',
 'publish', NOW()),
(3, 2, 'Kegiatan Bakti Sosial Siswa TK IT Quantum School di Panti Asuhan', 'kegiatan-bakti-sosial-siswa-tk-it-quantum-school-di-panti-asuhan',
 'Siswa-siswi menunjukkan kepedulian sosial melalui kegiatan bakti sosial rutin.',
 '<p>Sebagai bagian dari pendidikan karakter, siswa TK IT Quantum School mengadakan kegiatan bakti sosial ke panti asuhan setempat.</p>',
 'publish', NOW());

INSERT INTO pengumuman (user_id, judul, slug, isi_singkat, isi, status, tanggal_publish) VALUES
(2, 'Libur Semester Genap Tahun Ajaran 2025/2026', 'libur-semester-genap-tahun-ajaran-2025-2026',
 'Libur semester genap dimulai tanggal 20 Juni 2026.',
 '<p>Diberitahukan kepada seluruh siswa dan orang tua bahwa libur semester genap akan dimulai pada tanggal 20 Juni 2026.</p>', 'publish', NOW()),
(2, 'Jadwal Ujian Tengah Semester Ganjil', 'jadwal-ujian-tengah-semester-ganjil',
 'UTS akan dilaksanakan mulai minggu ketiga September.',
 '<p>Ujian Tengah Semester Ganjil akan dilaksanakan mulai minggu ketiga bulan September 2026. Jadwal lengkap dapat dilihat di papan pengumuman sekolah.</p>', 'publish', NOW());

INSERT INTO agenda (judul, tanggal_mulai, tanggal_selesai, lokasi, deskripsi) VALUES
('Masa Orientasi Siswa Baru', DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(NOW(), INTERVAL 9 DAY), 'Aula TK IT Quantum School', 'Kegiatan pengenalan lingkungan sekolah bagi siswa baru.'),
('Rapat Orang Tua Wali Murid', DATE_ADD(NOW(), INTERVAL 14 DAY), NULL, 'Aula TK IT Quantum School', 'Pembahasan program semester baru bersama wali murid.'),
('Pentas Seni Akhir Tahun', DATE_ADD(NOW(), INTERVAL 30 DAY), NULL, 'Lapangan Sekolah', 'Pentas seni menampilkan kreativitas siswa di berbagai bidang.');

INSERT INTO galeri_kategori (nama_kategori) VALUES ('Kegiatan Belajar'), ('Ekstrakurikuler'), ('Fasilitas Sekolah'), ('Acara Sekolah');

INSERT INTO guru (nip, nama_lengkap, mata_pelajaran, pendidikan_terakhir, jabatan, email, no_hp, riwayat_singkat, status) VALUES
('198501012010011001', 'Dr. Budi Santoso, M.Pd.', 'Matematika', 'S3 Pendidikan Matematika', 'Kepala Sekolah', 'budi.santoso@tkitquantum.sch.id', '081234567001', 'Berpengalaman mengajar matematika lebih dari 15 tahun.', 'aktif'),
('198703152011012002', 'Siti Rahmawati, S.Pd., M.Pd.', 'Bahasa Indonesia', 'S2 Pendidikan Bahasa Indonesia', 'Wakil Kepala Sekolah Kurikulum', 'siti.rahmawati@tkitquantum.sch.id', '081234567002', 'Aktif dalam pengembangan kurikulum sekolah.', 'aktif'),
('199002202012011003', 'Ahmad Fauzi, S.Kom.', 'Informatika', 'S1 Ilmu Komputer', 'Guru Mapel', 'ahmad.fauzi@tkitquantum.sch.id', '081234567003', 'Fokus pada pengembangan literasi digital siswa.', 'aktif'),
('199105102013012004', 'Dewi Lestari, S.Pd.', 'Bahasa Inggris', 'S1 Pendidikan Bahasa Inggris', 'Wali Kelas', 'dewi.lestari@tkitquantum.sch.id', '081234567004', 'Pembina klub debat bahasa Inggris.', 'aktif'),
('198812252014011005', 'Rizky Pratama, S.Pd.', 'Pendidikan Jasmani', 'S1 Pendidikan Olahraga', 'Guru Mapel', 'rizky.pratama@tkitquantum.sch.id', '081234567005', 'Pelatih tim futsal sekolah.', 'aktif'),
('199303182015012006', 'Nurul Hidayah, S.Si.', 'IPA', 'S1 Pendidikan Fisika', 'Guru Mapel', 'nurul.hidayah@tkitquantum.sch.id', '081234567006', 'Aktif membina tim olimpiade sains.', 'aktif');

INSERT INTO tenaga_kependidikan (nip, nama_lengkap, jabatan, pendidikan_terakhir, email, no_hp, deskripsi_singkat, status) VALUES
('199408102016012007', 'Hendra Wijaya, S.E.', 'Kepala Tata Usaha', 'S1 Ekonomi', 'hendra.wijaya@tkitquantum.sch.id', '081234567007', 'Mengoordinasikan seluruh administrasi sekolah.', 'aktif'),
('199502142017012008', 'Yuni Kartika, A.Md.', 'Bendahara', 'D3 Akuntansi', 'yuni.kartika@tkitquantum.sch.id', '081234567008', 'Mengelola keuangan sekolah secara transparan.', 'aktif'),
('199611052018011009', 'Doni Setiawan', 'Operator Sekolah', 'SMA/SMK', 'doni.setiawan@tkitquantum.sch.id', '081234567009', 'Mengelola data pokok pendidikan (Dapodik).', 'aktif'),
('199709222019012010', 'Lina Marlina, S.I.Pust.', 'Pustakawan', 'S1 Ilmu Perpustakaan', 'lina.marlina@tkitquantum.sch.id', '081234567010', 'Mengelola perpustakaan dan program literasi.', 'aktif'),
('199801302020011011', 'Agus Salim', 'Satpam', 'SMA/SMK', NULL, '081234567011', 'Bertanggung jawab atas keamanan lingkungan sekolah.', 'aktif'),
('199912082021012012', 'Sri Wahyuni', 'Petugas Kebersihan', 'SMP', NULL, '081234567012', 'Menjaga kebersihan dan kenyamanan lingkungan sekolah.', 'aktif');

INSERT INTO struktur_organisasi (nama, jabatan, urutan, parent_id) VALUES
('Dr. Budi Santoso, M.Pd.', 'Kepala Sekolah', 1, NULL);
INSERT INTO struktur_organisasi (nama, jabatan, urutan, parent_id) VALUES
('Siti Rahmawati, S.Pd., M.Pd.', 'Wakil Kepala Sekolah Kurikulum', 2, 1),
('Hendra Wijaya, S.E.', 'Kepala Tata Usaha', 3, 1);
INSERT INTO struktur_organisasi (nama, jabatan, urutan, parent_id) VALUES
('Yuni Kartika, A.Md.', 'Bendahara', 4, 3),
('Doni Setiawan', 'Operator Sekolah', 5, 3);

INSERT INTO prestasi (judul, kategori, tingkat, tahun, deskripsi) VALUES
('Juara 1 Olimpiade Sains Nasional Bidang Fisika', 'akademik', 'Nasional', 2025, 'Diraih oleh tim siswa kelas XI dalam ajang OSN tingkat nasional.'),
('Juara 2 Lomba Cerdas Cermat Tingkat Provinsi', 'akademik', 'Provinsi', 2025, 'Tim CCA TK IT Quantum School meraih juara 2 dari 30 sekolah peserta.'),
('Juara 1 Futsal Antar Pelajar Se-Kota', 'non-akademik', 'Kota', 2026, 'Tim futsal sekolah menjadi juara dalam turnamen antar pelajar se-kota.'),
('Juara 3 Lomba Debat Bahasa Inggris', 'non-akademik', 'Provinsi', 2026, 'Tim debat bahasa Inggris meraih peringkat 3 tingkat provinsi.');

INSERT INTO ekstrakurikuler (nama, pembina, deskripsi, jadwal) VALUES
('Pramuka', 'Rizky Pratama, S.Pd.', 'Kegiatan kepanduan untuk melatih kemandirian dan kepemimpinan siswa.', 'Setiap Jumat, 14.00 - 16.00'),
('Futsal', 'Rizky Pratama, S.Pd.', 'Ekstrakurikuler olahraga futsal untuk siswa berprestasi di bidang olahraga.', 'Setiap Sabtu, 08.00 - 10.00'),
('Klub Debat Bahasa Inggris', 'Dewi Lestari, S.Pd.', 'Melatih kemampuan berbicara dan berargumentasi dalam bahasa Inggris.', 'Setiap Rabu, 15.00 - 17.00'),
('Klub Robotik', 'Ahmad Fauzi, S.Kom.', 'Mengembangkan minat siswa di bidang teknologi dan robotika.', 'Setiap Kamis, 15.00 - 17.00');

INSERT INTO fasilitas (nama, deskripsi) VALUES
('Laboratorium Komputer', 'Dilengkapi 40 unit komputer dengan koneksi internet untuk mendukung pembelajaran berbasis teknologi.'),
('Perpustakaan Digital', 'Menyediakan koleksi buku fisik dan digital yang dapat diakses siswa kapan saja.'),
('Lapangan Olahraga', 'Lapangan multifungsi untuk kegiatan futsal, basket, dan upacara.'),
('Ruang Multimedia', 'Ruang kelas dilengkapi proyektor dan sound system untuk pembelajaran interaktif.');

INSERT INTO testimoni_alumni (nama, angkatan, profesi, isi_testimoni, status) VALUES
('Farhan Ramadhan', '2019', 'Mahasiswa Teknik Informatika', 'TK IT Quantum School membentuk dasar berpikir kritis saya sejak SMA. Guru-gurunya sangat suportif.', 'publish'),
('Amanda Putri', '2020', 'Karyawan BUMN', 'Lingkungan belajar yang nyaman dan fasilitas lengkap membuat saya siap menghadapi dunia kerja.', 'publish'),
('Rian Saputra', '2021', 'Wirausahawan', 'Ekstrakurikuler di TK IT Quantum School mengajarkan saya jiwa kepemimpinan yang terpakai sampai sekarang.', 'publish');

INSERT INTO kalender_akademik (judul, tanggal_mulai, tanggal_selesai, keterangan) VALUES
('Masa Orientasi Siswa Baru', '2026-07-14', '2026-07-16', 'Pengenalan lingkungan sekolah bagi siswa baru.'),
('Ujian Tengah Semester Ganjil', '2026-09-21', '2026-09-25', 'Pelaksanaan UTS untuk seluruh jenjang kelas.'),
('Ujian Akhir Semester Ganjil', '2026-12-07', '2026-12-11', 'Pelaksanaan UAS untuk seluruh jenjang kelas.'),
('Libur Semester Ganjil', '2026-12-19', '2027-01-02', 'Libur akhir semester ganjil tahun ajaran 2026/2027.');

INSERT INTO mata_pelajaran (nama_mapel, kode, deskripsi) VALUES
('Matematika', 'MTK', 'Mata pelajaran wajib yang mencakup aljabar, geometri, dan statistika dasar.'),
('Bahasa Indonesia', 'BIN', 'Mata pelajaran wajib untuk mengembangkan kemampuan berbahasa dan bersastra.'),
('Bahasa Inggris', 'BIG', 'Mata pelajaran wajib untuk kemampuan komunikasi bahasa asing.'),
('Ilmu Pengetahuan Alam', 'IPA', 'Mencakup Fisika, Kimia, dan Biologi dasar.'),
('Ilmu Pengetahuan Sosial', 'IPS', 'Mencakup Sejarah, Geografi, dan Ekonomi dasar.'),
('Informatika', 'INF', 'Mata pelajaran literasi digital dan dasar-dasar pemrograman.'),
('Pendidikan Jasmani, Olahraga, dan Kesehatan', 'PJOK', 'Mata pelajaran untuk kebugaran jasmani dan pola hidup sehat.');

-- ============================
-- 8. UPDATE TAMBAHAN (fitur galeri video multi-platform, PPDB banner & promo, kategori lingkungan)
-- ============================
ALTER TABLE galeri_video
  ADD COLUMN platform VARCHAR(30) NOT NULL DEFAULT 'YouTube' AFTER url_youtube;

ALTER TABLE ppdb_pengaturan
  ADD COLUMN banner VARCHAR(255) NULL AFTER biaya_pendaftaran,
  ADD COLUMN promo_nama VARCHAR(150) NULL AFTER banner,
  ADD COLUMN promo_potongan DECIMAL(10,2) NULL DEFAULT 0 AFTER promo_nama,
  ADD COLUMN promo_mulai DATE NULL AFTER promo_potongan,
  ADD COLUMN promo_selesai DATE NULL AFTER promo_mulai;

INSERT INTO galeri_kategori (nama_kategori) VALUES ('Lingkungan Sekolah');

-- ============================
-- 9. HERO SLIDE (banner landing page yang bisa diunggah admin)
-- ============================
CREATE TABLE hero_slide (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gambar VARCHAR(255) NOT NULL,
    judul VARCHAR(200) NULL,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================
-- 10. PESAN KONTAK (form tanya-jawab dari orang tua/pengunjung)
-- ============================
CREATE TABLE pesan_kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    no_hp VARCHAR(30) NULL,
    pesan TEXT NOT NULL,
    status ENUM('baru','dibaca') DEFAULT 'baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================
-- 11. KONTEN ISLAMI BERJALAN (running text: ayat/hadits/kata mutiara di beranda)
-- ============================
CREATE TABLE konten_islami (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teks VARCHAR(500) NOT NULL,
    sumber VARCHAR(150) NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO konten_islami (teks, sumber, status, urutan) VALUES
('Sebaik-baik kalian adalah yang paling baik akhlaknya.', 'HR. Bukhari', 'aktif', 1),
('Didiklah anak-anakmu karena mereka diciptakan untuk zaman yang berbeda denganmu.', 'Umar bin Khattab', 'aktif', 2),
('Rabbi zidni ilma — Ya Tuhanku, tambahkanlah ilmu kepadaku.', 'QS. Thaha: 114', 'aktif', 3);

-- ============================
-- 12. FOTO SEJARAH SEKOLAH (ditampilkan di samping teks Sejarah pada halaman Profil)
-- ============================
ALTER TABLE profil_sekolah
  ADD COLUMN foto_sejarah VARCHAR(255) NULL AFTER sejarah;

-- ============================
-- 13. THUMBNAIL VIDEO (untuk video dari platform selain YouTube yang tidak auto-embed)
-- ============================
ALTER TABLE galeri_video
  ADD COLUMN thumbnail VARCHAR(255) NULL AFTER platform;

-- ============================
-- 14. TOGGLE TAMPIL DI BERANDA + TEKS CTA PROMOSI PPDB
-- ============================
ALTER TABLE ppdb_pengaturan
  ADD COLUMN tampil_beranda ENUM('ya','tidak') NOT NULL DEFAULT 'ya',
  ADD COLUMN cta_judul VARCHAR(200) NULL,
  ADD COLUMN cta_subjudul VARCHAR(255) NULL;

-- ============================
-- 15. MUSIK LATAR (diputar otomatis saat website dibuka, diatur admin)
-- ============================
ALTER TABLE pengaturan_website
  ADD COLUMN musik_latar VARCHAR(255) NULL,
  ADD COLUMN musik_aktif ENUM('ya','tidak') NOT NULL DEFAULT 'tidak';

-- ============================
-- 16. GALERI FOTO TAMBAHAN PER BERITA (jadi slide hero jika >1 foto)
-- ============================
CREATE TABLE berita_galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    berita_id INT NOT NULL,
    file VARCHAR(255) NOT NULL,
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (berita_id) REFERENCES berita(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================
-- 17. PERMAINAN (mini game anak-anak + pengaturan tampil/hide + leaderboard)
-- ============================
CREATE TABLE permainan_pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tampil_menu ENUM('ya','tidak') NOT NULL DEFAULT 'ya'
) ENGINE=InnoDB;
INSERT INTO permainan_pengaturan (tampil_menu) VALUES ('ya');

CREATE TABLE permainan_game (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    ikon VARCHAR(10) NULL,
    deskripsi VARCHAR(255) NULL,
    status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    urutan INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO permainan_game (slug, nama, ikon, deskripsi, status, urutan) VALUES
('cocok-kartu', 'Cocokkan Kartunya', '🐬', 'Latih ingatan dengan mencocokkan kartu bergambar', 'aktif', 1),
('tangkap-bintang', 'Tangkap Bintang', '⭐', 'Klik bintang secepat mungkin sebelum menghilang', 'aktif', 2),
('puzzle-angka', 'Puzzle Angka', '🔢', 'Susun ulang angka 1-8 dengan langkah sesedikit mungkin', 'aktif', 3);

CREATE TABLE permainan_skor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_slug VARCHAR(50) NOT NULL,
    nama_pemain VARCHAR(50) NOT NULL,
    skor INT NOT NULL DEFAULT 0,
    detail VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================
-- 18. TAMBAHAN 6 GAME BARU DI ZONA PERMAINAN
-- ============================
INSERT INTO permainan_game (slug, nama, ikon, deskripsi, status, urutan) VALUES
('tebak-angka', 'Tebak Angka', '🎯', 'Tebak angka rahasia 1-100 dengan petunjuk lebih besar/kecil', 'aktif', 4),
('ketuk-warna', 'Ketuk Warna', '🎨', 'Ingat & ulangi urutan warna yang menyala, makin lama makin panjang!', 'aktif', 5),
('hitung-cepat', 'Hitung Cepat', '➕', 'Jawab soal tambah & kurang sebanyak mungkin dalam 60 detik', 'aktif', 6),
('tebak-kata', 'Tebak Kata', '🔤', 'Tebak huruf untuk menemukan kata tersembunyi', 'aktif', 7),
('uji-reaksi', 'Uji Reaksi', '⚡', 'Klik secepat mungkin begitu warna berubah hijau', 'aktif', 8),
('balap-ketik', 'Balap Ketik', '⌨️', 'Ketik kata yang muncul secepat & setepat mungkin dalam 30 detik', 'aktif', 9);

-- ============================
-- 19. MUSIK LATAR KHUSUS GAME (berlaku untuk semua game di Zona Permainan)
-- ============================
ALTER TABLE permainan_pengaturan
  ADD COLUMN musik_game VARCHAR(255) NULL;

UPDATE permainan_game SET ikon = '🎯' WHERE slug = 'tebak-angka';

-- ============================
-- 20. TAMBAHAN 6 GAME INTERAKTIF BARU
-- ============================
INSERT INTO permainan_game (slug, nama, ikon, deskripsi, status, urutan) VALUES
('tebak-silang', 'Tebak Silang', '❌', 'Main tic-tac-toe (X-O) melawan komputer', 'aktif', 10),
('tebak-emoji', 'Tebak Emoji', '😄', 'Tebak kata dari rangkaian emoji yang ditampilkan', 'aktif', 11),
('klik-bentuk', 'Klik Bentuk Sama', '🔺', 'Klik bentuk yang sesuai target secepat mungkin', 'aktif', 12),
('labirin-ceria', 'Labirin Ceria', '🧩', 'Bantu karakter menemukan jalan keluar labirin', 'aktif', 13),
('piano-ceria', 'Piano Ceria', '🎹', 'Ingat & mainkan ulang nada yang berbunyi', 'aktif', 14),
('lompat-kodok', 'Lompat Kodok', '🐸', 'Klik tepat saat penunjuk masuk area hijau', 'aktif', 15);
