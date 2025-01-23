/*
 Navicat Premium Data Transfer

 Source Server         : Sibara
 Source Server Type    : MySQL
 Source Server Version : 100616 (10.6.16-MariaDB-cll-lve)
 Source Host           : ardhacodes.com:3306
 Source Schema         : ardhacod_staging_sibara

 Target Server Type    : MySQL
 Target Server Version : 100616 (10.6.16-MariaDB-cll-lve)
 File Encoding         : 65001

 Date: 18/01/2024 19:24:03
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
  PRIMARY KEY (`id_alamat`),
  KEY `FK_alamat_customer` (`customer_id`),
  CONSTRAINT `FK_alamat_customer` FOREIGN KEY (`customer_id`) REFERENCES `tbl_customer` (`id_customer`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_banner
-- ----------------------------
BEGIN;
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
  KEY `index_barang` (`id_barang`,`kategori_id`,`satuan_id`,`kode_barang`),
  KEY `FK_barang_kategori` (`kategori_id`),
  KEY `FK_barang_satuan` (`satuan_id`),
  CONSTRAINT `FK_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `tbl_kategori` (`id_kategori`) ON UPDATE CASCADE,
  CONSTRAINT `FK_barang_satuan` FOREIGN KEY (`satuan_id`) REFERENCES `tbl_satuan` (`id_satuan`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `toko_id` bigint(20) DEFAULT NULL,
  `order_detail_id` bigint(20) DEFAULT NULL,
  `harga_id` bigint(20) NOT NULL,
  `jenis_keluar` varchar(100) NOT NULL COMMENT 'TERJUAL DISTRIBUSI',
  `request_id` bigint(20) DEFAULT 0,
  `jml_keluar` bigint(20) NOT NULL,
  `bukti_keluar` text NOT NULL,
  `tanggal_barang_keluar` datetime DEFAULT NULL,
  `is_rollback` int(11) DEFAULT 0,
  `user_input` varchar(100) NOT NULL,
  `user_edit` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_barang_keluar`),
  KEY `FK_barkel_toko` (`toko_id`),
  KEY `FK_barkel_orderdetail` (`order_detail_id`),
  KEY `FK_barkel_harga` (`harga_id`),
  CONSTRAINT `FK_barkel_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE,
  CONSTRAINT `FK_barkel_orderdetail` FOREIGN KEY (`order_detail_id`) REFERENCES `tbl_order_detail` (`id_order_detail`) ON UPDATE CASCADE,
  CONSTRAINT `FK_barkel_toko` FOREIGN KEY (`toko_id`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `tipe` varchar(100) DEFAULT NULL COMMENT '''toko_luar'',''gudang'', ''antar_toko''',
  `tanggal_barang_masuk` datetime DEFAULT NULL,
  `nama_supplier` varchar(255) DEFAULT NULL,
  `nomor_supplier` varchar(255) DEFAULT NULL,
  `nama_toko_beli` varchar(100) DEFAULT NULL COMMENT 'kusus untuk beli dari toko luar',
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_barang_masuk`),
  KEY `FK_bar_masuk_harga` (`harga_id`),
  CONSTRAINT `FK_bar_masuk_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `id_barang_temp` bigint(20) NOT NULL AUTO_INCREMENT,
  `kategori_id` text NOT NULL COMMENT 'relasi ke tbl_kategori',
  `satuan_id` text NOT NULL COMMENT 'relasi ke tbl_satuan',
  `kode_barang` text NOT NULL,
  `nama_barang` text NOT NULL,
  `slug_barang` text NOT NULL,
  `barcode_barang` text NOT NULL,
  `harga_pokok` text NOT NULL,
  `berat_barang` text NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_barang_temp`) USING BTREE
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  PRIMARY KEY (`id_diskon`),
  KEY `FK_diskon_harga` (`harga_id`),
  CONSTRAINT `FK_diskon_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  KEY `index_harga` (`id_harga`,`barang_id`,`toko_id`),
  KEY `FK_harga_barang` (`barang_id`),
  KEY `FK_harga_toko` (`toko_id`),
  CONSTRAINT `FK_harga_barang` FOREIGN KEY (`barang_id`) REFERENCES `tbl_barang` (`id_barang`) ON UPDATE CASCADE,
  CONSTRAINT `FK_harga_toko` FOREIGN KEY (`toko_id`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_harga
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_harga_temp
-- ----------------------------
DROP TABLE IF EXISTS `tbl_harga_temp`;
CREATE TABLE `tbl_harga_temp` (
  `id_harga` bigint(20) NOT NULL AUTO_INCREMENT,
  `barang_id` text DEFAULT NULL,
  `toko_id` text DEFAULT NULL,
  `stok_toko` text DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `is_active` int(11) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_harga`),
  KEY `index_name` (`id_harga`) USING BTREE,
  FULLTEXT KEY `index_name2` (`barang_id`,`toko_id`,`stok_toko`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_harga_temp
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
  PRIMARY KEY (`id_karyawan`),
  KEY `FK_karyawan_user` (`user_id`),
  KEY `FK_karyawan_toko` (`toko_id`)
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
  `id_karyawan_toko_temp` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` text DEFAULT NULL,
  `toko_id` text DEFAULT NULL,
  `username` text DEFAULT NULL,
  `nama_karyawan` text DEFAULT NULL,
  `hp_karyawan` text DEFAULT NULL,
  `alamat_karyawan` text DEFAULT NULL,
  `bagian` text DEFAULT NULL,
  `is_sess_toko` int(11) DEFAULT NULL,
  `user_input` text NOT NULL,
  `user_update` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_karyawan_toko_temp`)
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_kategori
-- ----------------------------
BEGIN;
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (1, 'LISTRIK', 'Alat Listrik', 'alat-listrik', 1, 'sistem', 'sistem', '2023-12-22 01:30:01', '2024-01-03 14:01:14');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (2, 'BANGUNAN', 'Bangunan', 'bangunan', 1, 'sistem', 'sistem', '2023-12-22 01:30:29', '2024-01-03 14:01:41');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (3, 'PERKAKAS_DAPUR', 'Gerabah', 'gerabah', 1, 'sistem', 'sistem', '2024-01-01 13:14:23', '2024-01-03 14:01:52');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (4, 'ALAT TULIS', 'Alat Tulis', 'alat-tulis', 1, 'sistem', 'sistem', '2024-01-03 14:02:05', '2024-01-03 14:02:05');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (5, 'PERIKANAN', 'Perikanan', 'perikanan', 0, 'sistem', 'sistem', '2024-01-04 00:46:10', '2024-01-17 21:19:49');
INSERT INTO `tbl_kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`, `slug_kategori`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (6, 'SABUN', 'sabun cuci', 'sabun-cuci', 1, 'sistem', 'sistem', '2024-01-17 21:22:09', '2024-01-17 21:22:44');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
INSERT INTO `tbl_menu` (`id_menu`, `menu`, `icon`, `is_active`) VALUES (8, 'Marketplace', 'menu-icon tf-icons bx bx-store', 1);
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
  KEY `FK_order_user` (`user_id`),
  KEY `FK_order_toko` (`toko_id`),
  FULLTEXT KEY `kode_order_index` (`kode_order`),
  CONSTRAINT `FK_order_toko` FOREIGN KEY (`toko_id`) REFERENCES `tbl_toko` (`id_toko`) ON UPDATE CASCADE,
  CONSTRAINT `FK_order_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  KEY `index_order_detail` (`id_order_detail`,`harga_id`,`order_id`),
  KEY `FK_ord_det_harga` (`harga_id`),
  KEY `FK_ord_det_order` (`order_id`),
  CONSTRAINT `FK_ord_det_harga` FOREIGN KEY (`harga_id`) REFERENCES `tbl_harga` (`id_harga`) ON UPDATE CASCADE,
  CONSTRAINT `FK_ord_det_order` FOREIGN KEY (`order_id`) REFERENCES `tbl_order` (`id_order`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (8, 1, 8);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (9, 2, 1);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (10, 2, 3);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (11, 2, 4);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (12, 2, 5);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (13, 2, 6);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (14, 2, 7);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (15, 2, 8);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (16, 3, 1);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (17, 3, 4);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (18, 3, 5);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (19, 3, 7);
INSERT INTO `tbl_privilege_menu` (`id_privilege`, `role_id`, `menu_id`) VALUES (20, 3, 8);
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
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (8, 3, 16);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (9, 4, 10);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (10, 4, 11);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (11, 4, 12);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (12, 4, 14);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (13, 4, 15);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (14, 4, 18);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (15, 4, 27);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (16, 5, 13);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (17, 5, 17);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (18, 5, 19);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (19, 5, 20);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (20, 6, 21);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (21, 6, 23);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (22, 6, 25);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (23, 6, 26);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (24, 7, 7);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (25, 7, 8);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (26, 9, 1);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (27, 10, 5);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (28, 10, 6);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (29, 10, 16);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (30, 11, 10);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (31, 11, 11);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (32, 11, 12);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (33, 11, 14);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (34, 11, 15);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (35, 11, 18);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (36, 11, 27);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (37, 12, 13);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (38, 12, 17);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (39, 12, 19);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (40, 12, 20);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (41, 13, 21);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (42, 13, 23);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (43, 13, 25);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (44, 13, 26);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (45, 14, 7);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (46, 14, 8);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (47, 16, 1);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (48, 17, 15);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (49, 18, 17);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (50, 18, 19);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (51, 18, 20);
INSERT INTO `tbl_privilege_submenu` (`id_privilege_submenu`, `privilege_id`, `submenu_id`) VALUES (52, 19, 8);
COMMIT;

-- ----------------------------
-- Table structure for tbl_report_penjualan
-- ----------------------------
DROP TABLE IF EXISTS `tbl_report_penjualan`;
CREATE TABLE `tbl_report_penjualan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `is_rollback` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_report_penjualan
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for tbl_request_delete_barang
-- ----------------------------
DROP TABLE IF EXISTS `tbl_request_delete_barang`;
CREATE TABLE `tbl_request_delete_barang` (
  `id_request` bigint(20) NOT NULL AUTO_INCREMENT,
  `barang_id` bigint(20) DEFAULT NULL,
  `harga_id` bigint(20) DEFAULT NULL,
  `type_request` varchar(255) DEFAULT NULL COMMENT 'delete_barang, delete_barang_toko',
  `keterangan` text DEFAULT NULL COMMENT 'alasan',
  `is_deleted` int(11) DEFAULT 0 COMMENT 'jika 0 belum terhapus, tapi jika 1 sudah dihapus',
  `requested_shop` varchar(255) DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `requested_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id_request`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_request_delete_barang
-- ----------------------------
BEGIN;
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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_role
-- ----------------------------
BEGIN;
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (1, 'Developer', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (2, 'Superadmin', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (3, 'Kasir', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (4, 'Customer', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (5, 'Inventory', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (6, 'Direktur', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (7, 'Akuntan', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (8, 'Marketing', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (9, 'Keuangan', 1);
INSERT INTO `tbl_role` (`id_role`, `role`, `is_active`) VALUES (17, 'HRD', 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_satuan
-- ----------------------------
BEGIN;
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (1, 'KG', 1, 'sistem', 'sistem', '2023-12-22 01:31:04', '2023-12-22 01:31:04');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (2, 'METER', 1, 'sistem', 'sistem', '2023-12-22 01:31:24', '2023-12-22 01:31:33');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (3, 'GRAM', 1, 'sistem', NULL, '0000-00-00 00:00:00', NULL);
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (4, 'ONS', 1, 'sistem', NULL, '0000-00-00 00:00:00', NULL);
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (5, 'LEMBAR', 1, 'sistem', 'sistem', '2024-01-03 14:02:37', '2024-01-03 14:02:37');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (7, 'BUNGKUS', 1, 'sistem', 'sistem', '2024-01-04 00:51:14', '2024-01-04 09:24:35');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (8, 'ROLL', 1, 'sistem', 'sistem', '2024-01-04 09:25:04', '2024-01-04 09:25:04');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (9, 'BUAH', 1, 'sistem', 'sistem', '2024-01-04 09:25:20', '2024-01-04 09:25:20');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (10, 'PCS', 1, 'sistem', 'sistem', '2024-01-04 09:25:30', '2024-01-04 09:25:30');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (11, 'PACK', 1, 'sistem', 'sistem', '2024-01-04 12:03:04', '2024-01-04 12:03:04');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (12, 'DUS', 1, 'sistem', 'sistem', '2024-01-04 12:03:10', '2024-01-04 12:03:10');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (13, 'SLOP', 1, 'sistem', 'sistem', '2024-01-04 12:03:24', '2024-01-04 12:03:24');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (14, 'SET', 1, 'sistem', 'sistem', '2024-01-04 12:03:30', '2024-01-04 12:03:30');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (15, 'dos', 0, 'sistem', 'sistem', '2024-01-04 12:06:46', '2024-01-04 12:07:12');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (16, 'GROSS', 1, 'sistem', 'sistem', '2024-01-04 14:23:45', '2024-01-04 14:23:45');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (17, 'RENTENG', 1, 'sistem', 'sistem', '2024-01-04 14:24:20', '2024-01-04 14:24:20');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (18, 'RIM', 1, 'sistem', 'sistem', '2024-01-04 14:24:56', '2024-01-04 14:24:56');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (19, 'BIJI', 1, 'sistem', 'sistem', '2024-01-04 17:00:45', '2024-01-04 17:00:45');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (20, 'KALENG', 1, 'sistem', 'sistem', '2024-01-04 17:02:21', '2024-01-04 17:02:21');
INSERT INTO `tbl_satuan` (`id_satuan`, `satuan`, `is_active`, `user_input`, `user_update`, `created_at`, `updated_at`) VALUES (21, 'LUSIN', 1, 'sistem', 'sistem', '2024-01-04 17:02:44', '2024-01-04 17:02:44');
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
INSERT INTO `tbl_setting` (`id_setting`, `instansi`, `kode_instansi`, `alamat_instansi`, `owner`, `wa_instansi`, `wa_admin`, `tlp_instansi`, `ig_instansi`, `fb_instansi`, `email_instansi`, `deskripsi_singkat`, `img_instansi`) VALUES (1, 'ardhaPOS', 'ARDHAPOS', 'Sidoarjo', 'sembarang', '628787687682', '6281234567898', '2799896', 'wsss', 'ssdff', 'robby@ardhacodes.com', NULL, 'ARDHAPOS1704309483.png');
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
  PRIMARY KEY (`id_submenu`),
  KEY `FK_submenu_menu` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (13, 5, 'Order', 'kasir/order', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (14, 4, 'Barang Masuk', 'barang/masuk', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (15, 4, 'Barang Toko', 'barang/barang_toko', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (16, 3, 'Diskon', 'toko/diskon', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (17, 5, 'Scan', 'kasir/scan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (18, 4, 'Barang Keluar', 'barang/keluar', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (19, 5, 'Sales order', 'kasir/sales_order', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (20, 5, 'Transaksi', 'kasir/transaksi', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (21, 6, 'Laporan Penjualan', 'laporan/penjualan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (22, 6, 'Riwayat Transaksi', 'riwayat/transaksi', 0);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (23, 6, 'Laporan Transaksi', 'laporan/transaksi', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (24, 9, 'Banner', 'marketplace/banner', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (25, 6, 'Laporan Pendapatan', 'laporan/pendapatan', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (26, 6, 'Laporan Stok', 'laporan/stok', 1);
INSERT INTO `tbl_submenu` (`id_submenu`, `menu_id`, `title`, `url`, `is_active`) VALUES (27, 4, 'Request Hapus Barang', 'request_hapus_barang', 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_toko
-- ----------------------------
BEGIN;
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (1, 'SA ONLINE', 'SA ONLINE', '6285257162700', 'MARKETPLACE', 1, '', 'sistem', '2024-01-17 08:51:47', '2023-12-22 01:23:25');
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (2, 'TOKO KUNING', 'Jl. Raya Surabaya Kapuas No.04-AA1', '987689792', 'TOKO', 1, '', 'ss', '2024-01-01 12:37:28', '2023-12-22 01:23:25');
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (3, 'TOKO BIRU', 'hxjchsvjcv', '62812344556', 'TOKO', 0, 'Sinyo', NULL, '2023-12-22 23:06:04', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (4, 'TOKO HIJAU', 'ahsjhsdsa', '628123445561313', 'TOKO', 0, 'Sinyo', NULL, '2023-12-22 23:07:00', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (5, 'TOKO MERAH', 'Consequatur velit c', '6281234563277', 'TOKO', 0, 'Sinyo', NULL, '2023-12-27 23:24:33', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (6, 'TOKO SUMBER BANGUNAN', 'Jl. Ahmad Yani No.145, Megersari, Gedangan, Kec. Gedangan, Kabupaten Sidoarjo, Jawa Timur 61254', '62817339663', 'TOKO', 0, '', NULL, '2024-01-03 23:44:48', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (7, 'sumber jaya oke', 'asdasdasdasdasdasd', '567890', 'TOKO', 0, '', NULL, '2024-01-04 01:38:08', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (8, 'SUMBER AGUNG SURABAYA', 'JL. SAMBIKEREP NO. 13 SURABAYA', '6285257162700', 'TOKO', 1, '', NULL, '2024-01-17 08:58:26', NULL);
INSERT INTO `tbl_toko` (`id_toko`, `nama_toko`, `alamat_toko`, `notelp_toko`, `jenis`, `is_active`, `user_input`, `user_edit`, `created_at`, `updated_at`) VALUES (9, 'SUMBER AGUNG PUSAT', 'Perum. QUALITY REVERSIDE 2 CLUSTER DAISY BLOK II-27 RT. 29 RW. 07, KEMASAN KRIAN SIDOARJO', '6285257162700', 'TOKO', 1, 'Owner Toko', NULL, '2024-01-17 08:49:52', NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `tbl_transaksi`;
CREATE TABLE `tbl_transaksi` (
  `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
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
  `tanggal_beli` datetime DEFAULT NULL,
  `is_active` int(11) DEFAULT 1,
  `user_input` varchar(100) DEFAULT NULL,
  `user_edit` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `index_kodetrans` (`kode_transaksi`(1000)),
  KEY `index_transaksi` (`id_transaksi`,`order_id`) USING BTREE,
  KEY `FK_order` (`order_id`),
  CONSTRAINT `FK_order` FOREIGN KEY (`order_id`) REFERENCES `tbl_order` (`id_order`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `id_user` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `nama_user` varchar(200) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role_id` int(11) NOT NULL,
  `type_user` varchar(255) DEFAULT NULL COMMENT 'Jika Developer Akan Muncul Menu Management Akses\n\ndan tidak muncul pada menu karyawan toko\n\n\njika null tidak memiliki management akses',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
BEGIN;
INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'robby@ardhacodes.com', 'SINYO', '$2y$10$LehzOBWPnnLpkjTMN4V3fON8golwh6AqGhS5EzLWZ1qLBeHqJwvPC', 1, 'DEV', 1, '2024-01-01 13:00:27', '2024-01-01 13:00:46');
INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'dev@ardhacodes.com', 'DEVELOPER', '$2y$10$rggSg5krhFPztTGHNYPq4O9rHHBy.5JUeo0XNr5ibP9nKQiyf28y2', 1, 'DEV', 1, '2024-01-01 13:00:27', '2024-01-01 13:03:59');
INSERT INTO `tbl_user` (`id_user`, `username`, `nama_user`, `password`, `role_id`, `type_user`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'owner', 'Owner Toko', '$2y$10$3I216e2lj1gmWm1VYCE0iuW2IKYU3nj6da/nl8kHofdzkpmJNFN1a', 2, NULL, 1, '2024-01-01 13:25:19', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
