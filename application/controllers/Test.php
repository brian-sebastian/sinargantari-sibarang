<?php

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = "Management Toko";
        $data['title_menu'] = "Toko";
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('test/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function search()
    {
        $term = $this->input->post('term');
        $data['results'] = $this->getSearchData($term);
        echo json_encode($data);
    }
    public function searchIsset()
    {
        if (isset($_GET['term'])) {
            $result = $this->getSearchDataLimit($_GET['term']);
            if (count($result) > 0) {
                foreach ($result as $row)
                    $arr_result[] = $row->barcode_barang;
                echo json_encode($arr_result);
            }
        }
    }
    public function searchIssetMulti()
    {
        $result = $this->getSearchDataNoLimit($_GET['term']);
        // $result = $this->getSearchDataLimit($_GET['term']);

        $data = array();
        foreach ($result as $row) {
            $data[] = array(
                'label' => $row->nama_barang . " | " . $row->barcode_barang, // replace with the actual column name
                'value' => $row->barcode_barang, // replace with the actual column name
                // add other properties if needed
            );
        }

        echo json_encode($data);
    }

    public function getSearchDataNoLimit($term)
    {
        // Sesuaikan dengan struktur tabel dan kolom di database Anda
        $this->db->like('barcode_barang', $term, 'both');
        $this->db->order_by('barcode_barang', 'ASC');
        $query = $this->db->get('tbl_barang');
        return $query->result();
    }
    public function getSearchDataLimit($term)
    {
        // Sesuaikan dengan struktur tabel dan kolom di database Anda
        $this->db->like('barcode_barang', $term, 'both');
        $this->db->order_by('barcode_barang', 'ASC');
        $this->db->limit(10);
        $query = $this->db->get('tbl_barang');
        return $query->result();
    }


    public function getSearchData($term)
    {
        // Sesuaikan dengan struktur tabel dan kolom di database Anda
        $this->db->like('barcode_barang', $term);
        $query = $this->db->get('tbl_barang');
        return $query->result_array();
    }
}
