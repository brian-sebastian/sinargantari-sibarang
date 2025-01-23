<?php

class Diskon extends CI_Controller
{

    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("User_model", "user");
        $this->load->model("Role_model", "role");
        $this->load->model("Menu_model", "menu");
        $this->load->model("Diskon_model", "diskon");
        $this->load->model("Barang_model", "barang");
        $this->load->model("Harga_model", "harga");
        $this->load->model("Karyawan_model", "karyawan");
        $this->load->model("Toko_model", "toko");
    }

    public function index()
    {

        $this->view["title_menu"]   = "Toko";
        $this->view["title"]        = "Diskon";
        $this->view["content"]      = "diskon/v_diskon_index";

        $id_toko = $this->session->userdata('toko_id');

        if ($this->session->userdata("toko_id")) {

            $this->view["data_toko"]    = $this->toko->findByIdToko($id_toko);
            $this->view["data_diskon"] = $this->diskon->ambilSemuaDiskonToko($id_toko);

        } else {

            $this->view["data_toko"]    = $this->toko->ambilSemuaToko();
            $this->view["data_diskon"]    = $this->diskon->ambilSemuaDiskon();
        }

        $this->view["data_user"]    = $this->user->ambilSemuaUserNotInKaryawantoko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function dt_toko($id)
    {
        $data_toko = ['toko_id' => $id];

        $data['barang_toko'] = $this->barang->selectDataBarangToko($data_toko);
    }

    public function dt_barang_toko()
    {
        $id_harga   = $this->input->post('id_harga');

        $data_barang_toko = $this->harga->getBarangHargaById($id_harga);

        $array = array('satuan' => $data_barang_toko['satuan']);

        echo json_encode($array);
    }

    public function tambah()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "toko_id",
                "label" => "Nama Toko",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "id_harga",
                "label" => "Barang Toko",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "nama_diskon",
                "label" => "Nama Diskon",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "harga_potongan",
                "label" => "Harga Potongan",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "tgl_mulai",
                "label" => "Tanggal Mulai",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "tgl_akhir",
                "label" => "Tanggal Akhir",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "minimal_beli",
                "label" => "Minimal Pembelian",
                "rules" => "trim|required",
                "errors"    => ["required" => '%s tidak boleh kosong!']
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["harga_id"]       = htmlspecialchars($this->input->post("id_harga"));
            $data_post["nama_diskon"]    = htmlspecialchars($this->input->post("nama_diskon"));
            $data_post["harga_potongan"] = htmlspecialchars($this->input->post("harga_potongan"));
            $data_post["tgl_mulai"]      = htmlspecialchars($this->input->post("tgl_mulai"));
            $data_post["tgl_akhir"]      = htmlspecialchars($this->input->post("tgl_akhir"));
            $data_post["minimal_beli"]   = htmlspecialchars($this->input->post("minimal_beli"));
            $data_post["user_input"]     = ($this->session->userdata("nama_user")) ? $this->session->userdata("nama_user") : "sistem";
            $data_post["created_at"]     = date("Y-m-d H:i:s");
            $data_post["is_active"]      = 1;

            if ($this->diskon->tambahData($data_post)) {

                $data_json["status"]            = "berhasil";
                $this->session->set_flashdata("berhasil", "Data Diskon Barang berhasil ditambahkan");
            } else {

                $data_json["status"]            = "gagal";
                $this->session->set_flashdata("gagal", "Data Diskon Barang gagal ditambahkan");
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_toko_id_diskon"]    = form_error("toko_id");
            $data_json["err_id_harga_diskon"]   = form_error("id_harga");
            $data_json["err_nama_diskon"]       = form_error("nama_diskon");
            $data_json["err_harga_potongan"]    = form_error("harga_potongan");
            $data_json["err_tgl_mulai"]         = form_error("tgl_mulai");
            $data_json["err_tgl_akhir"]         = form_error("tgl_akhir");
            $data_json["err_minimal_beli"]      = form_error("minimal_beli");
        }

        echo json_encode($data_json);
    }

    public function lihat_diskonmodel($id_diskon)
    {
        $where = array('id_diskon' => base64_decode($id_diskon));

        $this->view["data_diskon"]    = $this->diskon->ambilDataDiskon($where);

        $this->load->view("diskon/v_lihat_Modal", $this->view);
    }

    public function ubah_diskonmodel($id_diskon)
    {
        $where = array('id_diskon' => base64_decode($id_diskon));

        $data_diskon = $this->diskon->ambilDataDiskon($where);

        $data_barang = array(
                            'id_toko' => $data_diskon['id_toko'],
                            'id_barang' => $data_diskon['id_barang'],
                        );

        $id_toko = $this->session->userdata('toko_id');

        if ($this->session->userdata("toko_id")) {

            $this->view["data_toko"]    = $this->toko->findByIdToko($id_toko);

        } else {

            $this->view["data_toko"]    = $this->toko->ambilTokoKecualiIdToko($data_diskon['id_toko']);
        }

        $this->view["data_barang"]    = $this->harga->ambilBarangBerdasarkanTokoDanKecualiIdHarga($data_barang);

        $this->view["data_diskon"]    = $this->diskon->ambilDataDiskon($where);

        $this->load->view("diskon/v_ubah_Modal", $this->view);
    }

    public function ubah()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "toko_id",
                "label" => "Nama Toko",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "id_harga",
                "label" => "Barang Toko",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "nama_diskon",
                "label" => "Nama Diskon",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "harga_potongan",
                "label" => "Harga Potongan",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "tgl_mulai",
                "label" => "Tanggal Mulai",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "tgl_akhir",
                "label" => "Tanggal Akhir",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "minimal_beli",
                "label" => "Minimal Pembelian",
                "rules" => "trim|required",
                "errors"    => ["required" => '%s tidak boleh kosong!']
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["id_diskon"]      = htmlspecialchars($this->input->post("id_diskon"));
            $data_post["harga_id"]       = htmlspecialchars($this->input->post("id_harga"));
            $data_post["nama_diskon"]    = htmlspecialchars($this->input->post("nama_diskon"));
            $data_post["harga_potongan"] = htmlspecialchars($this->input->post("harga_potongan"));
            $data_post["tgl_mulai"]      = htmlspecialchars($this->input->post("tgl_mulai"));
            $data_post["tgl_akhir"]      = htmlspecialchars($this->input->post("tgl_akhir"));
            $data_post["minimal_beli"]   = htmlspecialchars($this->input->post("minimal_beli"));
            $data_post["user_edit"]      = ($this->session->userdata("nama_user")) ? $this->session->userdata("nama_user") : "sistem";

            if ($this->diskon->ubahData($data_post)) {

                $data_json["status"]            = "berhasil";
                $this->session->set_flashdata("berhasil", "Data Diskon Barang berhasil diubah");
            } else {

                $data_json["status"]            = "gagal";
                $this->session->set_flashdata("gagal", "Data Diskon Barang gagal diubah");
            }
        } else {

            $data_json["status"]                 = "error";
            $data_json["err_toko_id_diskonU"]    = form_error("toko_id");
            $data_json["err_id_harga_diskonU"]   = form_error("id_harga");
            $data_json["err_nama_diskonU"]       = form_error("nama_diskon");
            $data_json["err_harga_potonganU"]    = form_error("harga_potongan");
            $data_json["err_tgl_mulaiU"]         = form_error("tgl_mulai");
            $data_json["err_tgl_akhirU"]         = form_error("tgl_akhir");
            $data_json["err_minimal_beliU"]      = form_error("minimal_beli");
        }

        echo json_encode($data_json);
    }

    public function hapus($id_diskon)
    {

        if ($this->diskon->hapusData(base64_decode($id_diskon))) {

            $this->session->set_flashdata("berhasil", "Data Diskon Barang berhasil dihapus");
        } else {

            $this->session->set_flashdata("gagal", "Data Diskon Barang gagal dihapus");
        }

        redirect("toko/diskon", "refresh");
    }
}
