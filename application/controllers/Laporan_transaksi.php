<?php

class Laporan_transaksi extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Laporan_transaksi_model", "laporan_transaksi");
        $this->load->model("Toko_model", "toko");
    }

    public function index()
    {

        $this->view["title_menu"]    = "Laporan";
        $this->view["title"]         = "Laporan Transaksi";
        $this->view["content"]       = "laporan_transaksi/v_laporan_transaksi";
        // load data yang di perlukan disini
        $this->view["data_toko"]     = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaTransaksi()
    {
        $newArr = [];
        $data = $this->laporan_transaksi->ajaxAmbilSemuaTransaksi();
        $no = 1;

        foreach ($data as $d) {

            $row = [];

            $row[]  = "<button type='button' class='btn btn-sm btn-primary itsDetail' data-order='" . $d["kode_order"] . "'>Detail barang</button>";
            $row[]  = $no;
            $row[]  = $d["kode_transaksi"];
            $row[]  = $d["kode_order"];
            $row[]  = $d["nama_toko"];
            $row[]  = $d["nama_cust"];
            $row[]  = $d["tipe_order"];
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
        $data_json["recordsTotal"]      = $this->laporan_transaksi->ajaxAmbilHitungSemuaTransaksi();
        $data_json["recordsFiltered"]   = $this->laporan_transaksi->ajaxAmbilFilterSemuaTransaksi();

        echo json_encode($data_json);
    }

    public function ajaxAmbilSemuaTotalTransaksi()
    {

        $data = $this->laporan_transaksi->ajaxAmbilSemuaTotalTransaksi();

        echo json_encode(["totalTransaksi"  => $data]);
    }

    public function exportSemuaTransaksi()
    {
        if ($this->input->get("field")) {

            $field = json_decode($this->input->get("field"), TRUE);

            $res = $this->laporan_transaksi->ajaxAmbilSemuaTransaksiGet($field);

            if ($res) {

                $data_excel = [

                    "func"              => "export",
                    "filename"          => "report-transaksi-" . date("Y-m-d-H-i-s") . ".xlsx",
                    "jenis"             => "laporan_transaksi",
                    "data_transaksi"    => $res,
                    "range"             => $field["created_at"]

                ];

                $this->load->library("Excel_library", $data_excel);
            } else {

                $this->session->set_flashdata("error", "Data kosong");
                redirect("laporan/transaksi", "refresh");
            }
        } else {

            $this->session->set_flashdata("gagal", "Field tidak boleh kosong");
            redirect("laporan/transaksi", "refresh");
        }
    }

    public function detailBarang()
    {

        $form_validation = $this->form_validation;


        $field = [

            [
                "field" => "kode_order",
                "label" => "Kode order",
                "rules" => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $kode_order = htmlspecialchars($this->input->post("kode_order"));

            $result = $this->laporan_transaksi->getDetailBarangByKodeOrder($kode_order);

            if ($result) {

                $data_json["status"] = true;
                $data_json["message"] = "Success";
                $data_json["view"] = $this->load->view("laporan_transaksi/v_modal_laporan_transaksi", [
                    "data" => $result
                ], TRUE);
            } else {

                $data_json["status"] = false;
                $data_json["message"] = "Data not found";
            }
        } else {

            $data_json["status"] = false;
            $data_json["message"] = "Error";
        }

        echo json_encode($data_json);
    }
}
