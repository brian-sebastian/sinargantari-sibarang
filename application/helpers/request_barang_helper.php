<?php

class request_barang_helper
{
    public static function checkRequestCode()
    {
        $CI = &get_instance();
        $CI->db->select('kode_request');
        $CI->db->order_by('kode_request', 'DESC');
        $CI->db->get('tbl_request_barang');
        $query = $CI->db->get('tbl_request_barang');
        return $query->row_array();
    }
}
