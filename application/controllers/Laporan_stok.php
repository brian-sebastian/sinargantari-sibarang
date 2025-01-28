<?php

class Laporan_stok extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Barang_model", "barang");
        $this->load->model("Harga_model", "harga");
        $this->load->model("Karyawan_model", "karyawan");
        $this->load->model("Toko_model", "toko");
        $this->load->model("Laporan_stok_model", "laporan_stok");
    }

    public function index()
    {
        $role_id = $this->session->userdata('toko_id');

        $this->view["title_menu"]    = "Laporan";
        $this->view["title"]         = "Laporan Stok";
        $this->view["content"]       = "laporan_stok/v_laporan_stok";
        // load data yang di perlukan disini
        if($role_id){
            $this->view["data_barang_toko"] = $this->harga->ambilBarangBerdasarkanToko($role_id);
        }else{
            $this->view["data_barang"]  = $this->barang->getAllBarang();
        }
        $this->view["data_toko"]    = $this->toko->ambilSemuaToko();
       
        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajax_laporan_stok()
    {

        $newArr = [];
        $data = (!$this->input->post("toko_id")) ? [] : $this->laporan_stok->ambilSemuaStok();
        $hitungStok = (!$this->input->post("toko_id")) ? 0 : $this->laporan_stok->ambilHitungStok();
        $filterStok = (!$this->input->post("toko_id")) ? 0 : $this->laporan_stok->ambilFilterStok();
        


        $no = 1;
        foreach ($data as $d) {
            $jml_stok = $d['jml_stok'] + $d['stok_gudang'];

            $row = [];
            $row[] = $no;
            // $row[] = $d['nama_toko'];
            $row[] = $d['kode_barang'];
            $row[] = $d['nama_barang'];
            $row[] = $jml_stok;
            $row[] = (!empty($d['stok_toko'])) ? $d['stok_toko'] : 0;
            $row[] = (!empty($d['stok_gudang'])) ? $d['stok_gudang'] : 0;
            $row[] = $d['harga_jual'];
            $row[] = $d['jml_masuk'];
            $row[] = $d['jml_keluar'];

            array_push($newArr, $row);
            $no++;
        }

        $data_json = [

            'draw' => $this->input->post('draw'),
            'data' => $newArr,
            'recordsTotal' => $hitungStok,
            'recordsFiltered' => $filterStok,

        ];


        echo json_encode($data_json);
    }

    public function barang()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "toko_id",
                "label"     => "toko",
                "rules"     => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $toko_id = htmlspecialchars($this->input->post("toko_id"));

            $data_json["status"]     = "berhasil";
            $data_json["response"]   = $this->harga->ambilBarangBerdasarkanToko($toko_id);
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = "Parameter tidak sesuai";
        }

        echo json_encode($data_json);
    }

    public function cetak_excel()
    {
        $getField = json_decode($this->input->get('field'), TRUE);
        $data = $this->laporan_stok->ambilSemuaStokExcel($getField["tanggal"], $getField["harga_id"], $getField["toko_id"]);

        $libary_excel = [
            'func'      => 'export',
            'jenis'     => 'laporan_stok',
            'filename'  => 'laporan_stok_' . date('Y_m_d_His') . ".xlsx",
            'data_stok' => $data,
            'tanggal'   => ($getField["tanggal"]) ? $getField["tanggal"] : "Semua",
            'toko'      => ($getField["toko_id"]) ? $this->toko->findByIdToko($getField["toko_id"])["nama_toko"] : "Semua",
            'barang'    => ($getField["harga_id"]) ? $this->harga->ambilBarangByHargaId($getField["harga_id"]) : "Semua"
        ];

        if ($data) {
            $this->load->library('Excel_library', $libary_excel);
        }
    }
}
