<?php

class M_karyawan extends CI_Model
{
    public function index()
    {
        return $this->db->get_where("", [""])->result_array();
    }
}
