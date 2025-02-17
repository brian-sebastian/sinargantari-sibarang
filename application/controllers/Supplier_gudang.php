<?php

class Supplier_gudang extends CI_Controller{

    private $view;

    public function __construct()
    {
        
        parent:: __construct();
        cek_login();

        $this->load->model("Supplier_model", "supplier");
        $this->load->model("Gudang_model", "gudang");
        $this->load->model("Barang_model", "barang");
        $this->load->model("Harga_model");
    }

    public function index(){

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "supplier_id",
                "label"     => "Supplier",
                "rules"     => "required|trim",
            ],

            [
                "field"     => "gudang_id",
                "label"     => "Gudang",
                "rules"     => "required|trim",
            ],

            [
                "field"     => "barang_id",
                "label"     => "Barang",
                "rules"     => "required|trim",
            ],

            [
                "field"     => "tanggal_barang_masuk",
                "label"     => "Tanggal barang masuk",
                "rules"     => "required|trim",
            ],

            [
                "field"     => "jml_masuk",
                "label"     => "Jumlah barang masuk",
                "rules"     => "required|trim",
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $result = $this->gudang->createBarangMasuk();

            if($result){

                $this->session->set_flashdata("berhasil", "Data berhasil di insert");
                redirect("gudang/supplier_gudang");

            }else{


                $this->session->set_flashdata("gagal", "Data gagal di insert");
                redirect("gudang/supplier_gudang");
            }


        } else {
            

            $this->view["title_menu"]       = "Gudang";
            $this->view["title"]            = "Supplier Gudang";
            $this->view["content"]          = "supplier_gudang/index";
            $this->view["data_suppliers"]   = $this->supplier->getAllSupplier();
            $this->view["data_gudangs"]     = $this->gudang->getGudang();

            $this->load->view("layout/wrapper", $this->view);
        }

    }

    public function getDataBarangHargaAjaxByGudang()
    {
        $currentTokoId = $this->input->post('toko_id');
        $result = $this->Harga_model->ambilBarangBerdasarkanGudang($currentTokoId);
        echo json_encode($result);
    }

}

?>