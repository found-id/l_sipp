-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2025 at 11:35 PM
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
-- Database: `l_sipp`
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
  `value_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE `assessment_results` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_user_id` bigint UNSIGNED NOT NULL,
  `total_percent` decimal(5,2) NOT NULL,
  `letter_grade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gpa_point` decimal(3,2) DEFAULT NULL,
  `decided_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dospems`
--

CREATE TABLE `dospems` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dospems`
--

INSERT INTO `dospems` (`id`, `user_id`, `nip`, `created_at`, `updated_at`) VALUES
(1, 3, '23423234244', '2025-11-07 14:00:56', '2025-11-07 14:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `history_aktivitas`
--

CREATE TABLE `history_aktivitas` (
  `id_aktivitas` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_mahasiswa` bigint UNSIGNED DEFAULT NULL,
  `tipe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `history_aktivitas`
--

INSERT INTO `history_aktivitas` (`id_aktivitas`, `id_user`, `id_mahasiswa`, `tipe`, `pesan`, `tanggal_dibuat`) VALUES
(2, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"bebeks\", \"new_status\": \"tervalidasi\", \"old_status\": \"menunggu\", \"document_type\": \"khs\"}', '2025-10-04 14:10:57'),
(29, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\"}', '2025-10-04 17:16:04'),
(30, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\"}', '2025-10-04 17:16:18'),
(31, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Mahasiswa 001\", \"new_status\": \"tervalidasi\", \"old_status\": \"belum_valid\", \"document_type\": \"khs\"}', '2025-10-04 17:16:34'),
(32, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Mahasiswa 001\", \"new_status\": \"revisi\", \"old_status\": \"revisi\", \"document_type\": \"laporan_pkl\"}', '2025-10-04 17:17:10'),
(37, 3, NULL, 'logout', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"logout\"}', '2025-10-04 17:37:53'),
(38, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\"}', '2025-10-04 17:38:10'),
(46, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-04 19:02:07'),
(47, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-04 19:02:19'),
(48, 3, NULL, 'logout', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"logout\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-04 19:10:11'),
(49, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-04 19:10:30'),
(50, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-04 19:17:14'),
(51, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-04 19:17:42'),
(52, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"MUHAMMAD SHODIQ\", \"new_status\": \"tervalidasi\", \"old_status\": \"menunggu\", \"document_type\": \"khs\"}', '2025-10-04 19:19:56'),
(54, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-04 19:21:48'),
(55, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"bebeks\", \"new_status\": \"belum_valid\", \"old_status\": \"tervalidasi\", \"document_type\": \"khs\"}', '2025-10-04 19:45:08'),
(57, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-05 09:53:01'),
(59, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-05 10:13:48'),
(60, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-05 10:14:11'),
(61, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"tervalidasi\", \"old_status\": \"menunggu\", \"document_type\": \"khs\"}', '2025-10-05 10:14:34'),
(62, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"belum_valid\", \"old_status\": \"tervalidasi\", \"document_type\": \"khs\"}', '2025-10-05 10:21:57'),
(64, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"tervalidasi\", \"old_status\": \"menunggu\", \"document_type\": \"khs\"}', '2025-10-05 10:22:53'),
(65, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"belum_valid\", \"old_status\": \"tervalidasi\", \"document_type\": \"khs\"}', '2025-10-05 10:23:16'),
(66, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"revisi\", \"old_status\": \"belum_valid\", \"document_type\": \"khs\"}', '2025-10-05 10:31:30'),
(68, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"tervalidasi\", \"old_status\": \"menunggu\", \"document_type\": \"surat_balasan\"}', '2025-10-05 10:34:44'),
(69, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"tervalidasi\", \"old_status\": \"revisi\", \"document_type\": \"khs\"}', '2025-10-05 10:56:56'),
(70, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-05 14:39:05'),
(71, 3, NULL, 'validasi_dokumen', '{\"action\": \"validasi_dokumen\", \"catatan\": null, \"mahasiswa\": \"Found 404\", \"new_status\": \"belum_valid\", \"old_status\": \"tervalidasi\", \"document_type\": \"khs\"}', '2025-10-05 14:39:26'),
(72, 3, NULL, 'logout', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"logout\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-05 14:41:18'),
(73, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-05 14:41:27'),
(74, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-05 14:42:26'),
(75, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-05 14:43:50'),
(79, 3, NULL, 'logout', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"logout\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-05 14:53:29'),
(80, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-05 15:08:16'),
(82, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-05 15:28:42'),
(83, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-05 15:29:15'),
(84, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-05 15:38:31'),
(85, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-05 15:38:46'),
(86, 3, NULL, 'login', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"login\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-05 15:39:10'),
(88, 1, NULL, 'login', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"login\", \"message\": \"First Atminnts (Admin) melakukan login\"}', '2025-10-05 16:24:16'),
(89, 3, NULL, 'logout', '{\"role\": \"dospem\", \"user\": \"Dr. Ahmad Wijaya, S.T., M.T.\", \"action\": \"logout\", \"message\": \"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-05 17:30:22'),
(99, 1, NULL, 'logout', '{\"role\": \"admin\", \"user\": \"First Atminnts\", \"action\": \"logout\", \"message\": \"First Atminnts (Admin) melakukan logout\"}', '2025-10-05 18:54:56'),
(100, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-06 09:33:31'),
(101, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-06 10:49:40'),
(106, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-06 10:59:55'),
(107, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-07 00:32:16'),
(108, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-07 00:46:54'),
(113, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-07 01:28:49'),
(114, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-07 01:31:56'),
(115, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-07 01:32:54'),
(116, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"menunggu\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-07 01:33:59'),
(117, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"tervalidasi\",\"new_status\":\"belum_valid\",\"catatan\":null}', '2025-10-07 01:34:54'),
(118, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"belum_valid\",\"new_status\":\"belum_valid\",\"catatan\":null}', '2025-10-07 01:34:59'),
(119, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-07 01:35:46'),
(121, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-07 02:33:30'),
(122, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"belum_valid\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-07 02:33:59'),
(123, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"tervalidasi\",\"new_status\":\"belum_valid\",\"catatan\":null}', '2025-10-07 02:36:40'),
(124, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"belum_valid\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-07 02:37:38'),
(125, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"tervalidasi\",\"new_status\":\"revisi\",\"catatan\":\"uqsgd s c\"}', '2025-10-07 02:38:06'),
(126, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-07 03:04:39'),
(131, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-07 06:27:31'),
(132, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-08 01:09:03'),
(133, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-08 01:42:40'),
(137, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-08 03:11:54'),
(143, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-09 08:15:08'),
(150, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-14 07:43:44'),
(151, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-14 07:45:56'),
(154, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-14 09:30:11'),
(155, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-14 10:15:01'),
(156, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-14 10:15:14'),
(157, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"menunggu\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-14 10:33:46'),
(158, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"tervalidasi\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-14 10:33:55'),
(159, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"tervalidasi\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-14 10:34:00'),
(160, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"khs\",\"mahasiswa\":\"Muhammad Yoga\",\"old_status\":\"tervalidasi\",\"new_status\":\"tervalidasi\",\"catatan\":null}', '2025-10-14 10:34:03'),
(161, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-14 12:01:27'),
(165, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-15 02:24:42'),
(166, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-15 03:28:18'),
(168, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-15 08:33:50'),
(169, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-15 11:09:37'),
(170, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-15 11:12:10'),
(171, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-15 11:12:20'),
(172, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-15 12:25:17'),
(173, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-15 12:25:28'),
(174, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-15 12:42:49'),
(175, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-15 12:43:06'),
(176, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-15 12:50:47'),
(177, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-15 12:51:00'),
(178, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-15 14:23:13'),
(182, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 01:01:59'),
(183, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-16 01:47:18'),
(184, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 01:47:41'),
(185, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-16 02:00:02'),
(186, 4, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Siti Nurhaliza, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Siti Nurhaliza, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 02:00:18'),
(187, 4, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Siti Nurhaliza, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Siti Nurhaliza, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-16 02:01:27'),
(192, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 02:13:04'),
(193, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-16 02:14:36'),
(197, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 04:58:26'),
(198, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-16 05:00:37'),
(199, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 05:01:02'),
(200, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-16 05:10:45'),
(201, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 05:10:55'),
(202, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-16 05:16:01'),
(203, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 05:17:09'),
(204, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-16 05:29:50'),
(205, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 05:31:03'),
(206, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-16 05:51:08'),
(211, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 06:09:20'),
(212, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-16 06:12:20'),
(215, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 06:20:59'),
(216, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 07:02:25'),
(217, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-10-16 07:19:21'),
(219, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 14:19:38'),
(220, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-16 14:28:21'),
(223, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-16 15:05:19'),
(251, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-17 12:57:50'),
(252, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-18 11:44:19'),
(255, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 08:57:17'),
(256, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 12:31:34'),
(257, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 12:39:46'),
(258, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 12:55:32'),
(260, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 13:00:52'),
(263, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 13:03:57'),
(269, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 13:15:07'),
(272, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 13:20:30'),
(274, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 13:33:30'),
(277, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 13:40:01'),
(284, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 13:53:04'),
(285, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 13:54:23'),
(352, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 16:39:21'),
(353, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 16:39:41'),
(354, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-21 16:42:18'),
(355, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-21 17:08:00'),
(358, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 03:03:42'),
(359, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 03:08:43'),
(360, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-22 03:25:03'),
(363, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-22 03:38:30'),
(364, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 03:38:38'),
(368, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-22 05:09:28'),
(369, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 05:10:53'),
(370, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-22 05:11:20'),
(373, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 05:46:42'),
(374, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-22 05:53:37'),
(379, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 06:01:25'),
(381, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 11:28:18'),
(383, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 11:39:47'),
(384, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-22 12:42:31'),
(385, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-10-22 12:43:38'),
(388, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-10-22 12:49:55'),
(389, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-10-23 00:53:27'),
(391, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-11-06 10:21:55'),
(392, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-11-06 11:26:25'),
(393, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-06 11:26:33'),
(395, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-11-06 11:41:31'),
(396, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-06 11:41:42'),
(397, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-11-06 11:41:50'),
(398, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-11-06 11:42:03'),
(400, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-11-06 11:52:51'),
(401, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-06 11:53:04'),
(413, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"pemberkasan_kelayakan\",\"mahasiswa\":\"Huamusika\",\"old_status\":\"menunggu\",\"new_status\":\"tervalidasi\",\"catatan\":\"Mantap\"}', '2025-11-06 12:20:40'),
(417, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-06 15:40:56'),
(418, 3, NULL, 'validasi_dokumen', '{\"action\":\"validasi_dokumen\",\"document_type\":\"pemberkasan_instansi_mitra\",\"mahasiswa\":\"Huamusika\",\"old_status\":\"menunggu\",\"new_status\":\"revisi\",\"catatan\":null}', '2025-11-06 15:41:49'),
(420, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-07 04:58:00'),
(429, 74, 74, 'register', '{\"action\":\"register\",\"user\":\"Muhammad Fariz\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Fariz telah melakukan registrasi sebagai Mahasiswa\"}', '2025-11-07 05:51:48'),
(430, 74, 74, 'logout', '{\"action\":\"logout\",\"user\":\"Muhammad Fariz\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Fariz (Mahasiswa) melakukan logout\"}', '2025-11-07 05:52:04'),
(431, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-07 05:52:18'),
(433, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-07 12:01:04'),
(434, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-11-07 13:10:55'),
(443, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-11-07 14:21:33'),
(450, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-07 15:09:44'),
(452, 81, 81, 'register', '{\"action\":\"register\",\"user\":\"2401301056 MUHAMMAD SODIQ\",\"role\":\"mahasiswa\",\"message\":\"2401301056 MUHAMMAD SODIQ telah melakukan registrasi via Google sebagai Mahasiswa\"}', '2025-11-07 15:39:02'),
(453, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"1\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S1_KHS_Muhammad_Sodiq_2401301056_1762530026_0.pdf\",\"upload_type\":\"multiple\"}', '2025-11-07 15:40:26'),
(454, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"2\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S2_KHS_Muhammad_Sodiq_2401301056_1762530026_1.pdf\",\"upload_type\":\"multiple\"}', '2025-11-07 15:40:26'),
(455, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"3\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S3_KHS_Muhammad_Sodiq_2401301056_1762530026_2.pdf\",\"upload_type\":\"multiple\"}', '2025-11-07 15:40:26'),
(456, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"4\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S4_KHS_Muhammad_Sodiq_2401301056_1762530026_3.pdf\",\"upload_type\":\"multiple\"}', '2025-11-07 15:40:26'),
(457, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"5\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S5_KHS_Muhammad_Sodiq_2401301056_1762530026_4.pdf\",\"upload_type\":\"multiple\"}', '2025-11-07 15:40:26'),
(458, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":1,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-07 15:42:01'),
(459, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":2,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-07 15:42:07'),
(460, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":2,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":535}', '2025-11-07 15:42:09'),
(461, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":3,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-07 15:42:11'),
(462, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":3,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-07 15:42:14'),
(463, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":4,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-07 15:42:15'),
(464, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":5,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-07 15:42:24'),
(465, 81, 81, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"Muhammad Sodiq\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":true}}', '2025-11-07 15:42:51'),
(466, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"Surat Balasan\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"Surat_Balasan_Muhammad_Sodiq_2401301056_1762530207.pdf\"}', '2025-11-07 15:43:27'),
(467, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"Laporan PKL\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"Laporan_PKL_Muhammad_Sodiq_2401301056_1762530216.pdf\"}', '2025-11-07 15:43:36'),
(468, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-08 09:38:51'),
(469, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"5\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S5_KHS_Muhammad_Sodiq_2401301056_1762597830_0.pdf\",\"upload_type\":\"multiple\"}', '2025-11-08 10:30:30'),
(470, 81, 81, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"Muhammad Sodiq\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":false}}', '2025-11-08 10:31:33'),
(471, 81, 81, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"Muhammad Sodiq\",\"links_saved\":{\"pkkmb\":false,\"ecourse\":true,\"more\":false}}', '2025-11-08 10:31:50'),
(472, 81, 81, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"Muhammad Sodiq\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":false}}', '2025-11-08 10:56:48'),
(473, 81, 81, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"Muhammad Sodiq\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":true}}', '2025-11-08 11:00:51'),
(474, 81, 81, 'save_gdrive_links', '{\"action\":\"save_gdrive_links\",\"mahasiswa\":\"Muhammad Sodiq\",\"links_saved\":{\"pkkmb\":true,\"ecourse\":true,\"more\":false}}', '2025-11-08 11:01:00'),
(475, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"5\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S5_KHS_Muhammad_Sodiq_2401301056_1762600934_0.pdf\",\"upload_type\":\"multiple\"}', '2025-11-08 11:22:14'),
(476, 82, 82, 'register', '{\"action\":\"register\",\"user\":\"Shodiq Found\",\"role\":\"mahasiswa\",\"message\":\"Shodiq Found telah melakukan registrasi via Google sebagai Mahasiswa\"}', '2025-11-09 09:42:28'),
(477, 82, 82, 'logout', '{\"action\":\"logout\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan logout\"}', '2025-11-09 09:44:34'),
(478, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-09 09:44:44'),
(479, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-09 09:53:27'),
(480, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-11-09 09:54:06'),
(481, 81, 81, 'upload_dokumen', '{\"action\":\"upload_dokumen\",\"document_type\":\"KHS\",\"semester\":\"1\",\"mahasiswa\":\"Muhammad Sodiq\",\"file_name\":\"S1_KHS_Muhammad_Sodiq_2401301056_1762682263_0.pdf\",\"upload_type\":\"multiple\"}', '2025-11-09 09:57:43'),
(482, 81, 81, 'logout', '{\"action\":\"logout\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan logout\"}', '2025-11-09 13:10:16'),
(483, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-09 15:58:07'),
(484, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-11-10 06:43:13'),
(485, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-11-10 06:43:35'),
(486, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-10 06:44:01'),
(487, 1, NULL, 'login', '{\"action\":\"login\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan login\"}', '2025-11-11 03:38:05'),
(488, 1, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"First Atminnts\",\"role\":\"admin\",\"message\":\"First Atminnts (Admin) melakukan logout\"}', '2025-11-11 03:38:33'),
(489, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-11 03:39:20'),
(490, 81, 81, 'logout', '{\"action\":\"logout\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan logout\"}', '2025-11-11 03:40:52'),
(491, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-11 03:41:10'),
(492, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-11-11 03:46:53'),
(493, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-11 03:48:08'),
(494, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-13 07:02:54'),
(495, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-11-13 07:04:06'),
(496, 3, NULL, 'login', '{\"action\":\"login\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan login\"}', '2025-11-13 07:38:15'),
(497, 3, NULL, 'logout', '{\"action\":\"logout\",\"user\":\"Dr. Ahmad Wijaya, S.T., M.T.\",\"role\":\"dospem\",\"message\":\"Dr. Ahmad Wijaya, S.T., M.T. (Dospem) melakukan logout\"}', '2025-11-13 07:44:18'),
(498, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-13 07:44:45'),
(499, 81, 81, 'logout', '{\"action\":\"logout\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan logout\"}', '2025-11-13 08:19:06'),
(500, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-13 08:20:52'),
(501, 81, 81, 'login', '{\"action\":\"login\",\"user\":\"Muhammad Sodiq\",\"role\":\"mahasiswa\",\"message\":\"Muhammad Sodiq (Mahasiswa) melakukan login via Google\"}', '2025-11-13 14:39:05'),
(502, 81, 81, 'delete_transcript_data', '{\"action\":\"delete_transcript_data\",\"semester\":\"1\",\"mahasiswa\":\"Muhammad Sodiq\",\"deleted\":true}', '2025-11-13 14:39:31'),
(503, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":1,\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":536}', '2025-11-13 14:39:44'),
(504, 81, 81, 'save_transcript_data', '{\"action\":\"save_transcript_data\",\"semester\":\"1\",\"mahasiswa\":\"Muhammad Sodiq\",\"transcript_data_length\":547}', '2025-11-13 14:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_seminar`
--

CREATE TABLE `jadwal_seminar` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subjudul` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis` enum('file','link') COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_eksternal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `dibuat_oleh` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_seminar`
--

INSERT INTO `jadwal_seminar` (`id`, `judul`, `subjudul`, `jenis`, `lokasi_file`, `url_eksternal`, `status_aktif`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(11, 'Alur Sistem yang Berjalan', NULL, 'file', 'jadwal/jadwal_20251107_211454_690df0ce35d32.jpg', NULL, 1, 1, '2025-11-07 13:14:54', '2025-11-07 13:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `khs`
--

CREATE TABLE `khs` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int DEFAULT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khs`
--

INSERT INTO `khs` (`id`, `mahasiswa_id`, `file_path`, `semester`, `status_validasi`, `created_at`, `updated_at`) VALUES
(42, 81, 'documents/khs/81/S2_KHS_Muhammad_Sodiq_2401301056_1762530026_1.pdf', 2, 'menunggu', '2025-11-07 15:40:26', '2025-11-07 15:40:26'),
(43, 81, 'documents/khs/81/S3_KHS_Muhammad_Sodiq_2401301056_1762530026_2.pdf', 3, 'menunggu', '2025-11-07 15:40:26', '2025-11-07 15:40:26'),
(44, 81, 'documents/khs/81/S4_KHS_Muhammad_Sodiq_2401301056_1762530026_3.pdf', 4, 'menunggu', '2025-11-07 15:40:26', '2025-11-07 15:40:26'),
(47, 81, 'documents/khs/81/S5_KHS_Muhammad_Sodiq_2401301056_1762600934_0.pdf', 5, 'menunggu', '2025-11-08 11:22:14', '2025-11-08 11:22:14'),
(48, 81, 'documents/khs/81/S1_KHS_Muhammad_Sodiq_2401301056_1762682263_0.pdf', 1, 'menunggu', '2025-11-09 09:57:43', '2025-11-09 09:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `khs_manual_transkrip`
--

CREATE TABLE `khs_manual_transkrip` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `transcript_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
(17, 81, 2, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	C		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', NULL, NULL, 0, 0, 0, '2025-11-07 15:42:07', '2025-11-07 15:42:09'),
(18, 81, 3, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	D		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', NULL, NULL, 0, 0, 0, '2025-11-07 15:42:11', '2025-11-07 15:42:14'),
(19, 81, 4, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', NULL, NULL, 0, 0, 0, '2025-11-07 15:42:15', '2025-11-07 15:42:15'),
(20, 81, 5, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\n7	AIK232304	Struktur Data	3	4.00	12	A		\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\nTotal SKS	20	 	75	 \nIndeks Prestasi Semester	3.75', NULL, NULL, 0, 0, 0, '2025-11-07 15:42:24', '2025-11-07 15:42:24'),
(21, 81, 1, 'No	Kode	Nama Mata Kuliah	SKS	Nilai Mutu	Bobot	Nilai	Keterangan	Transkrip\r\n1	AII232202	Statistika dan Probabilitas	2	2.50	5	C+		\r\n2	AII232203	Aljabar Linier	2	3.50	7	B+		\r\n3	AIK232308	Pemrograman Web Dasar	2	4.00	8	A		\r\n4	AIK232207	Sistem Manajemen Basis Data	2	4.00	8	A		\r\n5	AIK232201	Arsitektur Komputer	2	4.00	8	A		\r\n6	AIK232306	Desain UI/UX	3	4.00	12	A		\r\n7	AIK232304	Struktur Data	3	4.00	12	A		\r\n8	PAP232209	Kewarganegaraan	2	3.50	7	B+		\r\n9	AIK232205	Perancangan Perangkat Lunak	2	4.00	8	A		\r\nTotal SKS	20	 	75	 \r\nIndeks Prestasi Semester	3.75', 3.75, 20, 0, 0, 1, '2025-11-13 14:39:44', '2025-11-13 14:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_pkl`
--

CREATE TABLE `laporan_pkl` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(61, '2025_11_13_165744_add_max_mahasiswa_to_mitra_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `mitra`
--

CREATE TABLE `mitra` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kontak` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jarak` int NOT NULL DEFAULT '0',
  `honor` tinyint NOT NULL DEFAULT '1',
  `fasilitas` tinyint NOT NULL DEFAULT '1',
  `kesesuaian_jurusan` tinyint NOT NULL DEFAULT '1',
  `tingkat_kebersihan` tinyint NOT NULL DEFAULT '1',
  `max_mahasiswa` int NOT NULL DEFAULT '4' COMMENT 'Maksimal jumlah mahasiswa yang bisa memilih mitra ini',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mitra`
--

INSERT INTO `mitra` (`id`, `nama`, `alamat`, `kontak`, `jarak`, `honor`, `fasilitas`, `kesesuaian_jurusan`, `tingkat_kebersihan`, `max_mahasiswa`, `created_at`, `updated_at`) VALUES
(1, 'PT. Teknologi Digital Indonesia', 'Jl. Teknologi No. 123, Jakarta', '021-12345678', 0, 1, 1, 1, 1, 4, '2025-10-04 05:00:55', '2025-10-04 05:00:55'),
(2, 'Restu Guru Promosindo Cabang Banjarbaru', 'Jl.A Yani Km.38,7 No. 62 Martapura', '08115136404', 50, 3, 1, 3, 3, 4, '2025-10-04 05:00:55', '2025-10-16 05:44:22'),
(3, 'PT. Sistem Informasi Global', 'Jl. Sistem No. 789, Surabaya', '031-11223344', 0, 1, 1, 3, 3, 4, '2025-10-04 05:00:55', '2025-10-15 03:22:12'),
(5, 'PT. Wahyu Putra Ramadhan', 'Jl. A. Yani KM 122 RT 16 Desa Simpang 4 Sei Baru Kec. Jorong Kab. Tanah Laut', '083123456789', 2, 1, 3, 3, 3, 4, '2025-10-14 07:45:15', '2025-10-16 05:49:40'),
(9, 'PT Arutmin Indonesia Site Asam-Asam', 'Jalan A Yani KM 121 RT 12. Simpang Empat Sungai Baru', NULL, 63, 3, 3, 3, 3, 4, '2025-10-15 03:16:12', '2025-10-16 05:47:58'),
(10, 'Koperasi Sawit Makmur', NULL, NULL, 0, 1, 3, 1, 3, 4, '2025-10-22 11:40:37', '2025-10-22 11:41:37'),
(11, 'LPP TVRI STASIUN Kalimantan Selatan', NULL, NULL, 0, 3, 3, 1, 3, 4, '2025-10-22 11:40:58', '2025-10-22 11:41:53'),
(12, 'Politeknik Negeri Tanah Laut', NULL, NULL, 0, 1, 3, 3, 3, 4, '2025-10-22 11:41:07', '2025-10-22 11:42:03'),
(13, 'ULP PLN Banjarbaru', NULL, NULL, 0, 1, 1, 1, 1, 4, '2025-10-22 11:41:19', '2025-10-22 11:41:19'),
(14, 'RSUD KH. Mansyur', NULL, NULL, 0, 3, 1, 3, 1, 4, '2025-10-22 11:42:23', '2025-10-22 11:42:23'),
(15, 'KOPERASI BORNEO AGROSINDO SENTOSA', NULL, NULL, 0, 3, 3, 1, 1, 4, '2025-10-22 11:42:35', '2025-10-22 11:42:35'),
(16, 'dashbhdabjhb', 'mana saja', '1212', 1, 5, 4, 3, 2, 4, '2025-11-06 10:23:10', '2025-11-06 10:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `profil_mahasiswa`
--

CREATE TABLE `profil_mahasiswa` (
  `id_mahasiswa` bigint UNSIGNED NOT NULL,
  `nim` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prodi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `no_whatsapp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT NULL,
  `cek_min_semester` tinyint(1) NOT NULL DEFAULT '0',
  `cek_ipk_nilaisks` tinyint(1) NOT NULL DEFAULT '0',
  `cek_valid_biodata` tinyint(1) NOT NULL DEFAULT '0',
  `id_dospem` bigint UNSIGNED DEFAULT NULL,
  `mitra_selected` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gdrive_pkkmb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gdrive_ecourse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gdrive_more` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_mahasiswa`
--

INSERT INTO `profil_mahasiswa` (`id_mahasiswa`, `nim`, `prodi`, `semester`, `no_whatsapp`, `jenis_kelamin`, `ipk`, `cek_min_semester`, `cek_ipk_nilaisks`, `cek_valid_biodata`, `id_dospem`, `mitra_selected`, `created_at`, `updated_at`, `gdrive_pkkmb`, `gdrive_ecourse`, `gdrive_more`) VALUES
(27, '2401301059', 'Teknologi Informasi', 5, '83844879985', 'L', 3.90, 1, 1, 0, 3, NULL, '2025-10-05 09:21:43', '2025-10-05 10:33:54', NULL, NULL, NULL),
(31, '2401301099', 'D3 Teknologi Informasi', 6, '83119465702', 'L', NULL, 0, 0, 0, NULL, NULL, '2025-10-07 03:07:58', '2025-10-07 03:07:58', NULL, NULL, NULL),
(37, 'TEST001', 'Teknologi Informasi', 5, NULL, NULL, NULL, 0, 0, 0, 3, NULL, '2025-10-16 15:16:23', '2025-10-16 15:16:23', NULL, NULL, NULL),
(62, '2301301092', 'TEKNOLOGI INFORMASI', 5, '085393749800', 'P', 3.81, 0, 0, 0, 5, NULL, '2025-10-22 12:38:12', '2025-11-07 15:47:39', NULL, NULL, NULL),
(63, '2301301073', 'TEKNOLOGI INFORMASI', 5, '082252316600', 'L', 3.20, 0, 0, 0, 3, NULL, '2025-10-22 12:38:12', '2025-11-07 15:47:11', NULL, NULL, NULL),
(64, '2301301114', 'TEKNOLOGI INFORMASI', 5, '085752813800', 'L', 3.86, 0, 0, 0, 4, NULL, '2025-10-22 12:38:13', '2025-11-07 15:47:28', NULL, NULL, NULL),
(65, '2301301029', 'TEKNOLOGI INFORMASI', 5, '085951194100', 'L', 3.70, 0, 0, 0, 4, NULL, '2025-10-22 12:38:13', '2025-11-07 15:47:28', NULL, NULL, NULL),
(66, '2301301100', 'TEKNOLOGI INFORMASI', 5, '082250657900', 'L', 3.50, 0, 0, 0, 4, NULL, '2025-10-22 12:38:14', '2025-11-07 15:47:28', NULL, NULL, NULL),
(67, '2301301093', 'TEKNOLOGI INFORMASI', 5, '083824320100', 'P', 3.71, 0, 0, 0, 3, NULL, '2025-10-22 12:38:14', '2025-11-07 15:47:11', NULL, NULL, NULL),
(68, '2301301121', 'TEKNOLOGI INFORMASI', 5, '081251784500', 'P', 3.50, 0, 0, 0, 4, NULL, '2025-10-22 12:38:14', '2025-11-07 15:47:28', NULL, NULL, NULL),
(69, '2301301094', 'TEKNOLOGI INFORMASI', 5, '085248131800', 'L', 4.00, 0, 0, 0, 3, NULL, '2025-10-22 12:38:15', '2025-11-07 15:47:11', NULL, NULL, NULL),
(70, '2301301062', 'TEKNOLOGI INFORMASI', 5, '085754152200', 'L', 3.75, 0, 0, 0, 3, NULL, '2025-10-22 12:38:15', '2025-11-07 15:47:11', NULL, NULL, NULL),
(71, '2301301075', 'TEKNOLOGI INFORMASI', 5, '083862166800', 'P', 3.31, 0, 0, 0, 3, NULL, '2025-10-22 12:38:15', '2025-11-07 15:47:11', NULL, NULL, NULL),
(81, '2401301056', 'D3 Teknologi Informasi', 5, '83844879985', 'L', 3.90, 0, 0, 0, 3, 15, '2025-11-07 15:39:32', '2025-11-13 14:51:00', 'https://drive.google.com/file/d/...', 'https://drive.google.com/file/d/...', NULL),
(82, '2401301057', 'D3 Teknologi Informasi', 5, '83844879985', 'L', 3.80, 0, 0, 0, 3, NULL, '2025-11-09 09:43:19', '2025-11-09 09:43:19', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_penggantian_mitra`
--

CREATE TABLE `riwayat_penggantian_mitra` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `mitra_lama_id` bigint UNSIGNED DEFAULT NULL,
  `mitra_baru_id` bigint UNSIGNED NOT NULL,
  `jenis_alasan` enum('ditolak','alasan_tertentu','pilihan_pribadi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan_lengkap` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_penggantian_mitra`
--

INSERT INTO `riwayat_penggantian_mitra` (`id`, `mahasiswa_id`, `mitra_lama_id`, `mitra_baru_id`, `jenis_alasan`, `alasan_lengkap`, `created_at`, `updated_at`) VALUES
(3, 81, 16, 15, 'ditolak', NULL, '2025-11-11 03:40:16', '2025-11-11 03:40:16'),
(4, 81, 15, 10, 'ditolak', NULL, '2025-11-11 03:40:43', '2025-11-11 03:40:43'),
(5, 81, 10, 15, 'ditolak', NULL, '2025-11-13 14:51:00', '2025-11-13 14:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('jTRVIJ6yrZefNk7kjpDFEEkWJkImi4LySdqUbca9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUlA5SGxTSWZpNXlhdzMxS0ZoM3p4UFFWQnJ3b0pYRUcwdVZDeElheCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2dvb2dsZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTg6Imdvb2dsZV9vYXV0aF9zdGF0ZSI7czozMjoiNGVhMGQ5YTBhNmQyY2JiYWY3NDYwYTJkOGI0ZjQ0NTUiO3M6MjI6Imdvb2dsZV9vYXV0aF90aW1lc3RhbXAiO2k6MTc2MzA0NDcyNztzOjU6InN0YXRlIjtzOjQwOiJKdXJHcThoNll1OEVPT1ZjeUgyWTVhSDRpVkZYUXVrcE5kMmxUa2VtIjt9', 1763044728),
('wNtTcQpi3qU9gtj5hXKUV5wZmB7IPzyI3UxxesSc', 81, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVEZWdE1PdmRTYktycGoyNmViZXFlYVh3cklZTnRlYXdmaFlVaVhOUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kb2N1bWVudHMvbG9hZC1nZHJpdmUtbGlua3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4MTt9', 1763048057);

-- --------------------------------------------------------

--
-- Table structure for table `surat_balasan`
--

CREATE TABLE `surat_balasan` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `mitra_id` bigint UNSIGNED DEFAULT NULL,
  `mitra_nama_custom` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_balasan`
--

INSERT INTO `surat_balasan` (`id`, `mahasiswa_id`, `mitra_id`, `mitra_nama_custom`, `file_path`, `status_validasi`, `created_at`, `updated_at`) VALUES
(13, 81, 16, NULL, 'documents/surat_balasan/Surat_Balasan_Muhammad_Sodiq_2401301056_1762530207.pdf', 'menunggu', '2025-11-07 15:43:27', '2025-11-07 15:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `surat_pengantar`
--

CREATE TABLE `surat_pengantar` (
  `id` bigint UNSIGNED NOT NULL,
  `mahasiswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_validasi` enum('menunggu','belum_valid','tervalidasi','revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'laporan_pkl_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur upload Laporan PKL untuk mahasiswa', '2025-10-05 10:05:06', '2025-11-09 10:52:25'),
(2, 'penilaian_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur Penilaian untuk dosen pembimbing', '2025-10-05 17:20:11', '2025-11-09 10:52:25'),
(3, 'jadwal_seminar_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur Jadwal Seminar', '2025-10-05 17:20:11', '2025-11-09 10:52:25'),
(4, 'instansi_mitra_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur Instansi Mitra', '2025-10-05 17:40:13', '2025-11-09 10:52:25'),
(5, 'dokumen_pemberkasan_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan fitur upload dokumen pemberkasan (KHS, Surat Balasan)', '2025-10-05 17:40:13', '2025-11-09 10:52:25'),
(6, 'registration_enabled', '1', 'Toggle untuk mengaktifkan/menonaktifkan pendaftaran akun baru (termasuk Google OAuth)', '2025-10-05 17:56:31', '2025-11-10 06:43:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_linked` tinyint(1) NOT NULL DEFAULT '0',
  `google_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('mahasiswa','dospem','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `photo`, `google_linked`, `google_email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'First Atminnts', 'admin1@gmail.com', NULL, 0, NULL, NULL, '$2y$12$pZmg5OlGrdFDOKngawDFJ.wgQqR/NNXyBbIdOLNkeTG71Unb7d34S', 'admin', NULL, '2025-10-04 05:00:50', '2025-10-04 05:51:29'),
(3, 'Dr. Ahmad Wijaya, S.T., M.T.', 'dospem1@gmail.com', NULL, 0, NULL, NULL, '$2y$12$BZPpFv8/USLtNzGMMmVksOLqtayhOhXOjaVRoYEIFT408RYva/szy', 'dospem', NULL, '2025-10-04 05:00:51', '2025-10-04 05:00:51'),
(4, 'Dr. Siti Nurhaliza, S.T., M.T.', 'dospem2@gmail.com', NULL, 0, NULL, NULL, '$2y$12$MyycmSjzZxnn2xai8OPzx.aCTTvc4O8YTpVQ5mrGcdsFJadJxxjze', 'dospem', NULL, '2025-10-04 05:00:51', '2025-10-04 05:00:51'),
(5, 'Dr. Budi Santoso, S.T., M.T.', 'dospem3@gmail.com', NULL, 0, NULL, NULL, '$2y$12$RJG9TfUM9Dhv8eIVwFPU2exZs8as25GW4f.hP4/grC2zM2XZSJp9O', 'dospem', NULL, '2025-10-04 05:00:52', '2025-10-04 05:00:52'),
(62, 'Sayyidah Nafisah', 'sayyidahnafisah23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$NYYyR3Q6z.YEqz1iYesoA.EnE5qhJYI9R3Er17Vq6I5Ju4rtcsxv6', 'mahasiswa', NULL, '2025-10-22 12:31:21', '2025-10-22 12:31:21'),
(63, 'muhammad widigda', 'muhammadwidigdapratama23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$y.ruXrIm0Fnu8lm7NGE3I.dEhvkRRgC9it8IdbveLwBFxcg8IRdHa', 'mahasiswa', NULL, '2025-10-22 12:38:12', '2025-10-22 12:38:12'),
(64, 'M. Zainal Akli', 'mzainalakli23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$d6TVce9aWynenUoF2UpzwO0rzBnfdHGBBVeb8QZqTdjT.GOljaTiC', 'mahasiswa', NULL, '2025-10-22 12:38:13', '2025-10-22 12:38:13'),
(65, 'Ahmad Faisal Aditya', 'ahmadfaisaladitya23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$EpOyh3DzG7utnBid63/FY.8HHCMMVetMbK0Pt0B8NOejqUlcWKFc2', 'mahasiswa', NULL, '2025-10-22 12:38:13', '2025-10-22 12:38:13'),
(66, 'Zainal', 'zainal23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$EyvVIYBNcTDP8SESwLvshOJ5af9ToMtZ34VDJOEkMna0tiNuZa8CC', 'mahasiswa', NULL, '2025-10-22 12:38:14', '2025-10-22 12:38:14'),
(67, 'AIDA SEKAR NINGRUM', 'aidasekarningrum23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$ZQ2fjUOvB.FubAgr0IN5VO1StEPaWmohduLfHP05PcM6AT5GlLubW', 'mahasiswa', NULL, '2025-10-22 12:38:14', '2025-10-22 12:38:14'),
(68, 'Sima Sabrina', 'simasabrina23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$Dnju2bl0TUoxU7JZva6yDuVoHP.BOJ1vup/vj1GPhxptPSE0zWWoW', 'mahasiswa', NULL, '2025-10-22 12:38:14', '2025-10-22 12:38:14'),
(69, 'Muhammad Aditya', 'muhammadaditya23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$OYLlQ1spnB2RHi2bQJM/R.VTPmgRyaibn7.pZxSpFbZk7ZwuGEFQO', 'mahasiswa', NULL, '2025-10-22 12:38:15', '2025-10-22 12:38:15'),
(70, 'Muhammad Rifani', 'muhammadrifani23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$OekvZgi1PEY8fisuTM.9s.CHYiLDWvWtqvYXyQw/DD14XUa5q0Vlm', 'mahasiswa', NULL, '2025-10-22 12:38:15', '2025-10-22 12:38:15'),
(71, 'Ani Khairiyah', 'anikhairiyah23@mhs.politala.ac.id', NULL, 0, NULL, NULL, '$2y$12$2ZbRtWmbv9CyAJYKEx39/.iqhRFYMWVhvsGkWe9MUxcj07gOrZ0SW', 'mahasiswa', NULL, '2025-10-22 12:38:15', '2025-10-22 12:38:15'),
(74, 'Muhammad Fariz', 'fariz@gmail.com', NULL, 0, NULL, NULL, '$2y$12$RRwW4YRCN3to2d6Dnbpq4e5XJxjQxNwIdX.AQYmcqta4W7OFwrqWG', 'mahasiswa', NULL, '2025-11-07 05:51:48', '2025-11-07 05:51:48'),
(81, 'Muhammad Sodiq', 'muhammad.sodiq@mhs.politala.ac.id', 'https://lh3.googleusercontent.com/a/ACg8ocKt-H91MRh9uvV4aVnrWLobdMar4Aliqo_0xRRIK7Vq_XuG_xg=s96-c', 1, 'muhammad.sodiq@mhs.politala.ac.id', NULL, '$2y$12$Z.fEr2zFSP0.uMDKNQFV.O2AWBBgE03xaGIlZKEY3uMgzQNT.hnu6', 'mahasiswa', NULL, '2025-11-07 15:39:02', '2025-11-07 15:39:32'),
(82, 'Muhammad Sodiq', 'huamusika@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocKRq6fBvVxw5dReLzK_26_KLbA8jrc4YEZgYquHKwTeo9Rmhw=s96-c', 1, 'huamusika@gmail.com', NULL, '$2y$12$1orZh3g48.gjYz4joLRJIOPWMFvogiuN9wJBSiYX1MjhJX7cOIk.K', 'mahasiswa', NULL, '2025-11-09 09:42:28', '2025-11-09 09:43:19');

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
-- Indexes for table `dospems`
--
ALTER TABLE `dospems`
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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assessment_response_items`
--
ALTER TABLE `assessment_response_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `assessment_results`
--
ALTER TABLE `assessment_results`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dospems`
--
ALTER TABLE `dospems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `history_aktivitas`
--
ALTER TABLE `history_aktivitas`
  MODIFY `id_aktivitas` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_seminar`
--
ALTER TABLE `jadwal_seminar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `khs`
--
ALTER TABLE `khs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `khs_manual_transkrip`
--
ALTER TABLE `khs_manual_transkrip`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `profil_mahasiswa`
--
ALTER TABLE `profil_mahasiswa`
  MODIFY `id_mahasiswa` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `riwayat_penggantian_mitra`
--
ALTER TABLE `riwayat_penggantian_mitra`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `surat_balasan`
--
ALTER TABLE `surat_balasan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `surat_pengantar`
--
ALTER TABLE `surat_pengantar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

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
-- Constraints for table `dospems`
--
ALTER TABLE `dospems`
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
