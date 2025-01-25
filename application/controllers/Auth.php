<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property session $session
 * @property form_validation $form_validation
 * @property input $input
 * @property db $db
 * @property User_model $User_model
 */
class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('cart');
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[200]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/layout_auth/header');
            $this->load->view('auth/v_login');
            $this->load->view('auth/layout_auth/footer');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->findByUsername($username);
          
            if ($user) {
                if ($user['is_active'] == 1) {
                    if (password_verify($password, $user['password'])) {

                        $newArr = [];
                        $data = [
                            'id_user' => $user['id_user'],
                            'nama_user' => $user['nama_user'],
                            'username' => $user['username'],
                            'role_id' => $user['role_id'],
                        ];

                        if ($user['type_user'] == null || empty($user['type_user']) || $user['type_user'] == '') {
                            $data['type_user'] = "!DEV-NOT_DEV";
                            $data['is_developer'] = false;
                        } else {
                            $data['type_user'] = $user['type_user'];
                            $data['is_developer'] = true;
                        }

                        $queryKaryawan = $this->db->get_where('tbl_karyawan_toko', ['user_id' => $data['id_user']])->row_array();
                  
                        if ($queryKaryawan) {
                            $data['toko_id'] = $queryKaryawan['toko_id'];

                            $this->session->set_userdata($data);
                        } else {
                            $this->session->set_userdata($data);
                        }

                        if ($user['role_id'] == 1) {
                            redirect('dashboard');
                        } else {
                            redirect('dashboard');
                        }
                    } else {
                        $this->session->set_flashdata('message_error', 'Password Salah');
                        redirect('auth');
                    }
                } else {
                    $this->session->set_flashdata('message_error', 'Username Tidak Aktif');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message_error', 'Username Belum terdaftar');
                redirect('auth');
            }
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('id_user');
        $this->session->unset_userdata('nama_user');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('toko_id');
        $this->cart->destroy();

        $this->session->set_flashdata('message', 'Kamu berhasil logout');
        redirect('auth');
    }

    public function err_acces()
    {
        $this->session->unset_userdata('id_user');
        $this->session->unset_userdata('nama_user');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('toko_id');
        $this->session->set_flashdata('message_error', 'Maaf Kamu Tidak Ada Akses, silahkan hubungi admin');
        redirect('auth');
    }

    public function blocked()
    {
        $data['title'] = "Blocked Page";
        $this->load->view('auth/layout_auth/header');
        $this->load->view('auth/v_blocked');
        $this->load->view('auth/layout_auth/footer');
    }
}
