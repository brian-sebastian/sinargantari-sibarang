<?php

class Import_gudang extends CI_Controller{

    private $view;

    public function __construct()
    {
        parent:: __construct();
        cek_login();
        $this->load->model("Gudang_model", "gudang");
    }

    public function index(){

        $this->view["title_menu"]       = "Gudang";
        $this->view["title"]            = "Import Gudang";
        $this->view["content"]          = "import_gudang/index";

        $this->load->view("layout/wrapper", $this->view);

    }

    public function simpan_data_baru(){

        $result = $this->gudang->processCreateDataBarangGudangBaru();

        if($result === true){

            $this->session->set_flashdata('berhasil', "Data berhasil di simpan");
            redirect('gudang/import_gudang/data_baru');

        }else{

            $this->session->set_flashdata('gagal', $result);
            redirect('gudang/import_gudang/data_baru');
            
        }

    }

    public function data_baru(){

        if($this->input->server('REQUEST_METHOD') === 'GET'){

            $this->view["title_menu"]       = "Gudang";
            $this->view["title"]            = "Import Gudang";
            $this->view["content"]          = "import_gudang/data_baru";
            $this->view["data_import_gudang_barus"] = $this->gudang->getImportGudangBaru();

            $this->load->view("layout/wrapper", $this->view);
            
        }else{

            $fileBarang = $_FILES['file_barang'];
            
            if ($fileBarang['name'] == "" || $fileBarang['error'] == 4 || ($fileBarang['size'] == 0 && $fileBarang['error'] == 0)) {
                $this->session->set_flashdata('gagal', 'Tidak Ada File yang diupload');
                redirect('gudang/import_gudang/data_baru');
            }

            if (!empty($fileBarang)) {
                set_time_limit(300000);

                if ($fileBarang['size'] > 5000000) {
                    // 5 Mb maximum Mb -> Byte
                    $this->session->set_flashdata('gagal', 'Maximum File 5 Mb');
                    redirect('gudang/import_gudang/data_baru');
                }

                $this->load->library('Excel_library', array('data' => $_FILES, 'func' => 'import', 'jenis' => 'barang_gudang_baru'));
                
            } else {

                $this->session->set_flashdata('gagal', 'Tidak Ada File yang diupload');
                redirect('gudang/import_gudang/data_baru');
            }

        }

    }

}

?>