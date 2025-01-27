<?php

class Laporan_stok_model extends CI_Model
{
    public function getAllStok($tanggal = "", $harga_id = "", $toko_id = "")
    {
        $tgl = ($this->input->post('tanggal')) ? $this->input->post('tanggal') : $tanggal;
        $harga_id = ($this->input->post("harga_id")) ? $this->input->post("harga_id") : $harga_id;
        $toko_id = ($this->input->post("toko_id")) ? $this->input->post("toko_id") : $toko_id;

        $whereStatement = "";

        if ($tgl) {

            if (strlen($tgl) > 10) {

                $tgl        = explode(" to ", $tgl);
                $tglAwal    = $tgl[0];
                $tglAkhir   = $tgl[1];

                $whereStatement = "AND DATE_FORMAT(created_at, '%Y-%m-%d') >= '$tglAwal' AND DATE_FORMAT(created_at, '%Y-%m-%d') <= '$tglAkhir'";
            } else {

                $whereStatement = "AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$tgl'";
            }
        }

        $this->db->select("tbl_toko.nama_toko, tbl_harga.harga_jual, tbl_barang.kode_barang, tbl_barang.nama_barang,  tbl_harga.stok_toko, (SELECT tbl_harga.stok_toko FROM tbl_harga as th WHERE th.id_harga = tbl_harga.id_harga AND th.toko_id = '$toko_id') as jml_stok, (SELECT COALESCE(SUM(tbl_barang_masuk.jml_masuk), 0) FROM tbl_barang_masuk WHERE tbl_barang_masuk.harga_id = tbl_harga.id_harga $whereStatement) as jml_masuk, (SELECT COALESCE(SUM(tbl_barang_keluar.jml_keluar), 0) FROM tbl_barang_keluar WHERE tbl_barang_keluar.harga_id = tbl_harga.id_harga $whereStatement) as jml_keluar, (select SUM(stok_toko) from tbl_harga as tbl_harga_subquery inner join tbl_toko as tbl_toko_subquery on tbl_harga_subquery.toko_id = tbl_toko_subquery.id_toko where tbl_harga_subquery.barang_id = tbl_harga.barang_id and tbl_harga_subquery.is_active = 0 and tbl_toko_subquery.jenis = 'GUDANG') as stok_gudang");

        $this->db->from("tbl_harga");
        $this->db->join("tbl_toko", "tbl_harga.toko_id = tbl_toko.id_toko", "inner");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        if ($toko_id) {

            $this->db->where("tbl_harga.toko_id", $toko_id);
        }

        if ($harga_id) {

            $this->db->where("tbl_harga.id_harga", $harga_id);
        }
    }

    public function ambilSemuaStok()
    {

        $this->getAllStok();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ambilHitungStok()
    {

        $this->getAllStok();
        return $this->db->count_all_results();
    }

    public function ambilFilterStok()
    {
        $this->getAllStok();
        return $this->db->get()->num_rows();
    }

    public function ambilSemuaStokExcel($tanggal = "", $harga_id = "", $toko_id = "")
    {

        $this->getAllStok($tanggal, $harga_id, $toko_id);
        return $this->db->get()->result_array();
    }
}
