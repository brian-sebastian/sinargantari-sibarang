<?php

/**
 * @property Barang_masuk_model $Barang_masuk_model
 */

function __construct()
{
    $CI = &get_instance();
    $CI->load->model('Barang_masuk_model');
}

function getReport($report_type, $data)
{
    $CI = &get_instance();

    switch ($report_type) {
        case 'penjualan':
            break;
        case 'transaksi':
            break;
        case 'barang_masuk':
            $tambahBarangMasuk =  $CI->Barang_masuk_model->tambah_data($data);

            if ($tambahBarangMasuk == true) {
                return true;
            } else {
                return false;
            }
            break;
        case 'barang_keluar':
            break;
        default:
            break;
    }
}
