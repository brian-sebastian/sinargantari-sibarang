<?php

class Barang_cacat_model extends CI_Model
{
    public function queryAmbilSemuaBarangCacat()
    {
        $cari = ["tbl_barang_cacat.kd_barang_cacat", "tbl_barang.nama_barang", "tbl_toko.nama_toko"];
        $order = [null, "tbl_barang_cacat.kd_barang_cacat", "tbl_barang.nama_barang", "tbl_toko.nama_toko", "tbl_barang_cacat.stok_cacat", "tbl_barang_cacat.status"];
        // $order_column   = [null, 'tb_daftar.nocust', 'tb_daftar.nama', 'tb_daftar.hp', null, null, null, null, null];
        // $order          = ['tb_daftar.nocust' => 'desc'];

        $this->db->select("*");
        $this->db->from("tbl_barang_cacat");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_barang_cacat.barang_id");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_barang_cacat.toko_id");

        $tokoId = $this->session->userdata('toko_id');

        if ($tokoId) {

            $this->db->where("tbl_barang_cacat.toko_id", $tokoId);
        } else {
            $toko_id = $this->input->post('toko_id');
            $this->db->where('tbl_barang_cacat.toko_id', $toko_id);
        }

        $search = $this->input->post("search");

        if ($search && isset($this->input->post("search")['value'])) {

            for ($start = 0; $start < count($cari); $start++) {

                if ($start == 0) {

                    $this->db->group_start();
                    $this->db->like($cari[$start], $this->input->post("search")['value']);
                } else {

                    $this->db->or_like($cari[$start], $this->input->post("search")['value']);
                }

                if ($start == (count($cari) - 1)) {

                    $this->db->group_end();
                }
            }
        }

        if ($this->input->post("order")) {

            if ($this->input->post('order')[0]['column'] > 0) {
                $this->db->order_by($order[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
            }
        } else {

            $this->db->order_by("tbl_barang_cacat.kd_barang_cacat", "ASC");
            $this->db->order_by("tbl_barang_cacat.status", "ASC");
        }
    }

    public function ajaxAmbilSemuaBarangCacat()
    {
        $this->queryAmbilSemuaBarangCacat();
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        return $this->db->get()->result_array();
    }

    public function ajaxAmbilHitungSemuaBarangCacat()
    {
        $this->queryAmbilSemuaBarangCacat();
        return $this->db->count_all_results();
    }

    public function ajaxAmbilFilterSemuaBarangCacat()
    {
        $this->queryAmbilSemuaBarangCacat();
        return $this->db->get()->num_rows();
    }

    public function ambilBarangCacatBerdasarkanBarangdanToko($toko_id, $barang_id)
    {
        $this->db->select("*");
        $this->db->from("tbl_barang_cacat");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_barang_cacat.barang_id");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_barang_cacat.toko_id");
        $this->db->where("tbl_barang_cacat.toko_id", $toko_id);
        $this->db->where("tbl_barang_cacat.barang_id", $barang_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getKodeBarangCacat()
    {
        return $this->db->query("SELECT MAX(RIGHT(tbl_barang_cacat.kd_barang_cacat,6)) AS kd_barang_cacat FROM tbl_barang_cacat");
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_barang_cacat", $data);
        return $this->db->affected_rows();
    }

    public function ubahData($data)
    {
        $this->db->where("id_barang_cacat", $data["id_barang_cacat"]);
        unset($data["id_barang_cacat"]);
        $this->db->update("tbl_barang_cacat", $data);
        return $this->db->affected_rows();
    }
}
