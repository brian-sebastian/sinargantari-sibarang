<?php

/**
 * @property Kategori_model $kategori
 * @property Barang_model $barang
 * @property form_validation $form_validation
 * @property input $input
 * @property session $session
 * @property secure $secure
 */

class Kategori extends CI_Controller
{

    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Kategori_model", "kategori");
        $this->load->model("Barang_model", "barang");
    }

    public function index()
    {
        $this->view["title_menu"]       = "Barang";
        $this->view["title"]            = "Kategori";
        $this->view["content"]          = "kategori/v_kategori_index";
        $this->view["data_kategori"]    = $this->kategori->ambilSemuaKategori();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function detail()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_kategori",
                "label"     => "Kategori",
                "rules"     => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id_kategori    = htmlspecialchars($this->input->post("id_kategori"));

            $res            = $this->kategori->ambilDetailKategori($id_kategori);

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
                "field"     => "kode_kategori",
                "label"     => "Kode kategori",
                "rules"     => "required|trim|is_unique[tbl_kategori.kode_kategori]",
            ],

            [
                "field"     => "nama_kategori",
                "label"     => "Nama kategori",
                "rules"     => "required|trim|is_unique[tbl_kategori.nama_kategori]",
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["kode_kategori"]         = htmlspecialchars($this->input->post("kode_kategori"));
            $data_post["nama_kategori"]         = htmlspecialchars($this->input->post("nama_kategori"));
            $data_post["slug_kategori"]         = str_replace(" ", "-", strtolower(htmlspecialchars($this->input->post("nama_kategori"))));
            $data_post["user_input"]            = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_post["user_update"]           = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_post["created_at"]            = date("Y-m-d H:i:s");
            $data_post["updated_at"]            = date("Y-m-d H:i:s");

            if ($this->kategori->tambahData($data_post)) {

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
            $data_json["err_kode_kategori"]     = form_error("kode_kategori");
            $data_json["err_nama_kategori"]     = form_error("nama_kategori");
        }

        echo json_encode($data_json);
    }

    public function ubah()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_kategori",
                "label"     => "Kategori id",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "kode_kategori",
                "label"     => "Kode kategori",
                "rules"     => "required|trim|callback_cekKodeKategori[" . htmlspecialchars($this->input->post('id_kategori')) . "]",
                "errors"    => [
                    "cekKodeKategori"   => "%s has been added"
                ]
            ],

            [
                "field"     => "nama_kategori",
                "label"     => "Nama kategori",
                "rules"     => "required|trim|callback_cekNamaKategori[" . htmlspecialchars($this->input->post('id_kategori')) . "]",
                "errors"    => [
                    "cekNamaKategori"   => "%s has been added"
                ]

            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["id_kategori"]           = htmlspecialchars($this->input->post("id_kategori"));
            $data_post["kode_kategori"]         = htmlspecialchars($this->input->post("kode_kategori"));
            $data_post["nama_kategori"]         = htmlspecialchars($this->input->post("nama_kategori"));
            $data_post["slug_kategori"]         = str_replace(" ", "-", strtolower(htmlspecialchars($this->input->post("nama_kategori"))));
            $data_post["user_update"]           = ($this->session->userdata("nama")) ? $this->session->userdata("nama") : "sistem";
            $data_post["updated_at"]            = date("Y-m-d H:i:s");

            if ($this->kategori->ubahData($data_post)) {

                $this->session->set_flashdata("berhasil", "Data berhasil di ubah");


                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "Data berhasil di ubah";
            } else {
                $this->session->set_flashdata("gagal", "Data gagal di ubah");

                $data_json["status"]            = "gagal";
                $data_json["response"]          = "Data gagal di ubah";
            }
        } else {

            $data_json["status"]                 = "error";
            $data_json["err_kode_kategoriU"]     = form_error("kode_kategori");
            $data_json["err_nama_kategoriU"]     = form_error("nama_kategori");
        }

        echo json_encode($data_json);
    }

    public function hapus($id_kategori)
    {

        $id_kategori = $this->secure->decrypt_url($id_kategori);

        // cek pada barang
        if ($this->barang->cekBarangPadaKategori($id_kategori)) {

            $this->session->set_flashdata("gagal", "Hapus gagal, terdapat barang yang aktif pada kategori tersebut");
            redirect("barang/kategori", "refresh");
        }

        if ($this->kategori->hapusData($id_kategori)) {

            $this->session->set_flashdata("berhasil", "Data berhasil di hapus");
        } else {

            $this->session->set_flashdata("gagal", "Data gagal di hapus");
        }

        redirect("barang/kategori", "refresh");
    }

    public function cekKodeKategori($kode_kategori, $id_kategori)
    {
        return $this->kategori->cekKodeKategori($kode_kategori, $id_kategori);
    }

    public function cekNamaKategori($nama_kategori, $id_kategori)
    {
        return $this->kategori->cekNamaKategori($nama_kategori, $id_kategori);
    }
}
