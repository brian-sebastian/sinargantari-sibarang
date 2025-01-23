<?php

/**
 * @property Toko_model $toko
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 */
class Toko extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->library('form_validation');
        $this->load->model('Toko_model', 'toko');
    }

    public function index()
    {
        $data['title'] = "Management Toko";
        $data['title_menu'] = "Toko";
        $data['toko'] = $this->toko->ambilSemuaToko();
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('toko/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function tambah()
    {
        $this->form_validation->set_rules(
            'nama_toko',
            'Nama Toko',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'alamat_toko',
            'Alamat Toko',
            'required|max_length[200]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 200 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'notelp_toko',
            'Nomor Telepon Toko',
            'required|max_length[15]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'jenis',
            'Jenis Toko',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('toko/store_management');
        } else {
            $this->toko->tambahToko();
            $this->session->set_flashdata('message', 'Data toko Berhasil ditambahkan');
            redirect('toko/store_management');
        }
    }

    public function ubah()
    {
        $this->form_validation->set_rules(
            'nama_toko',
            'Nama Toko',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'alamat_toko',
            'Alamat Toko',
            'required|max_length[200]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 200 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'notelp_toko',
            'Nomor Telepon Toko',
            'required|max_length[15]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );
        $this->form_validation->set_rules(
            'jenis',
            'Jenis Toko',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('toko/store_management');
        } else {
            $this->toko->ubahToko();
            $this->session->set_flashdata('message', 'Data toko Berhasil diubah');
            redirect('toko/store_management');
        }
    }

    public function hapus()
    {
        $this->form_validation->set_rules(
            'id_toko',
            'ID Toko',
            'required|max_length[100]',
            [
                'required' => '%s wajib diisi',
                'max_length' => '%s max 100 karakter'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('toko/store_management');
        } else {
            $this->toko->hapusToko();
            $this->session->set_flashdata('message', 'Data toko Berhasil dihapus');
            redirect('toko/store_management');
        }
    }
}
