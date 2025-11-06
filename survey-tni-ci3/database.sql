-- Database Survey Kepuasan Pasien TNI
-- CodeIgniter 3 Application

-- ================================
-- Table: users (admin/petugas)
-- ================================
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas') DEFAULT 'petugas',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Table: kategori_pertanyaan
-- ================================
CREATE TABLE `kategori_pertanyaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Table: pertanyaan
-- ================================
CREATE TABLE `pertanyaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) DEFAULT NULL,
  `pertanyaan` text NOT NULL,
  `tipe_jawaban` enum('pilihan_ganda','skala','text','ya_tidak') DEFAULT 'skala',
  `pilihan_jawaban` text DEFAULT NULL COMMENT 'JSON untuk pilihan jawaban',
  `is_wajib` tinyint(1) DEFAULT 1,
  `urutan` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_pertanyaan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Table: responden
-- ================================
CREATE TABLE `responden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_registrasi` varchar(50) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `pangkat` varchar(50) DEFAULT NULL,
  `nrp` varchar(50) DEFAULT NULL,
  `kesatuan` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tanggal_berobat` date DEFAULT NULL,
  `poli` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_no_registrasi` (`no_registrasi`),
  KEY `idx_tanggal_berobat` (`tanggal_berobat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Table: survey
-- ================================
CREATE TABLE `survey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `responden_id` int(11) NOT NULL,
  `tanggal_survey` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `status` enum('draft','selesai') DEFAULT 'selesai',
  `total_nilai` decimal(5,2) DEFAULT NULL,
  `rata_rata` decimal(5,2) DEFAULT NULL,
  `kategori_kepuasan` varchar(50) DEFAULT NULL COMMENT 'Sangat Puas, Puas, Cukup, Kurang',
  `saran` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `responden_id` (`responden_id`),
  KEY `idx_tanggal_survey` (`tanggal_survey`),
  CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`responden_id`) REFERENCES `responden` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Table: jawaban
-- ================================
CREATE TABLE `jawaban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_id` int(11) NOT NULL,
  `pertanyaan_id` int(11) NOT NULL,
  `jawaban_value` text DEFAULT NULL COMMENT 'Nilai jawaban (angka untuk skala, text untuk text)',
  `jawaban_text` text DEFAULT NULL COMMENT 'Text jawaban jika ada',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `survey_id` (`survey_id`),
  KEY `pertanyaan_id` (`pertanyaan_id`),
  CONSTRAINT `jawaban_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_ibfk_2` FOREIGN KEY (`pertanyaan_id`) REFERENCES `pertanyaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Table: import_log
-- ================================
CREATE TABLE `import_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `type` enum('pertanyaan','responden') NOT NULL,
  `total_rows` int(11) DEFAULT 0,
  `success_rows` int(11) DEFAULT 0,
  `failed_rows` int(11) DEFAULT 0,
  `error_log` text DEFAULT NULL,
  `imported_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `imported_by` (`imported_by`),
  CONSTRAINT `import_log_ibfk_1` FOREIGN KEY (`imported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================
-- Insert Data Default
-- ================================

-- Admin default (username: admin, password: admin123)
INSERT INTO `users` (`username`, `password`, `nama`, `email`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@tni.mil.id', 'admin');

-- Kategori Pertanyaan Default
INSERT INTO `kategori_pertanyaan` (`nama_kategori`, `deskripsi`, `urutan`) VALUES
('Pendaftaran', 'Kepuasan terhadap proses pendaftaran', 1),
('Pelayanan Medis', 'Kepuasan terhadap pelayanan dokter dan perawat', 2),
('Farmasi', 'Kepuasan terhadap pelayanan farmasi/apotek', 3),
('Fasilitas', 'Kepuasan terhadap fasilitas rumah sakit', 4),
('Administrasi', 'Kepuasan terhadap pelayanan administrasi', 5);

-- Pertanyaan Default (contoh)
INSERT INTO `pertanyaan` (`kategori_id`, `pertanyaan`, `tipe_jawaban`, `urutan`, `is_wajib`) VALUES
(1, 'Bagaimana kepuasan Anda terhadap kecepatan proses pendaftaran?', 'skala', 1, 1),
(1, 'Apakah petugas pendaftaran melayani dengan ramah?', 'skala', 2, 1),
(2, 'Bagaimana kepuasan Anda terhadap penjelasan dokter mengenai kondisi kesehatan?', 'skala', 3, 1),
(2, 'Apakah dokter memberikan waktu yang cukup untuk konsultasi?', 'skala', 4, 1),
(2, 'Bagaimana keramahan perawat dalam memberikan pelayanan?', 'skala', 5, 1),
(3, 'Bagaimana kecepatan pelayanan di farmasi?', 'skala', 6, 1),
(3, 'Apakah penjelasan tentang obat yang diberikan sudah jelas?', 'skala', 7, 1),
(4, 'Bagaimana kebersihan ruang tunggu dan ruang pemeriksaan?', 'skala', 8, 1),
(4, 'Apakah fasilitas toilet sudah memadai dan bersih?', 'skala', 9, 1),
(5, 'Bagaimana kepuasan Anda terhadap pelayanan administrasi secara keseluruhan?', 'skala', 10, 1);

-- View untuk laporan
CREATE OR REPLACE VIEW `v_laporan_survey` AS
SELECT
    s.id as survey_id,
    s.tanggal_survey,
    r.nama,
    r.pangkat,
    r.nrp,
    r.kesatuan,
    r.jenis_kelamin,
    r.umur,
    r.poli,
    s.total_nilai,
    s.rata_rata,
    s.kategori_kepuasan,
    s.saran,
    COUNT(j.id) as total_jawaban
FROM survey s
LEFT JOIN responden r ON s.responden_id = r.id
LEFT JOIN jawaban j ON s.id = j.survey_id
GROUP BY s.id;

-- View untuk statistik per pertanyaan
CREATE OR REPLACE VIEW `v_statistik_pertanyaan` AS
SELECT
    p.id as pertanyaan_id,
    k.nama_kategori,
    p.pertanyaan,
    COUNT(j.id) as total_jawaban,
    ROUND(AVG(CAST(j.jawaban_value AS DECIMAL(10,2))), 2) as rata_rata_nilai,
    MIN(CAST(j.jawaban_value AS DECIMAL(10,2))) as nilai_min,
    MAX(CAST(j.jawaban_value AS DECIMAL(10,2))) as nilai_max
FROM pertanyaan p
LEFT JOIN kategori_pertanyaan k ON p.kategori_id = k.id
LEFT JOIN jawaban j ON p.id = j.pertanyaan_id
WHERE p.tipe_jawaban = 'skala'
GROUP BY p.id;

-- Indexes untuk performa
CREATE INDEX idx_survey_tanggal ON survey(tanggal_survey);
CREATE INDEX idx_responden_tanggal ON responden(tanggal_berobat);
CREATE INDEX idx_jawaban_survey ON jawaban(survey_id, pertanyaan_id);
