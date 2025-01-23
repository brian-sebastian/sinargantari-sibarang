<?php

// Guys controller ini cuman buat experiment aja ya, kalo kalian mau experiment dengan library or apapun itu, bisa disini ya thank u :D
class Custom extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Custom_model", "custom");
    }

    public function index()
    {
        echo "Hello world";
    }

    public function dynamicDatatables()
    {
        $this->view["title_menu"]           = "Custom";
        $this->view["title"]                = "Dynamic datatables";
        $this->view["content"]              = "custom/v_dynamic_datatables";

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxDynamicDatatables()
    {

        $newArr = [];
        $data   = $this->custom->queryAjaxAmbilSemuaDataTransaksi();

        foreach ($data as $d) {

            $row = [];
            $row[] = $d["kode_transaksi"];
            $row[] = $d["terbayar"];
            $row[] = $d["kembalian"];
            $row[] = $d["tagihan_cart"];
            $row[] = $d["total_diskon"];
            $row[] = $d["tagihan_after_diskon"];
            $row[] = $d["total_biaya_kirim"];
            $row[] = $d["total_tagihan"];
            $row[] = $d["tipe_transaksi"];

            array_push($newArr, $row);
        }

        $data_json["draw"]              = $this->input->post("draw");
        $data_json["data"]              = $newArr;
        $data_json["recordsTotal"]      = $this->custom->queryHitungSemuaAmbilSemuaDataTransaksi();
        $data_json["recordsFiltered"]   = $this->custom->queryHitungFilterAmbilSemuaDataTransaksi();

        echo json_encode($data_json);
    }
}
