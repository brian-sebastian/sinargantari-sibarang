<?php

/**
 * @property Request_hapus_barang_model $request_hapus_barang
 */

class Request_hapus_barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Request_hapus_barang_model', 'request_hapus_barang');
    }

    public function index()
    {

        $data['title'] = "Request Hapus Barang";
        $data['title_menu'] = "Barang";

        $data['request_hapus_barang'] = $this->request_hapus_barang->getAllRequest();
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('request_hapus_barang/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function setujui_hapus($id)
    {
        $id_decrypt = $this->secure->decrypt_url($id);
        $acc = $this->request_hapus_barang->acceptDelete($id_decrypt);
        if ($acc['err_code'] == 0) {
            $this->session->set_flashdata('message', 'Data Berhasil dihapus');
            redirect('barang/request_hapus_barang');
        } else {
            $this->session->set_flashdata('message_error', $acc['message']);
            redirect('barang/request_hapus_barang');
        }
    }
    public function tolak_hapus($id)
    {
        $id_decrypt = $this->secure->decrypt_url($id);
        $acc = $this->request_hapus_barang->rejectDelete($id_decrypt);
        if ($acc['err_code'] == 0) {
            $this->session->set_flashdata('message', 'Data Berhasil dihapus');
            redirect('barang/request_hapus_barang');
        } else {
            $this->session->set_flashdata('message_error', $acc['message']);
            redirect('barang/request_hapus_barang');
        }
    }
}
