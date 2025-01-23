<?php

class Bank extends CI_Controller
{

    private $view;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Bank_model", "bank");
    }

    public function index()
    {
        $this->view["title_menu"]           = "Setting";
        $this->view["title"]                = "Bank";
        $this->view["content"]              = "bank/v_bank_index";
        $this->view["data_bank"]            = $this->bank->ambilSemuaBank();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function tambahBank()
    {
        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "bank",
                "label" => "Bank",
                "rules" => "required|trim|max_length[100]"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["bank"]   = htmlspecialchars($this->input->post("bank"));

            $res = $this->bank->tambahData($data);

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
            $data_json["status"]    =  "error";
            $data_json["err_bank"]  = form_error("bank");
        }

        echo json_encode($data_json);
    }

    public function detailBank()
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

            $res = $this->bank->ambilBankBerdasarkanId($id);

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

    public function ubahBank()
    {

        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "id_bank",
                "label" => "Id",
                "rules" => "required|trim"
            ],

            [
                "field" => "bank",
                "label" => "Bank",
                "rules" => "required|trim"
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["id"]     = htmlspecialchars($this->input->post("id_bank"));
            $data["bank"]   = htmlspecialchars($this->input->post("bank"));

            $res = $this->bank->ubahData($data);

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

            $data_json["status"]    =  "error";
            $data_json["err_bankU"]  = form_error("bank");
        }

        echo json_encode($data_json);
    }

    public function hapusBank($id)
    {
        $id = $this->secure->decrypt_url($id);

        $result = checkRelationTable("tbl_payment", "bank_id", $id);

        if ($result["err_code"]) {

            $this->session->set_flashdata("gagal", $result["message"]);
            redirect("bank");
        }

        $resultDelete = $this->bank->hapusData($id);

        if ($resultDelete) {

            $this->session->set_flashdata("berhasil", "Data berhasil di hapus");
        } else {

            $this->session->set_flashdata("gagal", "Data gagal di hapus");
        }

        redirect("setting/bank");
    }
}
