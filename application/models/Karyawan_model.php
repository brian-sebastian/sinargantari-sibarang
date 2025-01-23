<?php

class Karyawan_model extends CI_Model
{
    public function ambilSemuaKaryawanJoinUserToko()
    {
        $this->db->select("tkaryawantoko.id_karyawan, tkaryawantoko.nama_karyawan, tkaryawantoko.hp_karyawan, tkaryawantoko.alamat_karyawan, tkaryawantoko.bagian,tuser.id_user, tuser.username, tuser.role_id, tuser.is_active ,ttoko.nama_toko, trole.id_role, trole.role")
            ->from("tbl_karyawan_toko tkaryawantoko")
            ->join("tbl_user tuser", "tkaryawantoko.user_id = tuser.id_user", "inner")
            ->join("tbl_toko ttoko", "tkaryawantoko.toko_id = ttoko.id_toko", "inner")
            ->join("tbl_role trole", "tuser.role_id = trole.id_role", "inner")
            ->where("tuser.is_active", 1)
            ->where("ttoko.is_active", 1);

        ($this->session->userdata("toko_id")) ? $this->db->where("toko_id", $this->session->userdata("toko_id")) : "";

        return $this->db->get()->result_array();
    }

    public function ambilDetailKaryawan($id_karyawan)
    {
        $this->db->select("tbl_user.username, tbl_user.role_id, tbl_karyawan_toko.id_karyawan, tbl_karyawan_toko.user_id, tbl_karyawan_toko.toko_id, tbl_karyawan_toko.nama_karyawan, tbl_karyawan_toko.hp_karyawan, tbl_karyawan_toko.alamat_karyawan, tbl_karyawan_toko.bagian");
        $this->db->from("tbl_user");
        $this->db->join("tbl_karyawan_toko", "tbl_user.id_user = tbl_karyawan_toko.user_id", "inner");
        $this->db->where("tbl_user.is_active", 1);
        $this->db->where("tbl_karyawan_toko.id_karyawan", $id_karyawan);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function ambilDetailKaryawandeganIduser($id_user)
    {
        $query = $this->db->get_where("tbl_karyawan_toko", ["user_id" => $id_user]);

        return $query->row_array();
    }

    public function tambahData($data_user, $data_karyawan)
    {

        $this->db->trans_begin();

        // tambah user terlebih dahulu
        $this->db->insert("tbl_user", $data_user);
        $last_id = $this->db->insert_id();

        // lalu tambahkan karyawan
        $data_karyawan["user_id"] = $last_id;
        $this->db->insert("tbl_karyawan_toko", $data_karyawan);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return 0;
        } else {

            $this->db->trans_commit();
            return 1;
        }
    }

    public function ubahData($data_user, $data_karyawan)
    {
        $this->db->trans_start();

        $this->db->where("id_user", $data_user["user_id"]);
        unset($data_user["user_id"]);
        $this->db->update("tbl_user", $data_user);

        $this->db->where("id_karyawan", $data_karyawan["id_karyawan"]);
        unset($data_karyawan["id_karyawan"]);
        $this->db->update("tbl_karyawan_toko", $data_karyawan);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return 0;
        } else {

            $this->db->trans_commit();
            return 1;
        }
    }

    public function hapusData($id_karyawan)
    {
        $this->db->where("id_karyawan", $id_karyawan);
        $this->db->delete("tbl_karyawan_toko");
        return $this->db->affected_rows();
    }

    public function ambilTempKaryawan($toko_id = NULL)
    {
        $this->db->select("role_id, toko_id, username, nama_karyawan, hp_karyawan, alamat_karyawan, bagian");
        $this->db->where("is_sess_toko", $toko_id);
        $query = $this->db->get("tbl_karyawan_toko_temp");
        return $query;
    }
}
