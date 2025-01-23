<?php

/**
 * @property Barang_model $barang
 * @property Harga_model $harga
 * @property Kategori_model $kategori
 * @property Barang_history_model $barang_history
 * @property Satuan_model $satuan
 * @property Toko_model $toko
 * @property Logfile_model $logfile
 * @property User_model $user
 * @property form_validation $form_validation
 * @property db $db
 * @property input $input
 * @property session $session
 * @property zend $zend
 * @property upload $upload
 * @property secure $secure
 * @property datelib $datelib
 * @property user $user
 */
class Warehouse extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Barang_history_model', 'barang_history');
        $this->load->model('Harga_model', 'harga');
        $this->load->model('Toko_model', 'toko');
        $this->load->model('Kategori_model', 'kategori');
        $this->load->model('Logfile_model', 'logfile');
        $this->load->model('User_model', 'user');
        $this->datelib->asiaJakartaDate();
        cek_login();
    }

    public function index()
    {

        $data['title_menu'] = "Gudang";
        $data['title']      = "Gudang";
        $data['toko']       = $this->toko->ambilSemuaToko();
        $data['kategori']   = $this->kategori->ambilSemuaKategori();

        if ($this->session->userdata('toko_id')) {

            $toko_id                  = $this->session->userdata('toko_id');
            $data['toko_id']          = $toko_id;
            $data['harga_barang']     = $this->barang->getBarangHargaToko($toko_id);
            $data['harga_temp']       = $this->harga->ambilBarangTemp($toko_id)->num_rows();
        } else {
            if ($this->input->get('toko')) {

                $id_toko                = $this->input->get('toko');
                $decrypt_id             = $this->secure->decrypt_url($id_toko);
                $data["data_toko"]      = $this->toko->ambilDetailToko($decrypt_id);
                $data['harga_barang']   = $this->barang->getHargaBarangToko($decrypt_id);
                $data['harga_temp']     = $this->harga->ambilBarangTemp($decrypt_id)->num_rows();
                $data['toko_id']        = $decrypt_id;

                if (!$data["data_toko"]) {
                    redirect("auth/logout");
                }
            } else {
                $data['barang']     = $this->barang->getAllBarang();
                $data['toko_id']    = null;
                $username           = $this->session->userdata('username');
                $user               = $this->user->findByUsername($username);

                if ($user['role_id'] == 1) {
                    $data['harga_barang'] = $this->barang->getHargaBarangTokoAll();
                } else {
                    $data['harga_barang'] = $this->barang->getHargaBarangFindUsername();
                }
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('warehouse/warehouse_barang_toko/index_warehouse', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function ajaxBarangToko()
    {
        $toko_id = $this->input->post("toko_id");
        $kategori_id = $this->input->post("kategori_id");
        $enkripsi_toko_id = $this->secure->encrypt_url($toko_id);

        $data = $this->barang->ambilSemuaBarangToko($toko_id, $kategori_id);
        $hitungBarangToko = $this->barang->ambilHitungBarangToko($toko_id, $kategori_id);
        $filterBarangToko = $this->barang->ambilFilterBarangToko($toko_id, $kategori_id);

        $row = [];
        $no = 1;

        foreach ($data as $d) {

            $resultCheckDelete = check_request_delete_barang_toko($d['id_harga']);
            $col = [];

            $col[]  = $no;
            $col[]  = $d["nama_toko"];
            $col[]  = $d["nama_barang"];
            $col[]  = $d["nama_kategori"];
            $col[]  = $d['stok_toko'];
            $col[]  = "Rp " . number_format($d['harga_jual'], 0, ".", ".");
            $col[]  = $d['berat_barang'] . " " . $d['satuan'];
            $col[]  = ($d["barcode_barang"] == null) ? "<a class='btn btn-info btn-sm' href='" . base_url('barang/create_barcode/') . $d['kode_barang'] . "'>Create Barcode</a>" : "<img src='" . base_url('assets/barcodes/') . $d['barcode_barang'] . '.png' . "' alt='' srcset=''>";


            array_push($row, $col);
            $no++;
        }

        $data_json = [
            'draw' => $this->input->post('draw'),
            'data' => $row,
            'recordsTotal' => $hitungBarangToko,
            'recordsFiltered' => $filterBarangToko,
        ];

        echo json_encode($data_json);
    }

    public function cetak_excel()
    {
        $getField = json_decode($this->input->get('field'), TRUE);
        $data = $this->barang->ambilSemuaBarangTokoExcel($getField["toko_id"], $getField["kategori_id"]);

        $libary_excel = [
            'func' => 'export',
            'jenis' => 'barang_toko',
            'filename' => 'barang_toko_' . strtolower(str_replace(" ", "_", $data[0]["nama_toko"])) . '_' . date('Y_m_d_His') . ".xlsx",
            'data_barang_toko' => $data,
        ];

        if ($data) {
            $this->load->library('Excel_library', $libary_excel);
        }
    }
}
