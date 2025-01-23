<?php

// Guys model ini cuman buat experiment aja ya, kalo kalian mau experiment dengan library or apapun itu, bisa disini ya thank u :D
class Custom_model extends CI_Model
{

    public function ambilSemuaDataTransaksi()
    {

        $search_column  = ['kode_transaksi', 'terbayar', 'tb_daftar.hp'];
        // $order_column   = [null, 'tb_daftar.nocust', 'tb_daftar.nama', 'tb_daftar.hp', null, null, null, null, null];
        // $order          = ['tb_daftar.nocust' => 'desc'];

        $this->db->select("kode_transaksi, terbayar, kembalian, tagihan_cart, total_diskon, tagihan_after_diskon, total_biaya_kirim, total_tagihan, tipe_transaksi");
        $this->db->from("tbl_transaksi");

        $search = $this->input->post("search");

        if ($search && isset($this->input->post("search")['value'])) {

            for ($start = 0; $start < count($search_column); $start++) {

                if ($start == 0) {

                    $this->db->group_start();
                    $this->db->like($search_column[$start], $this->input->post("search")['value']);
                } else {

                    $this->db->or_like($search_column[$start], $this->input->post("search")['value']);
                }

                if ($start == (count($search_column) - 1)) {

                    $this->db->group_end();
                }
            }
        }

        // if ($this->input->post("order")) {

        //     if ($this->input->post('order')[0]['column'] > 0) {
        //         $this->db->order_by($order_column[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        //     }
        // } else {

        //     $this->db->order_by(key($order), $order[key($order)]);
        // }
    }

    public function queryAjaxAmbilSemuaDataTransaksi()
    {

        $this->ambilSemuaDataTransaksi();
        return $this->db->get()->result_array();
    }

    public function queryHitungFilterAmbilSemuaDataTransaksi()
    {

        $this->ambilSemuaDataTransaksi();
        return $this->db->get()->num_rows();
    }

    public function queryHitungSemuaAmbilSemuaDataTransaksi()
    {
        $this->db->from("tbl_transaksi");
        return $this->db->count_all_results();
    }
}
