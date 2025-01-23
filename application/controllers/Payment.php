<?php

class Payment extends CI_Controller
{

    private $view;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Bank_model", "bank");
        $this->load->model("Payment_model", "payment");
    }

    public function index()
    {
        $this->view["title_menu"]           = "Payment";
        $this->view["title"]                = "Payment";
        $this->view["content"]              = "payment/v_payment_index";
        $this->view["data_payment"]         = $this->payment->ambilSemuaPayment();
        $this->view["data_bank"]            = $this->bank->ambilSemuaBank();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function tambahPayment()
    {
        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "bank_id",
                "label" => "Bank",
                "rules" => "required|trim"
            ],

            [
                "field" => "rekening",
                "label" => "Rekening",
                "rules" => "required|trim|max_length[20]|numeric"
            ],

            [
                "field" => "an_rekening",
                "label" => "Atas nama",
                "rules" => "required|trim|max_length[100]"
            ],

            [
                "field" => "no_kartu",
                "label" => "Nomor kartu",
                "rules" => "trim|max_length[20]|numeric"
            ],

            [
                "field" => "expired_date",
                "label" => "Tanggal expired",
                "rules" => "trim"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["bank_id"]        = htmlspecialchars($this->input->post("bank_id"));
            $data["rekening"]       = htmlspecialchars($this->input->post("rekening"));
            $data["an_rekening"]    = htmlspecialchars($this->input->post("an_rekening"));
            $data["no_kartu"]       = htmlspecialchars($this->input->post("no_kartu"));
            $data["expired_date"]   = htmlspecialchars($this->input->post("expired_date"));

            $res = $this->payment->tambahData($data);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = "Data berhasil di input";

                $this->session->set_flashdata("berhasil", "Data berhasil di input");
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data gagal di input";

                $this->session->set_flashdata("gagal", "Data gagal di input");
            }
        } else {
            $data_json["status"]            =  "error";
            $data_json["err_bank_id"]       = form_error("bank_id");
            $data_json["err_rekening"]      = form_error("rekening");
            $data_json["err_an_rekening"]   = form_error("an_rekening");
            $data_json["err_no_kartu"]      = form_error("no_kartu");
            $data_json["err_expired_date"]  = form_error("expired_date");
        }

        echo json_encode($data_json);
    }

    public function detailPayment()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id",
                "label" => "Id",
                "rules" => "required|trim",
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id = htmlspecialchars($this->input->post("id"));

            $res = $this->payment->ambilPaymentBerdasarkanId($id);

            if ($res) {

                $data_json["status"] = "berhasil";
                $data_json["response"] = $res;
            } else {

                $data_json["status"] = "gagal";
                $data_json["response"] = "Data tidak ada";
            }
        } else {

            $data_json["status"] = "error";
            $data_json["response"] = "Parameter tidak sesuai";
        }

        echo json_encode($data_json);
    }

    public function ubahPayment()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "id_payment",
                "label" => "Id",
                "rules" => "required|trim"
            ],

            [
                "field" => "bank_id",
                "label" => "Bank",
                "rules" => "required|trim"
            ],

            [
                "field" => "rekening",
                "label" => "Rekening",
                "rules" => "required|trim|max_length[20]|numeric"
            ],

            [
                "field" => "an_rekening",
                "label" => "Atas nama",
                "rules" => "required|trim|max_length[100]"
            ],

            [
                "field" => "no_kartu",
                "label" => "Nomor kartu",
                "rules" => "trim|max_length[20]|numeric"
            ],

            [
                "field" => "expired_date",
                "label" => "Tanggal expired",
                "rules" => "trim"
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["id"]             = htmlspecialchars($this->input->post("id_payment"));
            $data["bank_id"]        = htmlspecialchars($this->input->post("bank_id"));
            $data["rekening"]       = htmlspecialchars($this->input->post("rekening"));
            $data["an_rekening"]    = htmlspecialchars($this->input->post("an_rekening"));
            $data["no_kartu"]       = htmlspecialchars($this->input->post("no_kartu"));
            $data["expired_date"]   = htmlspecialchars($this->input->post("expired_date"));

            $res = $this->payment->ubahData($data);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = "Data berhasil di update";

                $this->session->set_flashdata("berhasil", "Data berhasil di update");
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data gagal di update";

                $this->session->set_flashdata("gagal", "Data gagal di update");
            }
        } else {

            $data_json["status"]            =  "error";
            $data_json["err_bank_idU"]       = form_error("bank_id");
            $data_json["err_rekeningU"]      = form_error("rekening");
            $data_json["err_an_rekeningU"]   = form_error("an_rekening");
            $data_json["err_no_kartuU"]      = form_error("no_kartu");
            $data_json["err_expired_dateU"]  = form_error("expired_date");
        }

        echo json_encode($data_json);
    }

    public function hapusPayment($id)
    {
        $id = $this->secure->decrypt_url($id);

        $resultDelete = $this->payment->hapusData($id);

        if ($resultDelete) {

            $this->session->set_flashdata("berhasil", "Data berhasil di hapus");
        } else {

            $this->session->set_flashdata("gagal", "Data gagal di hapus");
        }

        redirect("setting/payment");
    }
}
