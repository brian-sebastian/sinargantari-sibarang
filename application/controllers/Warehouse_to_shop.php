<?php

/**
 * @property Barang_model $barang
 * @property Harga_model $harga
 * @property Kategori_model $kategori
 * @property Barang_history_model $barang_history
 * @property Satuan_model $satuan
 * @property Toko_model $toko
 * @property Warehouse_model $warehouse
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
class Warehouse_to_shop extends CI_Controller
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
        $this->load->model('Warehouse_model', 'warehouse');
        $this->datelib->asiaJakartaDate();
        cek_login();
    }

    public function index()
    {
        $data['title_menu'] = "Stok Gudang Toko";
        $data['title']      = "Stok Gudang Toko";
        $data['toko']       = $this->warehouse->getShopWarehouse('RESULT', 'TOKO');
        $data['gudang']     = $this->warehouse->getShopWarehouse('RESULT', 'GUDANG');
        $data['kategori']   = $this->kategori->ambilSemuaKategori();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('warehouse_to_shop/index', $data);
        $this->load->view('warehouse_to_shop/script', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }


    public function getBarangGudang()
    {
        $toko_id = $this->input->get('toko_id');
        $cari = $this->input->get('cari_barang');
       
        $dec = $this->secure->decrypt_url($toko_id);
      
        $this->db->select('b.id_barang, b.nama_barang, k.nama_kategori, h.stok_toko, h.harga_jual, b.berat_barang, b.barcode_barang, h.id_harga');
        $this->db->from('tbl_harga h');
        $this->db->join('tbl_barang b', 'b.id_barang = h.barang_id');
        $this->db->join('tbl_kategori k', 'k.id_kategori = b.kategori_id');
        $this->db->where('h.toko_id', $dec);
        if (!empty($cari)) {
            // do query cari_barang
            $this->db->like("b.nama_barang", $cari);
        }
        $query = $this->db->get();
        $data = $query->result_array();
        if(!empty($data)){
            echo json_encode(['status' => true, 'data' => $data]);
        }else{
            echo json_encode(['status' => false, 'message' => "Barang Tidak Di Temukan"]);
        }
    }

    public function getBarangLuar()
    {
        $toko_id = $this->input->get('toko_id');

        if ($toko_id == 'TOKO_LUAR_TL_NONSHOP') {
            $this->db->select('id_barang, kode_barang, nama_barang');
            $this->db->from('tbl_barang');
            $query = $this->db->get();
            $barang = $query->result_array();

            if ($barang) {
                echo json_encode([
                    'status' => true,
                    'data' => $barang
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Tidak ada barang ditemukan.'
                ]);
            }
        }
    }


    public function saveBarang()
    {
        $gudang_id = $this->secure->decrypt_url($this->input->post('gudang_id'));
        $toko_id = $this->secure->decrypt_url($this->input->post('toko_id'));
        $barang_data = $this->input->post('barang_data');
        $valid = true;
        $message = '';
    //    var_dump($barang_data);
    //    die;

        if (!empty($barang_data)) {
            foreach ($barang_data as $barang) {

                $this->db->trans_start();
                // var_dump($toko_id);
                // var_dump($barang['id_barang']);
                $this->db->where('toko_id', $toko_id);
                $this->db->where('barang_id', $barang['id_barang']);
                $existing = $this->db->get('tbl_harga')->row();
                //    var_dump($existing);
                //    die;
                // var_dump($barang['qty_pindah']);
                // die;
                if ($existing) {
                    // Kurangi stok gudang
                    $this->db->set('stok_toko', 'stok_toko - ' . (int)$barang['qty_pindah'], false);
                    $this->db->where('id_harga', $barang['id_harga']);
                    $this->db->update('tbl_harga');

                    // tambah stok toko & update harga jual toko
                    // Di gudang opsional jangan di hapus barang kali di perlukan pada saat harga jual
                    // $this->db->set('harga_jual', $barang['harga_jual']);
                    $this->db->set('stok_toko', 'stok_toko + ' . (int)$barang['qty_pindah'], false);
                    $this->db->where('toko_id', $toko_id);
                    $this->db->where('barang_id', $barang['id_barang']);
                    $this->db->update('tbl_harga');

                    $data_barang_keluar = [
                        'toko_id' => $toko_id,
                        'harga_id' => $barang['id_harga'],
                        'jenis_keluar' => 'PINDAHAN_DARI_GUDANG',
                        'jml_keluar' => $barang['qty_pindah'],
                        'tanggal_barang_keluar' => date('Y-m-d H:i:s'),
                        'user_input' => $this->session->userdata('username'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insert('tbl_barang_keluar', $data_barang_keluar);
                } else {
                    // Kurangi stok gudang
                    $this->db->set('stok_toko', 'stok_toko - ' . (int)$barang['qty_pindah'], false);
                    $this->db->where('id_harga', $barang['id_harga']);
                    $this->db->update('tbl_harga');

                    $data = [
                        'barang_id' => $barang['id_barang'],
                        'toko_id' => $toko_id,
                        'stok_toko' => (int)$barang['qty_pindah'],
                        // Di gudang opsional jangan di hapus barang kali di perlukan pada saat harga jual
                        // 'harga_jual' => $barang['harga_jual'],
                        'is_active' => 1,
                    ];
                    $this->db->insert('tbl_harga', $data);
                    $lastIdHarga = $this->db->insert_id();

                    $data_barang_keluar = [
                        'toko_id' => $toko_id,
                        'harga_id' => $lastIdHarga,
                        'jenis_keluar' => 'PINDAHAN_DARI_GUDANG',
                        'jml_keluar' => $barang['qty_pindah'],
                        'tanggal_barang_keluar' => date('Y-m-d H:i:s'),
                        'user_input' => $this->session->userdata('username'),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insert('tbl_barang_keluar', $data_barang_keluar);

                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $valid = false;
                    $message = 'Transaksi Gagal';
                } else {
                    $this->db->trans_commit();
                    $valid = true;
                    $message = 'Data Berhasil Disimpan';
                }
            }

            if ($valid == true) {
                echo json_encode(['status' => true, 'message' => $message]);
            } else {
                echo json_encode(['status' => false, 'message' => $message]);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Tidak ada data yang dipilih']);
        }
    }
}
