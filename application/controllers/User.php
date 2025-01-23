<?php

/**
 * @property User_model $User_model
 * @property Role_model $Role_model
 * @property form_validation $form_validation
 * @property input $input
 * @property session $session
 */
class User extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('User_model');
        $this->load->model('Role_model');
    }

    public function index()
    {
        $this->view['title_menu']  = "Management Akses";
        $this->view['title']       = "User";
        $this->view['content']     = "user/v_user_index";
        $this->view['users']       = $this->User_model->ambilSemuaUser();
        $this->view['role']        = $this->Role_model->tampil_data()->result_array();

        $this->load->view('layout/wrapper', $this->view);
    }


    public function tambah()
    {

        $field = [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim|is_unique[tbl_user.username]'
            ],
            [
                'field' => 'nama_user',
                'label' => 'Nama User',
                'rules' => 'required|trim|max_length[100]',
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|trim|min_length[3]|matches[password2]'
            ],
            [
                'field' => 'password2',
                'label' => 'Password Confirm',
                'rules' => 'required|trim|min_length[3]|matches[password]'
            ],
            [
                'field' => 'role_id',
                'label' => 'Role',
                'rules' => 'required'
            ]
        ];

        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {
            $data = [

                'username' =>  htmlspecialchars($this->input->post('username')),
                'nama_user' => htmlspecialchars($this->input->post('nama_user')),
                'password' => htmlspecialchars(password_hash($this->input->post('password'), PASSWORD_DEFAULT)),
                'role_id' => htmlspecialchars($this->input->post('role_id')),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->User_model->tambah_data($data)) {
                $data_json['status'] = 'berhasil';
                $this->session->set_flashdata("berhasil", "Data User berhasil ditambahkan");
            } else {
                $data_json['status'] = 'gagal';
                $this->session->set_flashdata("gagal", "Data User gagal ditambahkan");
            }
        } else {

            $data_json['status'] = 'error';
            $data_json['err_username'] = form_error('username');
            $data_json['err_nama_user'] = form_error('nama_user');
            $data_json['err_password'] = form_error('password');
            $data_json['err_password2'] = form_error('password2');
            $data_json['err_role_id'] = form_error('role_id');
        }

        echo json_encode($data_json);
    }

    public function tampilan_edit()
    {

        $field = [
            [
                "field" => "id_user",
                "label" => "User id",
                "rules" => "required|trim"
            ]
        ];

        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() === true) {

            $id_user = htmlspecialchars($this->input->post("id_user"));

            $res = $this->User_model->findById($id_user);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = $res;
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data tidak ada";
            }
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = form_error("id_user");
        }

        echo json_encode($data_json);
    }

    public function edit()
    {


        $field = [
            [
                "field" => "id_user_edit",
                "label" => "User id",
                "rules" => "required|trim"
            ],
            [
                'field' => 'nama_user_edit',
                'label' => 'Nama User',
                'rules' => 'required|trim|max_length[100]',
            ],
            [
                'field' => 'role_id_edit',
                'label' => 'Role',
                'rules' => 'required'
            ],
            [
                'field' => 'is_active',
                'label' => 'User Aktif',
                'rules' => 'required'
            ]
        ];


        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'id_user'    => htmlspecialchars($this->input->post('id_user_edit')),
                'nama_user'  => htmlspecialchars($this->input->post('nama_user_edit')),
                'role_id'    => htmlspecialchars($this->input->post('role_id_edit')),
                'is_active'  => htmlspecialchars($this->input->post('is_active')),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->User_model->edit_data($data)) {
                $data_json['status']    = "berhasil";
                $this->session->set_flashdata("berhasil", "Data User berhasil diubah");
            } else {
                $data_json['status']    = "gagal";
                $this->session->set_flashdata("gagal", "Data User gagal diubah");
            }
        } else {
            $data_json['status']                = "error";
            $data_json['err_nama_user_edit']    = form_error('nama_user_edit');
            $data_json['err_role_id_edit']      = form_error('role_id_edit');
        }



        echo json_encode($data_json);
    }

    public function hapus($id)
    {
        if ($this->User_model->hapus_data(base64_decode($id))) {

            $this->session->set_flashdata("berhasil", "Data User berhasil dihapus");
        } else {

            $this->session->set_flashdata("gagal", "Data User gagal dihapus");
        }

        redirect('user');
    }


    public function changePassword()
    {

        $field = [
            [
                'field' => 'password_edit',
                'label' => 'Password',
                'rules' => 'required|trim|min_length[3]|matches[password2_edit]'
            ],
            [
                'field' => 'password2_edit',
                'label' => 'Password Confirm',
                'rules' => 'required|trim|min_length[3]|matches[password_edit]'
            ],
        ];

        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'id_user' => htmlspecialchars($this->input->post('id_user_edit_password')),
                'password' => htmlspecialchars(password_hash($this->input->post('password_edit'), PASSWORD_DEFAULT)),
                'updated_at' => date('Y-m-d H:i:s')
            ];


            if ($this->User_model->edit_data($data)) {

                $data_json['status']    = "berhasil";
                $this->session->set_flashdata("berhasil", "Password User berhasil di ubah");
            } else {
                $data_json['status']    = "gagal";
                $this->session->set_flashdata("gagal", "Password User gagal di ubah");
            }
        } else {
            $data_json['status']                = "error";
            $data_json['err_password_edit']    = form_error('password_edit');
            $data_json['err_password2_edit']      = form_error('password2_edit');
        }

        echo json_encode($data_json);
    }
}
