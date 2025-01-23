<?php

class Pindah_gudang extends CI_Controller{

    public $view;

    public function __construct()
    {
        parent:: __construct();
        cek_login();

        $this->load->model("Gudang_model", "gudang");
        
    }

    public function index(){

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "gudang_dari_id",
                "label"     => "Gudang dari",
                "rules"     => "required|trim",
            ],

            [
                "field"     => "gudang_ke_id",
                "label"     => "Gudang ke",
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
                "rules"     => "required|trim|callback_is_stok_ready[".(($this->input->post("gudang_dari_id") && $this->input->post("barang_id")) ? $this->input->post("gudang_dari_id") . "|" . $this->input->post("barang_id") : "")."]",
                "errors"    => [

                    "is_stok_ready" => "Qty more than stock ready"

                ]
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $result = $this->gudang->createBarangMasukGudangToGudang();

            if($result){

                $this->session->set_flashdata("berhasil", "Data berhasil di insert");
                redirect("gudang/pindah_gudang");

            }else{


                $this->session->set_flashdata("gagal", "Data gagal di insert");
                redirect("gudang/pindah_gudang");
            }


        } else {
            

            $this->view["title_menu"]       = "Gudang";
            $this->view["title"]            = "Pindah Gudang";
            $this->view["content"]          = "pindah_gudang/index";
            $this->view["data_gudangs"]     = $this->gudang->getGudang();

            $this->load->view("layout/wrapper", $this->view);
        }

    }

    public function is_stok_ready($jumlah, $gudang_and_barang_id = ""){

        if($gudang_and_barang_id){

            [$gudang_id, $barang_id] = explode("|", $gudang_and_barang_id);

            $this->db->where("toko_id", $gudang_id);
            $this->db->where("barang_id", $barang_id);
            $query = $this->db->get("tbl_harga")->row_array();

            if($query["stok_toko"] >= $jumlah){
                
                return TRUE;

            }

            return FALSE;

        }else{

            return FALSE;
        }

    }

    public function data_gudang(){

        $gudang_dari_id = $this->input->post("gudang_dari_id");

        $this->db->where_not_in("id_toko", [$gudang_dari_id]);
        $this->db->where("jenis", "GUDANG");
        $result = $this->db->get("tbl_toko")->result_array();

        $this->db->select("tbl_barang.*");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_harga.toko_id", $gudang_dari_id);
        $result2 = $this->db->get("tbl_harga")->result_array();

        echo json_encode([
            "data_gudang" => $result,
            "data_barang" => $result2,
        ]);
    }

}

?>