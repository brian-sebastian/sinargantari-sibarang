<?php

class Barang_musnah_model extends CI_Model
{

    private $col;

    public function __construct()
    {
        parent::__construct();
        $this->col = [];
    }

    private function baseQuery()
    {
        $this->col = ["tbl_barang.kode_barang", "tbl_barang.nama_barang", "tbl_barang.barcode_barang", "tbl_toko.nama_toko"];

        $this->db->select("tbl_barang_musnah.id_musnah, tbl_barang_musnah.barang_id, tbl_barang_musnah.toko_id, 
        tbl_barang_musnah.qty_sampah, tbl_barang_musnah.tgl_musnah, tbl_barang_musnah.bukti_musnah, tbl_barang_musnah.tgl_musnah, tbl_barang_musnah.is_rollback, 
        tbl_barang_musnah.is_proses, tbl_barang.nama_barang, tbl_barang.kode_barang, tbl_barang.barcode_barang, tbl_toko.nama_toko");
        $this->db->from("tbl_barang_musnah");
        $this->db->join("tbl_barang", "tbl_barang_musnah.barang_id = tbl_barang.id_barang", "inner");
        $this->db->join("tbl_toko", "tbl_barang_musnah.toko_id = tbl_toko.id_toko", "inner");
        $this->db->where_not_in("tbl_barang_musnah.is_rollback", array(1));

        $search = $this->input->post("search");

        if ($search && isset($this->input->post("search")['value'])) {

            for ($start = 0; $start < count($this->col); $start++) {

                if ($start == 0) {

                    $this->db->group_start();
                    $this->db->like($this->col[$start], $this->input->post("search")['value']);
                } else {

                    $this->db->or_like($this->col[$start], $this->input->post("search")['value']);
                }

                if ($start == (count($this->col) - 1)) {

                    $this->db->group_end();
                }
            }
        }

        $this->db->order_by("tbl_barang_musnah.is_rollback", "DESC");
        $this->db->order_by("tbl_barang_musnah.tgl_musnah", "ASC");
    }

    public function ambilSemuaBarangMusnah()
    {

        $this->baseQuery();

        ($this->input->post("length") != -1) ? $this->db->limit($this->input->post("length"), $this->input->post("start")) : "";

        return $this->db->get()->result_array();
    }

    public function ambilHitungBarangMusnah()
    {
        $this->baseQuery();
        return $this->db->count_all_results();
    }

    public function ambilFilterBarangMusnah()
    {
        $this->baseQuery();
        return $this->db->get()->num_rows();
    }

    public function hapus_data($status, $id)
    {
        $this->db->where("is_proses", $status);
        $this->db->where("id_musnah", $id);
        $query = $this->db->get("tbl_barang_musnah");

        if ($query->num_rows() > 0) {

            switch ($status) {
                case "barang_toko":
                case "barang_cacat":

                    $this->db->trans_start();

                    $data_barang = $query->row_array();

                    // kembalikan qty sesuai dengan status
                    if ($status == "barang_toko") {

                        $this->db->set("stok_toko", 'stok_toko + ' . $data_barang["qty_sampah"], FALSE);
                        $this->db->where("toko_id", $data_barang["toko_id"]);
                        $this->db->where("barang_id", $data_barang["barang_id"]);
                        $this->db->update("tbl_harga");
                    }

                    if ($status == "barang_cacat") {

                        $this->db->set("stok_cacat", 'stok_cacat + ' . $data_barang["qty_sampah"], FALSE);
                        $this->db->where("toko_id", $data_barang["toko_id"]);
                        $this->db->where("barang_id", $data_barang["barang_id"]);
                        $this->db->update("tbl_barang_cacat");
                    }

                    // ubah is_rollback menjadi 1 pada barang musnah
                    $this->db->set("is_rollback", 1);
                    $this->db->where("id_musnah", $id);
                    $this->db->update("tbl_barang_musnah");

                    if ($this->db->trans_status() == FALSE) {

                        $this->db->trans_rollback();
                        return false;
                    } else {

                        $this->db->trans_commit();
                        return true;
                    }

                    break;
                default:
                    return 0;
                    break;
            }
        }

        return 0;
    }
}
