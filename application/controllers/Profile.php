<?php


/**
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property User_model $User_model
 * @property Role_model $Role_model
 */


class Profile extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('User_model');
        $this->load->model('Role_model');
    }

    public function profil($username)
    {
        $this->view['title_menu']   = "Account Settings";
        $this->view['title']        = "Account";
        $this->view['content']      = "profile/v_profile_index";
        $this->view['users']        = $this->User_model->findByUsername($username);
        $this->view['role']         = $this->Role_model->tampil_data()->result_array();

        $this->load->view('layout/wrapper', $this->view);
    }

    public function tampilan_edit()
    {
        $field = [
            [
                'field' => "username",
                'label' => "Username",
                'rules' => "required|trim"
            ]
        ];

        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() === true) {

            $username = htmlspecialchars($this->input->post("username"));

            $res = $this->User_model->findByUsername($username);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = $res;
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data tidak ada";
            }
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = form_error("username");
        }

        echo json_encode($data_json);
    }

    public function edit()
    {

        $field = [
            [
                'field' => 'nama_user',
                'label' => 'Nama User',
                'rules' => 'required|trim|max_length[100]',
            ],
        ];

        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'id_user' => htmlspecialchars($this->input->post('id_user')),
                'nama_user' => htmlspecialchars($this->input->post('nama_user')),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->User_model->edit_data($data);

            $this->session->set_flashdata('berhasil', 'Data Berhasil Di Edit');
            redirect('profile/profil/' . $this->input->post('username'));
        } else {
            $this->session->set_flashdata('gagal', 'Data Gagal Di Edit');
            redirect('profile/profil/' . $this->input->post('username'));
        }
    }

    public function changepassword()
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
                'id_user' => htmlspecialchars($this->input->post('id_user')),
                'password' => htmlspecialchars(password_hash($this->input->post('password_edit'), PASSWORD_DEFAULT)),
            ];

            if ($this->User_model->edit_data($data)) {

                $data_json['status']    = "berhasil";
                $data_json['response']  = "Password Berhasil Di Ubah";
            } else {
                $data_json['status']    = "gagal";
                $data_json['response']  = "Password Gagal Di Ubah";
            }
        } else {
            $data_json['status']                = "error";
            $data_json['err_password_edit']    = form_error('password_edit');
            $data_json['err_password2_edit']      = form_error('password2_edit');
        }

        echo json_encode($data_json);
    }

    public function upload_photo()
    {
        $username = htmlspecialchars($this->input->post('username'));
        $config['upload_path']   = './assets/file_foto_profile/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library("upload");
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image_profile')) {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('gagal', $error['error']);
            redirect("profile/profil/$username");
        } else {
            $data = $this->upload->data();
            $file_name = $data['file_name'];

            // Simpan nama file ke database
            $user_id = htmlspecialchars($this->input->post('user_id'));
            $this->User_model->update_photo($user_id, $file_name);

            // Redirect atau beri pesan sukses
            $this->session->set_flashdata('berhasil', 'Photo uploaded successfully.');
            redirect("profile/profil/$username");
        }
    }
}
