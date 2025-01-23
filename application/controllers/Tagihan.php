<?php

class Tagihan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Tagihan_model", "tagihan");
        $this->load->model("Setting_model", "setting");
        $this->load->model("Bank_model", "bank");
        $this->load->model("Payment_model", "payment");
    }

    public function index($kode_order)
    {

        $kode_order = $this->secure->decrypt_url($kode_order);

        $res = $this->tagihan->ambilInvoice($kode_order, $this->session->userdata("sess_id"));

        if ($res) {

            $parameter = [

                'func'          => 'print',
                'filename'      => 'invoice-' . str_replace(" ", "-", $res[0]["kode_order"]) . "-" . date('Y-m-d-H-i-s'),
                'paper'         => [
                    'size'      => 'A4',
                    'position'  => 'potrait'
                ],
                'title'         => "Invoice " . $res[0]["kode_order"],
                'isi'           => 'cetak/cetak_tagihan',
                'data'          => $res,
                'data_bank'     => array_map(function ($elemen) {
                    $elemen["payment"] = $this->payment->ambilSemuaPaymentBerdasarkanBankId($elemen["id_bank"]);
                    return $elemen;
                }, $this->bank->ambilSemuaBank()),
                'setting'       => $this->setting->getSetting()
            ];

            $this->load->library('dompdf_library', $parameter);
        } else {

            redirect("auth/blocked", "refresh");
        }
    }
}
