<?php

/**
 * @property Satuan_model $satuan
 * @property Barang_model $barang
 * @property form_validation $form_validation
 * @property input $input
 * @property session $session
 * @property secure $secure
 */


class Satuan extends CI_Controller
{

    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Satuan_model", "satuan");
        $this->load->model("Barang_model", "barang");
    }

    public function index()
    {
        $this->view["title_menu"]       = "Barang";
        $this->view["title"]            = "Satuan";
        $this->view["content"]          = "satuan/v_satuan_index";
        $this->view["data_satuan"]      = $this->satuan->ambilSemuaSatuan();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function detail()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_satuan",
                "label"     => "Satuan",
                "rules"     => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id_satuan      = htmlspecialchars($this->input->post("id_satuan"));
            $res            = $this->satuan->ambilDetailSatuan($id_satuan);

            if ($res) {

                $data_json["status"]        = "berhasil";
                $data_json["response"]      = $res;
            } else {

                $data_json["status"]        = "gagal";
                $data_json["response"]      = "Data tidak ditemukan";
            }
        } else {

            $data_json["status"]        = "error";
            $data_json["response"]      = form_error("id_kategori");
        }

        echo json_encode($data_json);
    }

    public function tambah()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "satuan",
                "label"     => "Satuan",
                "rules"     => "required|trim|is_unique[tbl_satuan.satuan]",
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["satuan"]                = htmlspecialchars($this->input->post("satuan"));
            $data_post["user_input"]            = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_post["user_update"]           = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_post["created_at"]            = date("Y-m-d H:i:s");
            $data_post["updated_at"]            = date("Y-m-d H:i:s");

            if ($this->satuan->tambahData($data_post)) {
                $this->session->set_flashdata("berhasil", "Data berhasil di tambahkan");

                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "Data berhasil di tambah";
            } else {
                $this->session->set_flashdata("gagal", "Data gagal di tambahkan");

                $data_json["status"]            = "gagal";
                $data_json["response"]          = "Data gagal di tambah";
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_satuan"]            = form_error("satuan");
        }

        echo json_encode($data_json);
    }

    public function ubah()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_satuan",
                "label"     => "Satuan id",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "satuan",
                "label"     => "Satuan",
                "rules"     => "required|trim|callback_cekSatuan[" . htmlspecialchars($this->input->post('id_satuan')) . "]",
                "errors"    => [
                    "cekSatuan"   => "%s has been added"
                ]
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["id_satuan"]             = htmlspecialchars($this->input->post("id_satuan"));
            $data_post["satuan"]                = htmlspecialchars($this->input->post("satuan"));
            $data_post["user_update"]           = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_post["updated_at"]            = date("Y-m-d H:i:s");

            if ($this->satuan->ubahData($data_post)) {

                $this->session->set_flashdata("berhasil", "Data berhasil di ubah");


                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "Data berhasil di ubah";
            } else {

                $this->session->set_flashdata("gagal", "Data gagal di ubah");


                $data_json["status"]            = "gagal";
                $data_json["response"]          = "Data gagal di ubah";
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_satuanU"]           = form_error("satuan");
        }

        echo json_encode($data_json);
    }

    public function hapus($id_satuan)
    {

        $id_satuan = $this->secure->decrypt_url($id_satuan);

        // cek pada barang
        if ($this->barang->cekBarangPadaSatuan($id_satuan)) {

            $this->session->set_flashdata("gagal", "Hapus gagal, terdapat barang yang aktif pada kategori tersebut");
            redirect("barang/kategori", "refresh");
        }

        if ($this->satuan->hapusData($id_satuan)) {

            $this->session->set_flashdata("berhasil", "Data berhasil di hapus");
        } else {

            $this->session->set_flashdata("gagal", "Data gagal di hapus");
        }

        redirect("barang/satuan", "refresh");
    }

    public function cekSatuan($satuan, $id_satuan)
    {
        return $this->satuan->cekSatuan($satuan, $id_satuan);
    }
}
