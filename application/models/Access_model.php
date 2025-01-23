<?php
class Access_model extends CI_Model
{
    public function insertMenuAccess($data)
    {
        $result = $this->db->insert_batch('tbl_menu_access', $data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function insertSubMenuAccess($data)
    {
        $result = $this->db->insert_batch('tbl_submenu_access', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUserMenuAccess($role_id)
    {
        $this->db->where('role_id', $role_id);
        $this->db->delete('tbl_menu_access');
    }

    public function deleteUserSubMenuAccess($role_id)
    {
        $this->db->where('role_id', $role_id);
        $this->db->delete('tbl_submenu_access');
    }

    public function giveAccessUserMenu($role_id, $data)
    {
        $this->db->trans_start(); # Starting Transaction

        $this->db->where('role_id', $role_id);
        $this->db->delete('tbl_menu_access');


        $this->db->insert_batch('tbl_menu_access', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function giveAccessUserSubMenu($role_id, $data)
    {
        $this->db->trans_start(); # Starting Transaction

        $this->db->where('role_id', $role_id);
        $this->db->delete('tbl_submenu_access');

        $this->db->insert_batch('tbl_submenu_access', $data);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function getAccessMenuActive($role_id)
    {
        $query = $this->db->select('menu_id')->get_where('tbl_menu_access', array('role_id' => $role_id));

        return $query->result_array();
    }

    public function getAccessSubMenuActive($role_id)
    {
        $query = $this->db->select('submenu_id')->get_where('tbl_submenu_access', array('role_id' => $role_id));

        return $query->result_array();
    }
}
