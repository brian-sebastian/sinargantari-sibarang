<?php

class Laporan_penjualan_cacat_model extends CI_Model
{

    public function getAllPenjualan($tanggal = "", $barang_id = "", $toko_id = "")
    {
        $tgl = ($this->input->post('tanggal')) ? $this->input->post('tanggal') : $tanggal;
        $barang_id = ($this->input->post("barang_id")) ? $this->input->post("barang_id") : $barang_id;
        $toko_id = ($this->input->post("toko_id")) ? $this->input->post("toko_id") : $toko_id;

        $this->db->select("tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_transaksi_cacat.kode_transaksi, tbl_order_cacat.kode_order, tbl_report_penjualan_cacat.harga_satuan_pokok, tbl_report_penjualan_cacat.harga_satuan_jual, tbl_report_penjualan_cacat.qty, tbl_report_penjualan_cacat.total_harga_pokok, tbl_report_penjualan_cacat.total_harga_jual, tbl_report_penjualan_cacat.total_diskon, tbl_report_penjualan_cacat.total_keuntungan, DATE_FORMAT(tbl_report_penjualan_cacat.tanggal_beli, '%d/%m/%Y') as tanggal_beli");
        $this->db->from("tbl_report_penjualan_cacat");
        $this->db->join("tbl_toko", "tbl_report_penjualan_cacat.toko_id = tbl_toko.id_toko", "inner");
        $this->db->join("tbl_order_cacat", "tbl_report_penjualan_cacat.order_cacat_id = tbl_order_cacat.id_order_cacat", "inner");
        $this->db->join("tbl_transaksi_cacat", "tbl_report_penjualan_cacat.transaksi_cacat_id = tbl_transaksi_cacat.id_transaksi_cacat", "inner");
        $this->db->join("tbl_barang", "tbl_report_penjualan_cacat.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_report_penjualan_cacat.is_rollback", 0);

        if ($tgl) {

            if (strlen($tgl) > 10) {

                $tglPecah = explode('to', $tgl);
                $tgl1 = $tglPecah[0];
                $tgl2 = $tglPecah[1];

                $this->db->where("DATE_FORMAT(tbl_report_penjualan_cacat.tanggal_beli, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
            } else {

                $this->db->where("DATE_FORMAT(tbl_report_penjualan_cacat.tanggal_beli, '%Y-%m-%d') = '$tgl'");
            }
        }

        if ($barang_id) {

            $this->db->where("tbl_report_penjualan_cacat.barang_id", $barang_id);
        }

        if ($toko_id) {

            $this->db->where("tbl_report_penjualan_cacat.toko_id", $toko_id);
        }
    }

    public function ambilSemuaPenjualanExcel($tanggal = "", $barang_id = "", $toko_id = "")
    {

        $this->getAllPenjualan($tanggal, $barang_id, $toko_id);
        return $this->db->get()->result_array();
    }

    public function ambilSemuaPenjualan()
    {

        $this->getAllPenjualan();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ambilHitungTotalPenjualan()
    {

        $this->getAllPenjualan();
        return $this->db->count_all_results();
    }

    public function ambilFilterTotalPenjualan()
    {
        $this->getAllPenjualan();
        return $this->db->get()->num_rows();
    }

    public function ambilMinMaxDate()
    {
        $this->db->select("MIN(DATE_FORMAT(tanggal_beli,'%Y-%m-%d')) as minDate, MAX(DATE_FORMAT(tanggal_beli,'%Y-%m-%d')) as maxDate");
        $this->db->where("is_rollback", 0);
        $query = $this->db->get("tbl_report_penjualan_cacat");
        return $query->row_array();
    }
}
