<?php

class M_toko extends CI_Model
{

    public function ambilSemuaToko()
    {
        return $this->db->get_where("tbl_toko", ["is_active" => 1])->result_array();
    }
}
