<?php

class Barang_Keluar extends CI_Controller
{

    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("User_model", "user");
        $this->load->model("Role_model", "role");
        $this->load->model("Menu_model", "menu");
        $this->load->model("Request_model", "request");
        $this->load->model("Barang_model", "barang");
        $this->load->model("Harga_model", "harga");
        $this->load->model("Barang_keluar_model", "barang_keluar");
        $this->load->model("Karyawan_model", "karyawan");
        $this->load->model("Toko_model", "toko");
    }

    public function index()
    {

        $this->view["title_menu"]   = "Barang";
        $this->view["title"]        = "Barang Keluar";
        $this->view["content"]      = "barang_keluar/v_barang_keluar_index";

        if ($this->session->userdata("toko_id")) {

            $id_toko = $this->session->userdata("toko_id");
            $this->view["data_request"]        = $this->request->ambilSemuaRequestToko($id_toko);
            $this->view["data_barang"]         = $this->barang->getHargaBarangToko($id_toko);
        } else {

            $this->view["data_request"]        = $this->request->ambilSemuaRequest();
            $this->view["data_barang"]         = $this->barang->getHargaBarangTokoAll();
        }

        $this->view["data_user"]    = $this->user->ambilSemuaUserNotInKaryawantoko();
        $this->view['toko']         = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaBarangKeluar()
    {

        $newArr = [];
        $data   = $this->barang_keluar->ajaxAmbilSemuaBarangKeluar();

        $no = 1;
        foreach ($data as $d) {

            $rsb = "  
                        <a onclick='lihat_keluar(" . $d['id_barang_keluar'] . ")'>
                            <button type='button' class='btn btn-sm btn-info'>
                              <span class='tf-icons bx bx-show-alt'></span>&nbsp; Lihat
                            </button>
                        </a>
                     ";


            $row = [];
            $row[] = $no;
            $row[] = $d["nama_barang"];
            $row[] = $d["nama_toko"];
            $row[] = $d["jenis_keluar"];
            $row[] = $d["jml_keluar"];
            $row[] = ($d["is_rollback"] == 0) ? "<small class='text-success'>Sukses</small>" : "<small class='text-danger'>Batal</small>";
            $row[] = $rsb;

            array_push($newArr, $row);
            $no++;
        }

        $data_json["draw"]              = $this->input->post("draw");
        $data_json["data"]              = $newArr;
        $data_json["recordsTotal"]      = $this->barang_keluar->ajaxAmbilHitungSemuaBarangKeluar();
        $data_json["recordsFiltered"]   = $this->barang_keluar->ajaxAmbilFilterSemuaBarangKeluar();

        echo json_encode($data_json);
    }

    public function tambah()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "harga_id",
                "label" => "Barang Toko",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
            [
                "field" => "jml_keluar",
                "label" => "Jumlah Keluar",
                "rules" => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["request_id"]     = htmlspecialchars($this->input->post("request_id"));
            $data_post["harga_id"]       = htmlspecialchars($this->input->post("harga_id"));
            $data_post["jml_keluar"]     = htmlspecialchars($this->input->post("jml_keluar"));
            $data_post["bukti_keluar"]   = htmlspecialchars($this->input->post("bukti_keluar"));
            $data_post["jenis_keluar"]   = 'DISTRIBUSI';
            $data_post["user_input"]     = ($this->session->userdata("nama_user")) ? $this->session->userdata("nama_user") : "sistem";
            $data_post["created_at"]     = date("Y-m-d H:i:s");

            $barang_toko = $this->barang->getBarangHargaTokoRow($data_post["harga_id"]);

            $data_update['id_harga'] = $barang_toko['id_harga'];
            $data_update['stok_toko'] = $barang_toko['stok_toko'] - $data_post["jml_keluar"];

            if ($this->barang_keluar->tambahData($data_post)) {

                $data_json["status"] = "berhasil";
                $this->session->set_flashdata("berhasil", "Data Barang Keluar berhasil ditambahkan");

                if ($this->harga->ubahData($data_update)) {

                    $data_json["status1"] = "berhasil";
                } else {

                    $data_json["status1"] = "gagal";
                    $this->session->set_flashdata("gagal", "Data Stok Barang gagal diubah");
                }
            } else {

                $data_json["status"] = "gagal";
                $this->session->set_flashdata("gagal", "Data Barang Keluar gagal ditambahkan");
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_request_id"]        = form_error("request_id");
            $data_json["err_id_harga_keluar"]   = form_error("harga_id");
            $data_json["err_jml_keluar"]        = form_error("jml_keluar");
            $data_json["err_bukti_keluar"]      = form_error("bukti_keluar");
        }

        echo json_encode($data_json);
    }

    public function dt_barang_toko()
    {
        $id_harga   = $this->input->post('id_harga');

        $data_barang_toko = $this->harga->getBarangHargaById($id_harga);

        $array = array('satuan' => $data_barang_toko['satuan']);

        echo json_encode($array);
    }

    public function lihat_keluarmodel($id_barang_keluar)
    {
        $where = array('id_barang_keluar' => base64_decode($id_barang_keluar));

        $this->view["data_barang_keluar"] = $this->barang_keluar->ambilDataBarangKeluar($where);

        $this->load->view("barang_keluar/v_lihat_Modal", $this->view);
    }

    public function ubah_keluarmodel($id_barang_keluar)
    {
        $where = array('id_barang_keluar' => base64_decode($id_barang_keluar));

        $this->view["data_barang_keluar"] = $this->barang_keluar->ambilDataBarangKeluar($where);

        $this->load->view("barang_keluar/v_ubah_Modal", $this->view);
    }

    public function ubah()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field"     => "jml_keluar",
                "label"     => "Jumlah Keluar",
                "rules"     => "required|trim",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["id_barang_keluar"]  = htmlspecialchars($this->input->post("id_barang_keluar"));
            $data_post["jml_keluar"]        = htmlspecialchars($this->input->post("jml_keluar"));
            $data_post["user_edit"]         = ($this->session->userdata("nama_user")) ? $this->session->userdata("nama_user") : "sistem";

            if ($this->barang_keluar->ubahData($data_post)) {

                $data_json["status"]            = "berhasil";
                $this->session->set_flashdata("berhasil", "Data Barang Keluar berhasil diubah");
            } else {

                $data_json["status"]            = "gagal";
                $this->session->set_flashdata("gagal", "Data Barang Keluar gagal diubah");
            }
        } else {

            $data_json["status"]                 = "error";
            $data_json["err_jml_keluarU"]        = form_error("jml_keluar");
        }

        echo json_encode($data_json);
    }
}
