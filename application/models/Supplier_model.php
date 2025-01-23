<?php

class Supplier_model extends CI_Model
{


    public function getSupplier()
    {
        $this->db->select('tbl_supplier.id_supplier, tbl_supplier.nama_supplier, tbl_supplier.no_telpon_supplier, tbl_supplier.alamat_supplier');
        $this->db->from('tbl_supplier');
        $this->db->where('tbl_supplier.is_active', 1);
    }



    public function getAllSupplier()
    {
        $this->getSupplier();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function hitungTotalSupplier()
    {
        $this->getSupplier();
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->count_all_results();
    }

    public function hitungFilterSupplier()
    {
        $this->getSupplier();
        return $this->db->get()->num_rows();
    }

    public function findById($id)
    {
        $query =  $this->db->get_where('tbl_supplier', ['id_supplier' => $id]);

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return 0;
    }

    public function tambah_data($data)
    {
        $this->db->insert('tbl_supplier', $data);
        return $this->db->affected_rows();
    }

    public function edit_data($data)
    {
        $this->db->where('id_supplier', base64_decode($data['id_supplier']));
        unset($data['id_supplier']);
        $this->db->update('tbl_supplier', $data);
        return $this->db->affected_rows();
    }

    public function hapus_data($id)
    {
        $this->db->where('id_supplier', $id);
        $this->db->delete('tbl_supplier');
    }
}
