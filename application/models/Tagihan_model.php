<?php

class Tagihan_model extends CI_Model
{
    public function ambilInvoice($kode_order)
    {
        $this->db->select("tbl_order.kode_order, tbl_order.nama_cust, tbl_order.hp_cust, tbl_order.alamat_cust, tbl_order.tipe_pengiriman, 
                        tbl_order.biaya_kirim, tbl_order.bukti_kirim, tbl_order.total_order, tbl_order.paidst, tbl_order.status,  tbl_order.created_at,
                        tbl_order_detail.qty, tbl_order_detail.harga_total, tbl_order_detail.harga_potongan, tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_satuan.satuan")
            ->from("tbl_order")
            ->join("tbl_order_detail", "tbl_order.id_order = tbl_order_detail.order_id", "inner")
            ->join("tbl_harga", "tbl_order_detail.harga_id = tbl_harga.id_harga", "inner")
            ->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner")
            ->join("tbl_satuan", "tbl_barang.satuan_id = tbl_satuan.id_satuan", "inner")
            ->where("tbl_order.is_active", 1)
            ->where("tbl_order.kode_order", $kode_order);
        $query = $this->db->get();
        return $query->result_array();
    }
}
