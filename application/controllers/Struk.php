<?php

class Struk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Struk_model", "struk");
        $this->load->model("Setting_model", "setting");
    }

    public function index($kode_order)
    {
        $kode_order = $this->secure->decrypt_url($kode_order);

        if (!$kode_order) {
            redirect('auth/blocked');
        }

        if ($kode_order) {

            $dataTransaksi = $this->struk->getTransactionOrderDetail($kode_order);
            $setting = $this->setting->getSetting();

            if ($dataTransaksi) {

                //decode encryption
                $data['transaksi']  = $dataTransaksi;
                $data['setting']    = $setting;
                $kode_transaksi     = $dataTransaksi[0]['kode_transaksi'];

                $data = [
                    'func'      => 'cetak',
                    'view'      => 'cetak/cetak_struk',
                    'data'      => $data,
                    'jenis'     => 'data_pembayaran',
                    'paper'     => array(0, 0, 204, 650),
                    'dpi'       => 72,
                    'filename'  => "Cetak-Struk-$kode_transaksi.pdf"
                ];

                $this->load->library('dompdf_library', $data);
            } else {

                redirect('auth/blocked');
            }
        }
    }
}
