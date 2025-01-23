<?php

class Payment_model extends CI_Model
{

    public function ambilSemuaPayment()
    {
        $this->db->select("tbl_payment.id_payment, tbl_payment.bank_id, tbl_payment.rekening, tbl_payment.an_rekening, tbl_payment.no_kartu, tbl_payment.rekening, tbl_payment.expired_date, tbl_bank.bank");
        $this->db->from("tbl_payment");
        $this->db->join("tbl_bank", "tbl_payment.bank_id = tbl_bank.id_bank", "inner");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_payment", $data);
        return $this->db->affected_rows();
    }

    public function ambilPaymentBerdasarkanId($id)
    {

        $query = $this->db->get_where("tbl_payment", ["id_payment"  => $id]);

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function ambilSemuaPaymentBerdasarkanBankId($id)
    {

        return $this->db->get_where("tbl_payment", ["bank_id"  => $id])->result_array();
    }

    public function ubahData($data)
    {

        $this->db->where("id_payment", $data["id"]);
        unset($data["id"]);
        $this->db->update("tbl_payment", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id)
    {

        $this->db->where("id_payment", $id);
        $query = $this->db->get("tbl_payment");

        if ($query->num_rows() > 0) {

            $this->db->where("id_payment", $id);
            $this->db->delete("tbl_payment");
            return $this->db->affected_rows();
        }

        return 0;
    }
}
