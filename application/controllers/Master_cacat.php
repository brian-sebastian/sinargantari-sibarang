<?php

class Master_cacat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Harga_model', 'harga');
        $this->load->model('Masuk_cacat_model', 'masuk_cacat');
        $this->load->model('Barang_cacat_model', 'barang_cacat');
        $this->load->model('Toko_model', 'toko');
        $this->load->model('User_model', 'user');
        $this->datelib->asiaJakartaDate();
    }

    public function index()
    {

        $this->view["title_menu"]   = "Barang Cacat";
        $this->view["title"]        = "Master Cacat";
        $this->view["content"]      = "master_cacat/v_master_cacat_index";
        
        $this->view['toko']         = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaMasterCacat()
    {

        $newArr = [];
        $data   = $this->barang_cacat->ajaxAmbilSemuaBarangCacat();

        $no = 1;
        foreach ($data as $d) {

            $rsb = "  
                        <a onclick='lihat_keluar(" . $d['id_barang_keluar'] . ")'>
                            <button type='button' class='btn btn-sm btn-info'>
                              <span class='tf-icons bx bx-show-alt'></span>&nbsp; Lihat
                            </button>
                        </a>
                     ";


            $row = [];
            $row[] = $no;
            $row[] = $d["kd_barang_cacat"];
            $row[] = $d["nama_barang"];
            $row[] = $d["nama_toko"];
            $row[] = $d["stok_cacat"];
            $row[] = ($d["status"] == 1) ? "<small class='text-success'>Tersedia</small>" : "<small class='text-danger'>Kosong</small>";
            $row[] = $rsb;

            array_push($newArr, $row);
            $no++;
        }

        $data_json["draw"]              = $this->input->post("draw");
        $data_json["data"]              = $newArr;
        $data_json["recordsTotal"]      = $this->barang_cacat->ajaxAmbilHitungSemuaBarangCacat();
        $data_json["recordsFiltered"]   = $this->barang_cacat->ajaxAmbilFilterSemuaBarangCacat();

        echo json_encode($data_json);
    }
}
