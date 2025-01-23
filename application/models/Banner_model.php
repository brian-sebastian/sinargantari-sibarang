<?php

class Banner_model extends CI_Model
{

    public function ambilSemuaBanner()
    {
        return $this->db->get("tbl_banner")->result_array();
    }

    public function tambahBanner($data)
    {

        $this->db->insert("tbl_banner", $data);
        return $this->db->affected_rows();
    }

    public function ambilBannerBerdasarkanId($id_banner)
    {

        $this->db->select("id_banner, judul, gambar");
        $this->db->where("id_banner", $id_banner);
        $query = $this->db->get("tbl_banner");

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function ubahBanner($data)
    {
        $this->db->where("id_banner", $data["id_banner"]);
        unset($data["id_banner"]);
        $this->db->update("tbl_banner", $data);
        return $this->db->affected_rows();
    }

    public function hapusBanner($id_banner)
    {

        $this->db->where("id_banner", $id_banner);
        $this->db->delete("tbl_banner");
        return  $this->db->affected_rows();
    }
}
