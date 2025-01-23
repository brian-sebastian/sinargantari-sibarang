<?php

class Toko_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('indonesianumber');
        $this->load->library('datelib');
    }

    public function ambilSemuaToko()
    {
        return $this->db->get_where("tbl_toko", ["is_active" => 1])->result_array();
    }

    public function findByIdToko($id_toko)
    {
        return $this->db->get_where("tbl_toko", ["is_active" => 1, "id_toko" => $id_toko])->row_array();
    }

    public function ambilSemuaTokoNotMarketPlace()
    {
        $this->db->where('jenis <>', 'MARKETPLACE');
        $this->db->where('is_active', 1);
        return $this->db->get("tbl_toko")->result_array();
    }

    public function getTokoNotInCurrent($id)
    {
        $this->db->where('id_toko <>', $id);
        $this->db->where('is_active', 1);
        return $this->db->get("tbl_toko")->result_array();
    }


    public function ambilTokoKecualiIdToko($id_toko)
    {
        return $this->db->get_where("tbl_toko", ["is_active" => 1, "id_toko !=" => $id_toko])->result_array();
    }

    public function tambahToko()
    {

        $nama_toko = htmlspecialchars($this->input->post('nama_toko'));
        $alamat_toko = htmlspecialchars($this->input->post('alamat_toko'));
        $notelp_toko = htmlspecialchars($this->input->post('notelp_toko'));
        $convertToFormatIndonesia = $this->indonesianumber->changeFormatIndonesiaNumberPhone($notelp_toko);
        $jenis = htmlspecialchars($this->input->post('jenis'));

        $data = [
            'nama_toko' => $nama_toko,
            'alamat_toko' => $alamat_toko,
            'notelp_toko' => $convertToFormatIndonesia,
            'jenis' => $jenis,
            'is_active' => 1,
            'user_input' => $this->session->userdata('nama_user'),
            'created_at' => $this->datelib->asiaJakartaDate(),
        ];
        $this->db->insert('tbl_toko', $data);
        return $this->db->affected_rows();
    }

    public function ubahToko()
    {
        $id_toko = htmlspecialchars($this->input->post('id_toko'));
        $nama_toko = htmlspecialchars($this->input->post('nama_toko'));
        $alamat_toko = htmlspecialchars($this->input->post('alamat_toko'));
        $notelp_toko = htmlspecialchars($this->input->post('notelp_toko'));
        $convertToFormatIndonesia = $this->indonesianumber->changeFormatIndonesiaNumberPhone($notelp_toko);
        $jenis = htmlspecialchars($this->input->post('jenis'));

        $data = [
            'nama_toko' => $nama_toko,
            'alamat_toko' => $alamat_toko,
            'notelp_toko' => $convertToFormatIndonesia,
            'jenis' => $jenis,
            'is_active' => 1,
            'user_input' => $this->session->userdata('nama'),
            'created_at' => $this->datelib->asiaJakartaDate(),
        ];
        $this->db->where('id_toko', $id_toko);
        $this->db->update('tbl_toko', $data);
        return $this->db->affected_rows();
    }

    public function hapusToko()
    {
        $id_toko = htmlspecialchars($this->input->post('id_toko'));
        $is_active  = 0;
        $this->db->set('is_active', $is_active);
        $this->db->where('id_toko', $id_toko);
        $this->db->update('tbl_toko');
        return $this->db->affected_rows();
    }

    public function ambilDetailToko($toko_id)
    {
        $this->db->select("nama_toko, jenis");
        $this->db->where("id_toko", $toko_id);
        $this->db->where("is_active", 1);

        $query = $this->db->get("tbl_toko");

        if ($query->num_rows() === 1) {

            return $query->row_array();
        }

        return 0;
    }
    public function getGudangToko()
    {
        return $this->db->get_where('tbl_toko', ['is_active' => 1, 'jenis' => 'GUDANG'])->result_array();
    }
}
