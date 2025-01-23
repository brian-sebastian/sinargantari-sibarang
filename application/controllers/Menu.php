<?php

/**
 * @property Menu_model $menu
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property db $db
 */
class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Menu_model', 'menu');
        cek_login();
    }

    public function index()
    {
        $data['title'] = "Menu";
        $data['title_menu'] = "Management Akses";
        $data['menu'] = $this->menu->getAllMenu();
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function tambah()
    {

        $this->form_validation->set_rules(
            'menu',
            'Menu',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'icon',
            'Icon',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('access/menu');
        } else {
            $this->menu->tambahMenu();
            $this->session->set_flashdata('message', 'Data Berhasil ditambahkan');
            redirect('access/menu');
        }
    }

    public function ubah()
    {
        $idMenu = htmlspecialchars($this->input->post('id_menu'));
        $queryGetMenuId = $this->db->get_where('tbl_menu', ['id_menu' => $idMenu])->row_array();
        $this->form_validation->set_rules(
            'menu',
            'Menu',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'icon',
            'Icon',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('access/menu');
        } else {
            $this->menu->ubahMenu();
            $this->session->set_flashdata('message', 'Data Berhasil diubah');
            redirect('access/menu');
        }
    }

    public function hapus()
    {
        $this->form_validation->set_rules(
            'id',
            'ID Menu',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('access/menu');
        } else {
            $this->menu->hapusMenu();
            $this->session->set_flashdata('message', 'Data Berhasil dihapus');
            redirect('access/menu');
        }
    }
}
