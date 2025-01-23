<?php

/**
 * @property Banner_model $banner
 * @property getAllTransaksi $getAllTransaksi
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property upload $upload
 * @property secure $secure
 */

class Banner extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Banner_model", "banner");
    }

    public function index()
    {

        $this->view["title_menu"]   = "Marketplace";
        $this->view["title"]        = "Banner";
        $this->view["content"]      = "banner/v_banner_index";
        $this->view["data_banner"]  = $this->banner->ambilSemuaBanner();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function tambahBanner()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "judul",
                "label" => "Judul",
                "rules" => "required|trim|max_length[100]"
            ]
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            if (!empty($_FILES["gambar"]["name"])) {

                $config["file_name"]            = rand();
                $config['upload_path']          = './assets/file_banner';
                $config['allowed_types']        = 'jpeg|jpg|png|JPG|JPEG|PNG';
                $config['max_size']             = 2024;
                $config['max_width']            = 1920;
                $config['max_height']           = 1080;

                $this->load->library("upload");
                $this->upload->initialize($config);

                if ($this->upload->do_upload("gambar")) {

                    $data["judul"]      = htmlspecialchars($this->input->post("judul"));
                    $data["gambar"]     = $this->upload->data("file_name");
                    $data["created_at"] = date("Y-m-d H:i:s");
                    $data["user_input"] = $this->session->userdata("nama_user");

                    $res = $this->banner->tambahBanner($data);

                    if ($res) {

                        $this->session->set_flashdata("berhasil", "Data berhasil di tambah");

                        $data_json["status"]        = "berhasil";
                        $data_json["err_gambar"]    = "Data berhasil di tambah";
                    } else {

                        $this->session->set_flashdata("gagal", "Data gagal di tambah");

                        $data_json["status"]        = "gagal";
                        $data_json["err_gambar"]    = "Data gagal di tambah";
                    }
                } else {

                    $data_json["status"]        = "error";
                    $data_json["err_gambar"]    = $this->upload->display_errors();
                }
            } else {

                $data_json["status"]        = "error";
                $data_json["err_gambar"]    = "The images banner cant be empty";
            }
        } else {

            $data_json["status"]    = "error";
            $data_json["err_judul"] = form_error("judul");
        }

        echo json_encode($data_json);
    }

    public function detailBanner()
    {
        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id",
                "label" => "Id",
                "rules" => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id_banner = htmlspecialchars($this->input->post("id"));

            $res = $this->banner->ambilBannerBerdasarkanId($id_banner);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = $res;
            } else {
                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data tidak ada";
            }
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = form_error("id");
        }

        echo json_encode($data_json);
    }

    public function ubahBanner()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_banner",
                "label" => "Id",
                "rules" => "required|trim"
            ],

            [
                "field" => "judul",
                "label" => "Judul",
                "rules" => "required|trim|max_length[100]"
            ]
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $err        = false;
            $filename   = htmlspecialchars($this->input->post("gambar_old"));

            if (!empty($_FILES["gambar"]["name"])) {

                $config["file_name"]            = rand();
                $config['upload_path']          = './assets/file_banner';
                $config['allowed_types']        = 'jpeg|jpg|png|JPG|JPEG|PNG';
                $config['max_size']             = 2024;
                $config['max_width']            = 1920;
                $config['max_height']           = 1080;

                $this->load->library("upload");
                $this->upload->initialize($config);

                if ($this->upload->do_upload("gambar")) {

                    if ($filename) {

                        if (file_exists("./assets/file_banner/" . $filename)) {

                            unlink("./assets/file_banner/" . $filename);
                        }
                    }

                    $filename = $this->upload->data("file_name");
                } else {

                    $err = true;
                }
            }

            if (!$err) {

                $data["id_banner"]  = htmlspecialchars($this->input->post("id_banner"));
                $data["judul"]      = htmlspecialchars($this->input->post("judul"));
                $data["gambar"]     = $filename;
                $data["updated_at"] = date("Y-m-d H:i:s");
                $data["user_edit"]  = $this->session->userdata("nama_user");

                $res = $this->banner->ubahBanner($data);

                if ($res) {

                    $this->session->set_flashdata("berhasil", "Data berhasil di ubah");

                    $data_json["status"]        = "berhasil";
                    $data_json["err_gambar"]    = "Data berhasil di tambah";
                } else {

                    $this->session->set_flashdata("gagal", "Data gagal di ubah");

                    $data_json["status"]        = "gagal";
                    $data_json["err_gambar"]    = "Data gagal di ubah";
                }
            } else {

                $data_json["status"]        = "error";
                $data_json["err_gambarU"]    = $this->upload->display_errors();
            }
        } else {

            $data_json["status"]            = "error";
            $data_json["err_judulU"]        = form_error("judul");
            $data_json["err_id_bannerU"]    = form_error("id_banner");
        }

        echo json_encode($data_json);
    }

    public function hapusBanner($id_banner)
    {

        $id_banner = $this->secure->decrypt_url($id_banner);

        if ($this->banner->hapusBanner($id_banner)) {

            $this->session->set_flashdata("berhasil", "Data berhasil di hapus");
            redirect("marketplace/banner");
        } else {

            $this->session->set_flashdata("gagal", "Data gagal di hapus");
            redirect("marketplace/banner");
        }
    }
}
