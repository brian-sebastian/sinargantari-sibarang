<?php

class Submenu_model extends CI_Model
{
    public function getAllSubMenu()
    {
        $this->db->select('*, tbl_menu.id_menu, tbl_menu.menu, tbl_menu.is_active');
        $this->db->from('tbl_submenu');
        $this->db->join('tbl_menu', 'tbl_submenu.menu_id = tbl_menu.id_menu');
        $this->db->where('tbl_menu.is_active', 1);
        $this->db->where('tbl_submenu.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSubMenuByMenuId($menu_id)
    {
        $this->db->where('is_active', 1);
        return $this->db->get_where('tbl_submenu', ['menu_id' => $menu_id])->result_array();
    }

    public function tambahSubMenu()
    {
        $data = [
            'menu_id' => htmlspecialchars($this->input->post('menu_id')),
            'title' => htmlspecialchars($this->input->post('title')),
            'uri' => htmlspecialchars($this->input->post('uri')),
            'url' => htmlspecialchars($this->input->post('url')),
            'is_active' => 1,
        ];
        $this->db->insert('tbl_submenu', $data);
        return $this->db->affected_rows();
    }

    public function ubahSubMenu()
    {
        $id_submenu = htmlspecialchars($this->input->post('id'));
        $data = [
            'menu_id' => htmlspecialchars($this->input->post('menu_id')),
            'title' => htmlspecialchars($this->input->post('title')),
            'uri' => htmlspecialchars($this->input->post('uri')),
            'url' => htmlspecialchars($this->input->post('url')),
            'is_active' => 1,
        ];
        $this->db->where('id_submenu', $id_submenu);
        $this->db->update('tbl_submenu', $data);
        return $this->db->affected_rows();
    }

    public function hapusSubMenu()
    {
        $id_submenu = htmlspecialchars($this->input->post('id'));
        $this->db->set('is_active', 0);
        $this->db->where('id_submenu', $id_submenu);
        $this->db->update('tbl_submenu');
        return $this->db->affected_rows();
    }
}
