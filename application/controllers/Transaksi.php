<?php

/**
 * @property Transaksi_model $Transaksi_model
 * @property getAllTransaksi $getAllTransaksi
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property upload $upload
 */

class Transaksi extends CI_Controller
{

    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->helper(array('form', 'url'));
        $this->load->model('Transaksi_model');
    }

    public function index()
    {

        $tgl = $this->input->post('tanggal');


        if ($tgl) {
            $tglPecah = explode('to', $tgl);
            $tgl1 = $tglPecah[0];
            $tgl2 = $tglPecah[1];
        } else {
            $tgl1 = null;
            $tgl2 = null;
        }
        $this->view['title_menu']   = "Kasir";
        $this->view['title']        = "Transaksi";
        $this->view['content']      = "transaksi/v_transaksi_index";
        $this->view['transaksi']    = $this->Transaksi_model->getFindTanggal($tgl1, $tgl2);


        $this->load->view('layout/wrapper', $this->view);
    }



    public function detail($id)
    {
        $data = [
            'transaksi' => $this->Transaksi_model->getFindIdTransaksi(base64_decode($id))
        ];
        $this->load->view('transaksi/v_detail', $data);
    }

    public function tampilan_edit($id)
    {
        $this->view['title_menu']   = "Transaksi";
        $this->view['title']        = "Edit";
        $this->view['content']      = "transaksi/v_edit";
        $this->view['transaksi']    = $this->Transaksi_model->getFindIdTransaksi(base64_decode($id));

        $this->load->view('layout/header', $this->view);
        $this->load->view('layout/sidebar', $this->view);
        $this->load->view('layout/navbar', $this->view);
        $this->load->view('transaksi/v_edit', $this->view);
        $this->load->view('layout/footer', $this->view);
        $this->load->view('layout/script', $this->view);
    }

    public function edit()
    {

        $upload_file = $_FILES['bukti_transfer']['name'];


        $field = [

            [
                'field' => "terbayar",
                'label' => "Terbayar",
                'rules' => "required|trim|numeric",
            ],
            [
                'field' => "kembalian",
                'label' => "Kembalian",
                'rules' => "required|trim|numeric",
            ],
            [
                'field' => "tipe",
                'label' => "Tipe",
                'rules' => "required|trim",
            ],
        ];


        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {

            if ($upload_file) {
                $config['allowed_types']        = 'pdf|gif|jpg|jpeg|png|JPG|JPEG|PNG';
                $config['upload_path']          = './assets/file_bukti_transaksi/';
                $config['max_size']             = 1024;


                $this->load->library('upload', $config);

                if (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $config['upload_path'] . $_FILES['bukti_transfer']['name'])) {

                    $bukti_tf_old = $this->input->post('bukti_tf_old');

                    if ($bukti_tf_old) {
                        unlink(FCPATH . 'assets/file_bukti_transaksi/' . $bukti_tf_old);
                    }
                    $buktiTf = $_FILES['bukti_transfer']['name'];

                    $data = [
                        'id_transaksi' => $this->input->post('id_transaksi'),
                        'bukti_tf' => $buktiTf,
                        'terbayar' => $this->input->post('terbayar'),
                        'kembalian' => $this->input->post('kembalian'),
                        'tipe_transaksi' => $this->input->post('tipe'),
                        'user_edit' => $this->session->userdata('username'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $this->Transaksi_model->edit_data($data);
                    $this->session->set_flashdata('berhasil', 'Data berhasil di edit');
                    redirect('kasir/transaksi');
                } else {
                    $this->upload->display_errors();
                    $this->session->set_flashdata('gagal', 'Data gagal di edit');
                    redirect('kasir/transaksi/edit/' . base64_encode($this->input->post('id_transaksi')));
                }
            } else {
                $data = [
                    'id_transaksi' => $this->input->post('id_transaksi'),
                    'terbayar' => $this->input->post('terbayar'),
                    'kembalian' => $this->input->post('kembalian'),
                    'tipe_transaksi' => $this->input->post('tipe'),
                    'user_edit' => $this->session->userdata('username'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $this->Transaksi_model->edit_data($data);
                $this->session->set_flashdata('berhasil', 'Data berhasil di edit');
                redirect('kasir/transaksi');
            }
        } else {
            $this->view['title_menu']   = "Transaksi";
            $this->view['title']        = "Edit";
            $this->view['content']      = "transaksi/v_edit";
            $this->view['transaksi']    = $this->Transaksi_model->getFindIdTransaksi($this->input->post('id_transaksi'));

            $this->load->view('layout/header', $this->view);
            $this->load->view('layout/sidebar', $this->view);
            $this->load->view('layout/navbar', $this->view);
            $this->load->view('transaksi/v_edit', $this->view);
            $this->load->view('layout/footer', $this->view);
            $this->load->view('layout/script', $this->view);
        }
    }



    public function hapus($id)
    {
        $transaksi = $this->Transaksi_model->getFindIdTransaksi(base64_decode($id));
        $bukti_tf = $transaksi['bukti_tf'];
        if ($bukti_tf) {
            unlink(FCPATH . 'assets/file_bukti_transaksi/' . $bukti_tf);
        }

        $this->Transaksi_model->hapus_data(base64_decode($id));
        $this->session->set_flashdata('berhasil', 'Data berhasil di hapus');
        redirect('kasir/transaksi');
    }
}
