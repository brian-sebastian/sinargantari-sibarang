<?php

class Dashboard_model extends CI_Model
{

    public function ambilRekapitulasiPenjualan($data)
    {

        $bulan      = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $periode    = ($data["periode"]) ? $data["periode"] : date("Y");
        $toko       = ($data["toko_id"]) ? $data["toko_id"] : '';
        $union      = "";
        $subquery   = "";

        if ($toko) {

            $subquery = " AND order_id IN (SELECT id_order FROM tbl_order WHERE toko_id = '$toko')";
        }

        for ($i = 0; $i < count($bulan); $i++) {

            $bul    = $bulan[$i];

            $union .= "SELECT COALESCE(SUM(tagihan_after_diskon), 0) as total_penjualan FROM tbl_transaksi WHERE DATE_FORMAT(created_at, '%Y-%m') = '$periode-$bul'$subquery";

            if ($i != (count($bulan) - 1)) {

                $union .= " UNION ALL ";
            }
        }

        $result = $this->db->query($union)->result_array();
        return $result;
    }
}
