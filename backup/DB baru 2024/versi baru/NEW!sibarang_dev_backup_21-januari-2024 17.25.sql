-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 21, 2024 at 10:25 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sibarang_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `log_import`
--

CREATE TABLE `log_import` (
  `id` int(11) NOT NULL,
  `path` text,
  `file` text,
  `wk_input` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alamat_customer`
--

CREATE TABLE `tbl_alamat_customer` (
  `id_alamat` bigint(20) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `provinsi_id` bigint(20) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `kabupaten_id` bigint(20) DEFAULT NULL,
  `alamat` text,
  `is_active` int(11) DEFAULT '1',
  `is_default` int(11) DEFAULT '0',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_banner`
--

CREATE TABLE `tbl_banner` (
  `id_banner` bigint(20) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `gambar` text NOT NULL,
  `is_active` int(11) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `user_input` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_edit` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang`
--

CREATE TABLE `tbl_barang` (
  `id_barang` bigint(20) NOT NULL,
  `kategori_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_satuan',
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `slug_barang` text NOT NULL,
  `barcode_barang` text NOT NULL,
  `harga_pokok` decimal(65,0) NOT NULL,
  `berat_barang` double NOT NULL,
  `deskripsi` text NOT NULL,
  `informasi` text,
  `gambar` text,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_harga_history`
--

CREATE TABLE `tbl_barang_harga_history` (
  `id_barang_history` bigint(20) NOT NULL,
  `barang_id` bigint(20) NOT NULL,
  `kategori_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_satuan',
  `harga_pokok` decimal(65,0) NOT NULL,
  `berat_barang` double NOT NULL,
  `tanggal_perubahan` datetime NOT NULL,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_keluar`
--

CREATE TABLE `tbl_barang_keluar` (
  `id_barang_keluar` bigint(20) NOT NULL,
  `toko_id` bigint(20) DEFAULT NULL,
  `order_detail_id` bigint(20) DEFAULT NULL,
  `harga_id` bigint(20) NOT NULL,
  `jenis_keluar` varchar(100) NOT NULL COMMENT 'TERJUAL DISTRIBUSI',
  `request_id` bigint(20) DEFAULT '0',
  `jml_keluar` bigint(20) NOT NULL,
  `bukti_keluar` text NOT NULL,
  `tanggal_barang_keluar` datetime DEFAULT NULL,
  `is_rollback` int(11) DEFAULT '0',
  `user_input` varchar(100) NOT NULL,
  `user_edit` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_masuk`
--

CREATE TABLE `tbl_barang_masuk` (
  `id_barang_masuk` bigint(20) NOT NULL,
  `harga_id` bigint(20) DEFAULT NULL,
  `jml_masuk` bigint(20) DEFAULT NULL,
  `bukti_beli` text,
  `tipe` varchar(100) DEFAULT NULL COMMENT '''toko_luar'',''gudang'', ''antar_toko''',
  `tanggal_barang_masuk` datetime DEFAULT NULL,
  `nama_supplier` varchar(255) DEFAULT NULL,
  `nomor_supplier` varchar(255) DEFAULT NULL,
  `nama_toko_beli` varchar(100) DEFAULT NULL COMMENT 'kusus untuk beli dari toko luar',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_barang_temp`
--

CREATE TABLE `tbl_barang_temp` (
  `id_barang_temp` bigint(20) NOT NULL,
  `kategori_id` text NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` text NOT NULL COMMENT 'relasi ke tbl_satuan',
  `kode_barang` text NOT NULL,
  `nama_barang` text NOT NULL,
  `slug_barang` text NOT NULL,
  `barcode_barang` text NOT NULL,
  `harga_pokok` text NOT NULL,
  `berat_barang` text NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` text,
  `keterangan` text,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `id_customer` bigint(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hp` varchar(50) DEFAULT NULL,
  `password` text,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_diskon`
--

CREATE TABLE `tbl_diskon` (
  `id_diskon` bigint(20) NOT NULL,
  `harga_id` bigint(20) DEFAULT NULL,
  `nama_diskon` varchar(100) DEFAULT NULL,
  `harga_potongan` double DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_akhir` date DEFAULT NULL,
  `minimal_beli` bigint(20) DEFAULT '1',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_active` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_harga`
--

CREATE TABLE `tbl_harga` (
  `id_harga` bigint(20) NOT NULL,
  `barang_id` bigint(20) DEFAULT NULL,
  `toko_id` bigint(20) DEFAULT NULL,
  `stok_toko` bigint(20) DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_harga_temp`
--

CREATE TABLE `tbl_harga_temp` (
  `id_harga` bigint(20) NOT NULL,
  `barang_id` text,
  `toko_id` text,
  `stok_toko` text,
  `harga_jual` double DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_harga_toko_history`
--

CREATE TABLE `tbl_harga_toko_history` (
  `id_harga_toko_history` bigint(20) NOT NULL,
  `harga_id` bigint(20) NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_karyawan_toko`
--

CREATE TABLE `tbl_karyawan_toko` (
  `id_karyawan` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_user',
  `toko_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_toko',
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `hp_karyawan` varchar(100) DEFAULT NULL,
  `alamat_karyawan` text,
  `bagian` varchar(100) DEFAULT NULL,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_karyawan_toko_temp`
--

CREATE TABLE `tbl_karyawan_toko_temp` (
  `id_karyawan_toko_temp` bigint(20) NOT NULL,
  `role_id` text,
  `toko_id` text,
  `username` text,
  `nama_karyawan` text,
  `hp_karyawan` text,
  `alamat_karyawan` text,
  `bagian` text,
  `is_sess_toko` int(11) DEFAULT NULL,
  `user_input` text NOT NULL,
  `user_update` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` bigint(20) NOT NULL,
  `kode_kategori` varchar(255) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `slug_kategori` text NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_kategori`
--

INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES
(1, 'LISTRIK', 'Alat Listrik', 'alat-listrik', 1, 'sistem', 'sistem', '2023-12-22 01:30:01', '2024-01-03 14:01:14'),
(2, 'BANGUNAN', 'Bangunan', 'bangunan', 1, 'sistem', 'sistem', '2023-12-22 01:30:29', '2024-01-03 14:01:41'),
(3, 'PERKAKAS_DAPUR', 'Gerabah', 'gerabah', 1, 'sistem', 'sistem', '2024-01-01 13:14:23', '2024-01-03 14:01:52'),
(4, 'ALAT TULIS', 'Alat Tulis', 'alat-tulis', 1, 'sistem', 'sistem', '2024-01-03 14:02:05', '2024-01-03 14:02:05'),
(5, 'PERIKANAN', 'Perikanan', 'perikanan', 0, 'sistem', 'sistem', '2024-01-04 00:46:10', '2024-01-17 21:19:49'),
(6, 'SABUN', 'sabun cuci', 'sabun-cuci', 1, 'sistem', 'sistem', '2024-01-17 21:22:09', '2024-01-17 21:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `id_menu` int(11) NOT NULL,
  `menu` varchar(100) NOT NULL,
  `icon` varchar(155) NOT NULL,
  `is_active` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES
(1, 'Dashboard', 'menu-icon tf-icons bx bx-home-circle', 1),
(2, 'Management Akses', 'menu-icon tf-icons bx bx-lock-open', 1),
(3, 'Toko', 'menu-icon tf-icons bx bxs-store', 1),
(4, 'Barang', 'menu-icon tf-icons bx bxs-buildings', 1),
(5, 'Kasir', 'menu-icon tf-icons bx bxs-wallet', 1),
(6, 'Laporan', 'menu-icon tf-icons bx bxs-report', 1),
(7, 'Setting', 'menu-icon tf-icons bx bxs-cog', 1),
(8, 'Marketplace', 'menu-icon tf-icons bx bx-store', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_access`
--

CREATE TABLE `tbl_menu_access` (
  `id_menu_access` bigint(20) NOT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `menu_id` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_menu_access`
--

INSERT INTO `tbl_menu_access` (`id_menu_access`, `role_id`, `menu_id`) VALUES
(27, 1, 8),
(26, 1, 7),
(25, 1, 6),
(24, 1, 5),
(23, 1, 4),
(22, 1, 3),
(21, 1, 2),
(20, 1, 1),
(9, 2, 1),
(10, 2, 3),
(11, 2, 4),
(12, 2, 5),
(13, 2, 6),
(14, 2, 7),
(15, 2, 8),
(16, 3, 1),
(17, 3, 4),
(18, 3, 5),
(19, 3, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id_order` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Opsional berlaku pada marketplace',
  `toko_id` bigint(20) NOT NULL,
  `kode_order` longtext NOT NULL,
  `nama_cust` varchar(100) DEFAULT NULL,
  `hp_cust` varchar(100) DEFAULT NULL,
  `alamat_cust` text,
  `tipe_order` enum('Marketplace','Kasir') NOT NULL,
  `tipe_pengiriman` text NOT NULL,
  `biaya_kirim` decimal(10,0) NOT NULL,
  `bukti_kirim` text,
  `total_order` decimal(16,0) NOT NULL,
  `paidst` int(11) NOT NULL DEFAULT '0',
  `status` int(11) DEFAULT '1' COMMENT '1 = belum konfirmasi, 2 = sudah konfirmasi, 3 = dikemas, 4 = dikirim, 5 = selesai, 99 = dibatalkan',
  `is_active` int(11) NOT NULL DEFAULT '1',
  `user_edit` varchar(100) DEFAULT NULL,
  `user_input` varchar(100) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_detail`
--

CREATE TABLE `tbl_order_detail` (
  `id_order_detail` bigint(20) NOT NULL,
  `harga_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `harga_total` double DEFAULT NULL,
  `harga_potongan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `user_edit` varchar(100) DEFAULT NULL,
  `user_input` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_marketplace`
--

CREATE TABLE `tbl_order_marketplace` (
  `id_order_marketplace` bigint(20) NOT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_privilege_menu`
--

CREATE TABLE `tbl_privilege_menu` (
  `id_privilege` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_privilege_menu`
--

INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 2, 1),
(10, 2, 3),
(11, 2, 4),
(12, 2, 5),
(13, 2, 6),
(14, 2, 7),
(15, 2, 8),
(16, 3, 1),
(17, 3, 4),
(18, 3, 5),
(19, 3, 7),
(20, 3, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_privilege_submenu`
--

CREATE TABLE `tbl_privilege_submenu` (
  `id_privilege_submenu` int(11) NOT NULL,
  `privilege_id` int(11) NOT NULL,
  `submenu_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_privilege_submenu`
--

INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 9),
(6, 3, 5),
(7, 3, 6),
(8, 3, 16),
(9, 4, 10),
(10, 4, 11),
(11, 4, 12),
(12, 4, 14),
(13, 4, 15),
(14, 4, 18),
(15, 4, 27),
(16, 5, 13),
(17, 5, 17),
(18, 5, 19),
(19, 5, 20),
(20, 6, 21),
(21, 6, 23),
(22, 6, 25),
(23, 6, 26),
(24, 7, 7),
(25, 7, 8),
(26, 9, 1),
(27, 10, 5),
(28, 10, 6),
(29, 10, 16),
(30, 11, 10),
(31, 11, 11),
(32, 11, 12),
(33, 11, 14),
(34, 11, 15),
(35, 11, 18),
(36, 11, 27),
(37, 12, 13),
(38, 12, 17),
(39, 12, 19),
(40, 12, 20),
(41, 13, 21),
(42, 13, 23),
(43, 13, 25),
(44, 13, 26),
(45, 14, 7),
(46, 14, 8),
(47, 16, 1),
(48, 17, 15),
(49, 18, 17),
(50, 18, 19),
(51, 18, 20),
(52, 19, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_report_penjualan`
--

CREATE TABLE `tbl_report_penjualan` (
  `id` bigint(20) NOT NULL,
  `toko_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `transaksi_id` bigint(20) DEFAULT NULL,
  `harga_id` bigint(20) DEFAULT NULL,
  `barang_id` bigint(20) DEFAULT NULL,
  `harga_satuan_pokok` double DEFAULT NULL,
  `harga_satuan_jual` double DEFAULT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `total_harga_pokok` double DEFAULT NULL,
  `total_harga_jual` double DEFAULT NULL,
  `total_diskon` double DEFAULT NULL,
  `total_keuntungan` double DEFAULT NULL,
  `tanggal_beli` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_rollback` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_delete_barang`
--

CREATE TABLE `tbl_request_delete_barang` (
  `id_request` bigint(20) NOT NULL,
  `barang_id` bigint(20) DEFAULT NULL,
  `harga_id` bigint(20) DEFAULT NULL,
  `type_request` varchar(255) DEFAULT NULL COMMENT 'delete_barang, delete_barang_toko',
  `keterangan` text COMMENT 'alasan',
  `is_deleted` int(11) DEFAULT '0' COMMENT 'jika 0 belum terhapus, tapi jika 1 sudah dihapus',
  `requested_shop` varchar(255) DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `requested_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_toko`
--

CREATE TABLE `tbl_request_toko` (
  `id_request` bigint(20) NOT NULL,
  `kode_request` varchar(100) DEFAULT NULL,
  `request_toko_id` bigint(20) DEFAULT NULL,
  `penerima_toko_id` bigint(20) DEFAULT NULL,
  `atribut_barang` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `pengirim` varchar(100) DEFAULT NULL,
  `status` text COMMENT '"proses","terkirim","draft"',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_role`
--

CREATE TABLE `tbl_role` (
  `id_role` int(11) NOT NULL,
  `role` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_role`
--

INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES
(1, 'Developer', 1),
(2, 'Superadmin', 1),
(3, 'Kasir', 1),
(4, 'Customer', 1),
(5, 'Inventory', 1),
(6, 'Direktur', 1),
(7, 'Akuntan', 1),
(8, 'Marketing', 1),
(9, 'Keuangan', 1),
(17, 'HRD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_satuan`
--

CREATE TABLE `tbl_satuan` (
  `id_satuan` bigint(20) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `is_active` int(11) DEFAULT '1',
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_satuan`
--

INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES
(1, 'KG', 1, 'sistem', 'sistem', '2023-12-22 01:31:04', '2023-12-22 01:31:04'),
(2, 'METER', 1, 'sistem', 'sistem', '2023-12-22 01:31:24', '2023-12-22 01:31:33'),
(3, 'GRAM', 1, 'sistem', NULL, '0000-00-00 00:00:00', NULL),
(4, 'ONS', 1, 'sistem', NULL, '0000-00-00 00:00:00', NULL),
(5, 'LEMBAR', 1, 'sistem', 'sistem', '2024-01-03 14:02:37', '2024-01-03 14:02:37'),
(7, 'BUNGKUS', 1, 'sistem', 'sistem', '2024-01-04 00:51:14', '2024-01-04 09:24:35'),
(8, 'ROLL', 1, 'sistem', 'sistem', '2024-01-04 09:25:04', '2024-01-04 09:25:04'),
(9, 'BUAH', 1, 'sistem', 'sistem', '2024-01-04 09:25:20', '2024-01-04 09:25:20'),
(10, 'PCS', 1, 'sistem', 'sistem', '2024-01-04 09:25:30', '2024-01-04 09:25:30'),
(11, 'PACK', 1, 'sistem', 'sistem', '2024-01-04 12:03:04', '2024-01-04 12:03:04'),
(12, 'DUS', 1, 'sistem', 'sistem', '2024-01-04 12:03:10', '2024-01-04 12:03:10'),
(13, 'SLOP', 1, 'sistem', 'sistem', '2024-01-04 12:03:24', '2024-01-04 12:03:24'),
(14, 'SET', 1, 'sistem', 'sistem', '2024-01-04 12:03:30', '2024-01-04 12:03:30'),
(15, 'dos', 0, 'sistem', 'sistem', '2024-01-04 12:06:46', '2024-01-04 12:07:12'),
(16, 'GROSS', 1, 'sistem', 'sistem', '2024-01-04 14:23:45', '2024-01-04 14:23:45'),
(17, 'RENTENG', 1, 'sistem', 'sistem', '2024-01-04 14:24:20', '2024-01-04 14:24:20'),
(18, 'RIM', 1, 'sistem', 'sistem', '2024-01-04 14:24:56', '2024-01-04 14:24:56'),
(19, 'BIJI', 1, 'sistem', 'sistem', '2024-01-04 17:00:45', '2024-01-04 17:00:45'),
(20, 'KALENG', 1, 'sistem', 'sistem', '2024-01-04 17:02:21', '2024-01-04 17:02:21'),
(21, 'LUSIN', 1, 'sistem', 'sistem', '2024-01-04 17:02:44', '2024-01-04 17:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_setting`
--

CREATE TABLE `tbl_setting` (
  `id_setting` bigint(20) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `kode_instansi` varchar(255) NOT NULL,
  `alamat_instansi` varchar(255) DEFAULT NULL,
  `owner` varchar(255) NOT NULL,
  `wa_instansi` varchar(255) NOT NULL COMMENT 'Untuk Terdaftar di WA API',
  `wa_admin` varchar(255) NOT NULL COMMENT 'Untuk menerima pesan notifikasi, ketika ada order baru',
  `tlp_instansi` varchar(255) NOT NULL COMMENT 'nomor office kantor\n',
  `ig_instansi` varchar(255) DEFAULT NULL,
  `fb_instansi` varchar(255) DEFAULT NULL,
  `email_instansi` varchar(255) DEFAULT NULL,
  `deskripsi_singkat` text,
  `img_instansi` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_setting`
--

INSERT INTO `tbl_setting` (`id_setting`, `instansi`, `kode_instansi`, `alamat_instansi`, `owner`, `wa_instansi`, `wa_admin`, `tlp_instansi`, `ig_instansi`, `fb_instansi`, `email_instansi`, `deskripsi_singkat`, `img_instansi`) VALUES
(1, 'ardhaPOS', 'ARDHAPOS', 'Sidoarjo', 'sembarang', '628787687682', '6281234567898', '2799896', 'wsss', 'ssdff', 'robby@ardhacodes.com', NULL, 'ARDHAPOS1704309483.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_submenu`
--

CREATE TABLE `tbl_submenu` (
  `id_submenu` bigint(20) NOT NULL,
  `menu_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `uri` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_submenu`
--

INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `uri`, `url`, `is_active`) VALUES
(1, 1, 'Dashboard', 'dashboard', 'dashboard', 1),
(2, 2, 'Role', 'role_akses', 'role_akses', 1),
(3, 2, 'Menu', 'access', 'access/menu', 1),
(4, 2, 'Sub Menu', 'access', 'access/submenu', 1),
(5, 3, 'Management Toko', 'toko', 'toko/store_management', 1),
(6, 3, 'Karyawan Toko', 'toko', 'toko/karyawan', 1),
(7, 7, 'Setting Instansi', 'setting', 'setting', 1),
(8, 7, 'Backup Database', 'setting', 'setting/backupdb', 1),
(9, 2, 'User', 'user', 'user/index', 1),
(10, 4, 'Kategori', 'barang', 'barang/kategori', 1),
(11, 4, 'Satuan', 'barang', 'barang/satuan', 1),
(12, 4, 'Barang', 'barang', 'barang/index', 1),
(13, 5, 'Order', 'kasir', 'kasir/order', 1),
(14, 4, 'Barang Masuk', 'barang', 'barang/masuk', 1),
(15, 4, 'Barang Toko', 'barang', 'barang/barang_toko', 1),
(16, 3, 'Diskon', 'toko', 'toko/diskon', 1),
(17, 5, 'Scan', 'kasir', 'kasir/scan', 1),
(18, 4, 'Barang Keluar', 'barang', 'barang/keluar', 1),
(19, 5, 'Sales order', 'kasir', 'kasir/sales_order', 1),
(20, 5, 'Transaksi', 'kasir', 'kasir/transaksi', 1),
(21, 6, 'Laporan Penjualan', 'laporan', 'laporan/penjualan', 1),
(22, 6, 'Riwayat Transaksi', 'riwayat', 'riwayat/transaksi', 0),
(23, 6, 'Laporan Transaksi', 'laporan', 'laporan/transaksi', 1),
(24, 9, 'Banner', 'marketplace', 'marketplace/banner', 1),
(25, 6, 'Laporan Pendapatan', 'laporan', 'laporan/pendapatan', 1),
(26, 6, 'Laporan Stok', 'laporan', 'laporan/stok', 1),
(27, 4, 'Request Hapus Barang', 'barang', 'barang/request_hapus_barang', 1),
(28, 8, 'Banner', 'marketplace', 'marketplace/banner', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_submenu_access`
--

CREATE TABLE `tbl_submenu_access` (
  `id_submenu_access` bigint(20) NOT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `submenu_id` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_submenu_access`
--

INSERT INTO `tbl_submenu_access` (`id_submenu_access`, `role_id`, `submenu_id`) VALUES
(77, 1, 8),
(76, 1, 7),
(75, 1, 26),
(74, 1, 25),
(73, 1, 23),
(72, 1, 21),
(71, 1, 20),
(70, 1, 19),
(69, 1, 17),
(68, 1, 13),
(67, 1, 27),
(66, 1, 18),
(65, 1, 15),
(64, 1, 14),
(63, 1, 12),
(62, 1, 11),
(61, 1, 10),
(60, 1, 16),
(59, 1, 6),
(58, 1, 5),
(57, 1, 9),
(56, 1, 4),
(55, 1, 3),
(54, 1, 2),
(53, 1, 1),
(26, 2, 1),
(27, 2, 5),
(28, 2, 6),
(29, 2, 16),
(30, 2, 10),
(31, 2, 11),
(32, 2, 12),
(33, 2, 14),
(34, 2, 15),
(35, 2, 18),
(36, 2, 27),
(37, 2, 13),
(38, 2, 17),
(39, 2, 19),
(40, 2, 20),
(41, 2, 21),
(42, 2, 23),
(43, 2, 25),
(44, 2, 26),
(45, 2, 7),
(46, 2, 8),
(47, 3, 1),
(48, 3, 14),
(49, 3, 13),
(50, 3, 17),
(51, 3, 19),
(52, 3, 20),
(78, 1, 28);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_toko`
--

CREATE TABLE `tbl_toko` (
  `id_toko` bigint(20) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `alamat_toko` text NOT NULL,
  `notelp_toko` varchar(15) NOT NULL,
  `jenis` varchar(255) NOT NULL COMMENT 'MARKETPLACE | TOKO',
  `is_active` int(11) NOT NULL DEFAULT '1',
  `user_input` varchar(255) NOT NULL,
  `user_edit` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_toko`
--

INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES
(1, 'SA ONLINE', 'SA ONLINE', '6285257162700', 'MARKETPLACE', 1, '', 'sistem', '2024-01-17 08:51:47', '2023-12-22 01:23:25'),
(2, 'TOKO KUNING', 'Jl. Raya Surabaya Kapuas No.04-AA1', '987689792', 'TOKO', 1, '', 'ss', '2024-01-01 12:37:28', '2023-12-22 01:23:25'),
(3, 'TOKO BIRU', 'hxjchsvjcv', '62812344556', 'TOKO', 0, 'Sinyo', NULL, '2023-12-22 23:06:04', NULL),
(4, 'TOKO HIJAU', 'ahsjhsdsa', '628123445561313', 'TOKO', 0, 'Sinyo', NULL, '2023-12-22 23:07:00', NULL),
(5, 'TOKO MERAH', 'Consequatur velit c', '6281234563277', 'TOKO', 0, 'Sinyo', NULL, '2023-12-27 23:24:33', NULL),
(6, 'TOKO SUMBER BANGUNAN', 'Jl. Ahmad Yani No.145, Megersari, Gedangan, Kec. Gedangan, Kabupaten Sidoarjo, Jawa Timur 61254', '62817339663', 'TOKO', 0, '', NULL, '2024-01-03 23:44:48', NULL),
(7, 'sumber jaya oke', 'asdasdasdasdasdasd', '567890', 'TOKO', 0, '', NULL, '2024-01-04 01:38:08', NULL),
(8, 'SUMBER AGUNG SURABAYA', 'JL. SAMBIKEREP NO. 13 SURABAYA', '6285257162700', 'TOKO', 1, '', NULL, '2024-01-17 08:58:26', NULL),
(9, 'SUMBER AGUNG PUSAT', 'Perum. QUALITY REVERSIDE 2 CLUSTER DAISY BLOK II-27 RT. 29 RW. 07, KEMASAN KRIAN SIDOARJO', '6285257162700', 'TOKO', 1, 'Owner Toko', NULL, '2024-01-17 08:49:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaksi`
--

CREATE TABLE `tbl_transaksi` (
  `id_transaksi` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `kode_transaksi` text,
  `terbayar` double DEFAULT '0' COMMENT 'Nominal Customer Membayar',
  `kembalian` double DEFAULT '0' COMMENT 'Nominal Kembalian Customer',
  `tagihan_cart` double DEFAULT NULL COMMENT 'Tagihan Keranjang',
  `total_diskon` double DEFAULT NULL COMMENT 'Jumlah Keseluruhan Diskon',
  `tagihan_after_diskon` double DEFAULT NULL COMMENT 'Tagihan Keranjang - Total Diskon',
  `total_biaya_kirim` double DEFAULT NULL COMMENT 'Jumlah Nominal Biaya Kirim',
  `total_tagihan` double DEFAULT NULL COMMENT 'Total Tagihan = Tagihan After Diskon + Biaya Kirim',
  `tipe_transaksi` varchar(255) DEFAULT NULL COMMENT '"TRANSFER","TUNAI"',
  `bukti_tf` text,
  `tanggal_beli` datetime DEFAULT NULL,
  `is_active` int(11) DEFAULT '1',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` bigint(20) NOT NULL,
  `username` varchar(200) NOT NULL,
  `nama_user` varchar(200) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role_id` int(11) NOT NULL,
  `type_user` varchar(255) DEFAULT NULL COMMENT 'Jika Developer Akan Muncul Menu Management Akses\n\ndan tidak muncul pada menu karyawan toko\n\n\njika null tidak memiliki management akses',
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'robby@ardhacodes.com', 'SINYO', '$2y$10$LehzOBWPnnLpkjTMN4V3fON8golwh6AqGhS5EzLWZ1qLBeHqJwvPC', 1, 'DEV', 1, '2024-01-01 13:00:27', '2024-01-01 13:00:46'),
(2, 'dev@ardhacodes.com', 'DEVELOPER', '$2y$10$rggSg5krhFPztTGHNYPq4O9rHHBy.5JUeo0XNr5ibP9nKQiyf28y2', 1, 'DEV', 1, '2024-01-01 13:00:27', '2024-01-01 13:03:59'),
(3, 'owner', 'Owner Toko', '$2y$10$3I216e2lj1gmWm1VYCE0iuW2IKYU3nj6da/nl8kHofdzkpmJNFN1a', 2, NULL, 1, '2024-01-01 13:25:19', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_import`
--
ALTER TABLE `log_import`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `tbl_alamat_customer`
--
ALTER TABLE `tbl_alamat_customer`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `FK_alamat_customer` (`customer_id`);

--
-- Indexes for table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  ADD PRIMARY KEY (`id_banner`);

--
-- Indexes for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `index_barang` (`id_barang`,`kategori_id`,`satuan_id`,`kode_barang`),
  ADD KEY `FK_barang_kategori` (`kategori_id`),
  ADD KEY `FK_barang_satuan` (`satuan_id`);

--
-- Indexes for table `tbl_barang_harga_history`
--
ALTER TABLE `tbl_barang_harga_history`
  ADD PRIMARY KEY (`id_barang_history`) USING BTREE;

--
-- Indexes for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  ADD PRIMARY KEY (`id_barang_keluar`),
  ADD KEY `FK_barkel_toko` (`toko_id`),
  ADD KEY `FK_barkel_orderdetail` (`order_detail_id`),
  ADD KEY `FK_barkel_harga` (`harga_id`);

--
-- Indexes for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  ADD PRIMARY KEY (`id_barang_masuk`),
  ADD KEY `FK_bar_masuk_harga` (`harga_id`);

--
-- Indexes for table `tbl_barang_temp`
--
ALTER TABLE `tbl_barang_temp`
  ADD PRIMARY KEY (`id_barang_temp`) USING BTREE;

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `tbl_diskon`
--
ALTER TABLE `tbl_diskon`
  ADD PRIMARY KEY (`id_diskon`),
  ADD KEY `FK_diskon_harga` (`harga_id`);

--
-- Indexes for table `tbl_harga`
--
ALTER TABLE `tbl_harga`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `index_harga` (`id_harga`,`barang_id`,`toko_id`),
  ADD KEY `FK_harga_barang` (`barang_id`),
  ADD KEY `FK_harga_toko` (`toko_id`);

--
-- Indexes for table `tbl_harga_temp`
--
ALTER TABLE `tbl_harga_temp`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `index_name` (`id_harga`) USING BTREE;
ALTER TABLE `tbl_harga_temp` ADD FULLTEXT KEY `index_name2` (`barang_id`,`toko_id`,`stok_toko`);

--
-- Indexes for table `tbl_harga_toko_history`
--
ALTER TABLE `tbl_harga_toko_history`
  ADD PRIMARY KEY (`id_harga_toko_history`) USING BTREE,
  ADD KEY `index_harga` (`harga_id`);

--
-- Indexes for table `tbl_karyawan_toko`
--
ALTER TABLE `tbl_karyawan_toko`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD KEY `FK_karyawan_user` (`user_id`),
  ADD KEY `FK_karyawan_toko` (`toko_id`);

--
-- Indexes for table `tbl_karyawan_toko_temp`
--
ALTER TABLE `tbl_karyawan_toko_temp`
  ADD PRIMARY KEY (`id_karyawan_toko_temp`);

--
-- Indexes for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD KEY `index_kategori` (`id_kategori`,`kode_kategori`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `tbl_menu_access`
--
ALTER TABLE `tbl_menu_access`
  ADD PRIMARY KEY (`id_menu_access`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `index_order` (`id_order`,`user_id`,`toko_id`),
  ADD KEY `FK_order_user` (`user_id`),
  ADD KEY `FK_order_toko` (`toko_id`);
ALTER TABLE `tbl_order` ADD FULLTEXT KEY `kode_order_index` (`kode_order`);

--
-- Indexes for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  ADD PRIMARY KEY (`id_order_detail`),
  ADD KEY `index_order_detail` (`id_order_detail`,`harga_id`,`order_id`),
  ADD KEY `FK_ord_det_harga` (`harga_id`),
  ADD KEY `FK_ord_det_order` (`order_id`);

--
-- Indexes for table `tbl_order_marketplace`
--
ALTER TABLE `tbl_order_marketplace`
  ADD PRIMARY KEY (`id_order_marketplace`);

--
-- Indexes for table `tbl_privilege_menu`
--
ALTER TABLE `tbl_privilege_menu`
  ADD PRIMARY KEY (`id_privilege`);

--
-- Indexes for table `tbl_privilege_submenu`
--
ALTER TABLE `tbl_privilege_submenu`
  ADD PRIMARY KEY (`id_privilege_submenu`);

--
-- Indexes for table `tbl_report_penjualan`
--
ALTER TABLE `tbl_report_penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_request_delete_barang`
--
ALTER TABLE `tbl_request_delete_barang`
  ADD PRIMARY KEY (`id_request`);

--
-- Indexes for table `tbl_request_toko`
--
ALTER TABLE `tbl_request_toko`
  ADD PRIMARY KEY (`id_request`);

--
-- Indexes for table `tbl_role`
--
ALTER TABLE `tbl_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  ADD PRIMARY KEY (`id_satuan`),
  ADD KEY `index_satuan` (`id_satuan`);

--
-- Indexes for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indexes for table `tbl_submenu`
--
ALTER TABLE `tbl_submenu`
  ADD PRIMARY KEY (`id_submenu`),
  ADD KEY `FK_submenu_menu` (`menu_id`);

--
-- Indexes for table `tbl_submenu_access`
--
ALTER TABLE `tbl_submenu_access`
  ADD PRIMARY KEY (`id_submenu_access`);

--
-- Indexes for table `tbl_toko`
--
ALTER TABLE `tbl_toko`
  ADD PRIMARY KEY (`id_toko`),
  ADD KEY `index_toko` (`id_toko`);

--
-- Indexes for table `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `index_kodetrans` (`kode_transaksi`(1000)),
  ADD KEY `index_transaksi` (`id_transaksi`,`order_id`) USING BTREE,
  ADD KEY `FK_order` (`order_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_import`
--
ALTER TABLE `log_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_alamat_customer`
--
ALTER TABLE `tbl_alamat_customer`
  MODIFY `id_alamat` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_banner`
--
ALTER TABLE `tbl_banner`
  MODIFY `id_banner` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  MODIFY `id_barang` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_barang_harga_history`
--
ALTER TABLE `tbl_barang_harga_history`
  MODIFY `id_barang_history` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  MODIFY `id_barang_keluar` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  MODIFY `id_barang_masuk` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_barang_temp`
--
ALTER TABLE `tbl_barang_temp`
  MODIFY `id_barang_temp` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `id_customer` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_diskon`
--
ALTER TABLE `tbl_diskon`
  MODIFY `id_diskon` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_harga`
--
ALTER TABLE `tbl_harga`
  MODIFY `id_harga` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_harga_temp`
--
ALTER TABLE `tbl_harga_temp`
  MODIFY `id_harga` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_harga_toko_history`
--
ALTER TABLE `tbl_harga_toko_history`
  MODIFY `id_harga_toko_history` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_karyawan_toko`
--
ALTER TABLE `tbl_karyawan_toko`
  MODIFY `id_karyawan` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_karyawan_toko_temp`
--
ALTER TABLE `tbl_karyawan_toko_temp`
  MODIFY `id_karyawan_toko_temp` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  MODIFY `id_kategori` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_menu_access`
--
ALTER TABLE `tbl_menu_access`
  MODIFY `id_menu_access` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id_order` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  MODIFY `id_order_detail` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_marketplace`
--
ALTER TABLE `tbl_order_marketplace`
  MODIFY `id_order_marketplace` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_privilege_menu`
--
ALTER TABLE `tbl_privilege_menu`
  MODIFY `id_privilege` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_privilege_submenu`
--
ALTER TABLE `tbl_privilege_submenu`
  MODIFY `id_privilege_submenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `tbl_report_penjualan`
--
ALTER TABLE `tbl_report_penjualan`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_request_delete_barang`
--
ALTER TABLE `tbl_request_delete_barang`
  MODIFY `id_request` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_request_toko`
--
ALTER TABLE `tbl_request_toko`
  MODIFY `id_request` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_role`
--
ALTER TABLE `tbl_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_satuan`
--
ALTER TABLE `tbl_satuan`
  MODIFY `id_satuan` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_setting`
--
ALTER TABLE `tbl_setting`
  MODIFY `id_setting` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_submenu`
--
ALTER TABLE `tbl_submenu`
  MODIFY `id_submenu` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_submenu_access`
--
ALTER TABLE `tbl_submenu_access`
  MODIFY `id_submenu_access` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `tbl_toko`
--
ALTER TABLE `tbl_toko`
  MODIFY `id_toko` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  MODIFY `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_alamat_customer`
--
ALTER TABLE `tbl_alamat_customer`
  ADD CONSTRAINT `FK_alamat_customer` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id_customer`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_barang`
--
ALTER TABLE `tbl_barang`
  ADD CONSTRAINT `FK_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `tbl_kategori` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_barang_satuan` FOREIGN KEY (`satuan_id`) REFERENCES `tbl_satuan` (`id_satuan`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_barang_keluar`
--
ALTER TABLE `tbl_barang_keluar`
  ADD CONSTRAINT `FK_barkel_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_barkel_orderdetail` FOREIGN KEY (`order_detail_id`) REFERENCES `tbl_order_detail` (`id_order_detail`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_barkel_toko` FOREIGN KEY (`toko_id`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_barang_masuk`
--
ALTER TABLE `tbl_barang_masuk`
  ADD CONSTRAINT `FK_bar_masuk_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_diskon`
--
ALTER TABLE `tbl_diskon`
  ADD CONSTRAINT `FK_diskon_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_harga`
--
ALTER TABLE `tbl_harga`
  ADD CONSTRAINT `FK_harga_barang` FOREIGN KEY (`barang_id`) REFERENCES `tbl_barang` (`id_barang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_harga_toko` FOREIGN KEY (`toko_id`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD CONSTRAINT `FK_order_toko` FOREIGN KEY (`toko_id`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_order_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_order_detail`
--
ALTER TABLE `tbl_order_detail`
  ADD CONSTRAINT `FK_ord_det_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ord_det_order` FOREIGN KEY (`order_id`) REFERENCES `tbl_order` (`id_order`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_transaksi`
--
ALTER TABLE `tbl_transaksi`
  ADD CONSTRAINT `FK_order` FOREIGN KEY (`order_id`) REFERENCES `tbl_order` (`id_order`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
