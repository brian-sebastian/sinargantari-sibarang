<?php

class Kategori_model extends CI_Model
{

    public function ambilSemuaKategori()
    {
        return $this->db->get_where("tbl_kategori", ["is_active" => 1])->result_array();
    }

    public function ambilDetailKategori($id_kategori)
    {

        $query = $this->db->get_where("tbl_kategori", ["id_kategori" => $id_kategori, "is_active"   => 1]);

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function tambahData($data)
    {

        $this->db->insert("tbl_kategori", $data);
        return $this->db->affected_rows();
    }

    public function cekKodeKategori($kode_kategori, $id_kategori)
    {

        $query = $this->db->get_where("tbl_kategori", ["id_kategori" => $id_kategori, "kode_kategori" => $kode_kategori]);

        if ($query->num_rows() > 0) {

            return true;
        } else {

            $query2 = $this->db->get_where("tbl_kategori", ["kode_kategori" => $kode_kategori]);

            if ($query2->num_rows() > 0) {

                return false;
            }

            return true;
        }
    }

    public function cekNamaKategori($nama_kategori, $id_kategori)
    {

        $query = $this->db->get_where("tbl_kategori", ["id_kategori" => $id_kategori, "nama_kategori" => $nama_kategori]);

        if ($query->num_rows() > 0) {

            return true;
        } else {

            $query2 = $this->db->get_where("tbl_kategori", ["nama_kategori" => $nama_kategori]);

            if ($query2->num_rows() > 0) {

                return false;
            }

            return true;
        }
    }

    public function ubahData($data)
    {
        $this->db->where("id_kategori", $data["id_kategori"]);
        unset($data["id_kategori"]);
        $this->db->update("tbl_kategori", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id_kategori)
    {

        $this->db->set("is_active", 0);
        $this->db->set("user_update", ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem");
        $this->db->set("updated_at", date("Y-m-d H:i:s"));
        $this->db->where("is_active", 1);
        $this->db->where("id_kategori", $id_kategori);
        $this->db->update("tbl_kategori");
        return $this->db->affected_rows();
    }
}
