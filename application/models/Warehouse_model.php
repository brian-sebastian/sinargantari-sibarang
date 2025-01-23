<?php

class Warehouse_model extends CI_Model
{
    public function getShopWarehouse($res = 'RESULT', $type = 'GUDANG', $idToko = null)
    {
        if ($type == 'TOKO') {
            $this->db->where('is_active', 1);
        }

        $this->db->where('jenis', $type);
        if ($idToko) {
            $this->db->where('id_toko', $idToko);
        }
        $q = $this->db->get('tbl_toko');
        if ($res == 'RESULT') {
            $res = $q->result_array();
            return $res;
        } else {
            $res = $q->row_array();
            return $res;
        }
    }
}
