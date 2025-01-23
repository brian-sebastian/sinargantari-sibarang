<?php


class Role_model extends CI_Model
{

    function tampil_data()
    {

        if ($this->session->userdata('is_developer') == false) {
            $this->db->where('role <> ', 'Developer');
        }
        $this->db->where('is_active', 1);
        return $this->db->get('tbl_role');
    }

    function tampil_data_notdev()
    {
        $this->db->where('role <> ', 'Developer');
        $this->db->where('is_active', 1);
        return $this->db->get('tbl_role');
    }

    function insert_data($data)
    {
        $this->db->insert('tbl_role', $data);
        return $this->db->affected_rows();
    }

    function edit_data($where)
    {
        return $this->db->get_where('tbl_role', $where);
    }

    function update_data($data)
    {
        $this->db->where("id_role", $data["id_role"]);
        unset($data["id_diskon"]);
        $this->db->update("tbl_role", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id_role)
    {
        $this->db->where("id_role", $id_role);
        $this->db->delete("tbl_role");
        return $this->db->affected_rows();
    }

    public function getAllRole()
    {
        if ($this->session->userdata('is_developer') == false) {
            $this->db->where('role <> ', 'Developer');
        }
        $this->db->where('is_active', 1);
        return $this->db->get('tbl_role')->result_array();
    }

    public function tambahRole()
    {
        $data = [
            'role' => htmlspecialchars($this->input->post('role')),
        ];
        $this->db->insert('tbl_role', $data);
        return $this->db->affected_rows();
    }

    public function ubahRole()
    {
        $id_role = htmlspecialchars($this->input->post('id'));
        $data = [
            'role' => htmlspecialchars($this->input->post('role')),
        ];
        $this->db->where('id_role', $id_role);
        $this->db->update('tbl_role', $data);
        return $this->db->affected_rows();
    }

    public function hapusRole()
    {
        $id_role = htmlspecialchars($this->input->post('id'));
        $this->db->set('is_active', 0);
        $this->db->where('id_role', $id_role);
        $this->db->update('tbl_role');
        return $this->db->affected_rows();
    }

    public function getPrivilegeSubMenuByIdSubMenuDanIdRole($data_submenu)
    {
        $id_role    = $data_submenu['role_id'];
        $id_submenu = $data_submenu['submenu_id'];

        $this->db->select('*');
        $this->db->from('tbl_privilege_submenu');
        $this->db->join('tbl_submenu', 'tbl_privilege_submenu.submenu_id = tbl_submenu.id_submenu');
        $this->db->join('tbl_privilege_menu', 'tbl_privilege_submenu.privilege_id = tbl_privilege_menu.id_privilege');
        $this->db->where('tbl_privilege_menu.role_id', $id_role);
        $this->db->where('tbl_privilege_submenu.submenu_id', $id_submenu);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getMenuByIdMenuDanIdRole($data_menu)
    {
        $id_role    = $data_menu['role_id'];
        $id_menu    = $data_menu['menu_id'];
        
        $this->db->select('*');
        $this->db->from('tbl_menu');
        $this->db->join('tbl_privilege_menu', 'tbl_menu.id_menu = tbl_privilege_menu.menu_id');
        $this->db->join('tbl_role', 'tbl_privilege_menu.role_id = tbl_role.id_role');
        $this->db->where('tbl_privilege_menu.role_id', $id_role);
        $this->db->where('tbl_menu.id_menu', $id_menu);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert_data_privilege_submenu($data_insert)
    {
        $this->db->insert('tbl_privilege_submenu', $data_insert);
        return $this->db->affected_rows();
    }

    public function delete_data_privilege_submenu($data_delete)
    {
        $id_privilege  = $data_delete['privilege_id'];
        $id_submenu    = $data_delete['submenu_id'];

        $this->db->where("privilege_id", $id_privilege);

        if ($id_submenu != 0) {
            $this->db->where("submenu_id", $id_submenu);
        }

        $this->db->delete("tbl_privilege_submenu");
        return $this->db->affected_rows();
    }

    public function insert_data_privilege_menu($data)
    {
        $this->db->insert('tbl_privilege_menu', $data);
        return $this->db->affected_rows();
    }

    public function delete_data_privilege_menu($data)
    {
        $id_role  = $data['role_id'];
        $id_menu  = $data['menu_id'];

        $this->db->where("role_id", $id_role);
        $this->db->where("menu_id", $id_menu);
        $this->db->delete("tbl_privilege_menu");
        return $this->db->affected_rows();
    }
}
