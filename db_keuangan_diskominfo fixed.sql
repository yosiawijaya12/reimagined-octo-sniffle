-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 08:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_keuangan_diskominfo`
--

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `tahun` year(4) NOT NULL,
  `satuan_kerja` enum('ikp','tki','sekretariat') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `nama_kegiatan`, `tahun`, `satuan_kerja`, `created_at`, `updated_at`) VALUES
(1, 'Pengelolaan informasi dan Komunikasi Publik Pemerintah Daerah Kabupaten Kota', '2025', 'ikp', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(2, 'Pengelolaan E-government Di Lingkup Pemerintah Daerah Kabupaten Kota', '2025', 'tki', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(3, 'Perencanaan, Penganggaran, Dan Evaluasi Kinerja Perangkat Daerah', '2025', 'sekretariat', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(4, 'Administrasi Keuangan Perangkat Daerah', '2025', 'sekretariat', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(5, 'Administrasi Umum Perangkat Daerah', '2025', 'sekretariat', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(6, 'Penyediaan Jasa Penunjang Urusan Pemerintahan Daerah', '2025', 'sekretariat', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(7, 'Pemeliharaan Barang Milik Daerah Penunjang Urusan Pemerintahan Daerah', '2025', 'sekretariat', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(8, 'Penyelenggaraan Statistik Sektoral Di Lingkup Daerah Kabupaten Kota', '2025', 'ikp', '2025-05-06 02:39:31', '2025-06-11 01:48:57'),
(9, 'Penyelenggaraan Persandian Untuk Pengamanan Informasi Pemerintah Daerah Kabupaten Kota', '2025', 'tki', '2025-05-06 02:39:31', '2025-06-11 01:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` int(11) NOT NULL,
  `type` enum('pelaporan masuk','perlu revisi') NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `status` enum('terkirim','dibaca') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pagu_anggaran`
--

CREATE TABLE `pagu_anggaran` (
  `id` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `total_pagu` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelaporan`
--

CREATE TABLE `pelaporan` (
  `id` int(11) NOT NULL,
  `pptk_id` bigint(20) UNSIGNED NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `subkegiatan_id` int(11) NOT NULL,
  `jenis_belanja` enum('SPJ GU','SPJ GU Tunai','Belanja Tenaga Ahli','Belanja Jasa THL','LS') NOT NULL,
  `rekening_kegiatan` varchar(100) NOT NULL,
  `periode` year(4) NOT NULL,
  `nominal_pagu` decimal(15,2) NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `status` enum('Diajukan','Perlu Revisi','Disetujui Verifikator','Disetujui Bendahara','Disetujui Kepala Dinas') DEFAULT 'Diajukan',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelaporan`
--

INSERT INTO `pelaporan` (`id`, `pptk_id`, `kegiatan_id`, `subkegiatan_id`, `jenis_belanja`, `rekening_kegiatan`, `periode`, `nominal_pagu`, `nominal`, `status`, `file_path`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, 'SPJ GU Tunai', '2.16.02.2.01.0024', '2025', 5000000.00, 4500000.00, 'Diajukan', 'pelaporan_files/k2YfRSPpeuySFIyANbcRDXQrFu9uBRG5xJTN8BUc.pdf', NULL, '2025-05-08 19:18:03', '2025-05-14 21:08:06'),
(2, 2, 1, 2, 'SPJ GU Tunai', '2.16.02.2.01.0014', '2025', 458805000.00, 458805000.00, 'Diajukan', 'pelaporan_files/A7aOWGJ9JvBuocrEHgCUzGtXL0MXeLdVfNIpfB8i.pdf', NULL, '2025-05-14 19:37:59', '2025-05-14 19:37:59'),
(5, 3, 2, 10, 'SPJ GU Tunai', '2.16.03.2.02.0023', '2025', 2167070000.00, 2167070000.00, 'Disetujui Kepala Dinas', 'pelaporan_files/fSmmuGDz7TDqDqk3qN4rPb2O2QoQ9vQD7tTpsO5A.pdf', '-', '2025-06-10 22:40:57', '2025-06-10 22:43:06'),
(6, 3, 2, 10, 'SPJ GU Tunai', '2.16.03.2.02.0023', '2025', 2167070000.00, 2167070000.00, 'Diajukan', 'pelaporan_files/TZmUUNVv5z1Ceh1v9eL0Hj7pG9xT7vRCuspkJsu0.pdf', '-', '2025-06-10 22:40:57', '2025-06-10 22:40:57'),
(7, 4, 3, 15, 'SPJ GU', '2.16.01.2.01.0001', '2025', 14820700.00, 14820700.00, 'Diajukan', 'pelaporan_files/t6qQBJ5xIreppUotJkPA7UIyTKpAVbsWzBUoN5Xc.pdf', '-', '2025-06-10 22:44:06', '2025-06-10 22:44:06'),
(8, 4, 3, 15, 'SPJ GU', '2.16.01.2.01.0001', '2025', 14820700.00, 14820700.00, 'Diajukan', 'pelaporan_files/hZqhiYv76fW5jcB0JbItyBKowoLiNyPFquCGpFhy.pdf', '-', '2025-06-10 22:44:06', '2025-06-10 22:44:06'),
(9, 4, 4, 18, 'SPJ GU', '2.16.01.2.02.0001', '2025', 5897887080.00, 5897887080.00, 'Diajukan', 'pelaporan_files/ocp0lquCVEO7V1x5DbN0UT6hpx6Tqi7et1uK0WEQ.pdf', '-', '2025-06-10 22:56:21', '2025-06-10 22:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `pengesahan_kepala_dinas`
--

CREATE TABLE `pengesahan_kepala_dinas` (
  `id` int(11) NOT NULL,
  `dpa_skpd_id` int(11) DEFAULT NULL,
  `kepala_dinas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_pengesahan` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('Disahkan','Revisi') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `persetujuan_bendahara`
--

CREATE TABLE `persetujuan_bendahara` (
  `id` int(11) NOT NULL,
  `dpa_skpd_id` int(11) NOT NULL,
  `bendahara_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_persetujuan` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('Disetujui','Revisi') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('18PtliJCQQJvMiJJlqkXdelXmCIYQayriLxLWBWy', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUWV6a1NHQ21rY3p3NWlKZ0laOTlVa2dCOUJ4d3BiSjlHYm1KUHlBOCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjkzOiJodHRwOi8vZmluYW5jaWFsX3JlcG9ydF9hc3Npc3RhbmNlLnRlc3QvcGVsYXBvcmFuL2RhZnRhcj9zb3J0PWNyZWF0ZWRfZGVzYyZzdGF0dXM9JnRhaHVuPTIwMjUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1749621572),
('3mAtLJK19k9GYBxfYJm93HtWRIUegffMT7DMA7Il', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidzNtbnNjTjZyUTJkZVlpUnRhaUplc3lZVDRjZE1ZT1NZZDhtOUEzVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9maW5hbmNpYWxfcmVwb3J0X2Fzc2lzdGFuY2UudGVzdC8/aGVyZD1wcmV2aWV3Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1749614687),
('4gXb2w9bySahrHDR9k1flwFRtGWdSOmqNY6ddGZH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.20.2 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3FaVWNIaGdNVVFvOFd4bGJMUHFGc1RWNXh3eGtObml6b09hNkgzSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9maW5hbmNpYWxfcmVwb3J0X2Fzc2lzdGFuY2UudGVzdC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1749614687);

-- --------------------------------------------------------

--
-- Table structure for table `subkegiatan`
--

CREATE TABLE `subkegiatan` (
  `id` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `nama_subkegiatan` varchar(255) DEFAULT NULL,
  `tahun_anggaran` year(4) DEFAULT NULL,
  `rekening` varchar(255) NOT NULL,
  `jumlah_pagu` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subkegiatan`
--

INSERT INTO `subkegiatan` (`id`, `id_kegiatan`, `nama_subkegiatan`, `tahun_anggaran`, `rekening`, `jumlah_pagu`, `created_at`, `updated_at`) VALUES
(1, 1, 'Penguatan Kapasitas Sumber Daya Manusia Komunikasi Publik', '2025', '2.16.02.2.01.0024', 5000000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(2, 1, 'Relasi Media', '2025', '2.16.02.2.01.0014 ', 458805000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(3, 1, 'Kemitraan Komunikasi dengan Komunitas Informasi Masyarakat', '2025', '2.16.02.2.01.0015', 249450000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(4, 1, 'Monitoring Informasi Kebijakan, Opini, dan Aspirasi Publik', '2025', '2.16.02.2.01.0019', 55000000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(5, 1, 'Pelayanan Informasi Publik', '2025', '2.16.02.2.01.0017', 85000000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(6, 1, 'Diseminasi Informasi', '2025', '2.16.02.2.01.0020', 307600000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(7, 1, 'Pengelolaan Media Komunikasi Publik', '2025', '2.16.02.2.01.0021', 446155000.00, '2025-05-06 03:02:37', '2025-06-11 02:18:34'),
(8, 1, 'Penyusunan Konten', '2025', '2.16.02.2.01.0023', 151602800.00, '2025-05-06 03:09:16', '2025-06-11 02:18:34'),
(9, 2, 'Fasilitasi penyelenggaraan SPBE di lingkungan Pemda', '2025', '2.16.03.2.02.0015', 26517800.00, '2025-05-06 03:24:05', '2025-06-11 02:18:34'),
(10, 2, 'Koordinasi dan Fasilitasi Penyelenggaraan Kabupaten atau Kota Cerdas', '2025', '2.16.03.2.02.0023', 2167070000.00, '2025-05-06 03:24:05', '2025-06-11 02:18:34'),
(11, 2, 'Koordinasi pelaksanaan Manajemen SPBE', '2025', '2.16.03.2.02.0019', 22989600.00, '2025-05-06 03:24:05', '2025-06-11 02:18:34'),
(12, 2, 'Penyelenggaraan Jaringan Intra Pemerintah Daerah Kab/Kota', '2025', '2.16.03.2.02.0024', 1471800000.00, '2025-05-06 03:24:05', '2025-06-11 02:18:34'),
(13, 2, 'Koordinasi Pemanfaatan Pusat Data Nasional', '2025', '2.16.03.2.02.0013', 280000000.00, '2025-05-06 03:24:05', '2025-06-11 02:18:34'),
(14, 2, 'Penyelenggaraan Sistem Penghubung Layanan Pemerintah Daerah', '2025', '2.16.03.2.02.0021', 120000000.00, '2025-05-06 03:24:05', '2025-06-11 02:18:34'),
(15, 3, 'Penyusunan Dokumen Perencanaan Perangkat Daerah', '2025', '2.16.01.2.01.0001', 14820700.00, '2025-05-06 03:28:50', '2025-06-11 02:18:34'),
(16, 3, 'Koordinasi dan Penyusunan Laporan Capaian Kinerja dan Ikhtisar Realisasi Kinerja SKPD', '2025', '2.16.01.2.01.0006', 17117500.00, '2025-05-06 03:28:50', '2025-06-11 02:18:34'),
(17, 3, 'Evaluasi Kinerja Perangkat Daerah', '2025', '2.16.01.2.01.0007', 38899250.00, '2025-05-06 03:28:50', '2025-06-11 02:18:34'),
(18, 4, 'Penyediaan Gaji dan Tunjangan ASN', '2025', '2.16.01.2.02.0001', 5897887080.00, '2025-05-06 03:33:06', '2025-06-11 02:18:34'),
(19, 4, 'Penyediaan Administrasi Pelaksanaan Tugas ASN', '2025', '2.16.01.2.02.0002', 65322650.00, '2025-05-06 03:33:06', '2025-06-11 02:18:34'),
(20, 4, 'Koordinasi dan Penyusunan Laporan Keuangan Bulanan/ Triwulanan/ Semesteran SKPD', '2025', '2.16.01.2.02.0007', 88093300.00, '2025-05-06 03:33:06', '2025-06-11 02:18:34'),
(21, 5, 'Penyediaan Komponen Instalasi Listrik/Penerangan Bangunan Kantor', '2025', '2.16.01.2.06.0001', 6144000.00, '2025-05-06 04:19:00', '2025-06-11 02:18:34'),
(22, 5, 'Penyediaan Peralatan dan Perlengkapan Kantor', '2025', '2.16.01.2.06.0002', 231475000.00, '2025-05-06 04:19:00', '2025-06-11 02:18:34'),
(23, 5, 'Penyediaan Peralatan Rumah Tangga', '2025', '2.16.01.2.06.0003', 18106957.00, '2025-05-06 04:19:00', '2025-06-11 02:18:34'),
(24, 5, 'Penyediaan Barang Cetakan dan Penggandaan', '2025', '2.16.01.2.06.0005', 55366800.00, '2025-05-06 04:19:00', '2025-06-11 02:18:34'),
(25, 5, 'Penyediaan Bahan Bacaan dan Peraturan Perundang-undangan', '2025', '2.16.01.2.06.0006', 25990000.00, '2025-05-06 04:19:00', '2025-06-11 02:18:34'),
(26, 5, 'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD', '2025', '2.16.01.2.06.0009', 201045400.00, '2025-05-06 04:19:00', '2025-06-11 02:18:34'),
(27, 6, 'Penyediaan Jasa Surat Menyurat', '2025', '2.16.01.2.08.0001', 5160100.00, '2025-05-06 04:20:31', '2025-06-11 02:18:34'),
(28, 6, 'Penyediaan Jasa Peralatan dan Perlengkapan Kantor', '2025', '2.16.01.2.08.0003', 17050000.00, '2025-05-06 04:21:34', '2025-06-11 02:18:34'),
(29, 7, 'Penyediaan Jasa Pemeliharaan, Biaya Pemeliharaan, dan Pajak Kendaraan Perorangan Dinas atau Kendaraan Dinas Jabatan', '2025', '2.16.01.2.09.0001', 245514600.00, '2025-05-06 04:23:16', '2025-06-11 02:18:34'),
(30, 8, 'Pengingkatan Kapasitas Kelembagaan Statistik Sektoral', '2025', '2.20.02.2.01.0007', 49774000.00, '2025-05-06 04:24:24', '2025-06-11 02:18:34'),
(31, 9, 'Pelaksanaan Keamanan Informasi Pemerintahan Daerah Kabupaten/Kota Berbasis Elektronik dan Non Elektronik', '2025', '2.21.02.2.01.0003', 190000000.00, '2025-05-06 04:26:07', '2025-06-11 02:18:34'),
(32, 1, 'mancing', '2025', '2025.1.1.1', 5000000.00, '2025-06-10 19:18:52', '2025-06-10 19:18:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','ikp','tki','sekretariat','verifikator','bendahara','kepala_dinas') NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('green flag','red flag') NOT NULL DEFAULT 'green flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', NULL, '$2y$12$NQNcx6TMpDiMbXI2VjgS7ezA0RMiRvQT3ShQF3ImnxETdAd3rGeB2', NULL, NULL, NULL, 'green flag'),
(2, 'ikp', 'ikp@gmail.com', 'ikp', NULL, '$2y$12$lD1b4oZsYYJ5b7Rr.zcO2exADS.2ODiFTIy15kgg1OCR9V69xoNKO', NULL, NULL, NULL, 'green flag'),
(3, 'tki', 'tki@gmail.com', 'tki', NULL, '$2y$12$FUfPq/fOW.fmQYw3V9jUkuRfUDRws42VbW64qssN1Iidt5hNbcUfW', NULL, NULL, NULL, 'green flag'),
(4, 'sekretariat', 'sekretariat@gmail.com', 'sekretariat', NULL, '$2y$12$Upwqvn34oLbzjhewIajLbOfzVOHfM./M44aNXyWUtgasbNWAQNfD.', NULL, NULL, NULL, 'green flag'),
(5, 'verivikator', 'verivikator@gmail.com', 'verifikator', NULL, '$2y$12$JqfK./XMxRCnJJr9W0r7OesiEKr4UsGU6uCpyWSVBBaclZsGetH2.', NULL, NULL, NULL, 'green flag'),
(6, 'bendahara', 'bendahara@gmail.com', 'bendahara', NULL, '$2y$12$fJLIa8Th7YM3hNLvx3cH.urh6ic922g1aIHyfC9DU3eFGlHDrlNe6', NULL, NULL, NULL, 'green flag'),
(7, 'kepala dinas', 'kepala@gmail.com', 'kepala_dinas', NULL, '$2y$12$ak7QJQ3h3/K7D1FKqIFqQuB.tAUBhPGPiuIava5gbgIdjKrgs9mY6', NULL, NULL, NULL, 'green flag'),
(8, 'Yosia', 'yosiawijaya12@gmail.com', 'kepala_dinas', NULL, '$2y$12$bbRLTklNOmV7X15p9aPO.OtdYyTf8B.hjZm6oisl0rnwm5HmIZFaq', NULL, '2025-06-10 18:50:13', '2025-06-10 18:50:13', 'green flag');

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_laporan`
--

CREATE TABLE `verifikasi_laporan` (
  `id` int(11) NOT NULL,
  `dpa_skpd_id` int(11) NOT NULL,
  `verifikator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_verifikasi` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('Disetujui','Revisi') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verifikasi_laporan`
--

INSERT INTO `verifikasi_laporan` (`id`, `dpa_skpd_id`, `verifikator_id`, `tanggal_verifikasi`, `catatan`, `status`) VALUES
(1, 5, 5, '2025-06-11', '-', 'Disetujui'),
(2, 5, 6, '2025-06-11', '-', 'Disetujui'),
(3, 5, 7, '2025-06-11', '-', 'Disetujui');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`laporan_id`),
  ADD KEY `laporan_id` (`laporan_id`);

--
-- Indexes for table `pagu_anggaran`
--
ALTER TABLE `pagu_anggaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tahun` (`tahun`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pelaporan`
--
ALTER TABLE `pelaporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subkegiatan_id` (`subkegiatan_id`),
  ADD KEY `pptk_id` (`pptk_id`) USING BTREE,
  ADD KEY `kegiatan_id` (`kegiatan_id`);

--
-- Indexes for table `pengesahan_kepala_dinas`
--
ALTER TABLE `pengesahan_kepala_dinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kepala_dinas_id` (`kepala_dinas_id`),
  ADD KEY `pengesahan_kepala_dinas_id` (`dpa_skpd_id`);

--
-- Indexes for table `persetujuan_bendahara`
--
ALTER TABLE `persetujuan_bendahara`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bendahara_id` (`bendahara_id`),
  ADD KEY `bend_id_DPA_SKPD` (`dpa_skpd_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subkegiatan`
--
ALTER TABLE `subkegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kegiatan` (`id_kegiatan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `verifikasi_laporan`
--
ALTER TABLE `verifikasi_laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verifikator_id` (`verifikator_id`),
  ADD KEY `id_DPA_SKPD` (`dpa_skpd_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagu_anggaran`
--
ALTER TABLE `pagu_anggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelaporan`
--
ALTER TABLE `pelaporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pengesahan_kepala_dinas`
--
ALTER TABLE `pengesahan_kepala_dinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `persetujuan_bendahara`
--
ALTER TABLE `persetujuan_bendahara`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subkegiatan`
--
ALTER TABLE `subkegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `verifikasi_laporan`
--
ALTER TABLE `verifikasi_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `laporan_id` FOREIGN KEY (`laporan_id`) REFERENCES `pelaporan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifikasi_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pelaporan`
--
ALTER TABLE `pelaporan`
  ADD CONSTRAINT `kegiatan_id` FOREIGN KEY (`kegiatan_id`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pelaporan_ibfk_2` FOREIGN KEY (`subkegiatan_id`) REFERENCES `subkegiatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pptk_ibfk_2` FOREIGN KEY (`pptk_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengesahan_kepala_dinas`
--
ALTER TABLE `pengesahan_kepala_dinas`
  ADD CONSTRAINT `kepala_dinas_id_ibfk_2` FOREIGN KEY (`kepala_dinas_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pengesahan_kepala_dinas_id` FOREIGN KEY (`dpa_skpd_id`) REFERENCES `pelaporan` (`id`);

--
-- Constraints for table `persetujuan_bendahara`
--
ALTER TABLE `persetujuan_bendahara`
  ADD CONSTRAINT `bend_id_DPA_SKPD` FOREIGN KEY (`dpa_skpd_id`) REFERENCES `pelaporan` (`id`),
  ADD CONSTRAINT `bendahara_id_ibfk_2` FOREIGN KEY (`bendahara_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subkegiatan`
--
ALTER TABLE `subkegiatan`
  ADD CONSTRAINT `id_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `verifikasi_laporan`
--
ALTER TABLE `verifikasi_laporan`
  ADD CONSTRAINT `id_DPA_SKPD` FOREIGN KEY (`dpa_skpd_id`) REFERENCES `pelaporan` (`id`),
  ADD CONSTRAINT `verivikator_id_ibfk_2` FOREIGN KEY (`verifikator_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
