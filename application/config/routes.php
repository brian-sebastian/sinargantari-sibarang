<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = 'err_404';
$route['translate_uri_dashes'] = FALSE;

// Dhika
$route["toko/karyawan"]                             = "Karyawan/index";
$route["toko/karyawan/tambah"]                      = "Karyawan/tambah";
$route["toko/karyawan/ubah"]                        = "Karyawan/ubah";
$route["toko/karyawan/detail"]                      = "Karyawan/detail";
$route["toko/karyawan/hapus/(:any)"]                = "Karyawan/hapus/$1";
$route["toko/karyawan/import"]                      = "Karyawan/doImportKaryawan";
$route["toko/karyawan/temp"]                        = "Karyawan/temp";

$route["barang/kategori"]                           = "Kategori/index";
$route["barang/kategori/tambah"]                    = "Kategori/tambah";
$route["barang/kategori/ubah"]                      = "Kategori/ubah";
$route["barang/kategori/detail"]                    = "Kategori/detail";
$route["barang/kategori/hapus/(:any)"]              = "Kategori/hapus/$1";

$route["barang/satuan"]                             = "Satuan/index";
$route["barang/satuan/tambah"]                      = "Satuan/tambah";
$route["barang/satuan/ubah"]                        = "Satuan/ubah";
$route["barang/satuan/detail"]                      = "Satuan/detail";
$route["barang/satuan/hapus/(:any)"]                = "Satuan/hapus/$1";

$route["barang/barang_toko"]                        = "barang_toko/index";
$route["barang/barang_toko/tambah"]                 = "barang_toko/tambah";
$route["barang/barang_toko/ubah/(:any)"]            = "barang_toko/ubah/$1";
$route["barang/barang_toko/detail"]                 = "barang_toko/detail";
$route["barang/barang_toko/hapus/(:any)"]           = "barang_toko/hapus/$1";
$route["barang/barang_toko/request_hapus_again/(:any)"]           = "barang_toko/request_hapus_again/$1";
$route["barang/barang_toko/import"]                 = "barang_toko/importExcel";
$route["barang/barang_toko/status/(:any)"]          = "barang_toko/status/$1";
$route["barang/barang_toko/ajax"]                   = "barang_toko/ajaxBarangToko";
$route["barang/barang_toko/temp/(:any)"]            = "barang_toko/temp/$1";
$route["barang/barang_toko/deleteSelectedItems"]    = "barang_toko/deleteSelectedItems";
$route["barang/barang_toko/getHistoryPriceBarangToko"] = "barang_toko/getHistoryPriceBarangToko";
$route["barang/barang_toko/export"]                 = "barang_toko/exportExcel";

$route["kasir/sales_order"]                         = "Sales_order/index";
$route["kasir/sales_order/detail/(:any)"]           = "Sales_order/detailSalesOrder/$1";
$route["kasir/sales_order/view"]                    = "Sales_order/viewDetailSalesOrder";
$route["kasir/sales_order/ubah"]                    = "Sales_order/ubahSalesOrder";
$route["kasir/sales_order/tambah"]                  = "Sales_order/tambahSalesOrder";
$route["kasir/sales_order/submit"]                  = "Sales_order/submitSalesOrder";
$route["kasir/sales_order/rollback"]                = "Sales_order/rollbackSalesOrder";
$route["kasir/sales_order/upload"]                  = "Sales_order/uploadBuktiBayarSalesOrder";
$route["kasir/sales_order/konfirmasi"]              = "Sales_order/konfirmasiSalesOrder";
$route["kasir/sales_order/ajax"]                    = "Sales_order/ajaxAmbilSemuaSalesOrder";
$route["kasir/sales_order/rekening"]                = "Sales_order/ajaxRekeningBank";
$route["kasir/sales_order/notifikasi/(:any)"]       = "Sales_order/waNotifikasi/$1";
$route["kasir/sales_order/rollbackUbah"]               = "Sales_order/rollbackUbahSalesOrder";

$route["custom"]                                    = "Custom/index";
$route["custom/datatables"]                         = "Custom/dynamicDatatables";
$route["custom/ajax_datatables"]                    = "Custom/ajaxDynamicDatatables";

$route["laporan/transaksi"]                         = "Laporan_transaksi/index";
$route["laporan/transaksi/ajax"]                    = "Laporan_transaksi/ajaxAmbilSemuaTransaksi";
$route["laporan/transaksi/ajax/total"]              = "Laporan_transaksi/ajaxAmbilSemuaTotalTransaksi";
$route["laporan/transaksi/export/excel"]            = "Laporan_transaksi/exportSemuaTransaksi";
$route["laporan/transaksi/detail_barang"]           = "Laporan_transaksi/detailBarang";

$route["marketplace/banner"]                        = "Banner/index";
$route["marketplace/banner/hapus/(:any)"]           = "Banner/hapusBanner/$1";
$route["marketplace/banner/tambah"]                 = "Banner/tambahBanner";
$route["marketplace/banner/detail"]                 = "Banner/detailBanner";
$route["marketplace/banner/ubah"]                   = "Banner/ubahBanner";

$route["dashboard/penjualan"]                       = "Dashboard/ambilPenjualan";

// End dhika

$route["barang/index"]                      = "barang/index";
$route["barang/list"]                       = "barang/index";
$route["barang/list/tambah"]                = "barang/tambah";
$route["barang/list/ubah"]                  = "barang/ubah";
$route["barang/list/detail/(:any)"]         = "barang/detail/$1";
$route["barang/list/edit/barcode/(:any)"]   = "barang/tampilan_edit_barcode/$1";
$route["barang/list/edit/barcode"]          = "barang/edit_barcode";
$route["barang/list/hapus/(:any)"]          = "barang/hapus/$1";
$route["barang/list/request_hapus_again/(:any)"]          = "barang/request_hapus_again/$1";
$route["barang/list/import"]                = "barang/doImportBarang";
$route["barang/list/temp"]                  = "barang/temp";
$route["barang/list/ajax"]                  = "barang/ajaxBarang";
$route["barang/list/cetak_barcode"]              = "barang/cetakBarcode";

$route["barang/keluar"]                     = "Barang_Keluar/index";
$route["barang/keluar/dt_barang_toko"]      = "Barang_Keluar/dt_barang_toko";
$route["barang/keluar/lihat_keluarmodel/(:any)"]   = "Barang_Keluar/lihat_keluarmodel/$1";
$route["barang/keluar/ubah_keluarmodel/(:any)"]   = "Barang_Keluar/ubah_keluarmodel/$1";
$route["barang/keluar/tambah"]              = "Barang_Keluar/tambah";
$route["barang/keluar/ubah"]                = "Barang_Keluar/ubah";
$route["barang/keluar/ajax"]                = "Barang_Keluar/ajaxAmbilSemuaBarangKeluar";

$route["barang/request_barang"]                         = "Request_barang/index";
$route["barang/request_barang/checkRequestBarang"]      = "Request_barang/checkRequestBarang";
$route["barang/request_barang/getBarangTokoSelectAjax"]      = "Request_barang/getBarangTokoSelectAjax";
$route["barang/request_barang/getSelectedDetailBarang"]      = "Request_barang/getSelectedDetailBarang";
$route["barang/request_barang/saveAndAddRequest"]      = "Request_barang/saveAndAddRequest";
$route["barang/request_barang/deleteRequest"]      = "Request_barang/deleteRequest";
$route["barang/request_barang/tambah"]                     = "Request_barang/tambah";
$route["barang/request_barang/ajax_request"]             = "Request_barang/ajaxAmbilSemuaRequestBarangKeToko";
$route["barang/request_barang/tambah"]                  = "Request_barang/tambah";
$route["barang/request_barang/ajax_request"]            = "Request_barang/ajaxAmbilSemuaRequestBarangKeToko";
$route["barang/request_barang/ajax_request_penerimaan"] = "Request_barang/ajaxAmbilSemuaRequestBarangDariPenerimaToko";
$route["barang/request_barang/acc_detail"]              = "Request_barang/accDetailRequest";
$route["barang/request_barang/acc_submit"]              = "Request_barang/accSubmitRequest";

$route["toko/diskon"]                       = "Diskon/index";
$route["toko/diskon/dt_toko/(:any)"]        = "Diskon/dt_toko/$1";
$route["toko/diskon/dt_barang_toko"]        = "Diskon/dt_barang_toko";
$route["toko/diskon/tambah"]                = "Diskon/tambah";
$route["toko/diskon/lihat/(:any)"]           = "Diskon/lihat_diskonmodel/$1";
$route["toko/diskon/ubah/(:any)"]            = "Diskon/ubah_diskonmodel/$1";
$route["toko/diskon/tambah"]                = "Diskon/tambah";
$route["toko/diskon/ubah"]                  = "Diskon/ubah";
$route["toko/diskon/hapus/(:any)"]          = "Diskon/hapus/$1";

$route["laporan/stok"]                      = "Laporan_stok/index";
$route["laporan/stok/ajax"]                 = "Laporan_stok/ajax_laporan_stok";
$route["laporan/stok/barang"]               = "Laporan_stok/barang";
$route["laporan/stok/excel"]                = "Laporan_stok/cetak_excel";

$route["role_akses"]                        = "Role_akses/index";
$route["role_akses/akses_menu"]             = "Role_akses/changeaccess_menu";
$route["role_akses/akses_submenu"]          = "Role_akses/changeaccess_submenu";
$route["role_akses/ubah_role/(:any)"]       = "Role_akses/ubah/$1";
$route["role_akses/ubah_rolemodel/(:any)"]  = "Role_akses/ubah_rolemodel/$1";
$route["role_akses/hapus_role/(:any)"]      = "Role_akses/delete/$1";
$route["role_akses/detail/(:any)/(:any)"]   = "Role_akses/detailRole/$1/$2";
$route["role_akses/akses_rolemodel/(:any)"] = "Role_akses/akses_rolemodel/$1";

$route["kasir/order"]                       = "Order/index";
$route["kasir/order/(:any)"]                = "Order/index";
$route["kasir/cart"]                        = "Order/add_cart";
$route["kasir/ubah_qty"]                    = "Order/update_qty";
$route["kasir/chek_out"]                    = "Order/tambah_order";
$route["kasir/check_ordermodal/(:any)"]     = "Order/check_ordermodal/$1";

$route["kasir/scan"]                       = "scan/index";
$route["kasir/scan/get_data_barang_ajax"]  = "scan/get_data_barang_ajax";
$route["kasir/scan/show_cart_echo"]        = "scan/show_cart_echo";
$route["kasir/scan/checkout"]              = "scan/checkout";
$route["kasir/scan/print_nota"]            = "scan/print_nota";
$route["kasir/scan/print_nota_pdf"]        = "scan/print_nota_pdf";
$route["kasir/scan/print_nota_raw"]        = "scan/print_nota_raw";
$route["kasir/scan/cek_stok"]              = "scan/checkStockSpecific";
$route["kasir/scan/load_cart"]              = "scan/load_cart";
$route["kasir/scan/is_show_action_detail_cart_check"]              = "scan/is_show_action_detail_cart_check";
$route["kasir/scan/hapus_cart"]              = "scan/hapus_cart";
$route["kasir/scan/destroy_cart"]              = "scan/destroy_cart";
$route["kasir/scan/searchBarcodeAutoComplete"]              = "scan/searchBarcodeAutoComplete";
$route["kasir/scan/checkPaymentKembalian"]              = "scan/checkPaymentKembalian";
$route["kasir/scan/doCheckout"]              = "scan/doCheckout";
$route["kasir/scan/rekening"]                = "scan/ajaxRekeningBank";


$route['toko/store_management'] = "toko";
$route['toko/store_management'] = "toko/index";
$route['toko/store_management/tambah'] = "toko/tambah";
$route['toko/store_management/ubah'] = "toko/ubah";
$route['toko/store_management/hapus'] = "toko/hapus";

// Brian
$route['user/index']                            = "User/index";
$route['user/tambah']                           = "User/tambah";
$route['user/edit']                             = "User/edit";
$route['user/tampilan/edit']                    = "User/tampilan_edit";
$route['user/change_password']                  = "User/changePassword";
$route['user/hapus/(:any)']                     = "User/hapus/$1";

$route['barang/masuk']                          = "Barang_Masuk/index";
$route['barang/masuk/ajax']                     = "Barang_Masuk/ajax_barang_masuk";
$route['barang/masuk/tambah']                   = "Barang_Masuk/tambah";
$route['barang/masuk/barang/harga/(:any)']      = "Barang_Masuk/json_barang_by_toko/$1";
$route['barang/masuk/hapus/(:any)']             = "Barang_Masuk/hapus/$1";
$route['barang/masuk/getDataBarangHargaAjax']   = "Barang_Masuk/getDataBarangHargaAjax";
$route['barang/masuk/getDataBarangAjax']   		= "Barang_Masuk/getDataBarangAjax";
$route['barang/masuk/hapusDataBarangAjax']   	= "Barang_Masuk/hapusDataBarangAjax";


$route['toko/supplier']                         = "Supplier/index";
$route['toko/supplier/ajax']                    = "Supplier/ajax_supplier";
$route['toko/supplier/tambah']                  = "Supplier/tambah";
$route['toko/supplier/tampilan_edit/(:any)']    = "Supplier/tampilan_edit/$1";
$route['toko/supplier/edit']                    = "Supplier/edit";
$route['toko/supplier/hapus/(:any)']            = "Supplier/hapus/$1";

$route['profile/profil']                        = "profile/profil";
$route['profile/tampilan_edit']                 = "profile/tampilan_edit";
$route['profile/changepassword']                = "profile/changepassword";
$route['profile/edit']                          = "profile/edit";
$route['profile/upload_photo']                          = "profile/upload_photo";

$route['kasir/transaksi']                       = "Transaksi/index";
$route['kasir/transaksi/edit']                  = "Transaksi/edit";
$route['kasir/transaksi/edit/(:any)']           = "Transaksi/tampilan_edit/$1";
$route['kasir/transaksi/detail/(:any)']         = "Transaksi/detail/$1";

$route['laporan/penjualan']                     = "Laporan_Penjualan/index";
$route['laporan/penjualan/excel']               = "Laporan_Penjualan/cetak_excel";
$route['laporan/penjualan/detail/(:any)']       = "Laporan_Penjualan/detail/$1";
$route['laporan/penjualan/ajax']                = "Laporan_Penjualan/ajax_laporan_penjualan";
$route['laporan/penjualan/total']               = "Laporan_Penjualan/total_laporan_penjualan";

$route['laporan/pendapatan']                    = "Laporan_Pendapatan/index";
$route['laporan/pendapatan/ajax']               = "Laporan_Pendapatan/ajax_laporan_pendapatan";
$route['laporan/pendapatan/ajax/total']         = "Laporan_Pendapatan/ajax_total_pendapatan";
$route['laporan/pendapatan/excel']              = "Laporan_Pendapatan/cetak_excel";


// End Brian

$route['setting/index'] = "setting/index";
$route['setting'] = "setting/index";
$route['setting/update_setting'] = "setting/changeSetting";
$route['setting/backupdb'] = "setting/backupdb";

$route['setting/bank']  = "bank/index";
$route['setting/bank/tambah'] = "bank/tambahBank";
$route['setting/bank/detail'] = "bank/detailBank";
$route['setting/bank/ubah'] = "bank/ubahBank";
$route['setting/bank/hapus/(:any)'] = "bank/hapusBank/$1";

$route['setting/payment']  = "payment/index";
$route['setting/payment/tambah'] = "payment/tambahPayment";
$route['setting/payment/detail'] = "payment/detailPayment";
$route['setting/payment/ubah'] = "payment/ubahPayment";
$route['setting/payment/hapus/(:any)'] = "payment/hapusPayment/$1";

$route['access/menu'] = "menu";
$route['access/menu'] = "menu/index";
$route['access/menu/tambah'] = "menu/tambah";
$route['access/menu/ubah'] = "menu/ubah";
$route['access/menu/hapus'] = "menu/hapus";

$route['access/submenu'] = "submenu";
$route['access/submenu'] = "submenu/index";
$route['access/submenu/tambah'] = "submenu/tambah";
$route['access/submenu/ubah'] = "submenu/ubah";
$route['access/submenu/hapus'] = "submenu/hapus";

$route['access/role'] = "role";
$route['access/role'] = "role/index";
$route['access/role/tambah'] = "role/tambah";
$route['access/role/ubah'] = "role/ubah";
$route['access/role/hapus'] = "role/hapus";
$route['access/role/change_access'] = "role/changeAccess";

$route['barang/request_hapus_barang/index'] = "request_hapus_barang/index";
$route['barang/request_hapus_barang'] = "request_hapus_barang/index";
$route['barang/request_hapus_barang/accept_delete/(:any)'] = "request_hapus_barang/setujui_hapus/$1";
$route['barang/request_hapus_barang/reject_delete/(:any)'] = "request_hapus_barang/tolak_hapus/$1";

$route['tagihan/(:any)'] = "tagihan/index/$1";
$route['struk/(:any)'] = "struk/index/$1";

$route['barang_cacat/masuk_cacat']                          = "Masuk_cacat/index";
$route['barang_cacat/masuk_cacat/ajax']                      = "Masuk_cacat/ajaxAmbilSemuaMasukCacat";
$route['barang_cacat/masuk_cacat/tambah']                      = "Masuk_cacat/tambah";
$route['barang_cacat/masuk_cacat/lihat_masukcacat_model']      = "Masuk_cacat/lihat_masukcacat_model";

$route['barang_cacat/master_cacat']  = "Master_cacat/index";
$route['barang_cacat/master_cacat/ajax']  = "Master_cacat/ajaxAmbilSemuaMasterCacat";

$route['barang_cacat/lp_penjualan_cacat'] = "Laporan_penjualan_cacat/index";
$route['barang_cacat/lp_penjualan_cacat/ajax'] = "Laporan_penjualan_cacat/ajax_laporan_penjualan";
$route['barang_cacat/lp_penjualan_cacat/total'] = "Laporan_penjualan_cacat/total_laporan_penjualan";
$route['barang_cacat/lp_penjualan_cacat/excel'] = "Laporan_penjualan_cacat/cetak_excel";

$route["barang_cacat/lp_transaksi_cacat"] = "Laporan_transaksi_cacat/index";
$route["barang_cacat/lp_transaksi_cacat/ajax"] = "Laporan_transaksi_cacat/ajaxAmbilSemuaTransaksi";
$route["barang_cacat/lp_transaksi_cacat/ajax/total"] = "Laporan_transaksi_cacat/ajaxAmbilSemuaTotalTransaksi";
$route["barang_cacat/lp_transaksi_cacat/export/excel"] = "Laporan_transaksi_cacat/exportSemuaTransaksi";

$route["barang_cacat/musnah_cacat"] = "barang_musnah/index";
$route["barang_cacat/musnah_cacat/ajax"] = "barang_musnah/ajaxBarangMusnah";
$route["barang_cacat/musnah_cacat/hapus/(:any)/(:any)"] = "barang_musnah/hapus/$1/$2";

$route["barang_cacat/sales_order_cacat"]                         = "sales_order_cacat/index";
$route["barang_cacat/sales_order_cacat/detail/(:any)"]           = "sales_order_cacat/detailSalesOrderCacat/$1";
$route["barang_cacat/sales_order_cacat/view"]                    = "sales_order_cacat/viewDetailSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/ubah"]                    = "sales_order_cacat/ubahSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/submit"]                  = "sales_order_cacat/submitSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/rollback"]                = "sales_order_cacat/rollbackSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/upload"]                  = "sales_order_cacat/uploadBuktiBayarSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/konfirmasi"]              = "sales_order_cacat/konfirmasiSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/ajax"]                    = "sales_order_cacat/ajaxAmbilSemuaSalesOrderCacat";
$route["barang_cacat/sales_order_cacat/rekening"]                = "sales_order_cacat/ajaxRekeningBank";
$route["barang_cacat/sales_order_cacat/notifikasi/(:any)"]       = "sales_order_cacat/waNotifikasi/$1";

//Utility
$route["driver/driver_printer"] = "utility_driver/index";
$route["driver/driver_app"] = "utility_driver/driver_app";
$route["driver/driver_thermer"] = "utility_driver/driver_thermer";
$route["driver/downloadDriver58"] = "utility_driver/downloadDriver58";
$route["driver/downloadDriver80"] = "utility_driver/downloadDriver80";
$route["driver/downloadDriver120"] = "utility_driver/downloadDriver120";

$route["gudang/index"] = "warehouse/index";
$route["gudang/barang_toko/ajax"]                   = "warehouse/ajaxBarangToko";
$route["gudang/barang_toko/excel"]                  = "warehouse/cetak_excel";
$route["gudang/barang_toko/ubah/(:any)"]            = "warehouse/ubah/$1";

$route["gudang/toko_gudang"]                  = "shop_warehouse/index";
$route["gudang/toko_gudang/getBarangToko"]                  = "shop_warehouse/getBarangToko";
$route["gudang/toko_gudang/getBarangLuar"]                  = "shop_warehouse/getBarangLuar";
$route["gudang/toko_gudang/saveBarang"]                  = "shop_warehouse/saveBarang";


$route["gudang/supplier_gudang"] = "supplier_gudang/index";
$route["gudang/getDataBarangHargaAjax"] = "supplier_gudang/getDataBarangHargaAjaxByGudang";
$route["gudang/pindah_gudang"] = "pindah_gudang/index";
$route["gudang/pindah_gudang/data_gudang"] = "pindah_gudang/data_gudang";
$route["gudang/import_gudang"] = "import_gudang/index";
$route["gudang/import_gudang/data_baru"] = "import_gudang/data_baru";
$route["gudang/import_gudang/simpan_data_baru"] = "import_gudang/simpan_data_baru";

$route["gudang/dist_gudang_toko"]                  = "warehouse_to_shop/index";
$route["gudang/dist_gudang_toko/getBarangGudang"]                  = "warehouse_to_shop/getBarangGudang";
$route["gudang/dist_gudang_toko/saveBarang"]                  = "warehouse_to_shop/saveBarang";
