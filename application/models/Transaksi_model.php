<?php

class Transaksi_model extends CI_Model
{
    public function getAllTransaksi()
    {
        $this->db->select('*, tbl_order.kode_order');
        $this->db->from('tbl_transaksi');
        $this->db->join('tbl_order', 'tbl_order.id_order=tbl_transaksi.order_id');
        return $this->db->get()->result_array();
    }

    public function getFindTanggal($tgl1, $tgl2)
    {
        $this->db->select('*, tbl_transaksi.created_at, tbl_order.kode_order');
        $this->db->from('tbl_transaksi');
        $this->db->join('tbl_order', 'tbl_order.id_order=tbl_transaksi.order_id');
        $this->db->where('tbl_transaksi.created_at BETWEEN "' . $tgl1 . '" AND "' . $tgl2 . '"');

        return $this->db->get()->result_array();
    }

    public function getTotalTransaksi($tgl1, $tgl2)
    {
        $this->db->select('*, tbl_order.kode_order');
        $this->db->from('tbl_transaksi');
        $this->db->join('tbl_order', 'tbl_order.id_order=tbl_transaksi.order_id');
        $this->db->where('tbl_transaksi.created_at BETWEEN "' . $tgl1 . '" AND "' . $tgl2 . '"');
        return $this->db->get()->num_rows();
    }
    public function getPanggilTransaksi($tgl1, $tgl2)
    {
        $this->db->select('*, tbl_order.kode_order');
        $this->db->from('tbl_transaksi');
        $this->db->join('tbl_order', 'tbl_order.id_order=tbl_transaksi.order_id');
        $this->db->where('tbl_transaksi.created_at BETWEEN "' . $tgl1 . '" AND "' . $tgl2 . '"');

        return $this->db->count_all_results();
    }

    public function getFindIdTransaksi($id)
    {
        $this->db->select('*, tbl_order.kode_order');
        $this->db->from('tbl_transaksi');
        $this->db->join('tbl_order', 'tbl_order.id_order=tbl_transaksi.order_id');
        $this->db->where('tbl_transaksi.id_transaksi', $id);
        return $this->db->get()->row_array();
    }

    public function getTransaksiId($id)
    {
        $this->db->select('*');
        $this->db->from('tbl_transaksi');
        $this->db->where('id_transaksi', $id);
        return $this->db->get()->row_array();
    }

    public function getTransaksiBuktiTFWhereOrderId($id)
    {
        $this->db->select('bukti_tf');
        $this->db->from('tbl_transaksi');
        $this->db->where('order_id', $id);
        return $this->db->get()->row_array();
    }

    public function edit_data($data)
    {
        $this->db->where('id_transaksi', $data['id_transaksi']);
        unset($data['id_transaksi']);
        $this->db->update('tbl_transaksi', $data);
        return $this->db->affected_rows();
    }

    public function hapus_data($id)
    {
        $this->db->where('id_transaksi', $id);
        $this->db->delete('tbl_transaksi');
    }

    public function getTransactionOrderDetail($transid)
    {
        $this->db->select('tbl_transaksi.id_transaksi, tbl_transaksi.order_id,tbl_transaksi.kode_transaksi,tbl_transaksi.terbayar,tbl_transaksi.kembalian, tbl_transaksi.tagihan_cart, tbl_transaksi.total_diskon, tbl_transaksi.tagihan_after_diskon,tbl_transaksi.total_biaya_kirim,tbl_transaksi.total_tagihan, tbl_transaksi.tipe_transaksi, tbl_transaksi.bukti_tf, tbl_transaksi.is_active, tbl_transaksi.user_input, tbl_transaksi.created_at, tbl_order.id_order, tbl_order.kode_order, tbl_order.nama_cust, tbl_order.hp_cust, tbl_order.alamat_cust, tbl_order.tipe_order, tbl_order.tipe_pengiriman, tbl_order.biaya_kirim, tbl_order.bukti_kirim, tbl_order.total_order, tbl_order.paidst, tbl_order.status, tbl_order_detail.id_order_detail, tbl_order_detail.order_id ,tbl_order_detail.harga_id, tbl_order_detail.qty, tbl_order_detail.harga_total ,tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko, tbl_harga.harga_jual ,tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.barcode_barang, tbl_kategori.id_kategori, tbl_kategori.nama_kategori, tbl_satuan.id_satuan, tbl_satuan.satuan');
        $this->db->from('tbl_transaksi');
        $this->db->join('tbl_order', 'tbl_transaksi.order_id = tbl_order.id_order', 'left');
        $this->db->join('tbl_order_detail', 'tbl_transaksi.order_id = tbl_order_detail.order_id', 'left');
        $this->db->join('tbl_harga', 'tbl_order_detail.harga_id = tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_harga.barang_id', 'left');
        $this->db->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id', 'left');
        $this->db->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id', 'left');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id', 'left');
        $this->db->where('tbl_transaksi.id_transaksi', $transid);
        $query = $this->db->get();
        return $query->result_array();
    }
}
