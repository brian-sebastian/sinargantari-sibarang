/*
 Navicat Premium Data Transfer

 Source Server         : Sibara
 Source Server Type    : MySQL
 Source Server Version : 100616 (10.6.16-MariaDB-cll-lve)
 Source Host           : ardhacodes.com:3306
 Source Schema         : ardhacod_sibara

 Target Server Type    : MySQL
 Target Server Version : 100616 (10.6.16-MariaDB-cll-lve)
 File Encoding         : 65001

 Date: 01/01/2024 14:06:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for log_import
-- ----------------------------
DROP TABLE IF EXISTS `log_import`;
CREATE TABLE `log_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text DEFAULT NULL,
  `file` text DEFAULT NULL,
  `wk_input` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of log_import
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_alamat_customer
-- ----------------------------
DROP TABLE IF EXISTS `tbl_alamat_customer`;
CREATE TABLE `tbl_alamat_customer` (
  `id_alamat` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `provinsi_id` bigint(20) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `kabupaten_id` bigint(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `is_default` int(11) DEFAULT 0,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_alamat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_alamat_customer
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_banner
-- ----------------------------
DROP TABLE IF EXISTS `tbl_banner`;
CREATE TABLE `tbl_banner` (
  `id_banner` bigint(20) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `gambar` text NOT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `user_input` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_edit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_banner`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_banner
-- ----------------------------
BEGIN;
INSERT INTO `tbl_banner` (`id_banner`, `judul`, `gambar`, `is_active`, `created_at`, `user_input`, `updated_at`, `user_edit`) VALUES (4, 'tess', '293114066.png', 1, '2023-12-23 01:19:17', 'Sinyo', NULL, NULL);
INSERT INTO `tbl_banner` (`id_banner`, `judul`, `gambar`, `is_active`, `created_at`, `user_input`, `updated_at`, `user_edit`) VALUES (2, 'Hai riska ku', '1215920095.jpg', 0, '2023-12-22 22:10:55', 'Sinyo', NULL, NULL);
INSERT INTO `tbl_banner` (`id_banner`, `judul`, `gambar`, `is_active`, `created_at`, `user_input`, `updated_at`, `user_edit`) VALUES (3, 'kiw kiw', '1501226598.png', 1, '2023-12-23 01:18:48', 'Sinyo', '2023-12-23 01:19:06', 'Sinyo');
INSERT INTO `tbl_banner` (`id_banner`, `judul`, `gambar`, `is_active`, `created_at`, `user_input`, `updated_at`, `user_edit`) VALUES (5, 'haha', '169616792.png', 1, '2023-12-23 01:19:40', 'Sinyo', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_barang
-- ----------------------------
DROP TABLE IF EXISTS `tbl_barang`;
CREATE TABLE `tbl_barang` (
  `id_barang` bigint(20) NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_satuan',
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `slug_barang` text NOT NULL,
  `barcode_barang` text NOT NULL,
  `harga_pokok` decimal(65,0) NOT NULL,
  `berat_barang` double NOT NULL,
  `deskripsi` text NOT NULL,
  `informasi` text DEFAULT NULL,
  `gambar` text DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `index_barang` (`id_barang`,`kategori_id`,`satuan_id`,`kode_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_barang
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_barang_harga_history
-- ----------------------------
DROP TABLE IF EXISTS `tbl_barang_harga_history`;
CREATE TABLE `tbl_barang_harga_history` (
  `id_barang_history` bigint(20) NOT NULL AUTO_INCREMENT,
  `barang_id` bigint(20) NOT NULL,
  `kategori_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_satuan',
  `harga_pokok` decimal(65,0) NOT NULL,
  `berat_barang` double NOT NULL,
  `tanggal_perubahan` datetime NOT NULL,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_barang_history`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_barang_harga_history
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_barang_keluar
-- ----------------------------
DROP TABLE IF EXISTS `tbl_barang_keluar`;
CREATE TABLE `tbl_barang_keluar` (
  `id_barang_keluar` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_detail_id` bigint(20) DEFAULT NULL,
  `harga_id` bigint(20) NOT NULL,
  `jenis_keluar` varchar(100) NOT NULL COMMENT 'TERJUAL DISTRIBUSI',
  `request_id` bigint(20) DEFAULT 0,
  `jml_keluar` bigint(20) NOT NULL,
  `bukti_keluar` text NOT NULL,
  `is_rollback` int(11) DEFAULT 0,
  `user_input` varchar(100) NOT NULL,
  `user_edit` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_barang_keluar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_barang_keluar
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_barang_masuk
-- ----------------------------
DROP TABLE IF EXISTS `tbl_barang_masuk`;
CREATE TABLE `tbl_barang_masuk` (
  `id_barang_masuk` bigint(20) NOT NULL AUTO_INCREMENT,
  `harga_id` bigint(20) DEFAULT NULL,
  `jml_masuk` bigint(20) DEFAULT NULL,
  `bukti_beli` text DEFAULT NULL,
  `tipe` varchar(100) DEFAULT NULL COMMENT '''toko_luar'',''gudang''',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_barang_masuk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_barang_masuk
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_barang_temp
-- ----------------------------
DROP TABLE IF EXISTS `tbl_barang_temp`;
CREATE TABLE `tbl_barang_temp` (
  `id_barang` bigint(20) NOT NULL AUTO_INCREMENT,
  `kategori_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_satuan',
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `slug_barang` text NOT NULL,
  `barcode_barang` text NOT NULL,
  `harga_pokok` decimal(65,0) NOT NULL,
  `berat_barang` double NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` text DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_barang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_barang_temp
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_customer
-- ----------------------------
DROP TABLE IF EXISTS `tbl_customer`;
CREATE TABLE `tbl_customer` (
  `id_customer` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hp` varchar(50) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY (`id_customer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_customer
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_diskon
-- ----------------------------
DROP TABLE IF EXISTS `tbl_diskon`;
CREATE TABLE `tbl_diskon` (
  `id_diskon` bigint(20) NOT NULL AUTO_INCREMENT,
  `harga_id` bigint(20) DEFAULT NULL,
  `nama_diskon` varchar(100) DEFAULT NULL,
  `harga_potongan` double DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_akhir` date DEFAULT NULL,
  `minimal_beli` bigint(20) DEFAULT 1,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `is_active` int(11) DEFAULT 1,
  PRIMARY KEY (`id_diskon`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_diskon
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_harga
-- ----------------------------
DROP TABLE IF EXISTS `tbl_harga`;
CREATE TABLE `tbl_harga` (
  `id_harga` bigint(20) NOT NULL AUTO_INCREMENT,
  `barang_id` bigint(20) DEFAULT NULL,
  `toko_id` bigint(20) DEFAULT NULL,
  `stok_toko` bigint(20) DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_harga`),
  KEY `index_harga` (`id_harga`,`barang_id`,`toko_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_harga
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_harga_toko_history
-- ----------------------------
DROP TABLE IF EXISTS `tbl_harga_toko_history`;
CREATE TABLE `tbl_harga_toko_history` (
  `id_harga_toko_history` bigint(20) NOT NULL AUTO_INCREMENT,
  `harga_id` bigint(20) NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_harga_toko_history`) USING BTREE,
  KEY `index_harga` (`harga_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_harga_toko_history
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_karyawan_toko
-- ----------------------------
DROP TABLE IF EXISTS `tbl_karyawan_toko`;
CREATE TABLE `tbl_karyawan_toko` (
  `id_karyawan` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_user',
  `toko_id` bigint(20) NOT NULL COMMENT 'relasi ke tbl_toko',
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `hp_karyawan` varchar(100) DEFAULT NULL,
  `alamat_karyawan` text DEFAULT NULL,
  `bagian` varchar(100) DEFAULT NULL,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_karyawan`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_karyawan_toko
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_karyawan_toko_temp
-- ----------------------------
DROP TABLE IF EXISTS `tbl_karyawan_toko_temp`;
CREATE TABLE `tbl_karyawan_toko_temp` (
  `role_id` varchar(255) DEFAULT NULL,
  `toko_id` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `hp_karyawan` varchar(100) DEFAULT NULL,
  `alamat_karyawan` text DEFAULT NULL,
  `bagian` varchar(100) DEFAULT NULL,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_karyawan_toko_temp
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_kategori
-- ----------------------------
DROP TABLE IF EXISTS `tbl_kategori`;
CREATE TABLE `tbl_kategori` (
  `id_kategori` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(255) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `slug_kategori` text NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_kategori`),
  KEY `index_kategori` (`id_kategori`,`kode_kategori`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_kategori
-- ----------------------------
BEGIN;
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (1, 'ALAT_BERAT', 'Alat Berat', 'alat-berat', 1, 'sistem', 'sistem', '2023-12-22 01:30:01', '2023-12-22 01:30:01');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (2, 'ALAT_RINGAN', 'Alat Ringan', 'alat-ringan', 1, 'sistem', 'sistem', '2023-12-22 01:30:29', '2023-12-22 01:30:46');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (3, 'PERKAKAS_DAPUR', 'Perkakas Dapur', 'perkakas-dapur', 1, 'sistem', 'sistem', '2024-01-01 13:14:23', '2024-01-01 13:14:28');
COMMIT;

-- ----------------------------
-- Table structure for tbl_menu
-- ----------------------------
DROP TABLE IF EXISTS `tbl_menu`;
CREATE TABLE `tbl_menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) NOT NULL,
  `icon` varchar(155) NOT NULL,
  `is_active` int(2) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_menu`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_menu
-- ----------------------------
BEGIN;
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (1, 'Dashboard', 'menu-icon tf-icons bx bx-home-circle', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (2, 'Management Akses', 'menu-icon tf-icons bx bx-lock-open', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (3, 'Toko', 'menu-icon tf-icons bx bxs-store', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (4, 'Barang', 'menu-icon tf-icons bx bxs-buildings', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (5, 'Kasir', 'menu-icon tf-icons bx bxs-wallet', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (6, 'Laporan', 'menu-icon tf-icons bx bxs-report', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (7, 'Setting', 'menu-icon tf-icons bx bxs-cog', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (10, 'tes', 'bx bxl-flask', 0);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (9, 'Marketplace', 'menu-icon tf-icons bx bx-store', 1);
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (11, 'cobain yagaisss', 'bx bxl-flask', 0);
COMMIT;

-- ----------------------------
-- Table structure for tbl_order
-- ----------------------------
DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE `tbl_order` (
  `id_order` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL COMMENT 'Opsional berlaku pada marketplace',
  `toko_id` bigint(20) NOT NULL,
  `kode_order` longtext NOT NULL,
  `nama_cust` varchar(100) DEFAULT NULL,
  `hp_cust` varchar(100) DEFAULT NULL,
  `alamat_cust` text DEFAULT NULL,
  `tipe_order` enum('Marketplace','Kasir') NOT NULL,
  `tipe_pengiriman` text NOT NULL,
  `biaya_kirim` decimal(10,0) NOT NULL,
  `bukti_kirim` text DEFAULT NULL,
  `total_order` decimal(16,0) NOT NULL,
  `paidst` int(11) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT 1 COMMENT '1 = belum konfirmasi, 2 = sudah konfirmasi, 3 = dikemas, 4 = dikirim, 5 = selesai, 99 = dibatalkan',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `user_edit` varchar(100) DEFAULT NULL,
  `user_input` varchar(100) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_order`),
  KEY `index_order` (`id_order`,`user_id`,`toko_id`),
  FULLTEXT KEY `kode_order_index` (`kode_order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_order
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_order_detail
-- ----------------------------
DROP TABLE IF EXISTS `tbl_order_detail`;
CREATE TABLE `tbl_order_detail` (
  `id_order_detail` bigint(20) NOT NULL AUTO_INCREMENT,
  `harga_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `qty` bigint(20) DEFAULT NULL,
  `harga_total` double DEFAULT NULL,
  `harga_potongan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`harga_potongan`)),
  `user_edit` varchar(100) DEFAULT NULL,
  `user_input` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_order_detail`),
  KEY `index_order_detail` (`id_order_detail`,`harga_id`,`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_order_detail
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_order_marketplace
-- ----------------------------
DROP TABLE IF EXISTS `tbl_order_marketplace`;
CREATE TABLE `tbl_order_marketplace` (
  `id_order_marketplace` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) DEFAULT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_order_marketplace`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_order_marketplace
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_privilege_menu
-- ----------------------------
DROP TABLE IF EXISTS `tbl_privilege_menu`;
CREATE TABLE `tbl_privilege_menu` (
  `id_privilege` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id_privilege`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_privilege_menu
-- ----------------------------
BEGIN;
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (1, 1, 1);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (2, 1, 2);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (3, 1, 3);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (4, 1, 4);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (5, 1, 5);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (6, 1, 6);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (7, 1, 7);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (8, 1, 9);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (9, 2, 1);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (11, 2, 3);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (12, 2, 4);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (13, 2, 5);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (14, 2, 6);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (15, 2, 7);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (16, 2, 9);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (17, 3, 1);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (18, 3, 5);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (19, 3, 7);
COMMIT;

-- ----------------------------
-- Table structure for tbl_privilege_submenu
-- ----------------------------
DROP TABLE IF EXISTS `tbl_privilege_submenu`;
CREATE TABLE `tbl_privilege_submenu` (
  `id_privilege_submenu` int(11) NOT NULL AUTO_INCREMENT,
  `privilege_id` int(11) NOT NULL,
  `submenu_id` int(11) NOT NULL,
  PRIMARY KEY (`id_privilege_submenu`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_privilege_submenu
-- ----------------------------
BEGIN;
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (1, 1, 1);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (2, 2, 2);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (3, 2, 3);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (4, 2, 4);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (5, 2, 9);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (6, 3, 5);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (7, 3, 6);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (8, 3, 18);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (9, 4, 10);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (10, 4, 11);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (11, 4, 12);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (12, 4, 13);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (13, 4, 15);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (14, 4, 17);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (15, 4, 20);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (17, 5, 19);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (18, 5, 21);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (19, 5, 22);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (20, 6, 23);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (21, 6, 25);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (22, 6, 30);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (23, 7, 7);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (24, 7, 8);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (25, 8, 29);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (26, 9, 1);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (28, 11, 6);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (29, 11, 18);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (30, 12, 10);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (31, 12, 11);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (32, 12, 12);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (33, 12, 13);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (34, 12, 15);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (35, 12, 17);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (36, 12, 20);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (37, 13, 14);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (38, 13, 19);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (39, 13, 21);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (40, 13, 22);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (41, 14, 23);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (42, 14, 25);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (43, 14, 27);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (45, 15, 7);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (46, 15, 8);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (47, 16, 26);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (48, 17, 1);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (49, 18, 19);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (50, 18, 21);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (51, 18, 22);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (52, 19, 7);
COMMIT;

-- ----------------------------
-- Table structure for tbl_request_toko
-- ----------------------------
DROP TABLE IF EXISTS `tbl_request_toko`;
CREATE TABLE `tbl_request_toko` (
  `id_request` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_request` varchar(100) DEFAULT NULL,
  `request_toko_id` bigint(20) DEFAULT NULL,
  `penerima_toko_id` bigint(20) DEFAULT NULL,
  `atribut_barang` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`atribut_barang`)),
  `pengirim` varchar(100) DEFAULT NULL,
  `status` text DEFAULT NULL COMMENT '"proses","terkirim","draft"',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_request`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_request_toko
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_role
-- ----------------------------
DROP TABLE IF EXISTS `tbl_role`;
CREATE TABLE `tbl_role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_role`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_role
-- ----------------------------
BEGIN;
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (1, 'Developer', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (2, 'Superadmin', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (3, 'Kasir', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (4, 'Customer', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (5, 'Inventory', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (8, 'Pegawai Admin Toko', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (10, 'Akuntan', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (11, 'Keuangan', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (12, 'Marketing', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (14, 'Tukang Ketik', 1);
COMMIT;

-- ----------------------------
-- Table structure for tbl_satuan
-- ----------------------------
DROP TABLE IF EXISTS `tbl_satuan`;
CREATE TABLE `tbl_satuan` (
  `id_satuan` bigint(20) NOT NULL AUTO_INCREMENT,
  `satuan` varchar(255) NOT NULL,
  `is_active` int(11) DEFAULT 1,
  `user_input` varchar(255) NOT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_satuan`),
  KEY `index_satuan` (`id_satuan`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_satuan
-- ----------------------------
BEGIN;
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (1, 'KG', 1, 'sistem', 'sistem', '2023-12-22 01:31:04', '2023-12-22 01:31:04');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (2, 'METER', 1, 'sistem', 'sistem', '2023-12-22 01:31:24', '2023-12-22 01:31:33');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (3, 'GRAM', 1, 'sistem', NULL, '0000-00-00 00:00:00', NULL);
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (4, 'ONS', 1, 'sistem', NULL, '0000-00-00 00:00:00', NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_setting
-- ----------------------------
DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE `tbl_setting` (
  `id_setting` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `deskripsi_singkat` text DEFAULT NULL,
  `img_instansi` text DEFAULT NULL,
  PRIMARY KEY (`id_setting`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_setting
-- ----------------------------
BEGIN;
INSERT INTO `tbl_setting` (`id_setting`, `instansi`, `kode_instansi`, `alamat_instansi`, `owner`, `wa_instansi`, `wa_admin`, `tlp_instansi`, `ig_instansi`, `fb_instansi`, `email_instansi`, `deskripsi_singkat`, `img_instansi`) VALUES (1, 'ardhaPOS', 'ARDHAPOS', 'Sidoarjo', 'sembarang', '628787687682', '6281234567898', '2799896', 'wsss', 'ssdff', 'robby@ardhacodes.com', NULL, 'ARDHAPOS1703297315.PNG');
COMMIT;

-- ----------------------------
-- Table structure for tbl_submenu
-- ----------------------------
DROP TABLE IF EXISTS `tbl_submenu`;
CREATE TABLE `tbl_submenu` (
  `id_submenu` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_submenu`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_submenu
-- ----------------------------
BEGIN;
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (1, 1, 'Dashboard', 'dashboard', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (2, 2, 'Role', 'role_akses', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (3, 2, 'Menu', 'access/menu', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (4, 2, 'Sub Menu', 'access/submenu', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (5, 3, 'Management Toko', 'toko/store_management', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (6, 3, 'Karyawan Toko', 'toko/karyawan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (7, 7, 'Setting Instansi', 'setting', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (8, 7, 'Backup Database', 'setting/backupdb', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (9, 2, 'User', 'user/index', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (10, 4, 'Kategori', 'barang/kategori', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (11, 4, 'Satuan', 'barang/satuan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (12, 4, 'Barang', 'barang/index', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (13, 4, 'Barang', 'barang/list', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (14, 5, 'Order', 'kasir/order', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (15, 4, 'Barang Masuk', 'barang/masuk', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (17, 4, 'Barang Toko', 'barang/barang_toko', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (18, 3, 'Diskon', 'toko/diskon', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (19, 5, 'Scan', 'kasir/scan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (20, 4, 'Barang Keluar', 'barang/keluar', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (21, 5, 'Sales order', 'kasir/sales_order', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (22, 5, 'Transaksi', 'kasir/transaksi', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (23, 6, 'Laporan Penjualan', 'laporan/penjualan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (24, 6, 'Riwayat Transaksi', 'riwayat/transaksi', 0);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (25, 6, 'Laporan Transaksi', 'laporan/transaksi', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (26, 9, 'Banner', 'marketplace/banner', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (27, 6, 'Laporan Pendapatan', 'laporan/pendapatan', 1);
COMMIT;

-- ----------------------------
-- Table structure for tbl_toko
-- ----------------------------
DROP TABLE IF EXISTS `tbl_toko`;
CREATE TABLE `tbl_toko` (
  `id_toko` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_toko` varchar(255) NOT NULL,
  `alamat_toko` text NOT NULL,
  `notelp_toko` varchar(15) NOT NULL,
  `jenis` varchar(255) NOT NULL COMMENT 'MARKETPLACE | TOKO',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `user_input` varchar(255) NOT NULL,
  `user_edit` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_toko`),
  KEY `index_toko` (`id_toko`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_toko
-- ----------------------------
BEGIN;
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (1, 'Marketplace', 'Market Place', '9876', 'MARKETPLACE', 1, 'sistem', 'sistem', '2023-12-22 01:23:21', '2023-12-22 01:23:25');
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (2, 'TOKO KUNING', 'Jl. Raya A.b', '987689792', 'TOKO', 1, '', 'ss', '2024-01-01 12:37:28', '2023-12-22 01:23:25');
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (3, 'TOKO BIRU', 'hxjchsvjcv', '62812344556', 'TOKO', 1, 'Sinyo', NULL, '2023-12-22 23:06:04', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (4, 'TOKO HIJAU', 'ahsjhsdsa', '628123445561313', 'TOKO', 1, 'Sinyo', NULL, '2023-12-22 23:07:00', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (5, 'TOKO MERAH', 'Consequatur velit c', '6281234563277', 'TOKO', 0, 'Sinyo', NULL, '2023-12-27 23:24:33', NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `tbl_transaksi`;
CREATE TABLE `tbl_transaksi` (
  `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) DEFAULT NULL,
  `kode_transaksi` text DEFAULT NULL,
  `terbayar` double DEFAULT 0 COMMENT 'Nominal Customer Membayar',
  `kembalian` double DEFAULT 0 COMMENT 'Nominal Kembalian Customer',
  `tagihan_cart` double DEFAULT NULL COMMENT 'Tagihan Keranjang',
  `total_diskon` double DEFAULT NULL COMMENT 'Jumlah Keseluruhan Diskon',
  `tagihan_after_diskon` double DEFAULT NULL COMMENT 'Tagihan Keranjang - Total Diskon',
  `total_biaya_kirim` double DEFAULT NULL COMMENT 'Jumlah Nominal Biaya Kirim',
  `total_tagihan` double DEFAULT NULL COMMENT 'Total Tagihan = Tagihan After Diskon + Biaya Kirim',
  `tipe_transaksi` varchar(255) DEFAULT NULL COMMENT '"TRANSFER","TUNAI"',
  `bukti_tf` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `index_kodetrans` (`kode_transaksi`(1000)),
  KEY `index_transaksi` (`id_transaksi`,`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_transaksi
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `nama_user` varchar(200) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role_id` int(11) NOT NULL,
  `type_user` varchar(255) DEFAULT NULL COMMENT 'Jika Developer Akan Muncul Menu Management Akses\n\ndan tidak muncul pada menu karyawan toko\n\n\njika null tidak memiliki management akses',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
BEGIN;
INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'robby@ardhacodes.com', 'SINYO', '$2y$10$LehzOBWPnnLpkjTMN4V3fON8golwh6AqGhS5EzLWZ1qLBeHqJwvPC', 1, 'DEV', 1, '2024-01-01 13:00:27', '2024-01-01 13:00:46');
INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'dev@ardhacodes.com', 'DEVELOPER', '$2y$10$rggSg5krhFPztTGHNYPq4O9rHHBy.5JUeo0XNr5ibP9nKQiyf28y2', 1, 'DEV', 1, '2024-01-01 13:00:27', '2024-01-01 13:03:59');
INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'owner', 'Owner Toko', '$2y$10$3I216e2lj1gmWm1VYCE0iuW2IKYU3nj6da/nl8kHofdzkpmJNFN1a', 2, NULL, 1, '2024-01-01 13:25:19', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
