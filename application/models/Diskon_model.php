<?php

class Diskon_model extends CI_Model
{

    public function ambilSemuaDiskon()
    {
        $this->db->select('*')
            ->from('tbl_diskon')
            ->join('tbl_harga', 'tbl_harga.id_harga = tbl_diskon.harga_id', 'left')
            ->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id', 'left')
            ->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', 'left')
            ->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id', 'left')
            ->where('tbl_diskon.is_active', 1);
        return $this->db->get()->result_array();
    }

    public function ambilSemuaDiskonToko($id_toko)
    {
        $this->db->select('*')
            ->from('tbl_diskon')
            ->join('tbl_harga', 'tbl_harga.id_harga = tbl_diskon.harga_id', 'left')
            ->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id', 'left')
            ->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', 'left')
            ->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id', 'left')
            ->where('tbl_toko.id_toko', $id_toko)
            ->where('tbl_diskon.is_active', 1);
        return $this->db->get()->result_array();
    }

    public function ambilDataDiskon($where)
    {
        $id_diskon = $where['id_diskon'];
        $this->db->select('*')
            ->from('tbl_diskon')
            ->join('tbl_harga', 'tbl_harga.id_harga = tbl_diskon.harga_id', 'left')
            ->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id', 'left')
            ->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', 'left')
            ->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id', 'left')
            ->where('tbl_diskon.id_diskon', $id_diskon);
        return $this->db->get()->row_array();
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_diskon", $data);
        return $this->db->affected_rows();
    }

    public function ubahData($data)
    {
        $this->db->where("id_diskon", $data["id_diskon"]);
        unset($data["id_diskon"]);
        $this->db->update("tbl_diskon", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id_diskon)
    {
        $this->db->where("id_diskon", $id_diskon);
        $this->db->delete("tbl_diskon");
        return $this->db->affected_rows();
    }

    public function cekDiskonBarang($harga_id, $qty)
    {
        $tgl_sekarang = date("Y-m-d");

        // ambil diskon
        $this->db->select("id_diskon, nama_diskon, harga_potongan")
            ->from("tbl_diskon")
            ->where("'$tgl_sekarang' >= DATE_FORMAT(tgl_mulai, '%Y-%m-%d')")
            ->where("'$tgl_sekarang' <= DATE_FORMAT(tgl_akhir, '%Y-%m-%d')")
            ->where("harga_id", $harga_id)
            ->where("minimal_beli <=", intval($qty))
            ->where("is_active", 1);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function cekDiskonBarangBerdasarkanId($harga_id)
    {
        $tgl_sekarang = date("Y-m-d");

        // ambil diskon
        $this->db->select("id_diskon, nama_diskon, harga_potongan, minimal_beli, DATE_FORMAT(tgl_akhir, '%Y-%m-%d') as tgl_akhir")
            ->from("tbl_diskon")
            ->where("'$tgl_sekarang' >= DATE_FORMAT(tgl_mulai, '%Y-%m-%d')")
            ->where("'$tgl_sekarang' <= DATE_FORMAT(tgl_akhir, '%Y-%m-%d')")
            ->where("harga_id", $harga_id)
            ->where("is_active", 1);

        $query = $this->db->get();
        return $query->result_array();
    }
}
