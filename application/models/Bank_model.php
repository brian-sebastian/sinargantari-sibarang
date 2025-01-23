<?php

class Bank_model extends CI_Model
{

    public function ambilSemuaBank()
    {
        $this->db->select("id_bank, bank");
        $query = $this->db->get("tbl_bank");
        return $query->result_array();
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_bank", $data);
        return $this->db->affected_rows();
    }

    public function ambilBankBerdasarkanId($id)
    {

        $query = $this->db->get_where("tbl_bank", ["id_bank"  => $id]);

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function ubahData($data)
    {

        $this->db->where("id_bank", $data["id"]);
        unset($data["id"]);
        $this->db->update("tbl_bank", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id)
    {

        $this->db->where("id_bank", $id);
        $query = $this->db->get("tbl_bank");

        if ($query->num_rows() > 0) {

            $this->db->where("id_bank", $id);
            $this->db->delete("tbl_bank");
            return $this->db->affected_rows();
        }

        return 0;
    }
}
