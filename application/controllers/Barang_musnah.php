<?php

class Barang_musnah extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Barang_musnah_model", "barang_musnah");
    }

    public function index()
    {

        $this->view['title_menu']   = "Barang Cacat";
        $this->view['title']        = "Barang Musnah";
        $this->view['content']      = "barang_musnah/index";

        $this->load->view('layout/wrapper', $this->view);
    }

    public function ajaxBarangMusnah()
    {

        $result = $this->barang_musnah->ambilSemuaBarangMusnah();

        $no = 1;
        $rows = [];
        foreach ($result as $r) {

            $row = [];
            $row[] = $no;
            $row[] = $r["kode_barang"];
            $row[] = $r["nama_barang"];
            $row[] = $r["nama_toko"];
            $row[] = $r["qty_sampah"];
            $row[] = $r["tgl_musnah"];
            $row[] = ($r["bukti_musnah"]) ? "<a href='" . base_url('assets/file_bukti_barang_musnah/' . $r["bukti_musnah"]) . "' class='btn btn-sm btn-info text-white'>Lihat Bukti</a>" : "Tidak ada bukti yang di upload";
            $row[] = ($r["is_proses"] == "barang_toko") ? "Dari Barang Toko" : (($r["is_proses"] == "barang_cacat") ? "Dari Barang Cacat" : "Dari Barang Masuk");
            $row[] = ($r["is_proses"] != "barang_masuk") ? "<a href='" . site_url('barang_cacat/musnah_cacat/hapus/' . $r["is_proses"] . '/' . $r["id_musnah"]) . "' class='btn btn-sm btn-danger text-white'>Batalkan</a>" : "";

            array_push($rows, $row);
        }

        $datajson["draw"] = $this->input->post("draw");
        $datajson["data"] = $rows;
        $datajson["recordsTotal"] = $this->barang_musnah->ambilHitungBarangMusnah();
        $datajson["recordsFiltered"] = $this->barang_musnah->ambilFilterBarangMusnah();

        echo json_encode($datajson);
    }

    public function hapus($status, $id)
    {

        // checker
        $result = $this->barang_musnah->hapus_data($status, $id);

        if ($result === true) {

            $this->session->set_flashdata("message", "Data berhasil di kembalikan");
            redirect("barang_cacat/musnah_cacat");
        } else if ($result === false) {

            $this->session->set_flashdata("message_error", "Data gagal di kembalikan");
            redirect("barang_cacat/musnah_cacat");
        } else {

            $this->session->set_flashdata("message_error", "Data tidak ada!");
            redirect("barang_cacat/musnah_cacat");
        }
    }
}
