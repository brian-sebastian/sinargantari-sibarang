<?php

/**
 * @property Menu_model $menu
 * @property Submenu_model $submenu
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 */
class Submenu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Menu_model', 'menu');
        $this->load->model('Submenu_model', 'submenu');
        cek_login();
    }

    public function index()
    {
        $data['title'] = "Sub Menu";
        $data['title_menu'] = "Management Akses";
        $data['menu'] = $this->menu->getAllMenu();
        $data['submenu'] = $this->submenu->getAllSubMenu();
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('submenu/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function tambah()
    {
        $this->form_validation->set_rules(
            'menu_id',
            'Menu',
            'required|max_length[11]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 11 karakter'
            ]
        );

        $this->form_validation->set_rules(
            'title',
            'Title',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'uri',
            'URI',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'url',
            'URL',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('access/submenu');
        } else {
            $this->submenu->tambahSubMenu();
            $this->session->set_flashdata('message', 'Data Berhasil ditambahkan');
            redirect('access/submenu');
        }
    }

    public function ubah()
    {
        $this->form_validation->set_rules(
            'menu_id',
            'Menu',
            'required|max_length[11]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 11 karakter'
            ]
        );

        $this->form_validation->set_rules(
            'title',
            'Title',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'uri',
            'URI',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'url',
            'URL',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('access/submenu');
        } else {
            $this->submenu->ubahSubMenu();
            $this->session->set_flashdata('message', 'Data Berhasil diubah');
            redirect('access/submenu');
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
            redirect('access/submenu');
        } else {
            $this->submenu->hapusSubMenu();
            $this->session->set_flashdata('message', 'Data Berhasil dihapus');
            redirect('access/submenu');
        }
    }
}
