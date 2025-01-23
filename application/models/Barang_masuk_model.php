<?php

class Barang_masuk_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Harga_model");
    }


    public function getBarangMasuk()
    {
        $this->db->select('tbl_barang_masuk.id_barang_masuk, tbl_barang_masuk.jml_masuk, tbl_barang_masuk.tanggal_barang_masuk, tbl_barang_masuk.bukti_beli, tbl_barang_masuk.tipe, tbl_barang_masuk.user_input, tbl_barang_masuk.created_at, tbl_barang_masuk.nama_sales, tbl_barang_masuk.nomor_supplier, tbl_barang_masuk.nama_toko_beli, tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_supplier.nama_supplier, tbl_supplier.no_telpon_supplier');
        $this->db->from('tbl_barang_masuk');
        $this->db->join('tbl_supplier', 'tbl_barang_masuk.nama_sales=tbl_supplier.id_supplier', 'left');
        $this->db->join('tbl_harga', 'tbl_barang_masuk.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id=tbl_toko.id_toko', 'left');
        $this->db->where('tbl_barang.is_active', 1);
        // Di comment sementara 
        // $this->db->where('tbl_supplier.is_active', 1);
        $this->db->where('tbl_toko.is_active', 1);
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->order_by('tbl_barang_masuk.id_barang_masuk', 'desc');
    }

    public function getAllBarangMasuk()
    {
        $tokoId = $this->session->userdata('toko_id');

        $this->getBarangMasuk();

        if ($tokoId) {
            $this->db->where('tbl_harga.toko_id', $tokoId);
        } else {
            $toko_id = $this->input->post('toko_id');

            $this->db->where('tbl_harga.toko_id', $toko_id);
        }

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ambilHitungTotalBarangMasuk()
    {
        $tokoId = $this->session->userdata('toko_id');
        $this->getBarangMasuk();
        if ($tokoId) {
            $this->db->where('tbl_harga.toko_id', $tokoId);
        } else {

            $toko_id = $this->input->post('toko_id');

            $this->db->where('tbl_harga.toko_id', $toko_id);
        }

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->count_all_results();
    }


    public function ambilFilterTotalBarangMasuk()
    {
        $tokoId = $this->session->userdata('toko_id');
        $this->getBarangMasuk();
        if ($tokoId) {
            $this->db->where('tbl_harga.toko_id', $tokoId);
        } else {
            $toko_id = $this->input->post('toko_id');

            $this->db->where('tbl_harga.toko_id', $toko_id);
        }

        return $this->db->get()->num_rows();
    }


    public function getAllTokoByHarga()
    {
        $roleId = $this->session->userdata('role_id');

        $this->db->select('tbl_toko.id_toko, tbl_toko.nama_toko');
        $this->db->from('tbl_toko');
        $this->db->where('tbl_toko.is_active', 1);
        if ($roleId == 1 || $roleId == 2) {
            $this->db->where('tbl_toko.jenis', 'TOKO');
        } else {
            $tokoId = $this->session->userdata('toko_id');
            $this->db->where('tbl_toko.id_toko', $tokoId);
        }
        return $this->db->get()->result_array();
    }

    public function getAllBarangFindTokoByHarga($idToko)
    {
        $this->db->select('tbl_barang.nama_barang');
        $this->db->from('tbl_harga');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.toko_id', $idToko);

        return $this->db->get()->result_array();
    }


    public function getBarangMasukFindId($id)
    {
        $this->db->select('*, tbl_barang.nama_barang, tbl_toko.nama_toko');
        $this->db->from('tbl_barang_masuk');
        $this->db->join('tbl_harga', 'tbl_barang_masuk.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id=tbl_toko.id_toko', 'left');
        $this->db->where('tbl_barang_masuk.id_barang_masuk', $id);
        return $this->db->get()->row_array();
    }

    public function tambah_data($data)
    {

        $this->db->trans_start();
        $dariTokoId = $data['dari_toko_by_id_harga'];
        $stok_barang_by_harga = $this->db->get_where('tbl_harga', ['id_harga' => $data['harga_id']])->row_array();

        $isChangeCode = 0;
        $jml_stok = $stok_barang_by_harga['stok_toko'];

        if ($stok_barang_by_harga['stok_toko'] != $data['jml_masuk']) {
            $jml_stok = $data['jml_masuk'];
            $isChangeCode++;
        }

        if ($isChangeCode > 0) {

            $dataBarangMasuk = [
                'harga_id' => $data['harga_id'],
                'jml_masuk' => $jml_stok,
                'bukti_beli' => $data['bukti_beli'],
                'tipe' => $data['tipe'],
                'tanggal_barang_masuk' => $data['tanggal_barang_masuk'],
                'nama_sales' => ($data['tipe'] == 'gudangsupplier') ? $data['nama_sales'] : null,
                'nomor_supplier' => ($data['tipe'] == 'gudangsupplier') ? $data['nomor_supplier'] : null,
                'nama_toko_beli' => $data['nama_toko_beli'],
                'user_input' => $data['user_input'],
                'created_at' => $data['created_at']
            ];

            $this->db->insert("tbl_barang_masuk", $dataBarangMasuk);
        }

        // get barang_id dari harga_id toko pengirim
        $this->db->where("toko_id", $data["ke_toko"]);
        $this->db->where("barang_id", $stok_barang_by_harga["barang_id"]);
        $q = $this->db->get("tbl_harga");

        $this->db->where("id_barang", $stok_barang_by_harga["barang_id"]);
        $w = $this->db->get("tbl_barang")->row_array();

        if ($q->num_rows() > 0) {

            $ke_toko = $q->row_array();

            // update
            $total_stok = intval($data['jml_masuk']) + intval($ke_toko['stok_toko']);

            // Update Stok Tambah
            $this->db->set('stok_toko', $total_stok);
            $this->db->set('user_edit', $this->session->userdata('nama_user'));
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->where('id_harga', $ke_toko['id_harga']);
            $this->db->update("tbl_harga");
        } else {

            // insert
            $this->db->set('toko_id', $data['ke_toko']);
            $this->db->set('harga_jual', $w['harga_pokok']);
            $this->db->set('barang_id', $w["id_barang"]);
            $this->db->set('stok_toko', intval($data['jml_masuk']));
            $this->db->insert("tbl_harga");
        }

        // Update Stok Kurang
        if ($data['tipe'] == 'antar_toko') {
            $total_stok_kurang = $data['stok_toko_dari_by_id_harga'] - $data['jml_masuk'];
            $this->kurangstokdaritoko($dariTokoId, $total_stok_kurang);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {

            $this->db->trans_rollback();
            return false;
        } else {

            $this->db->trans_commit();
            return true;
        }
    }

    public function kurangstokdaritoko($dariTokoId, $total_stok_kurang)
    {
        $this->db->set('stok_toko', $total_stok_kurang);
        $this->db->set('user_edit', $this->session->userdata('nama_user'));
        $this->db->set('updated_at', date('Y-m-d H:i:s'));
        $this->db->where('tbl_harga.id_harga', $dariTokoId);
        $this->db->update("tbl_harga");
    }

    public function edit_data($data)
    {
        $this->db->where('id_barang_masuk', $data['id_barang_masuk']);
        unset($data['id_barang_masuk']);
        $this->db->update('tbl_barang_masuk', $data);
        return $this->db->affected_rows();
    }

    public function hapus_data($id)
    {
        $this->db->where('id_barang_masuk', $id);
        $this->db->delete('tbl_barang_masuk');
    }

    public function tambahDataGudangSupplier($data)
    {
        $this->db->trans_start();
        $this->db->set('harga_id', $data['harga_id']);
        $this->db->set('jml_masuk', $data['jml_masuk']);
        $this->db->set('bukti_beli', $data['bukti_beli']);
        $this->db->set('tipe', $data['tipe']);
        $this->db->set('tanggal_barang_masuk', $data['tanggal_barang_masuk']);
        $this->db->set('nama_sales', $data['nama_sales']);
        $this->db->set('user_input', $data['user_input']);
        $this->db->set('created_at', $data['created_at']);
        $this->db->insert('tbl_barang_masuk');

        $this->db->set('stok_toko', $data['stock_sum']);
        $this->db->where('id_harga', $data['harga_id']);
        $this->db->update('tbl_harga');

        $this->db->trans_complete();


        $this->db->trans_complete();
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}
