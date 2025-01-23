<?php

class Menu_model extends CI_Model
{
    function get_data_menu()
    {
        $this->db->where('is_active', 1);
        return $this->db->get('tbl_menu');
    }

    function insert_data($data)
    {
        return $this->db->insert('tbl_menu', $data);
    }

    function edit_data($where)
    {
        return $this->db->get_where('tbl_menu', $where);
    }

    function update_data($data, $where)
    {
        $this->db->where($where);
        $this->db->update('tbl_menu', $data);
    }

    function hapus_data($where)
    {
        $this->db->where($where);
        $this->db->delete('tbl_menu');
    }

    public function getAllMenu()
    {
        return $this->db->get_where('tbl_menu', ['is_active' => 1])->result_array();
    }

    public function tambahMenu()
    {
        $data = [
            'menu' => htmlspecialchars($this->input->post('menu')),
            'icon' => htmlspecialchars($this->input->post('icon')),
            'is_active' => 1,
        ];
        $this->db->insert('tbl_menu', $data);
        return $this->db->affected_rows();
    }

    public function ubahMenu()
    {
        $id_menu = htmlspecialchars($this->input->post('id'));
        $data = [
            'menu' => htmlspecialchars($this->input->post('menu')),
            'icon' => htmlspecialchars($this->input->post('icon')),
            'is_active' => 1,
        ];
        $this->db->where('id_menu', $id_menu);
        $this->db->update('tbl_menu', $data);
        return $this->db->affected_rows();
    }

    public function hapusMenu()
    {
        $id_menu = htmlspecialchars($this->input->post('id'));
        $this->db->set('is_active', 0);
        $this->db->where('id_menu', $id_menu);
        $this->db->update('tbl_menu');
        return $this->db->affected_rows();
    }
}
