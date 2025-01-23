<?php

class Masuk_cacat_model extends CI_Model
{
    public function queryAmbilSemuaMasukCacat()
    {

        $cari = ["tbl_barang.kode_barang", "tbl_barang.nama_barang", "tbl_masuk_cacat.is_rollback", "tbl_masuk_cacat.tgl_masuk"];
        $order = [null, "tbl_barang.kode_barang", "tbl_barang.nama_barang", "tbl_masuk_cacat.jumlah_masuk", "tbl_masuk_cacat.tgl_masuk", "tbl_masuk_cacat.is_rollback"];
        // $order_column   = [null, 'tb_daftar.nocust', 'tb_daftar.nama', 'tb_daftar.hp', null, null, null, null, null];
        // $order          = ['tb_daftar.nocust' => 'desc'];

        $this->db->select("*");
        $this->db->from("tbl_masuk_cacat");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_masuk_cacat.barang_id");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_masuk_cacat.toko_id");
        $this->db->where("tbl_barang.is_active", 1);
        $this->db->where("tbl_toko.is_active", 1);

        $tokoId = $this->session->userdata('toko_id');

        if ($tokoId) {

            $this->db->where("tbl_masuk_cacat.toko_id", $tokoId);
        } else {
            $toko_id = $this->input->post('toko_id');
            $this->db->where('tbl_masuk_cacat.toko_id', $toko_id);
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

            $this->db->order_by("tbl_masuk_cacat.tgl_masuk", "ASC");
            $this->db->order_by("tbl_masuk_cacat.is_rollback", "ASC");
        }
    }

    public function ajaxAmbilSemuaMasukCacat()
    {

        $this->queryAmbilSemuaMasukCacat();
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        return $this->db->get()->result_array();
    }

    public function ajaxAmbilHitungSemuaMasukCacat()
    {

        $this->queryAmbilSemuaMasukCacat();
        return $this->db->count_all_results();
    }

    public function ajaxAmbilFilterSemuaMasukCacat()
    {

        $this->queryAmbilSemuaMasukCacat();
        return $this->db->get()->num_rows();
    }

    public function get_where_BarangMasukCacat($where)
    {
        $this->db->select('*');
        $this->db->from('tbl_masuk_cacat');
        $this->db->join("tbl_barang", "tbl_masuk_cacat.barang_id = tbl_barang.id_barang");
        $this->db->join("tbl_toko", "tbl_masuk_cacat.toko_id = tbl_toko.id_toko");
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    public function prosesstokcacat_if_barangcacat($update_stok_toko, $insert_masuk_cacat, $update_stok_cacat)
    {
        $this->db->trans_start();

        $this->db->where("id_harga", $update_stok_toko["id_harga"]);
        unset($update_stok_toko["id_harga"]);
        $this->db->update("tbl_harga", $update_stok_toko);

        $this->db->insert("tbl_masuk_cacat", $insert_masuk_cacat);

        $this->db->where("id_harga", $update_stok_cacat["id_harga"]);
        unset($update_stok_cacat["id_harga"]);
        $this->db->update("tbl_barang_cacat", $update_stok_cacat);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function prosesstokcacat_if_notbarangcacat($update_stok_toko, $insert_masuk_cacat, $insert_master_cacat)
    {
        $this->db->trans_start();

        $this->db->where("id_harga", $update_stok_toko["id_harga"]);
        unset($update_stok_toko["id_harga"]);
        $this->db->update("tbl_harga", $update_stok_toko);

        $this->db->insert("tbl_masuk_cacat", $insert_masuk_cacat);

        $this->db->insert("tbl_barang_cacat", $insert_master_cacat);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function prosesdipakai_if_barangcacat($update_stok_toko, $insert_masuk_cacat, $insert_keluar_cacat)
    {
        $this->db->trans_start();

        $this->db->where("id_harga", $update_stok_toko["id_harga"]);
        unset($update_stok_toko["id_harga"]);
        $this->db->update("tbl_harga", $update_stok_toko);

        $this->db->insert("tbl_masuk_cacat", $insert_masuk_cacat);

        $this->db->insert("tbl_keluar_cacat", $insert_keluar_cacat);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function prosesdipakai_if_notbarangcacat($update_stok_toko, $insert_masuk_cacat, $insert_master_cacat, $insert_keluar_cacat)
    {
        $this->db->trans_start();

        $this->db->where("id_harga", $update_stok_toko["id_harga"]);
        unset($update_stok_toko["id_harga"]);
        $this->db->update("tbl_harga", $update_stok_toko);

        $this->db->insert("tbl_masuk_cacat", $insert_masuk_cacat);

        $this->db->insert("tbl_barang_cacat", $insert_master_cacat);
        $last_id = $this->db->insert_id();

        $this->db->set('barang_cacat_id', $last_id);
        $this->db->insert("tbl_keluar_cacat", $insert_keluar_cacat);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function prosesmusnah($update_stok_toko, $insert_masuk_cacat, $insert_barang_musnah)
    {
        $this->db->trans_start();

        $this->db->where("id_harga", $update_stok_toko["id_harga"]);
        unset($update_stok_toko["id_harga"]);
        $this->db->update("tbl_harga", $update_stok_toko);

        $this->db->insert("tbl_masuk_cacat", $insert_masuk_cacat);

        $this->db->insert("tbl_barang_musnah", $insert_keluar_cacat);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}
