<?php

class Order_model extends CI_Model
{

    public function ambilSemuaOrder()
    {
        return $this->db->get_where("tbl_order", ["is_active"  => 1])->result_array();
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_order", $data);
        return $this->db->affected_rows();
    }

    public function get_kodeOrder()
    {
        $query_Kdorder = $this->db->query("SELECT MAX(RIGHT(tbl_order.kode_order,5)) AS kode_order FROM tbl_order");
        $kode = "";
        if ($query_Kdorder->num_rows() > 0) {
            //cek kode jika telah tersedia    
            foreach ($query_Kdorder->result_array() as $k) {
                $tmp = ((int)$k['kode_order']) + 1;
                // $tmp = ((int)$k['NOCUST']) + 1;
                $kode = sprintf("%05s", $tmp);
            }
        } else {
            $kode = "000001";
        }

        $batas = str_pad($kode, 6, "0", STR_PAD_LEFT);


        $kode_order = "ODR-" . $batas;
        // $kodebaru = $prefix . $batas;

        // var_dump($kodebarucust);
        // die;
        return $kode_order;
    }

    public function getAffectedRows()
    {
        // Retrieve the count of rows with status = 1
        $this->db->where('status', 1);
        $affected_rows = $this->db->count_all_results('tbl_order');


        return $affected_rows;
    }
}
