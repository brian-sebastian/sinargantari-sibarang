<?php

/**
 * @property User_model $user
 * @property Karyawan_model $karyawan
 * @property Toko_model $toko
 * @property Role_model $role
 * @property form_validation $form_validation
 * @property input $input
 * @property session $session
 * @property secure $secure
 * @property Logfile_model $logfile
 */
class Karyawan extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("User_model", "user");
        $this->load->model("Karyawan_model", "karyawan");
        $this->load->model("Toko_model", "toko");
        $this->load->model("Role_model", "role");
        $this->load->model("Logfile_model", "logfile");
    }

    public function index()
    {
        $this->view["title_menu"]           = "Toko";
        $this->view["title"]                = "Karyawan Toko";
        $this->view["content"]              = "karyawan/v_karyawan_index";
        $this->view["data_toko"]            = $this->toko->ambilSemuaToko();
        $this->view["data_user"]            = $this->user->ambilSemuaUserNotInKaryawantoko();
        $this->view["data_karyawan"]        = $this->karyawan->ambilSemuaKaryawanJoinUserToko();
        $this->view["data_role"]            = $this->role->getAllRole();
        $this->view["karyawan_temp"]        = $this->karyawan->ambilTempKaryawan(($this->session->userdata("toko_id")) ? $this->session->userdata("toko_id") : NULL)->num_rows();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function detail()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_karyawan",
                "label" => "Karyawan id",
                "rules" => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id_karyawan = htmlspecialchars($this->input->post("id_karyawan"));

            $res = $this->karyawan->ambilDetailKaryawan($id_karyawan);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = $res;
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data tidak ada";
            }
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = form_error("id_karyawan");
        }

        echo json_encode($data_json);
    }

    public function tambah()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "toko_id",
                "label" => "Toko",
                "rules" => "required|trim"
            ],

            [
                "field" => "role_id",
                "label" => "Role",
                "rules" => "required|trim"
            ],

            [
                "field" => "username",
                "label" => "Username",
                "rules" => "required|trim|is_unique[tbl_user.username]"
            ],

            [
                "field" => "password",
                "label" => "Password",
                "rules" => "trim"
            ],

            [
                "field" => "nama_karyawan",
                "label" => "Nama karyawan",
                "rules" => "required|trim|max_length[100]",
            ],
            [
                "field" => "hp_karyawan",
                "label" => "Hp karyawan",
                "rules" => "required|trim|min_length[10]|max_length[13]",
            ],
            [
                "field" => "alamat_karyawan",
                "label" => "Alamat karyawan",
                "rules" => "trim",
            ],
            [
                "field" => "bagian",
                "label" => "Bagian",
                "rules" => "trim|required"
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            // $data_post["user_id"]               = htmlspecialchars($this->input->post("user_id"));

            $data_us["role_id"]               = htmlspecialchars($this->input->post("role_id"));
            $data_us["nama_user"]             = htmlspecialchars($this->input->post("nama_karyawan"));
            $data_us["username"]              = htmlspecialchars($this->input->post("username"));
            $data_us["password"]              = (htmlspecialchars($this->input->post("password"))) ? password_hash(htmlspecialchars($this->input->post("password")), PASSWORD_DEFAULT) : password_hash("12345678", PASSWORD_DEFAULT);
            $data_us["created_at"]            = date("Y-m-d H:i:s");

            $data_kar["toko_id"]               = htmlspecialchars($this->input->post("toko_id"));
            $data_kar["nama_karyawan"]         = htmlspecialchars($this->input->post("nama_karyawan"));
            $data_kar["hp_karyawan"]           = htmlspecialchars($this->input->post("hp_karyawan"));
            $data_kar["alamat_karyawan"]       = htmlspecialchars($this->input->post("alamat_karyawan"));
            $data_kar["bagian"]                = htmlspecialchars($this->input->post("bagian"));
            $data_kar["user_input"]            = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_kar["user_update"]           = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_kar["created_at"]            = date("Y-m-d H:i:s");
            $data_kar["updated_at"]            = date("Y-m-d H:i:s");

            if ($this->karyawan->tambahData($data_us, $data_kar)) {

                $this->session->set_flashdata("berhasil", "Data berhasil di tambahkan");

                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "data berhasil di tambahkan";
            } else {

                $this->session->set_flashdata("gagal", "Data gagal di tambahkan");

                $data_json["status"]            = "gagal";
                $data_json["response"]          = "data gagal di tambahkan";
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_toko_id"]           = form_error("toko_id");
            $data_json["err_role_id"]           = form_error("role_id");
            $data_json["err_username"]          = form_error("username");
            $data_json["err_password"]          = form_error("password");
            $data_json["err_nama_karyawan"]     = form_error("nama_karyawan");
            $data_json["err_hp_karyawan"]       = form_error("hp_karyawan");
            $data_json["err_alamat_karyawan"]   = form_error("alamat_karyawan");
            $data_json["err_bagian"]            = form_error("bagian");
        }

        echo json_encode($data_json);
    }

    public function ubah()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "id_karyawan",
                "label" => "Karyawan id",
                "rules" => "required|trim",
            ],
            [
                "field" => "user_id",
                "label" => "User id",
                "rules" => "required|trim",
            ],
            [
                "field" => "toko_id",
                "label" => "Toko",
                "rules" => "required|trim"
            ],
            [
                "field"     => "role_id",
                "label"     => "Role",
                "rules"     => "required|trim"
            ],
            [
                "field"     => "username",
                "label"     => "Username",
                "rules"     => "required|trim|callback_check_username[" . htmlspecialchars($this->input->post("user_id")) . "]",
                "errors"    => [
                    "check_username"    => "%s has been added"
                ]
            ],
            [
                "field"     => "password",
                "label"     => "Password",
                "rules"     => "trim"
            ],
            [
                "field" => "nama_karyawan",
                "label" => "Nama karyawan",
                "rules" => "required|trim|max_length[100]",
            ],
            [
                "field" => "hp_karyawan",
                "label" => "Hp karyawan",
                "rules" => "required|trim|min_length[10]|max_length[13]",
            ],
            [
                "field" => "alamat_karyawan",
                "label" => "Alamat karyawan",
                "rules" => "trim",
            ],
            [
                "field" => "bagian",
                "label" => "Bagian",
                "rules" => "trim|required"
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_us["user_id"]               = htmlspecialchars($this->input->post("user_id"));
            $data_us["role_id"]               = htmlspecialchars($this->input->post("role_id"));
            $data_us["nama_user"]             = htmlspecialchars($this->input->post("nama_karyawan"));
            $data_us["username"]              = htmlspecialchars($this->input->post("username"));
            if ($this->input->post("password")) {

                $data_us["password"]              = password_hash(htmlspecialchars($this->input->post("password")), PASSWORD_DEFAULT);
            }
            $data_us["updated_at"]            = date("Y-m-d H:i:s");

            $data_kar["id_karyawan"]           = htmlspecialchars($this->input->post("id_karyawan"));
            $data_kar["toko_id"]               = htmlspecialchars($this->input->post("toko_id"));
            $data_kar["nama_karyawan"]         = htmlspecialchars($this->input->post("nama_karyawan"));
            $data_kar["hp_karyawan"]           = htmlspecialchars($this->input->post("hp_karyawan"));
            $data_kar["alamat_karyawan"]       = htmlspecialchars($this->input->post("alamat_karyawan"));
            $data_kar["bagian"]                = htmlspecialchars($this->input->post("bagian"));
            $data_kar["user_update"]           = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_kar["updated_at"]            = date("Y-m-d H:i:s");

            if ($this->karyawan->ubahData($data_us, $data_kar)) {

                $this->session->set_flashdata("berhasil", "data berhasil di ubah");

                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "data berhasil di ubah";
            } else {

                $this->session->set_flashdata("gagal", "data gagal di ubah");

                $data_json["status"]            = "gagal";
                $data_json["response"]          = "data gagal di ubah";
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_toko_idU"]          = form_error("toko_id");
            $data_json["err_user_idU"]          = form_error("user_id");
            $data_json["err_role_idU"]          = form_error("role_id");
            $data_json["err_usernameU"]         = form_error("username");
            $data_json["err_passwordU"]         = form_error("password");
            $data_json["err_nama_karyawanU"]    = form_error("nama_karyawan");
            $data_json["err_hp_karyawanU"]      = form_error("hp_karyawan");
            $data_json["err_alamat_karyawanU"]  = form_error("alamat_karyawan");
            $data_json["err_bagianU"]           = form_error("bagian");
        }

        echo json_encode($data_json);
    }

    public function hapus($id_karyawan)
    {

        $id_karyawan = $this->secure->decrypt_url($id_karyawan);

        if ($this->karyawan->hapusData($id_karyawan)) {

            $this->session->set_flashdata("berhasil", "Data berhasil di hapus");
        } else {

            $this->session->set_flashdata("gagal", "Data gagal di hapus");
        }

        redirect("toko/karyawan", "refresh");
    }

    public function check_username($username, $id)
    {
        return $this->user->checkUsername($username, $id);
    }

    public function doImportKaryawan()
    {
        $fileKaryawan = $_FILES['file_barang'];

        if (!empty($fileKaryawan)) {

            if ($fileKaryawan['size'] > 5000000) {
                // 5 Mb maximum Mb -> Byte
                $this->session->set_flashdata('gagal', 'Maximum File 5 Mb');
                redirect('toko/karyawan');
            }

            if (!empty($_FILES['file_barang']['name'])) {

                $data = [
                    'path' => realpath($_FILES['file_barang']['tmp_name']),
                    'file' => $_FILES['file_barang']['name'],
                    'wk_input'  => date('Y-m-d H:i:s')
                ];

                $res = $this->logfile->insertLog($data);

                if ($res) {

                    $this->load->library('Excel_library', array('data' => $_FILES, 'func' => 'import', 'jenis' => 'karyawan_toko'));
                } else {

                    $this->session->set_flashdata('gagal', 'Gagal dalam menyimpan path file');
                    redirect('toko/karyawan', 'refresh');
                }
            } else {
                $this->session->set_flashdata('gagal', 'Tidak Ada File yang diupload');
                redirect('toko/karyawan');
            }
        } else {

            $this->session->set_flashdata('gagal', 'Tidak Ada File yang diupload');
            redirect('toko/karyawan');
        }
    }

    public function temp()
    {

        $this->view["title_menu"]           = "Toko";
        $this->view["title"]                = "Karyawan Toko";
        $this->view["content"]              = "karyawan/v_karyawan_temp";
        $this->view["karyawan_temp"]        = $this->karyawan->ambilTempKaryawan(($this->session->userdata("toko_id")) ? $this->session->userdata("toko_id") : NULL)->result_array();

        $this->load->view("layout/wrapper", $this->view);
    }
}
