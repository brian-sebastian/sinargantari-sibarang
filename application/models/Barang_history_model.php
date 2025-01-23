<?php

class Barang_history_model extends CI_Model
{

    public function getBarangHistoryId($id)
    {
        $this->db->select('tbl_barang_harga_history.id_barang_history,tbl_barang_harga_history.barang_id,tbl_barang_harga_history.kategori_id, tbl_barang_harga_history.satuan_id,tbl_barang_harga_history.harga_pokok, tbl_barang_harga_history.berat_barang, tbl_barang_harga_history.tanggal_perubahan, tbl_barang_harga_history.user_input, tbl_barang_harga_history.created_at, tbl_barang.id_barang, tbl_barang.kode_barang,tbl_barang.nama_barang,tbl_barang.harga_pokok as harga_pokok_sekarang, tbl_barang.berat_barang as berat_barang_sekarang, tbl_kategori.id_kategori, tbl_kategori.nama_kategori, tbl_satuan.id_satuan, tbl_satuan.satuan');
        $this->db->from('tbl_barang_harga_history');
        $this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_barang_harga_history.barang_id', 'left');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang_harga_history.satuan_id', 'left');
        $this->db->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang_harga_history.kategori_id', 'left');
        $this->db->where('tbl_barang_harga_history.barang_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBarangHistoryIdNumRows($id)
    {
        $this->db->select('tbl_barang_harga_history.id_barang_history,tbl_barang_harga_history.barang_id,tbl_barang_harga_history.kategori_id, tbl_barang_harga_history.satuan_id,tbl_barang_harga_history.harga_pokok, tbl_barang_harga_history.berat_barang, tbl_barang_harga_history.tanggal_perubahan, tbl_barang_harga_history.user_input, tbl_barang_harga_history.created_at, tbl_barang.id_barang, tbl_barang.kode_barang,tbl_barang.nama_barang,tbl_barang.harga_pokok as harga_pokok_sekarang, tbl_barang.berat_barang as berat_barang_sekarang, tbl_kategori.id_kategori, tbl_kategori.nama_kategori, tbl_satuan.id_satuan, tbl_satuan.satuan');
        $this->db->from('tbl_barang_harga_history');
        $this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_barang_harga_history.barang_id', 'left');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang_harga_history.satuan_id', 'left');
        $this->db->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang_harga_history.kategori_id', 'left');
        $this->db->where('tbl_barang_harga_history.barang_id', $id);
        $query = $this->db->get();
        return $query->num_rows();
    }


    public function getBarangHistoryHargaTokoId($id_harga, $toko_id, $barang_id)
    {

        $this->db->select(
            'tbl_harga_toko_history.id_harga_toko_history, tbl_harga_toko_history.harga_id, tbl_harga_toko_history.harga_jual, tbl_harga_toko_history.user_input,tbl_harga_toko_history.created_at as created_at_history_barang_toko ,tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko as stok_toko_harga, tbl_harga.harga_jual as harga_jual_before_edit, tbl_harga.is_active, tbl_kategori.nama_kategori, tbl_satuan.satuan,
            '
        );
        $this->db->from('tbl_harga_toko_history');
        $this->db->join('tbl_harga', 'tbl_harga.id_harga = tbl_harga_toko_history.harga_id');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_barang.is_active', 1);

        $this->db->where('tbl_harga_toko_history.harga_id', $id_harga);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBarangHistoryHargaTokoIdNumRows($id_harga, $toko_id, $barang_id)
    {
        $this->db->select(
            'tbl_harga_toko_history.id_harga_toko_history, tbl_harga_toko_history.harga_id, tbl_harga_toko_history.harga_jual, tbl_harga_toko_history.user_input,tbl_harga_toko_history.created_at as created_at_history_barang_toko ,tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko as stok_toko_harga, tbl_harga.harga_jual as harga_jual_before_edit, tbl_harga.is_active, tbl_kategori.nama_kategori, tbl_satuan.satuan,
            '
        );
        $this->db->from('tbl_harga_toko_history');
        $this->db->join('tbl_harga', 'tbl_harga.id_harga = tbl_harga_toko_history.harga_id');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_barang.is_active', 1);
        // $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_harga_toko_history.harga_id', $id_harga);
        $query = $this->db->get();
        return $query->num_rows();
    }
}
