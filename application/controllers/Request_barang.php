<?php

/**
 * @property session $session
 * @property Request_model $request
 * @property Toko_model $toko
 * @property User_model $user
 * @property Role_model $role
 * @property Menu_model $menu
 * @property Barang_model $barang
 * @property form_validation $form_validation
 * @property input $input
 */
class Request_barang extends CI_Controller
{

    private $view;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Toko_model", "toko");
        $this->load->model("Request_model", "request");
        $this->load->model("User_model", "user");
        $this->load->model("Role_model", "role");
        $this->load->model("Menu_model", "menu");
        $this->load->model("Barang_model", "barang");
        cek_login();
    }

    public function index()
    {
        $this->view["title_menu"]    = "Barang";
        $this->view["title"]         = "Request Barang";
        $this->view["content"]       = "request_barang/v_requestbarang_index";

        $id_toko = $this->session->userdata('toko_id');

        if ($this->session->userdata("toko_id")) {
            $this->view["data_toko"]             = $this->toko->findByIdToko($id_toko);
            $this->view["data_perequest"]        = $this->request->ambilTokoRequestBarangKeToko($id_toko);
            $this->view["data_penerima_request"] = $this->request->ambilTokoRequestBarangDariPenerimaToko($id_toko);
        } else {

            $this->view["data_toko"]             = $this->toko->ambilSemuaToko();
            $this->view["data_perequest"]        = $this->request->ambilSemuaRequestBarangKeToko();
            $this->view["data_penerima_request"] = $this->request->ambilSemuaRequestBarangDariPenerimaToko();
        }

        $this->view["data_user"]    = $this->user->ambilSemuaUserNotInKaryawantoko();
        $this->view['toko']         = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function accDetailRequest()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "id",
                "label" => "Request",
                "rules" => "required|trim"
            ]
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() == true) {

            $id = htmlspecialchars($this->input->post("id"));

            $res = $this->request->ambilRequestBerdasarkanId($id);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = $this->load->view("request_barang/v_modal_acc_barang", ["data" => $res], true);
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data tidak ada";
            }
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = "Parameter tidak sesuai";
        }

        echo json_encode($data_json);
    }

    public function accSubmitRequest()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_request",
                "label" => "Request",
                "rules" => "required|trim"
            ],
            [
                "field" => "penerima_toko_id",
                "label" => "Penerima toko",
                "rules" => "required|trim"
            ],
            [
                "field" => "request_toko_id",
                "label" => "Request toko",
                "rules" => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $err                = 0;
            $arr_err            = [];
            $arr_fix            = [];

            $qtyRequest         = $this->input->post("qty_request");
            $id_barang          = $this->input->post("id_barang");
            $id_harga_penerima  = $this->input->post("id_harga_penerima");
            $id_harga_request   = $this->input->post("id_harga_request");
            $nama_barang        = $this->input->post("nama_barang");
            $ket                = $this->input->post("ket");

            if (count($id_barang) > 0) {

                for ($i = 0; $i < count($id_barang); $i++) {

                    $stok = getStokByIdBarangDanTokoId($id_barang[$i], htmlspecialchars($this->input->post("penerima_toko_id")));

                    if (($qtyRequest[$i] <= $stok) && ($stok > 0)) {

                        // true
                        array_push($arr_fix, [
                            "id_harga_penerima" => $id_harga_penerima[$i],
                            "id_harga_request"  => $id_harga_request[$i],
                            "id_request"        => htmlspecialchars($this->input->post('id_request')),
                            "id_barang"         => $id_barang[$i],
                            "nama_barang"       => $nama_barang[$i],
                            "ket"               => $ket[$i],
                            "request_toko_id"   => htmlspecialchars($this->input->post('request_toko_id')),
                            "penerima_toko_id"  => htmlspecialchars($this->input->post('penerima_toko_id')),
                            "qtyRequest"        => $qtyRequest[$i],
                            "realStok"          => $stok - $qtyRequest[$i],
                        ]);
                    } else {

                        // false
                        array_push($arr_err, ["id_barang" => $id_barang[$i], "stok" => $stok, "pesan" => "Stok tidak cukup"]);
                        $err++;
                    }
                }
            }

            if ($err) {

                // return error
                $data_json["status"]            = "error";
                $data_json["err_barang"]        = json_encode($arr_err);
            } else {

                // do insert data
                $res = $this->request->accRequestBarang($arr_fix, htmlspecialchars($this->input->post('id_request')));

                if ($res) {

                    $data_json["status"]        = "berhasil";
                    $data_json["response"]      = "Data berhasil di eksekusi";
                } else {

                    $data_json["status"]        = "gagal";
                    $data_json["response"]      = "Data gagal di eksekusi";
                }
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_id_request"]        = form_error("id_request");
            $data_json["err_penerima_toko_id"]  = form_error("penerima_toko_id");
            $data_json["err_request_toko_id"]   = form_error("request_toko_id");
        }

        echo json_encode($data_json);
    }

    public function checkRequestBarang()
    {
        $kode_request = '';
        $kode_request_result = '';
        $resultCheck = request_barang_helper::checkRequestCode();

        if ($resultCheck == null || empty($resultCheck) || $resultCheck == '') {
            $kode_request_result = "REQ-" . date('Ym') . "000001";
        } else {
            $kode_request = $resultCheck['kode_request'];
            $getCode = $this->generateKodeRequest($kode_request);
            $kode_request_result = $getCode;
        }

        $toko_id_session = $this->session->userdata('toko_id');
        $toko_id_url = $this->input->get('toko_id');
        $toko_id_url_decrypt = '';
        if ($toko_id_session) {
            $getDetailToko = $this->toko->findByIdToko($toko_id_session);
            $getListNotCurrentToko = $this->toko->getTokoNotInCurrent($toko_id_session);
            $currentTokoId = $toko_id_session;
        } else {
            $toko_id_url_decrypt = $this->secure->decrypt_url($toko_id_url);
            $getDetailToko = $this->toko->findByIdToko($toko_id_url_decrypt);
            $getListNotCurrentToko = $this->toko->getTokoNotInCurrent($toko_id_url_decrypt);
            $currentTokoId = $toko_id_url_decrypt;
        }

        $dataResult = [
            'kode_request' => $kode_request_result,
            'request_toko_id' => $currentTokoId,
            'pengirim' => $getDetailToko['nama_toko'],
            'list_penerima_toko_id' => $getListNotCurrentToko
        ];

        echo json_encode($dataResult);
    }
    public function generateKodeRequest($lastKodeRequest)
    {
        if (empty($lastKodeRequest)) {
            return "REQ-" . date('Ym') . "000001";
        } else {
            $lastNumber = (int)substr($lastKodeRequest, -6);
            $nextNumber = $lastNumber + 1;
            $nextKodeRequest = "REQ-" . date('Ym') . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            return $nextKodeRequest;
        }
    }

    public function getBarangTokoSelectAjax()
    {
        $toko_id = $this->input->post('toko_id');
        $getBarangToko = $this->barang->getBarangHargaToko($toko_id);
        $err_code = 0;
        $message = "";
        $data = "";

        if (count($getBarangToko) == 0) {
            $err_code++;
            $message = "Maaf Barang Toko Kosong";
            $data = "";
        } else {
            $err_code = 0;
            $message = "Gotcha";
            $data = $getBarangToko;
        }
        $dataResult = [
            'err_code' => $err_code,
            'message' => $message,
            'list_barang_toko' => $data,
        ];

        echo json_encode($dataResult);
    }

    public function getSelectedDetailBarang()
    {
        $err_code = 0;
        $message = '';
        $data_barang = '';
        $id_barang = $this->input->post('id_barang');

        $getDetailBarang = $this->barang->getFindById($id_barang);

        if ($getDetailBarang) {
            $err_code = 0;
            $message = "Gotcha";
            $data_barang = $getDetailBarang;
        } else {
            $err_code++;
            $message = "Tidak ditemukan";
            $data_barang = '';
        }

        $resultData = [
            'err_code' => $err_code,
            'message' => $message,
            'data_barang' => $data_barang,
        ];
        echo json_encode($resultData);
    }


    public function saveAndAddRequest()
    {
        date_default_timezone_set('Asia/Jakarta');
        $err_code = 0;
        $message = '';
        $this->form_validation->set_rules(
            'kode_request',
            'Kode Request',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'request_toko_id',
            'Request Toko',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'penerima_toko_id',
            'Penerima Toko',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'pengirim',
            'Pengirim',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'atribut_barang',
            'Atribute',
            'required|max_length[2000]'
        );

        if ($this->form_validation->run() == false) {
            $err_code++;
            $message = validation_errors();
        } else {
            $dataPost = $this->input->post();
            $kode_request = $dataPost['kode_request'];
            $request_toko_id = $dataPost['request_toko_id'];
            $penerima_toko_id = $dataPost['penerima_toko_id'];
            $pengirim = $dataPost['pengirim'];
            $atribut_barang = $dataPost['atribut_barang'];
            $status = STATUS_REQUEST_DRAFT;
            $user_input = $this->session->userdata('nama_user');
            $created_at = date('Y-m-d H:i:s');

            $dataSave = [
                'kode_request' => $kode_request,
                'request_toko_id' => $request_toko_id,
                'penerima_toko_id' => $penerima_toko_id,
                'pengirim' => $pengirim,
                'atribut_barang' => $atribut_barang,
                'status' => $status,
                'user_input' => $user_input,
                'created_at' => $created_at,
            ];
            $resultSaveData = $this->request->saveAndCreateRequest($dataSave);

            if ($resultSaveData) {
                $err_code = 0;
                $message = "Berhasil Buat Request";
            } else {
                $err_code++;
                $message = "Gagal Save dan Buat Request";
            }
        }

        $showResultData = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        echo json_encode($showResultData);
    }

    public function deleteRequest()
    {
        $id_request = $this->input->post('id_request');

        $err_code = 0;
        $message = '';

        $deleteRequest = $this->request->deleteRequest($id_request);
        if ($deleteRequest) {
            $err_code = 0;
            $message = "Berhasil Hapus Data";
        } else {
            $err_code++;
            $message = "Gagal Hapus Data";
        }
        $dataResult = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        echo json_encode($dataResult);
    }
}
