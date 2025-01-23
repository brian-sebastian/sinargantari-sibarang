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
class Shop_warehouse extends CI_Controller
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


        $data['title_menu'] = "Toko Gudang";
        $data['title']      = "Toko Gudang";
        $data['toko']       = $this->warehouse->getShopWarehouse('RESULT', 'TOKO');
        $data['gudang']       = $this->warehouse->getShopWarehouse('RESULT', 'GUDANG');
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
        $this->load->view('shop_warehouse/index', $data);
        $this->load->view('shop_warehouse/script', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }


    public function getBarangToko()
    {
        $toko_id = $this->input->get('toko_id');
        $dec = $this->secure->decrypt_url($toko_id);
        $this->db->select('b.id_barang, b.nama_barang, k.nama_kategori, h.stok_toko, h.harga_jual, b.berat_barang, b.barcode_barang, h.id_harga');
        $this->db->from('tbl_harga h');
        $this->db->join('tbl_barang b', 'b.id_barang = h.barang_id');
        $this->db->join('tbl_kategori k', 'k.id_kategori = b.kategori_id');
        $this->db->where('h.toko_id', $dec);
        $query = $this->db->get();
        $data = $query->result_array();
        echo json_encode(['status' => true, 'data' => $data]);
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
        $toko_id = $this->input->post('toko_id');
        $gudang_id = $this->secure->decrypt_url($this->input->post('gudang_id'));
        $barang_data = $this->input->post('barang_data');
        $valid = true;
        $message = '';

        // JIKA BELI DARI TOKO LUAR KE GUDANG
        if ($toko_id == 'TOKO_LUAR_TL_NONSHOP') {
            $nama_toko = $this->input->post('nama_toko');
            if (!empty($barang_data)) {
                foreach ($barang_data as $barang) {
                    $this->db->trans_start();

                    $this->db->where('toko_id', $gudang_id);
                    $this->db->where('barang_id', $barang['id_barang']);
                    $existing = $this->db->get('tbl_harga')->row();

                    if ($existing) {
                        $this->db->set('stok_toko', 'stok_toko + ' . (int)$barang['qty_beli'], false);
                        $this->db->where('id_harga', $existing->id_harga);
                        $this->db->update('tbl_harga');
                    } else {
                        $data = [
                            'toko_id' => $gudang_id,
                            'barang_id' => $barang['id_barang'],
                            'stok_toko' => (int)$barang['qty_beli'],
                            'harga_jual' => 0,
                            'is_active' => 0,
                        ];
                        $this->db->insert('tbl_harga', $data);
                        $lastIdHarga = $this->db->insert_id();

                        $data_barang_masuk = [
                            'harga_id' => $lastIdHarga,
                            'jml_masuk' => (int)$barang['qty_beli'],
                            'tipe' => 'toko_luar',
                            'tanggal_barang_masuk' => $barang['tgl_beli'],
                            'nama_toko_beli' => $nama_toko,
                            'user_input' => $this->session->userdata('username'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->db->insert('tbl_barang_masuk', $data_barang_masuk);
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

        // JIKA PINDAHAN DARI TOKO KE GUDANG 
        if ($toko_id != 'TOKO_LUAR_TL_NONSHOP') {
            $dcdToko = $this->secure->decrypt_url($toko_id);
            if (!empty($barang_data)) {
                foreach ($barang_data as $barang) {

                    $this->db->trans_start();

                    $this->db->where('toko_id', $gudang_id);
                    $this->db->where('barang_id', $barang['id_barang']);
                    $existing = $this->db->get('tbl_harga')->row();

                    if ($existing) {

                        $this->db->set('stok_toko', 'stok_toko - ' . (int)$barang['qty_pindah'], false);
                        $this->db->where('id_harga', $barang['id_harga']);
                        $this->db->update('tbl_harga');

                        $this->db->set('stok_toko', 'stok_toko + ' . (int)$barang['qty_pindah'], false);
                        $this->db->where('toko_id', $gudang_id);
                        $this->db->where('barang_id', $barang['id_barang']);
                        $this->db->update('tbl_harga');
                    } else {
                        $this->db->set('stok_toko', 'stok_toko - ' . (int)$barang['qty_pindah'], false);
                        $this->db->where('id_harga', $barang['id_harga']);
                        $this->db->update('tbl_harga');

                        $data = [
                            'barang_id' => $barang['id_barang'],
                            'toko_id' => $gudang_id,
                            'stok_toko' => (int)$barang['qty_pindah'],
                            'harga_jual' => 0,
                            'is_active' => 0,
                        ];
                        $this->db->insert('tbl_harga', $data);
                        $lastIdHarga = $this->db->insert_id();

                        $data_barang_masuk = [
                            'harga_id' => $lastIdHarga,
                            'jml_masuk' => (int)$barang['qty_pindah'],
                            'tanggal_barang_masuk' => date('Y-m-d H:i:s'),
                            'tipe' => 'pindahan_toko_internal',
                            'user_input' => $this->session->userdata('username'),
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->db->insert('tbl_barang_masuk', $data_barang_masuk);
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
}
