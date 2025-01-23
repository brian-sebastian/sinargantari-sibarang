<?php

class Request_model extends CI_Model
{

    public function ambilTokoRequestBarangKeToko($tokoId)
    {
        $this->db->select("tbl_request_barang.id_request, tbl_request_barang.kode_request, tbl_toko.nama_toko, tbl_request_barang.atribut_barang, tbl_request_barang.pengirim, tbl_request_barang.status, tbl_request_barang.created_at, tbl_request_barang.updated_at");
        $this->db->from("tbl_request_barang");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_request_barang.request_toko_id");
        $this->db->where("tbl_request_barang.penerima_toko_id", $tokoId);
        $this->db->order_by("tbl_request_barang.created_at", "ASC");
        $this->db->order_by("tbl_request_barang.status", "ASC");
        return $this->db->get()->result_array();
    }

    public function ambilSemuaRequestBarangKeToko()
    {
        $this->db->select("tbl_request_barang.id_request, tbl_request_barang.kode_request, tbl_toko.nama_toko, tbl_request_barang.atribut_barang, tbl_request_barang.pengirim, tbl_request_barang.status, tbl_request_barang.created_at, tbl_request_barang.updated_at");
        $this->db->from("tbl_request_barang");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_request_barang.request_toko_id");

        if ($this->input->get("toko_id")) {
            $toko_id = $this->secure->decrypt_url($this->input->get("toko_id"));
            $this->db->where("request_toko_id", $toko_id);
        }

        $this->db->order_by("tbl_request_barang.created_at", "ASC");
        $this->db->order_by("tbl_request_barang.status", "ASC");
        return $this->db->get()->result_array();
    }

    public function ambilTokoRequestBarangDariPenerimaToko($tokoId)
    {
        $this->db->select("tbl_request_barang.id_request, tbl_request_barang.kode_request, tbl_toko.nama_toko, tbl_request_barang.atribut_barang, tbl_request_barang.pengirim, tbl_request_barang.status, tbl_request_barang.created_at, tbl_request_barang.updated_at");
        $this->db->from("tbl_request_barang");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_request_barang.penerima_toko_id");
        $this->db->where("tbl_request_barang.request_toko_id", $tokoId);
        $this->db->order_by("tbl_request_barang.created_at", "ASC");
        $this->db->order_by("tbl_request_barang.status", "ASC");
        return $this->db->get()->result_array();
    }

    public function ambilSemuaRequestBarangDariPenerimaToko()
    {
        $this->db->select("tbl_request_barang.id_request, tbl_request_barang.kode_request, tbl_toko.nama_toko, tbl_request_barang.atribut_barang, tbl_request_barang.pengirim, tbl_request_barang.status, tbl_request_barang.created_at, tbl_request_barang.updated_at");
        $this->db->from("tbl_request_barang");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_request_barang.penerima_toko_id");
        if ($this->input->get("toko_id")) {
            $toko_id = $this->secure->decrypt_url($this->input->get("toko_id"));
            $this->db->where("request_toko_id", $toko_id);
        }
        $this->db->order_by("tbl_request_barang.created_at", "ASC");
        $this->db->order_by("tbl_request_barang.status", "ASC");
        return $this->db->get()->result_array();
    }

    public function getKodeRequest()
    {
        return $this->db->query("SELECT MAX(RIGHT(tbl_request_barang.kode_request,6)) AS kode_request FROM tbl_request_barang");
    }

    public function ambilSemuaRequestToko($id_toko)
    {
        $this->db->select('*')
            ->from('tbl_request_barang')
            ->join('tbl_toko', 'tbl_toko.id_toko = tbl_request_barang.request_toko_id')
            ->where('tbl_request_barang.request_toko_id', $id_toko);
        return $this->db->get()->result_array();
    }

    public function ambilSemuaRequest()
    {
        $this->db->select('*')
            ->from('tbl_request_barang');
        return $this->db->get()->result_array();
    }

    public function ambilRequestBerdasarkanId($id_request)
    {
        $this->db->select("tbl_request_barang.id_request, tbl_request_barang.kode_request, tbl_request_barang.request_toko_id, tbl_request_barang.penerima_toko_id, tbl_request_barang.atribut_barang, tbl_request_barang.pengirim, tbl_request_barang.created_at, tbl_request_barang.status");
        $this->db->from("tbl_request_barang");
        $this->db->where("id_request", $id_request);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function accRequestBarang($data, $request_id)
    {
        if (count($data) > 0) {

            $data_masuk = array_map(function ($elemen) {

                $arrnew = [

                    "harga_id"      => $elemen["id_harga_request"],
                    "jml_masuk"     => intval($elemen["qtyRequest"]),
                    "tipe"          => "antar_toko",
                    "user_input"    => $this->session->userdata("nama_user"),
                    "created_at"    => date("Y-m-d H:i:s")
                ];

                return $arrnew;
            }, $data);

            $data_keluar = array_map(function ($elemen) {

                $arrnew = [

                    "toko_id"       => $elemen["penerima_toko_id"],
                    "harga_id"      => $elemen["id_harga_penerima"],
                    "jenis_keluar"  => "DISTRIBUSI",
                    "request_id"    => $elemen["id_request"],
                    "jml_keluar"    => intval($elemen["qtyRequest"]),
                    "user_input"    => $this->session->userdata("nama_user"),
                    "created_at"    => date("Y-m-d H:i:s")
                ];

                return $arrnew;
            }, $data);

            $data_update_penerima = array_map(function ($elemen) {

                $arrnew = [
                    "id_harga"      => $elemen["id_harga_penerima"],
                    "stok_toko"     => intval($elemen["realStok"]),
                    "user_edit"     => $this->session->userdata("nama_user"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ];

                return $arrnew;
            }, $data);

            $data_update_request = array_map(function ($elemen) {

                $arrnew = [
                    "id_harga"      => $elemen["id_harga_request"],
                    "stok_toko"     => intval($elemen["qtyRequest"]) + intval(getStokByIdHarga($elemen["id_harga_request"])),
                    "user_edit"     => $this->session->userdata("nama_user"),
                    "updated_at"    => date("Y-m-d H:i:s")
                ];

                return $arrnew;
            }, $data);

            $json_request = array_map(function ($elemen) {

                $arrnew = [

                    "id_barang"     => $elemen["id_barang"],
                    "nama_barang"   => $elemen["nama_barang"],
                    "qty_request"   => $elemen["qtyRequest"],
                    "ket"           => $elemen["ket"]

                ];

                return $arrnew;
            }, $data);

            $this->db->trans_begin();

            // insert barang masuk 
            $this->db->insert_batch("tbl_barang_masuk", $data_masuk);
            // insert barang keluar 
            $this->db->insert_batch("tbl_barang_keluar", $data_keluar);
            // update stok toko by penerima
            $this->db->update_batch("tbl_harga", $data_update_penerima, "id_harga");
            // update stok toko by request.
            $this->db->update_batch("tbl_harga", $data_update_request, "id_harga");
            // update barang request. 
            $this->db->set("atribut_barang", json_encode($json_request));
            $this->db->set("status", "accepted");
            $this->db->where("id_request", $request_id);
            $this->db->update("tbl_request_barang");

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                return 0;
            } else {

                $this->db->trans_commit();
                return 1;
            }
        } else {

            $this->db->set("atribut_barang", "[]");
            $this->db->set("status", "decline");
            $this->db->where("id_request", $request_id);
            $this->db->update("tbl_request_barang");
            return $this->db->affected_rows();
        }
    }

    public function saveAndCreateRequest($data)
    {
        $this->db->insert('tbl_request_barang', $data);
        return $this->db->affected_rows();
    }

    public function deleteRequest($id_request)
    {
        $this->db->where('id_request', $id_request);
        $this->db->delete('tbl_request_barang');
        return $this->db->affected_rows();
    }
}
