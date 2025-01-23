<?php

class Menu_model extends CI_Model
{
    public function getAllMenu()
    {
        return $this->db->get_where('tbl_menu', ['is_active' => 1])->result_array();
    }

    public function tambahMenu()
    {
        $data = [
            'menu' => htmlspecialchars($this->input->post('menu')),
            'uri' => htmlspecialchars($this->input->post('uri')),
            'icon' => htmlspecialchars($this->input->post('icon')),
            'is_active' => 1,
        ];
        $this->db->insert('tbl_menu', $data);
        return $this->db->affected_rows();
    }

    public function ubahMenu()
    {
        $uri = htmlspecialchars($this->input->post('uri'));
        $uri_clean = preg_replace('/\s*/', '', $uri);
        // convert the string to all lowercase
        $uri_clean = strtolower($uri_clean);
        $id_menu = htmlspecialchars($this->input->post('id'));
        $data = [
            'menu' => htmlspecialchars($this->input->post('menu')),
            'uri' => $uri_clean,
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
