<?php

class User_model extends CI_Model
{
    function session_data()
    {
        return $this->db->get_where('tbl_user', ['username' => $this->session->userdata('username')])->row_array();
    }

    public function ambilSemuaUser()
    {
        if ($this->session->userdata('is_developer') == false) {
            $this->db->where('type_user <> ', 'DEV');
        }
        $this->db->where('is_active', 1);
        return $this->db->get('tbl_user')->result_array();


        return $this->db->get_where('tbl_user')->result_array();
    }

    public function findByUsername($username)
    {
        return $this->db->get_where('tbl_user', ['username' => $username])->row_array();
    }

    public function findById($id)
    {
        $query =  $this->db->get_where('tbl_user', ['id_user' => $id]);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return 0;
    }

    public function ambilSemuaUserNotInKaryawantoko()
    {

        $this->db->select('tuser.*, trole.role');
        $this->db->from('tbl_user tuser');
        $this->db->join("tbl_role trole", "tuser.role_id = trole.id_role", "inner");
        $this->db->where("tuser.is_active", 1);
        $this->db->where("tuser.id_user NOT IN (SELECT user_id FROM tbl_karyawan_toko)");
        if ($this->session->userdata('is_developer') == false) {
            $this->db->where('tuser.type_user <> ', 'DEV');
        }
        $query = $this->db->get();
        // var_dump($this->db->last_query($query));
        // die;
        return $query->result_array();

        // return $this->db->select("tuser.*, trole.role")
        //     ->from("tbl_user tuser")
        //     ->join("tbl_role trole", "tuser.role_id = trole.id_role", "inner")
        //     ->where("tuser.is_active", 1)
        //     ->where("tuser.id_user NOT IN (SELECT user_id FROM tbl_karyawan_toko)")
        //     ->get()->result_array();
    }

    public function tambah_data($data)
    {
        $this->db->insert('tbl_user', $data);
        return  $this->db->affected_rows();
    }

    public function edit_data($data)
    {
        $this->db->where('id_user', $data['id_user']);
        unset($data['id_user']);
        $this->db->update('tbl_user', $data);
        return $this->db->affected_rows();
    }

    public function hapus_data($id)
    {
        $this->db->where('id_user', $id);
        $this->db->delete('tbl_user');
        return $this->db->affected_rows();
    }

    public function checkUsername($username, $id)
    {
        $query = $this->db->get_where("tbl_user", ["username" => $username, "id_user"  => $id]);

        if ($query->num_rows() === 1) {

            return true;
        } else {

            $query2 = $this->db->get_where("tbl_user", ["username" => $username]);

            if ($query2->num_rows() > 0) {

                return false;
            } else {

                return true;
            }
        }
    }

    public function update_photo($user_id, $file_name)
    {
        $isKaryawan = $this->isKaryawan($user_id);

        if ($isKaryawan) {

            $data = array('image_profile' => $file_name);
            $this->db->where('user_id', $user_id);
            return $this->db->update('tbl_karyawan_toko', $data);
        } else {

            $data = array('image_profile' => $file_name);
            $this->db->where('id_user', $user_id);
            return $this->db->update('tbl_user', $data);
        }
    }

    private function isKaryawan($user_id)
    {
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("tbl_karyawan_toko");
        return $query->num_rows();
    }
}
