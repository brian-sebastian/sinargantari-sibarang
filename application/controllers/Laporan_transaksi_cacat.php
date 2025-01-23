<?php

class Laporan_transaksi_cacat extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Laporan_transaksi_cacat_model", "laporan_transaksi_cacat");
        $this->load->model("Toko_model", "toko");
    }

    public function index()
    {

        $this->view["title_menu"]    = "Barang Cacat";
        $this->view["title"]         = "Laporan Transaksi Cacat";
        $this->view["content"]       = "laporan_transaksi_cacat/v_laporan_transaksi_cacat";
        $this->view["data_toko"]     = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaTransaksi()
    {
        $newArr = [];

        $data = $this->laporan_transaksi_cacat->ajaxAmbilSemuaTransaksi();

        $no = 1;

        foreach ($data as $d) {

            $row = [];

            $row[]  = $no;
            $row[]  = $d["kode_transaksi"];
            $row[]  = $d["kode_order"];
            $row[]  = $d["nama_toko"];
            $row[]  = $d["nama_cust"];
            // $row[]  = $d["tipe_order"];
            $row[]  = number_format($d["tagihan_cart"], 0, ".", ".");
            $row[]  = number_format($d["total_diskon"], 0, ".", ".");
            $row[]  = number_format($d["tagihan_after_diskon"], 0, ".", ".");
            $row[]  = number_format($d["total_biaya_kirim"], 0, ".", ".");
            $row[]  = number_format($d["total_tagihan"], 0, ".", ".");
            $row[]  = number_format($d["terbayar"], 0, ".", ".");
            $row[]  = number_format($d["kembalian"], 0, ".", ".");
            $row[]  = $d["tipe_transaksi"];
            $row[]  = convertDate($d["created_at"]);

            array_push($newArr, $row);

            $no++;
        }

        $data_json["draw"]              = $this->input->post("draw");
        $data_json["data"]              = $newArr;
        $data_json["recordsTotal"]      = $this->laporan_transaksi_cacat->ajaxAmbilHitungSemuaTransaksi();
        $data_json["recordsFiltered"]   = $this->laporan_transaksi_cacat->ajaxAmbilFilterSemuaTransaksi();

        echo json_encode($data_json);
    }

    public function ajaxAmbilSemuaTotalTransaksi()
    {

        $data = $this->laporan_transaksi_cacat->ajaxAmbilSemuaTotalTransaksi();

        echo json_encode(["totalTransaksi"  => $data]);
    }

    public function exportSemuaTransaksi()
    {
        if ($this->input->get("field")) {

            $field = json_decode($this->input->get("field"), TRUE);

            $res = $this->laporan_transaksi_cacat->ajaxAmbilSemuaTransaksiGet($field);

            if ($res) {

                $data_excel = [

                    "func"              => "export",
                    "filename"          => "report-transaksi-cacat-" . date("Y-m-d-H-i-s") . ".xlsx",
                    "jenis"             => "laporan_transaksi_cacat",
                    "data_transaksi"    => $res,
                    "range"             => $field["created_at"]

                ];

                $this->load->library("Excel_library", $data_excel);
            } else {

                $this->session->set_flashdata("error", "Data kosong");
                redirect("barang_cacat/lp_penjualan_cacat", "refresh");
            }
        } else {

            $this->session->set_flashdata("gagal", "Field tidak boleh kosong");
            redirect("barang_cacat/lp_penjualan_cacat", "refresh");
        }
    }
}
