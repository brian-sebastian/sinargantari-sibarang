<?php

class Satuan_model extends CI_Model
{

    public function ambilSemuaSatuan()
    {
        return $this->db->get_where("tbl_satuan", ["is_active"  => 1])->result_array();
    }

    public function ambilDetailSatuan($id_satuan)
    {
        $query = $this->db->get_where("tbl_satuan", ["id_satuan"    => $id_satuan, "is_active"  => 1]);

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_satuan", $data);
        return $this->db->affected_rows();
    }

    public function ubahData($data)
    {
        $this->db->where("id_satuan", $data["id_satuan"]);
        $this->db->where("is_active", 1);
        unset($data["id_satuan"]);
        $this->db->update("tbl_satuan", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id_satuan)
    {

        $this->db->set("is_active", 0);
        $this->db->set("user_update", ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem");
        $this->db->set("updated_at", date("Y-m-d H:i:s"));
        $this->db->where("is_active", 1);
        $this->db->where("id_satuan", $id_satuan);
        $this->db->update("tbl_satuan");
        return $this->db->affected_rows();
    }

    public function cekSatuan($satuan, $id_satuan)
    {
        $query = $this->db->get_where("tbl_satuan", ["id_satuan" => $id_satuan, "satuan" => $satuan]);

        if ($query->num_rows() > 0) {

            return true;
        } else {

            $query2 = $this->db->get_where("tbl_satuan", ["satuan" => $satuan]);

            if ($query2->num_rows() > 0) {

                return false;
            }

            return true;
        }
    }
}
