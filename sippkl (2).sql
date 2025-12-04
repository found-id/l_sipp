-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2025 at 04:09 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sippkl`
--

-- --------------------------------------------------------

--
-- Table structure for table `assessment_responses`
--

CREATE TABLE `assessment_responses` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_user_id` bigint UNSIGNED NOT NULL,
  `dosen_user_id` bigint UNSIGNED NOT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_responses`
--

INSERT INTO `assessment_responses` (`id`, `mahasiswa_user_id`, `dosen_user_id`, `is_final`, `created_at`, `updated_at`) VALUES
(10, 62, 1, 1, '2025-11-20 03:28:09', '2025-11-20 03:28:09'),
(11, 62, 5, 1, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(12, 69, 3, 1, '2025-11-20 03:59:17', '2025-11-20 03:59:17'),
(13, 69, 1, 1, '2025-11-20 03:59:54', '2025-11-20 03:59:54'),
(21, 68, 1, 1, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(24, 67, 3, 1, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(26, 112, 3, 1, '2025-12-04 00:58:44', '2025-12-04 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_response_items`
--

CREATE TABLE `assessment_response_items` (
  `id` bigint UNSIGNED NOT NULL,
  `response_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `value_numeric` decimal(5,2) DEFAULT NULL,
  `value_bool` tinyint(1) DEFAULT NULL,
  `value_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_response_items`
--

INSERT INTO `assessment_response_items` (`id`, `response_id`, `item_id`, `value_numeric`, `value_bool`, `value_text`, `created_at`, `updated_at`) VALUES
(55, 10, 27, 97.00, NULL, NULL, '2025-11-20 03:28:09', '2025-11-20 04:21:22'),
(56, 10, 28, 83.00, NULL, NULL, '2025-11-20 03:28:09', '2025-11-20 04:21:22'),
(57, 10, 29, 95.00, NULL, NULL, '2025-11-20 03:28:09', '2025-11-20 04:21:22'),
(58, 10, 30, 89.00, NULL, NULL, '2025-11-20 03:28:09', '2025-11-20 04:21:22'),
(59, 10, 31, 95.00, NULL, NULL, '2025-11-20 03:28:09', '2025-11-20 04:21:22'),
(60, 10, 32, NULL, NULL, NULL, '2025-11-20 03:28:09', '2025-11-20 03:28:09'),
(61, 11, 27, 97.00, NULL, NULL, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(62, 11, 28, 92.00, NULL, NULL, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(63, 11, 29, 95.00, NULL, NULL, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(64, 11, 30, 89.00, NULL, NULL, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(65, 11, 31, 95.00, NULL, NULL, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(66, 11, 32, NULL, NULL, NULL, '2025-11-20 03:49:12', '2025-11-20 03:49:12'),
(67, 12, 27, 97.00, NULL, NULL, '2025-11-20 03:59:17', '2025-11-20 03:59:30'),
(68, 12, 28, 92.00, NULL, NULL, '2025-11-20 03:59:17', '2025-11-20 03:59:30'),
(69, 12, 29, 96.00, NULL, NULL, '2025-11-20 03:59:17', '2025-11-20 03:59:30'),
(70, 12, 30, 95.00, NULL, NULL, '2025-11-20 03:59:17', '2025-11-20 04:00:03'),
(71, 12, 31, 88.00, NULL, NULL, '2025-11-20 03:59:17', '2025-11-20 03:59:23'),
(72, 12, 32, NULL, NULL, NULL, '2025-11-20 03:59:17', '2025-11-20 03:59:17'),
(73, 13, 27, 97.00, NULL, NULL, '2025-11-20 03:59:54', '2025-11-20 03:59:54'),
(74, 13, 28, 73.00, NULL, NULL, '2025-11-20 03:59:54', '2025-11-20 04:19:37'),
(75, 13, 29, 96.00, NULL, NULL, '2025-11-20 03:59:54', '2025-11-20 03:59:54'),
(76, 13, 30, 0.00, NULL, NULL, '2025-11-20 03:59:54', '2025-11-20 03:59:54'),
(77, 13, 31, 88.00, NULL, NULL, '2025-11-20 03:59:54', '2025-11-20 03:59:54'),
(78, 13, 32, NULL, NULL, NULL, '2025-11-20 03:59:54', '2025-11-20 03:59:54'),
(121, 21, 27, 2.00, NULL, NULL, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(122, 21, 28, 2.00, NULL, NULL, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(123, 21, 29, 88.00, NULL, NULL, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(124, 21, 30, 96.00, NULL, NULL, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(125, 21, 31, 100.00, NULL, NULL, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(126, 21, 32, NULL, NULL, NULL, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(139, 24, 27, 100.00, NULL, NULL, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(140, 24, 28, 79.00, NULL, NULL, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(141, 24, 29, 4.00, NULL, NULL, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(142, 24, 30, 78.00, NULL, NULL, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(143, 24, 31, 100.00, NULL, NULL, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(144, 24, 32, NULL, NULL, NULL, '2025-11-20 04:16:45', '2025-11-20 04:16:45'),
(151, 26, 27, 94.00, NULL, NULL, '2025-12-04 00:58:44', '2025-12-04 00:58:44'),
(152, 26, 28, 72.00, NULL, NULL, '2025-12-04 00:58:44', '2025-12-04 00:58:44'),
(153, 26, 29, 82.00, NULL, NULL, '2025-12-04 00:58:44', '2025-12-04 00:58:44'),
(154, 26, 30, 98.00, NULL, NULL, '2025-12-04 00:58:44', '2025-12-04 00:58:44'),
(155, 26, 31, 72.00, NULL, NULL, '2025-12-04 00:58:44', '2025-12-04 00:58:44'),
(156, 26, 32, NULL, NULL, NULL, '2025-12-04 00:58:44', '2025-12-04 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE `assessment_results` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_user_id` bigint UNSIGNED NOT NULL,
  `total_percent` decimal(5,2) NOT NULL,
  `letter_grade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gpa_point` decimal(3,2) DEFAULT NULL,
  `decided_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assessment_results`
--

INSERT INTO `assessment_results` (`id`, `mahasiswa_user_id`, `total_percent`, `letter_grade`, `gpa_point`, `decided_by`, `created_at`, `updated_at`) VALUES
(9, 62, 92.20, 'A', 4.00, 1, '2025-11-20 03:28:09', '2025-11-20 04:21:22'),
(10, 69, 72.25, 'C', 2.00, 1, '2025-11-20 03:59:17', '2025-11-20 04:19:37'),
(11, 68, 69.90, 'C', 2.00, 1, '2025-11-20 04:15:56', '2025-11-20 04:15:56'),
(12, 67, 54.05, 'E', 0.00, 3, '2025-11-20 04:16:16', '2025-11-20 04:16:45'),
(14, 112, 83.40, 'B', 3.00, 3, '2025-12-04 00:58:44', '2025-12-04 00:58:44');

-- --------------------------------------------------------

--
-- Table structure for table `dospem`
--

CREATE TABLE `dospem` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dospem`
--

INSERT INTO `dospem` (`id`, `user_id`, `nip`, `created_at`, `updated_at`) VALUES
(1, 3, '234232342449', '2025-11-07 14:00:56', '2025-11-27 06:26:55'),
(2, 4, '3345346354', '2025-11-15 05:36:40', '2025-11-15 05:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `history_aktivitas`
--

CREATE TABLE `history_aktivitas` (
  `id_aktivitas` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED DEFAULT NULL,
  `tipe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history_aktivitas`
--

INSERT INTO `history_aktivitas` (`id_aktivitas`, `id_user`, `id_mahasiswa`, `tipe`, `pesan`, `tanggal_dibuat`) VALUES
(1, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-12-03 13:32:47'),
(2, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-12-03 13:36:16'),
(5, 111, 111, 'register', '{\"action\":\"register\",\"user\":\"jersey club\",\"role\":\"mahasiswa\",\"message\":\"jersey club telah melakukan registrasi via Google sebagai Mahasiswa\"}', '2025-12-03 13:48:39'),
(6, 110, 110, 'login', '{\"action\":\"login\",\"user\":\"MUHAMMAD SODIQ\",\"role\":\"mahasiswa\",\"message\":\"MUHAMMAD SODIQ (Mahasiswa) melakukan login via Google\"}', '2025-12-03 15:29:50'),
(7, 111, 111, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"1\",\"mahasiswa\":\"jersey club\",\"file_name\":\"S1_KHS_jersey_club_2401301056_1764776269_0.pdf\",\"upload_type\":\"multiple\"}', '2025-12-03 15:37:49'),
(8, 111, 111, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"2\",\"mahasiswa\":\"jersey club\",\"file_name\":\"S2_KHS_jersey_club_2401301056_1764776269_1.pdf\",\"upload_type\":\"multiple\"}', '2025-12-03 15:37:49'),
(9, 111, 111, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"3\",\"mahasiswa\":\"jersey club\",\"file_name\":\"S3_KHS_jersey_club_2401301056_1764776269_2.pdf\",\"upload_type\":\"multiple\"}', '2025-12-03 15:37:49'),
(10, 111, 111, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"4\",\"mahasiswa\":\"jersey club\",\"file_name\":\"S4_KHS_jersey_club_2401301056_1764776269_3.pdf\",\"upload_type\":\"multiple\"}', '2025-12-03 15:37:49'),
(11, 111, 111, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":1,\"mahasiswa\":\"jersey club\",\"transcript_data_length\":536}', '2025-12-03 15:38:52'),
(12, 111, 111, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":2,\"mahasiswa\":\"jersey club\",\"transcript_data_length\":536}', '2025-12-03 15:38:56'),
(13, 111, 111, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":3,\"mahasiswa\":\"jersey club\",\"transcript_data_length\":536}', '2025-12-03 15:38:59'),
(14, 111, 111, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":4,\"mahasiswa\":\"jersey club\",\"transcript_data_length\":536}', '2025-12-03 15:39:00'),
(15, 111, 111, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"jersey club\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":false}}', '2025-12-03 17:00:12'),
(16, 111, 111, 'upload_dokumen', '\"{\\\"action\\\":\\\"upload_surat_pengantar\\\",\\\"user\\\":\\\"jersey club\\\",\\\"mahasiswa\\\":\\\"2401301056\\\",\\\"filename\\\":\\\"Surat_Pengantar_jersey_club_2401301056_1764781237.pdf\\\",\\\"document_type\\\":\\\"surat_pengantar\\\"}\"', '2025-12-03 17:00:37'),
(17, 111, 111, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"Surat Balasan\",\"mahasiswa\":\"jersey club\",\"file_name\":\"Surat_Balasan_jersey_club_2401301056_1764781257.pdf\"}', '2025-12-03 17:00:57'),
(18, 111, 111, 'activate_pkl_status', '{\"action\":\"activate_pkl_status\",\"mahasiswa\":\"jersey club\",\"new_status\":\"aktif\"}', '2025-12-03 17:01:17'),
(19, 111, 111, 'complete_pkl_status', '{\"action\":\"complete_pkl_status\",\"mahasiswa\":\"jersey club\",\"new_status\":\"selesai\"}', '2025-12-03 17:01:24'),
(20, 111, 111, 'revert_pkl_status', '{\"action\":\"revert_pkl_status\",\"mahasiswa\":\"jersey club\",\"new_status\":\"aktif\"}', '2025-12-03 17:01:31'),
(21, 111, 111, 'deactivate_pkl_status', '{\"action\":\"deactivate_pkl_status\",\"mahasiswa\":\"jersey club\",\"new_status\":\"siap\"}', '2025-12-03 17:01:45'),
(22, 112, 112, 'register', '{\"action\":\"register\",\"user\":\"buahnaga\",\"role\":\"mahasiswa\",\"message\":\"buahnaga telah melakukan registrasi sebagai Mahasiswa\"}', '2025-12-04 00:56:33'),
(23, 112, 112, 'login', '{\"action\":\"login\",\"user\":\"buahnaga\",\"role\":\"mahasiswa\",\"message\":\"buahnaga (Mahasiswa) melakukan login\"}', '2025-12-04 01:21:13'),
(24, 110, 110, 'login', '{\"action\":\"login\",\"user\":\"MUHAMMAD SODIQ\",\"role\":\"mahasiswa\",\"message\":\"MUHAMMAD SODIQ (Mahasiswa) melakukan login via Google\"}', '2025-12-04 04:45:50'),
(25, 112, 112, 'login', '{\"action\":\"login\",\"user\":\"buahnaga\",\"role\":\"mahasiswa\",\"message\":\"buahnaga (Mahasiswa) melakukan login\"}', '2025-12-04 04:49:36'),
(26, 112, 112, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"1\",\"mahasiswa\":\"buahnaga\",\"file_name\":\"S1_KHS_buahnaga_2401101010_1764826127_0.pdf\",\"upload_type\":\"multiple\"}', '2025-12-04 05:28:47'),
(27, 112, 112, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"2\",\"mahasiswa\":\"buahnaga\",\"file_name\":\"S2_KHS_buahnaga_2401101010_1764826127_1.pdf\",\"upload_type\":\"multiple\"}', '2025-12-04 05:28:47'),
(28, 112, 112, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"3\",\"mahasiswa\":\"buahnaga\",\"file_name\":\"S3_KHS_buahnaga_2401101010_1764826127_2.pdf\",\"upload_type\":\"multiple\"}', '2025-12-04 05:28:47'),
(29, 112, 112, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"4\",\"mahasiswa\":\"buahnaga\",\"file_name\":\"S4_KHS_buahnaga_2401101010_1764826127_3.pdf\",\"upload_type\":\"multiple\"}', '2025-12-04 05:28:47'),
(30, 112, 112, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":1,\"mahasiswa\":\"buahnaga\",\"transcript_data_length\":533}', '2025-12-04 05:30:03'),
(31, 112, 112, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":2,\"mahasiswa\":\"buahnaga\",\"transcript_data_length\":533}', '2025-12-04 05:30:05'),
(32, 112, 112, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":3,\"mahasiswa\":\"buahnaga\",\"transcript_data_length\":533}', '2025-12-04 05:30:07'),
(33, 112, 112, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":4,\"mahasiswa\":\"buahnaga\",\"transcript_data_length\":533}', '2025-12-04 05:30:09'),
(34, 112, 112, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"buahnaga\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":false}}', '2025-12-04 05:30:29'),
(35, 112, 112, 'logout', '{\"action\":\"logout\",\"user\":\"buahnaga\",\"role\":\"mahasiswa\",\"message\":\"buahnaga (Mahasiswa) melakukan logout\"}', '2025-12-04 07:17:53'),
(36, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-12-04 07:18:15'),
(37, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-12-04 07:18:41'),
(38, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-12-04 07:19:10'),
(39, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-12-04 07:19:25'),
(40, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-12-04 07:19:42'),
(41, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-12-04 07:24:29'),
(42, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-12-04 07:24:48'),
(43, 3, 112, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"pemberkasan_kelayakan\",\"mahasiswa\":\"buahnaga\",\"old_status\":\"menunggu\",\"new_status\":\"revisi\",\"catatan\":\"Perlu revisi yahhh\"}', '2025-12-04 07:30:26'),
(44, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-12-04 07:48:12'),
(45, 110, 110, 'login', '{\"action\":\"login\",\"user\":\"MUHAMMAD SODIQ\",\"role\":\"mahasiswa\",\"message\":\"MUHAMMAD SODIQ (Mahasiswa) melakukan login via Google\"}', '2025-12-04 07:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_seminar`
--

CREATE TABLE `jadwal_seminar` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subjudul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis` enum('file','link') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_eksternal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `dibuat_oleh` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_seminar`
--

INSERT INTO `jadwal_seminar` (`id`, `judul`, `subjudul`, `jenis`, `lokasi_file`, `url_eksternal`, `status_aktif`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(13, 'Assessment', 'Tell', 'file', 'jadwal/jadwal_20251127_132813_6927e16d1dfb1.pdf', NULL, 1, 1, '2025-11-27 05:28:13', '2025-11-27 05:28:13'),
(14, 'Jadwal baru', 'Subjudul', 'file', 'jadwal/jadwal_20251204_152043_6931364ba5a86.pdf', NULL, 1, 1, '2025-12-04 07:20:43', '2025-12-04 07:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `khs`
--

CREATE TABLE `khs` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int DEFAULT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khs`
--

INSERT INTO `khs` (`id`, `mahasiswa_id`, `file_path`, `semester`, `status_validasi`, `created_at`, `updated_at`) VALUES
(89, 111, 'documents/khs/111/S1_KHS_jersey_club_2401301056_1764776269_0.pdf', 1, 'menunggu', '2025-12-03 15:37:49', '2025-12-03 15:37:49'),
(90, 111, 'documents/khs/111/S2_KHS_jersey_club_2401301056_1764776269_1.pdf', 2, 'menunggu', '2025-12-03 15:37:49', '2025-12-03 15:37:49'),
(91, 111, 'documents/khs/111/S3_KHS_jersey_club_2401301056_1764776269_2.pdf', 3, 'menunggu', '2025-12-03 15:37:49', '2025-12-03 15:37:49'),
(92, 111, 'documents/khs/111/S4_KHS_jersey_club_2401301056_1764776269_3.pdf', 4, 'menunggu', '2025-12-03 15:37:49', '2025-12-03 15:37:49'),
(93, 112, 'documents/khs/112/S1_KHS_buahnaga_2401101010_1764826127_0.pdf', 1, 'revisi', '2025-12-04 05:28:47', '2025-12-04 07:30:26'),
(94, 112, 'documents/khs/112/S2_KHS_buahnaga_2401101010_1764826127_1.pdf', 2, 'revisi', '2025-12-04 05:28:47', '2025-12-04 07:30:26'),
(95, 112, 'documents/khs/112/S3_KHS_buahnaga_2401101010_1764826127_2.pdf', 3, 'revisi', '2025-12-04 05:28:47', '2025-12-04 07:30:26'),
(96, 112, 'documents/khs/112/S4_KHS_buahnaga_2401101010_1764826127_3.pdf', 4, 'revisi', '2025-12-04 05:28:47', '2025-12-04 07:30:26');

-- --------------------------------------------------------

--
-- Table structure for table `khs_manual_transkrip`
--

CREATE TABLE `khs_manual_transkrip` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `transcript_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ips` decimal(3,2) DEFAULT NULL,
  `total_sks` int DEFAULT NULL,
  `total_sks_d` int NOT NULL DEFAULT '0',
  `has_e` tinyint(1) NOT NULL DEFAULT '0',
  `eligible` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khs_manual_transkrip`
--

INSERT INTO `khs_manual_transkrip` (`id`, `mahasiswa_id`, `semester`, `transcript_data`, `ips`, `total_sks`, `total_sks_d`, `has_e`, `eligible`, `created_at`, `updated_at`) VALUES
(50, 111, 1, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', 3.75, 20, 0, 0, 1, '2025-12-03 15:38:52', '2025-12-03 15:38:52'),
(51, 111, 2, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', 3.75, 20, 0, 0, 1, '2025-12-03 15:38:56', '2025-12-03 15:38:56'),
(52, 111, 3, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', 3.75, 20, 0, 0, 1, '2025-12-03 15:38:59', '2025-12-03 15:38:59'),
(53, 111, 4, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', 3.75, 20, 0, 0, 1, '2025-12-03 15:39:00', '2025-12-03 15:39:00'),
(54, 112, 1, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII231209	Aplikasi Komputer	2	4.00	8	A		\n2	AII231205	Kalkulus	2	2.50	5	C+		\n3	AII231206	Matematika Diskrit	2	4.00	8	A		\n4	PAI231202	Bahasa Inggris	2	3.50	7	B+		\n5	AII231203	Desain Grafis	2	4.00	8	A		\n6	AIK231204	Interaksi Manusia Komputer	2	4.00	8	A		\n7	AII231208	Sistem Informasi Manajemen	2	3.50	7	B+		\n8	AIK231301	Algoritma dan Pemrograman	3	4.00	12	A		\n9	AIK231307	Pengantar Basis Data	3	3.50	10.5	B+		\nTotal SKS	20	 	73.5	 \nIndeks Prestasi Semester	3.68', 3.68, 20, 0, 0, 1, '2025-12-04 05:30:03', '2025-12-04 05:30:03'),
(55, 112, 2, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII231209	Aplikasi Komputer	2	4.00	8	A		\n2	AII231205	Kalkulus	2	2.50	5	C+		\n3	AII231206	Matematika Diskrit	2	4.00	8	A		\n4	PAI231202	Bahasa Inggris	2	3.50	7	B+		\n5	AII231203	Desain Grafis	2	4.00	8	A		\n6	AIK231204	Interaksi Manusia Komputer	2	4.00	8	A		\n7	AII231208	Sistem Informasi Manajemen	2	3.50	7	B+		\n8	AIK231301	Algoritma dan Pemrograman	3	4.00	12	A		\n9	AIK231307	Pengantar Basis Data	3	3.50	10.5	B+		\nTotal SKS	20	 	73.5	 \nIndeks Prestasi Semester	3.68', 3.68, 20, 0, 0, 1, '2025-12-04 05:30:05', '2025-12-04 05:30:05'),
(56, 112, 3, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII231209	Aplikasi Komputer	2	4.00	8	A		\n2	AII231205	Kalkulus	2	2.50	5	C+		\n3	AII231206	Matematika Diskrit	2	4.00	8	A		\n4	PAI231202	Bahasa Inggris	2	3.50	7	B+		\n5	AII231203	Desain Grafis	2	4.00	8	A		\n6	AIK231204	Interaksi Manusia Komputer	2	4.00	8	A		\n7	AII231208	Sistem Informasi Manajemen	2	3.50	7	B+		\n8	AIK231301	Algoritma dan Pemrograman	3	4.00	12	A		\n9	AIK231307	Pengantar Basis Data	3	3.50	10.5	B+		\nTotal SKS	20	 	73.5	 \nIndeks Prestasi Semester	3.68', 3.68, 20, 0, 0, 1, '2025-12-04 05:30:07', '2025-12-04 05:30:07'),
(57, 112, 4, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII231209	Aplikasi Komputer	2	4.00	8	A		\n2	AII231205	Kalkulus	2	2.50	5	C+		\n3	AII231206	Matematika Diskrit	2	4.00	8	A		\n4	PAI231202	Bahasa Inggris	2	3.50	7	B+		\n5	AII231203	Desain Grafis	2	4.00	8	A		\n6	AIK231204	Interaksi Manusia Komputer	2	4.00	8	A		\n7	AII231208	Sistem Informasi Manajemen	2	3.50	7	B+		\n8	AIK231301	Algoritma dan Pemrograman	3	4.00	12	A		\n9	AIK231307	Pengantar Basis Data	3	3.50	10.5	B+		\nTotal SKS	20	 	73.5	 \nIndeks Prestasi Semester	3.68', 3.68, 20, 0, 0, 1, '2025-12-04 05:30:09', '2025-12-04 05:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_pkl`
--

CREATE TABLE `laporan_pkl` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_04_082538_create_profil_mahasiswa_table', 1),
(5, '2025_10_04_082545_create_mitra_table', 1),
(6, '2025_10_04_082558_create_khs_table', 1),
(7, '2025_10_04_082608_create_surat_balasan_table', 1),
(8, '2025_10_04_082615_create_laporan_pkl_table', 1),
(9, '2025_10_04_082641_create_formulir_penilaian_table', 1),
(10, '2025_10_04_082648_create_butir_pertanyaan_formulir_table', 1),
(11, '2025_10_04_082656_create_respon_penilaian_table', 1),
(12, '2025_10_04_082703_create_detail_jawaban_penilaian_table', 1),
(13, '2025_10_04_082710_create_hasil_penilaian_table', 1),
(14, '2025_10_04_082719_create_jadwal_seminar_table', 1),
(15, '2025_10_04_082732_create_history_aktivitas_table', 1),
(16, '2025_10_05_175828_create_system_settings_table', 2),
(17, '2025_10_05_232739_create_jadwal_seminar_management_table', 3),
(18, '2025_10_05_232804_create_assessment_forms_table', 3),
(19, '2025_10_05_232814_create_assessment_form_items_table', 3),
(20, '2025_10_05_232824_create_assessment_responses_table', 3),
(21, '2025_10_05_232833_create_assessment_response_items_table', 3),
(22, '2025_10_05_232841_create_assessment_results_table', 3),
(23, '2025_10_05_232850_create_grade_scale_steps_table', 3),
(30, '2025_10_10_155919_create_tpk_data_table', 4),
(31, '2025_10_11_182043_add_tahun_khs_to_tpk_data_table', 4),
(32, '2025_10_11_234940_create_transcripts_table', 4),
(33, '2025_10_11_235200_add_columns_to_transcripts_table', 4),
(34, '2025_10_12_001336_add_semester_to_khs_table', 4),
(35, '2025_10_12_001707_add_semester_data_to_transcripts_table', 4),
(36, '2025_10_12_152713_add_transcript_fields_to_khs_table', 4),
(37, '2025_10_12_154538_drop_unused_tables', 4),
(38, '2025_10_12_162057_add_total_sks_to_khs_table', 4),
(39, '2025_10_12_171437_create_khs_manual_transkrip_table', 4),
(40, '2025_10_12_171458_remove_ips_from_khs_table', 4),
(41, '2025_10_12_172744_simplify_khs_manual_transkrip_table', 4),
(42, '2025_10_13_213826_create_profile_mahasiswa_table', 4),
(43, '2025_10_13_215553_drop_profile_mahasiswa_table', 4),
(44, '2025_10_13_215610_add_gdrive_columns_to_profil_mahasiswa_table', 4),
(45, '2025_10_14_153047_add_saw_criteria_to_mitra_table', 5),
(46, '2025_10_14_181018_temp_fix_create_grade_scale_steps_table', 6),
(47, '2025_10_16_222348_drop_unused_tables', 7),
(48, '2025_10_16_224925_drop_assessment_forms_and_grade_scale_steps_tables', 8),
(49, '2025_10_16_225024_create_simplified_assessment_tables', 8),
(50, '2025_10_17_004819_rename_jadwal_seminar_management_to_jadwal_seminar', 9),
(51, '2025_10_17_005205_drop_tanggal_publikasi_from_jadwal_seminar', 10),
(52, '2025_10_17_005837_drop_decided_at_from_assessment_results', 11),
(53, '2025_10_17_014156_drop_submitted_at_from_assessment_responses', 12),
(54, '2025_10_22_003919_add_mitra_selected_to_profil_mahasiswa_table', 13),
(55, '2025_11_06_182117_update_mitra_criteria_to_scale_one_to_five', 14),
(56, '2025_11_07_133211_create_riwayat_penggantian_mitra_table', 15),
(57, '2025_11_07_133916_create_dospems_table', 16),
(59, '2025_11_08_004250_add_calculated_fields_to_khs_manual_transkrip_table', 17),
(60, '2025_11_13_084753_create_surat_pengantar_table', 17),
(61, '2025_11_13_165744_add_max_mahasiswa_to_mitra_table', 18),
(62, '2025_11_13_234258_rename_dospems_table_to_dospen', 19),
(63, '2025_11_14_002226_update_photo_column_to_text_in_users_table', 20),
(64, '2025_11_15_132619_rename_dospen_table_to_dospem', 21),
(65, '2025_11_17_101340_add_status_pkl_to_profil_mahasiswa_table', 22),
(66, '2025_11_17_230614_add_status_dokumen_pendukung_to_profil_mahasiswa_table', 23),
(67, '2025_11_25_142144_add_profile_photo_to_users_table', 24),
(68, '2025_11_27_103059_change_jarak_to_decimal_in_mitra_table', 24),
(69, '2025_12_04_011246_add_created_by_to_mitra_table', 24);

-- --------------------------------------------------------

--
-- Table structure for table `mitra`
--

CREATE TABLE `mitra` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kontak` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak` decimal(8,2) NOT NULL DEFAULT '0.00',
  `honor` tinyint NOT NULL DEFAULT '1',
  `fasilitas` tinyint NOT NULL DEFAULT '1',
  `kesesuaian_jurusan` tinyint NOT NULL DEFAULT '1',
  `tingkat_kebersihan` tinyint NOT NULL DEFAULT '1',
  `max_mahasiswa` int NOT NULL DEFAULT '4' COMMENT 'Maksimal jumlah mahasiswa yang bisa memilih mitra ini',
  `created_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mitra`
--

INSERT INTO `mitra` (`id`, `nama`, `alamat`, `kontak`, `jarak`, `honor`, `fasilitas`, `kesesuaian_jurusan`, `tingkat_kebersihan`, `max_mahasiswa`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PT. Teknologi Digital Indonesia', 'Jl. Teknologi No. 123, Jakarta', '021-12345678', 0.00, 1, 1, 1, 1, 4, NULL, '2025-10-04 05:00:55', '2025-10-04 05:00:55'),
(2, 'Restu Guru Promosindo Cabang Banjarbaru', 'Jl.A Yani Km.38,7 No. 62 Martapura', '08115136404', 50.00, 3, 1, 3, 3, 4, NULL, '2025-10-04 05:00:55', '2025-10-16 05:44:22'),
(3, 'PT. Sistem Informasi Global', 'Jl. Sistem No. 789, Surabaya', '031-11223344', 0.00, 1, 1, 3, 3, 4, NULL, '2025-10-04 05:00:55', '2025-10-15 03:22:12'),
(5, 'PT. Wahyu Putra Ramadhan', 'Jl. A. Yani KM 122 RT 16 Desa Simpang 4 Sei Baru Kec. Jorong Kab. Tanah Laut', '083123456789', 2.00, 1, 3, 3, 3, 4, NULL, '2025-10-14 07:45:15', '2025-10-16 05:49:40'),
(9, 'PT Arutmin Indonesia Site Asam-Asam', 'Jalan A Yani KM 121 RT 12. Simpang Empat Sungai Baru', NULL, 63.00, 3, 3, 3, 3, 4, NULL, '2025-10-15 03:16:12', '2025-10-16 05:47:58'),
(10, 'Koperasi Sawit Makmur', NULL, NULL, 44.00, 5, 3, 1, 3, 4, NULL, '2025-10-22 11:40:37', '2025-12-03 13:27:34'),
(11, 'LPP TVRI STASIUN Kalimantan Selatan', NULL, NULL, 0.00, 3, 3, 1, 3, 4, NULL, '2025-10-22 11:40:58', '2025-10-22 11:41:53'),
(12, 'Politeknik Negeri Tanah Laut', NULL, NULL, 0.00, 1, 3, 3, 3, 4, NULL, '2025-10-22 11:41:07', '2025-10-22 11:42:03'),
(13, 'ULP PLN Banjarbaru', NULL, NULL, 0.00, 1, 1, 1, 1, 4, NULL, '2025-10-22 11:41:19', '2025-10-22 11:41:19'),
(14, 'RSUD KH. Mansyur', NULL, NULL, 0.00, 3, 1, 3, 1, 4, NULL, '2025-10-22 11:42:23', '2025-10-22 11:42:23'),
(15, 'KOPERASI BORNEO AGROSINDO SENTOSA', NULL, NULL, 0.00, 1, 3, 1, 1, 4, NULL, '2025-10-22 11:42:35', '2025-12-03 13:27:25'),
(16, 'dashbhdabjhb', 'mana saja', '1212', 134534.00, 5, 3, 4, 5, 4, NULL, '2025-11-06 10:23:10', '2025-12-03 13:27:17'),
(17, 'Mahasiswa PT Alter Ego', '-', '-', 55.00, 5, 2, 2, 3, 4, 'jersey club', '2025-12-03 17:24:23', '2025-12-03 17:30:11');

-- --------------------------------------------------------

--
-- Table structure for table `profil_mahasiswa`
--

CREATE TABLE `profil_mahasiswa` (
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `nim` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `no_whatsapp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `cek_min_semester` tinyint(1) NOT NULL DEFAULT '0',
  `cek_ipk_nilaisks` tinyint(1) NOT NULL DEFAULT '0',
  `cek_valid_biodata` tinyint(1) NOT NULL DEFAULT '0',
  `id_dospem` bigint UNSIGNED DEFAULT NULL,
  `mitra_selected` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gdrive_pkkmb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gdrive_ecourse` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gdrive_more` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_dokumen_pendukung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `status_pkl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siap'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_mahasiswa`
--

INSERT INTO `profil_mahasiswa` (`id_mahasiswa`, `nim`, `prodi`, `semester`, `no_whatsapp`, `jenis_kelamin`, `ipk`, `cek_min_semester`, `cek_ipk_nilaisks`, `cek_valid_biodata`, `id_dospem`, `mitra_selected`, `created_at`, `updated_at`, `gdrive_pkkmb`, `gdrive_ecourse`, `gdrive_more`, `status_dokumen_pendukung`, `status_pkl`) VALUES
(31, '2401301099', 'D3 Teknologi Informasi', 6, '83119465702', 'L', NULL, 0, 0, 0, NULL, NULL, '2025-10-07 03:07:58', '2025-10-07 03:07:58', NULL, NULL, NULL, 'menunggu', 'siap'),
(62, '2301301092', 'TEKNOLOGI INFORMASI', 5, '085393749800', 'P', 3.81, 0, 0, 0, 5, NULL, '2025-10-22 12:38:12', '2025-11-07 15:47:39', NULL, NULL, NULL, 'menunggu', 'siap'),
(63, '2301301073', 'TEKNOLOGI INFORMASI', 5, '082252316600', 'L', 3.20, 0, 0, 0, 3, NULL, '2025-10-22 12:38:12', '2025-11-07 15:47:11', NULL, NULL, NULL, 'menunggu', 'siap'),
(64, '2301301114', 'TEKNOLOGI INFORMASI', 5, '085752813800', 'L', 3.86, 0, 0, 0, 4, NULL, '2025-10-22 12:38:13', '2025-11-07 15:47:28', NULL, NULL, NULL, 'menunggu', 'siap'),
(65, '2301301029', 'TEKNOLOGI INFORMASI', 5, '085951194100', 'L', 3.70, 0, 0, 0, 4, NULL, '2025-10-22 12:38:13', '2025-11-07 15:47:28', NULL, NULL, NULL, 'menunggu', 'siap'),
(66, '2301301100', 'TEKNOLOGI INFORMASI', 5, '082250657900', 'L', 3.50, 0, 0, 0, 4, NULL, '2025-10-22 12:38:14', '2025-11-07 15:47:28', NULL, NULL, NULL, 'menunggu', 'siap'),
(67, '2301301093', 'TEKNOLOGI INFORMASI', 5, '083824320100', 'P', 3.71, 0, 0, 0, 3, NULL, '2025-10-22 12:38:14', '2025-11-07 15:47:11', NULL, NULL, NULL, 'menunggu', 'siap'),
(68, '2301301121', 'TEKNOLOGI INFORMASI', 5, '081251784500', 'P', 3.50, 0, 0, 0, 4, NULL, '2025-10-22 12:38:14', '2025-11-07 15:47:28', NULL, NULL, NULL, 'menunggu', 'siap'),
(69, '2301301094', 'TEKNOLOGI INFORMASI', 5, '085248131800', 'L', 4.00, 0, 0, 0, 3, NULL, '2025-10-22 12:38:15', '2025-11-07 15:47:11', NULL, NULL, NULL, 'menunggu', 'siap'),
(70, '2301301062', 'TEKNOLOGI INFORMASI', 5, '085754152200', 'L', 3.75, 0, 0, 0, 3, NULL, '2025-10-22 12:38:15', '2025-11-07 15:47:11', NULL, NULL, NULL, 'menunggu', 'siap'),
(71, '2301301075', 'TEKNOLOGI INFORMASI', 5, '083862166800', 'P', 3.31, 0, 0, 0, 3, NULL, '2025-10-22 12:38:15', '2025-11-07 15:47:11', NULL, NULL, NULL, 'menunggu', 'siap'),
(109, '234782398', 'D3 Teknologi Informasi', 5, '82282882222', 'L', 3.90, 0, 0, 0, 3, NULL, '2025-12-03 13:13:01', '2025-12-03 13:13:01', NULL, NULL, NULL, 'menunggu', 'siap'),
(110, '234234323', 'D3 Teknologi Informasi', 5, '83838383833', 'L', 3.30, 1, 1, 1, 3, 15, '2025-12-03 13:15:57', '2025-12-04 07:50:14', NULL, NULL, NULL, 'menunggu', 'siap'),
(111, '2401301056', 'D3 Teknologi Informasi', 5, '83844879985', 'L', 3.93, 1, 1, 1, 3, 17, '2025-12-03 13:49:05', '2025-12-03 17:27:01', 'https://drive.google.com/file/d/...', 'https://drive.google.com/file/d/...', NULL, 'menunggu', 'siap'),
(112, '2401101010', 'D3 Teknologi Informasi', 5, '8883838838', 'L', 3.90, 1, 1, 1, 3, 17, '2025-12-04 00:56:33', '2025-12-04 05:47:18', 'http://localhost:8000/documents', 'http://localhost:8000/documents', NULL, 'menunggu', 'siap');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_penggantian_mitra`
--

CREATE TABLE `riwayat_penggantian_mitra` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `mitra_lama_id` bigint UNSIGNED DEFAULT NULL,
  `mitra_baru_id` bigint UNSIGNED NOT NULL,
  `jenis_alasan` enum('ditolak','alasan_tertentu','pilihan_pribadi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan_lengkap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_penggantian_mitra`
--

INSERT INTO `riwayat_penggantian_mitra` (`id`, `mahasiswa_id`, `mitra_lama_id`, `mitra_baru_id`, `jenis_alasan`, `alasan_lengkap`, `created_at`, `updated_at`) VALUES
(12, 111, 15, 17, 'ditolak', NULL, '2025-12-03 17:27:01', '2025-12-03 17:27:01'),
(13, 112, 16, 15, 'ditolak', NULL, '2025-12-04 01:16:11', '2025-12-04 01:16:11'),
(14, 112, 15, 17, 'alasan_tertentu', 'tes', '2025-12-04 01:16:48', '2025-12-04 01:16:48'),
(15, 112, 17, 12, 'ditolak', NULL, '2025-12-04 05:41:37', '2025-12-04 05:41:37'),
(16, 112, 12, 17, 'ditolak', NULL, '2025-12-04 05:47:18', '2025-12-04 05:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('m6cW4SRaEdOcKcR6daAww3o38rMKuPEh6FedDeAc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiZVFUNzFxS3dxU0FYSlh2elRoZVJKV1Z4SFFPa2dVNEZYRHhEOWZWZCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21pdHJhIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxODoiZ29vZ2xlX29hdXRoX3N0YXRlIjtzOjMyOiJjYzNkNWJlMjM1ZWY4MjQzMjk2NzRlYzRkYzBjNTJlNSI7czoyMjoiZ29vZ2xlX29hdXRoX3RpbWVzdGFtcCI7aToxNzY0ODIzNzU5O3M6NToic3RhdGUiO3M6NDA6IjhZOE9DWEVMZk5odXlDMkRxaEQwaTNXN2VQMzJxSUhFeGJkdHlMUGoiO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNToibGlua2VkX2FjY291bnRzIjthOjM6e2k6MDtpOjExMjtpOjE7aTozO2k6MjtpOjE7fX0=', 1764829135),
('VqOS6Sk6UGb2KozRoDAJlI6VYWiNkkti0dGnrNKr', 110, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicTFDNlZocU9PeldsYkw1NE1ld0duM3RoSTNNa2FiclYzSkNJM2p1YSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9qYWR3YWwvamFkd2FsXzIwMjUxMjA0XzE1MjA0M182OTMxMzY0YmE1YTg2LnBkZiI7czo1OiJyb3V0ZSI7czoxMToiamFkd2FsLmZpbGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMTA7fQ==', 1764834816);

-- --------------------------------------------------------

--
-- Table structure for table `surat_balasan`
--

CREATE TABLE `surat_balasan` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `mitra_id` bigint UNSIGNED DEFAULT NULL,
  `mitra_nama_custom` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_balasan`
--

INSERT INTO `surat_balasan` (`id`, `mahasiswa_id`, `mitra_id`, `mitra_nama_custom`, `file_path`, `status_validasi`, `created_at`, `updated_at`) VALUES
(23, 111, NULL, NULL, 'documents/surat_balasan/Surat_Balasan_jersey_club_2401301056_1764781257.pdf', 'menunggu', '2025-12-03 17:00:57', '2025-12-03 17:00:57');

-- --------------------------------------------------------

--
-- Table structure for table `surat_pengantar`
--

CREATE TABLE `surat_pengantar` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_pengantar`
--

INSERT INTO `surat_pengantar` (`id`, `mahasiswa_id`, `file_path`, `status_validasi`, `created_at`, `updated_at`) VALUES
(14, 111, 'documents/surat_pengantar/Surat_Pengantar_jersey_club_2401301056_1764781237.pdf', 'menunggu', '2025-12-03 17:00:37', '2025-12-03 17:00:37');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'laporan_pkl_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur upload Laporan PKL untuk mahasiswa', '2025-10-05 10:05:06', '2025-12-03 15:18:41'),
(2, 'penilaian_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur Penilaian untuk dosen pembimbing', '2025-10-05 17:20:11', '2025-12-03 15:18:41'),
(3, 'jadwal_seminar_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur Jadwal Seminar', '2025-10-05 17:20:11', '2025-12-03 15:18:41'),
(4, 'instansi_mitra_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur Instansi Mitra', '2025-10-05 17:40:13', '2025-12-03 15:18:41'),
(5, 'dokumen_pemberkasan_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur upload dokumen pemberkasan (KHS, Surat Balasan)', '2025-10-05 17:40:13', '2025-12-03 15:18:41'),
(6, 'registration_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan pendaftaran akun baru (termasuk Google OAuth)', '2025-10-05 17:56:31', '2025-11-10 06:43:21'),
(7, 'whatsapp_notification_enabled', '0', 'Toggle untuk mengaktifkan/menonaktifkan notifikasi WhatsApp via Fonnte', '2025-12-03 13:49:20', '2025-12-03 13:51:21'),
(8, 'system_font', 'ibm_plex_sans', 'Font sistem yang digunakan pada seluruh aplikasi', '2025-12-03 15:11:18', '2025-12-04 01:01:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_linked` tinyint(1) NOT NULL DEFAULT '0',
  `google_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('mahasiswa','dospem','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `photo`, `profile_photo`, `google_linked`, `google_email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'First Atminnts', 'admin1@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$pZmg5OlGrdFDOKngawDFJ.wgQqR/NNXyBbIdOLNkeTG71Unb7d34S', 'admin', NULL, '2025-10-04 05:00:50', '2025-10-04 05:51:29'),
(3, 'Dr. Ahmad Wijaya, S.T., M.T.', 'dospem1@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$18iIUaO5jzoTlHd0wiFesOG477TLXo7ccHuQ6Tj/9pLiGbkiZZlGq', 'dospem', NULL, '2025-10-04 05:00:51', '2025-11-20 02:23:53'),
(4, 'Dr. Siti Nurhaliza, S.T., M.T.', 'dospem2@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$MyycmSjzZxnn2xai8OPzx.aCTTvc4O8YTpVQ5mrGcdsFJadJxxjze', 'dospem', NULL, '2025-10-04 05:00:51', '2025-10-04 05:00:51'),
(5, 'Dr. Budi Santoso, S.T., M.T.', 'dospem3@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$RJG9TfUM9Dhv8eIVwFPU2exZs8as25GW4f.hP4/grC2zM2XZSJp9O', 'dospem', NULL, '2025-10-04 05:00:52', '2025-10-04 05:00:52'),
(62, 'Sayyidah Nafisah', 'sayyidahnafisah23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$NYYyR3Q6z.YEqz1iYesoA.EnE5qhJYI9R3Er17Vq6I5Ju4rtcsxv6', 'mahasiswa', NULL, '2025-10-22 12:31:21', '2025-10-22 12:31:21'),
(63, 'muhammad widigda', 'muhammadwidigdapratama23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$y.ruXrIm0Fnu8lm7NGE3I.dEhvkRRgC9it8IdbveLwBFxcg8IRdHa', 'mahasiswa', NULL, '2025-10-22 12:38:12', '2025-10-22 12:38:12'),
(64, 'M. Zainal Akli', 'mzainalakli23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$d6TVce9aWynenUoF2UpzwO0rzBnfdHGBBVeb8QZqTdjT.GOljaTiC', 'mahasiswa', NULL, '2025-10-22 12:38:13', '2025-10-22 12:38:13'),
(65, 'Ahmad Faisal Aditya', 'ahmadfaisaladitya23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$EpOyh3DzG7utnBid63/FY.8HHCMMVetMbK0Pt0B8NOejqUlcWKFc2', 'mahasiswa', NULL, '2025-10-22 12:38:13', '2025-10-22 12:38:13'),
(66, 'Zainal', 'zainal23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$EyvVIYBNcTDP8SESwLvshOJ5af9ToMtZ34VDJOEkMna0tiNuZa8CC', 'mahasiswa', NULL, '2025-10-22 12:38:14', '2025-10-22 12:38:14'),
(67, 'AIDA SEKAR NINGRUM', 'aidasekarningrum23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$ZQ2fjUOvB.FubAgr0IN5VO1StEPaWmohduLfHP05PcM6AT5GlLubW', 'mahasiswa', NULL, '2025-10-22 12:38:14', '2025-10-22 12:38:14'),
(68, 'Sima Sabrina', 'simasabrina23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$Dnju2bl0TUoxU7JZva6yDuVoHP.BOJ1vup/vj1GPhxptPSE0zWWoW', 'mahasiswa', NULL, '2025-10-22 12:38:14', '2025-10-22 12:38:14'),
(69, 'Muhammad Aditya', 'muhammadaditya23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$OYLlQ1spnB2RHi2bQJM/R.VTPmgRyaibn7.pZxSpFbZk7ZwuGEFQO', 'mahasiswa', NULL, '2025-10-22 12:38:15', '2025-10-22 12:38:15'),
(70, 'Muhammad Rifani', 'muhammadrifani23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$OekvZgi1PEY8fisuTM.9s.CHYiLDWvWtqvYXyQw/DD14XUa5q0Vlm', 'mahasiswa', NULL, '2025-10-22 12:38:15', '2025-10-22 12:38:15'),
(71, 'Ani Khairiyah', 'anikhairiyah23@mhs.politala.ac.id', NULL, NULL, 0, NULL, NULL, '$2y$12$2ZbRtWmbv9CyAJYKEx39/.iqhRFYMWVhvsGkWe9MUxcj07gOrZ0SW', 'mahasiswa', NULL, '2025-10-22 12:38:15', '2025-10-22 12:38:15'),
(109, 'bahlil', 'bahlil@gmail.com', NULL, NULL, 0, NULL, NULL, '$2y$12$2n9nXlIfY4KGZPfQ00fA/eB4jvCW6aYS775DaurSAX2TUW5LBPwVS', 'mahasiswa', NULL, '2025-12-03 13:13:01', '2025-12-03 13:13:01'),
(110, 'MUHAMMAD SODIQ', 'muhammad.sodiq@mhs.politala.ac.id', 'profile_photos/profile_110_1764767881.png', NULL, 0, 'muhammad.sodiq@mhs.politala.ac.id', NULL, '$2y$12$BIWHzoXZhOG2.5sHIYb1FOjNxiGRRvMAX5aY0FZHeu4p8dyE9AtLS', 'mahasiswa', NULL, '2025-12-03 13:15:33', '2025-12-03 13:18:01'),
(111, 'jersey club', 'jerseyclubfound@gmail.com', 'profile_photos/profile_111_1764769830.png', NULL, 0, 'jerseyclubfound@gmail.com', NULL, '$2y$12$xLGG1kPbGbqvl7PUyFcIBOu3r0m/XlTym4pZb4zQ5uv7zfFRcy8gu', 'mahasiswa', NULL, '2025-12-03 13:48:39', '2025-12-03 13:50:30'),
(112, 'buahnaga', 'buahnaga@gmail.com', 'profile_photos/profile_112_1764812377.jpg', NULL, 0, NULL, NULL, '$2y$12$/VWmAh0JJLN93gzrMa.KgO4N3ThyjaUBD2yl12MkIlBjlt8rcR1/W', 'mahasiswa', NULL, '2025-12-04 00:56:33', '2025-12-04 01:39:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assessment_responses`
--
ALTER TABLE `assessment_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_responses_mahasiswa_user_id_foreign` (`mahasiswa_user_id`),
  ADD KEY `assessment_responses_dosen_user_id_foreign` (`dosen_user_id`);

--
-- Indexes for table `assessment_response_items`
--
ALTER TABLE `assessment_response_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_response_items_response_id_foreign` (`response_id`);

--
-- Indexes for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assessment_results_mahasiswa_user_id_foreign` (`mahasiswa_user_id`),
  ADD KEY `assessment_results_decided_by_foreign` (`decided_by`);

--
-- Indexes for table `dospem`
--
ALTER TABLE `dospem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dospems_nip_unique` (`nip`),
  ADD KEY `dospems_user_id_foreign` (`user_id`);

--
-- Indexes for table `history_aktivitas`
--
ALTER TABLE `history_aktivitas`
  ADD PRIMARY KEY (`id_aktivitas`),
  ADD KEY `history_aktivitas_id_user_foreign` (`id_user`),
  ADD KEY `history_aktivitas_id_mahasiswa_foreign` (`id_mahasiswa`);

--
-- Indexes for table `jadwal_seminar`
--
ALTER TABLE `jadwal_seminar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_seminar_management_dibuat_oleh_foreign` (`dibuat_oleh`);

--
-- Indexes for table `khs`
--
ALTER TABLE `khs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khs_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `khs_manual_transkrip`
--
ALTER TABLE `khs_manual_transkrip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `khs_manual_transkrip_mahasiswa_id_semester_unique` (`mahasiswa_id`,`semester`);

--
-- Indexes for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_pkl_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_mahasiswa`
--
ALTER TABLE `profil_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `profil_mahasiswa_nim_unique` (`nim`),
  ADD KEY `profil_mahasiswa_id_dospem_foreign` (`id_dospem`),
  ADD KEY `profil_mahasiswa_mitra_selected_foreign` (`mitra_selected`);

--
-- Indexes for table `riwayat_penggantian_mitra`
--
ALTER TABLE `riwayat_penggantian_mitra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riwayat_penggantian_mitra_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `riwayat_penggantian_mitra_mitra_lama_id_foreign` (`mitra_lama_id`),
  ADD KEY `riwayat_penggantian_mitra_mitra_baru_id_foreign` (`mitra_baru_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `surat_balasan`
--
ALTER TABLE `surat_balasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_balasan_mahasiswa_id_foreign` (`mahasiswa_id`),
  ADD KEY `surat_balasan_mitra_id_foreign` (`mitra_id`);

--
-- Indexes for table `surat_pengantar`
--
ALTER TABLE `surat_pengantar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `surat_pengantar_mahasiswa_id_foreign` (`mahasiswa_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assessment_responses`
--
ALTER TABLE `assessment_responses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `assessment_response_items`
--
ALTER TABLE `assessment_response_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `dospem`
--
ALTER TABLE `dospem`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `history_aktivitas`
--
ALTER TABLE `history_aktivitas`
  MODIFY `id_aktivitas` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `jadwal_seminar`
--
ALTER TABLE `jadwal_seminar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `khs`
--
ALTER TABLE `khs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `khs_manual_transkrip`
--
ALTER TABLE `khs_manual_transkrip`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `profil_mahasiswa`
--
ALTER TABLE `profil_mahasiswa`
  MODIFY `id_mahasiswa` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `riwayat_penggantian_mitra`
--
ALTER TABLE `riwayat_penggantian_mitra`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `surat_balasan`
--
ALTER TABLE `surat_balasan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `surat_pengantar`
--
ALTER TABLE `surat_pengantar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment_responses`
--
ALTER TABLE `assessment_responses`
  ADD CONSTRAINT `assessment_responses_dosen_user_id_foreign` FOREIGN KEY (`dosen_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessment_responses_mahasiswa_user_id_foreign` FOREIGN KEY (`mahasiswa_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessment_response_items`
--
ALTER TABLE `assessment_response_items`
  ADD CONSTRAINT `assessment_response_items_response_id_foreign` FOREIGN KEY (`response_id`) REFERENCES `assessment_responses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assessment_results`
--
ALTER TABLE `assessment_results`
  ADD CONSTRAINT `assessment_results_decided_by_foreign` FOREIGN KEY (`decided_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assessment_results_mahasiswa_user_id_foreign` FOREIGN KEY (`mahasiswa_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dospem`
--
ALTER TABLE `dospem`
  ADD CONSTRAINT `dospems_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_aktivitas`
--
ALTER TABLE `history_aktivitas`
  ADD CONSTRAINT `history_aktivitas_id_mahasiswa_foreign` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `history_aktivitas_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_seminar`
--
ALTER TABLE `jadwal_seminar`
  ADD CONSTRAINT `jadwal_seminar_management_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`);

--
-- Constraints for table `khs`
--
ALTER TABLE `khs`
  ADD CONSTRAINT `khs_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `khs_manual_transkrip`
--
ALTER TABLE `khs_manual_transkrip`
  ADD CONSTRAINT `khs_manual_transkrip_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD CONSTRAINT `laporan_pkl_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profil_mahasiswa`
--
ALTER TABLE `profil_mahasiswa`
  ADD CONSTRAINT `profil_mahasiswa_id_dospem_foreign` FOREIGN KEY (`id_dospem`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `profil_mahasiswa_mitra_selected_foreign` FOREIGN KEY (`mitra_selected`) REFERENCES `mitra` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `riwayat_penggantian_mitra`
--
ALTER TABLE `riwayat_penggantian_mitra`
  ADD CONSTRAINT `riwayat_penggantian_mitra_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_penggantian_mitra_mitra_baru_id_foreign` FOREIGN KEY (`mitra_baru_id`) REFERENCES `mitra` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_penggantian_mitra_mitra_lama_id_foreign` FOREIGN KEY (`mitra_lama_id`) REFERENCES `mitra` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `surat_balasan`
--
ALTER TABLE `surat_balasan`
  ADD CONSTRAINT `surat_balasan_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_balasan_mitra_id_foreign` FOREIGN KEY (`mitra_id`) REFERENCES `mitra` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `surat_pengantar`
--
ALTER TABLE `surat_pengantar`
  ADD CONSTRAINT `surat_pengantar_mahasiswa_id_foreign` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
