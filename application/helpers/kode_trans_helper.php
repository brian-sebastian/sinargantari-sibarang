<?php

if (!function_exists('makeTransCode')) {

    function makeTransCode()
    {

        // Ex: INV-2023120000000001

        $CI = &get_instance();

        $date = date("Ym");

        // Do query here
        $CI->db->select("MAX(RIGHT(kode_transaksi, 10)) as kode_transaksi");
        $CI->db->where("SUBSTRING(kode_transaksi, 5, 6) = '$date'");
        $query = $CI->db->get("tbl_transaksi");
        $result = $query->row_array();

        if ($result["kode_transaksi"]) {

            $urutan = $result["kode_transaksi"];
            return "INV-" . $date . str_pad((intval($urutan) + 1), 10, 0, STR_PAD_LEFT);
        } else {

            return "INV-" . $date . "0000000001";
        }
    }
}

if (!function_exists('makeTransCodeCacat')) {

    function makeTransCodeCacat()
    {

        // Ex: INV-2023120000000001

        $CI = &get_instance();

        $date = date("Ym");

        // Do query here
        $CI->db->select("MAX(RIGHT(kode_transaksi, 10)) as kode_transaksi");
        $CI->db->where("SUBSTRING(kode_transaksi, 5, 6) = '$date'");
        $query = $CI->db->get("tbl_transaksi_cacat");
        $result = $query->row_array();

        if ($result["kode_transaksi"]) {

            $urutan = $result["kode_transaksi"];
            return "INV-" . $date . str_pad((intval($urutan) + 1), 10, 0, STR_PAD_LEFT);
        } else {

            return "INV-" . $date . "0000000001";
        }
    }
}
