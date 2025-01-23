<?php

class Laporan_penjualan_model extends CI_Model
{
    public function getAllPenjualan($tanggal = "", $barang_id = "", $toko_id = "")
    {
        $tgl = ($this->input->post('tanggal')) ? $this->input->post('tanggal') : $tanggal;
        $barang_id = ($this->input->post("barang_id")) ? $this->input->post("barang_id") : $barang_id;
        $toko_id = ($this->input->post("toko_id")) ? $this->input->post("toko_id") : $toko_id;

        $this->db->select("tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_transaksi.kode_transaksi, tbl_order.kode_order, tbl_report_penjualan.harga_satuan_pokok, tbl_report_penjualan.harga_satuan_jual, tbl_report_penjualan.qty, tbl_report_penjualan.total_harga_pokok, tbl_report_penjualan.total_harga_jual, tbl_report_penjualan.total_diskon, tbl_report_penjualan.total_keuntungan, DATE_FORMAT(tbl_report_penjualan.tanggal_beli, '%d/%m/%Y') as tanggal_beli");
        $this->db->from("tbl_report_penjualan");
        $this->db->join("tbl_toko", "tbl_report_penjualan.toko_id = tbl_toko.id_toko", "inner");
        $this->db->join("tbl_order", "tbl_report_penjualan.order_id = tbl_order.id_order", "inner");
        $this->db->join("tbl_transaksi", "tbl_report_penjualan.transaksi_id = tbl_transaksi.id_transaksi", "inner");
        $this->db->join("tbl_barang", "tbl_report_penjualan.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_report_penjualan.is_rollback", 0);

        if ($tgl) {

            if (strlen($tgl) > 10) {

                $tglPecah = explode(' to ', $tgl);
                $tgl1 = $tglPecah[0];
                $tgl2 = $tglPecah[1];

                $this->db->where("DATE_FORMAT(tbl_report_penjualan.tanggal_beli, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
            } else {

                $this->db->where("DATE_FORMAT(tbl_report_penjualan.tanggal_beli, '%Y-%m-%d') = '$tgl'");
            }
        }

        if ($barang_id) {

            $this->db->where("tbl_report_penjualan.barang_id", $barang_id);
        }

        if ($toko_id) {

            $this->db->where("tbl_report_penjualan.toko_id", $toko_id);
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
        $query = $this->db->get("tbl_report_penjualan");
        return $query->row_array();
    }

    // --> BRIAN CODE
    // public function getAllDataExcelPenjualan($tgl)
    // {
    //     if ($tgl) {
    //         $tglPecah = explode('to', $tgl);
    //         $tgl1 = $tglPecah[0];
    //         $tgl2 = $tglPecah[1];
    //     } else {
    //         $tgl1 = null;
    //         $tgl2 = null;
    //     }

    //     if ($tgl) {

    //         $this->db->select('*, tbl_order.created_at');
    //         $this->db->from('tbl_order_detail');
    //         $this->db->join('tbl_order', 'tbl_order_detail.order_id=tbl_order.id_order', 'left');
    //         $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
    //         $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
    //         $this->db->join('tbl_toko', 'tbl_harga.toko_id=tbl_toko.id_toko', 'left');
    //         $this->db->where('tbl_order.is_active', 1);

    //         $this->db->where('tbl_order.created_at BETWEEN "' . $tgl1 . '" AND "' . $tgl2 . '"');

    //         if ($this->input->post('length') != -1) {
    //             $this->db->limit($this->input->post('length'), $this->input->post('start'));
    //         }

    //         return $this->db->get()->result_array();
    //     }


    //     $this->db->select('*, tbl_order.created_at');
    //     $this->db->from('tbl_order_detail');
    //     $this->db->join('tbl_order', 'tbl_order_detail.order_id=tbl_order.id_order', 'left');
    //     $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
    //     $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
    //     $this->db->join('tbl_toko', 'tbl_harga.toko_id=tbl_toko.id_toko', 'left');
    //     $this->db->where('tbl_order.is_active', 1);

    //     if ($this->input->post('length') != -1) {
    //         $this->db->limit($this->input->post('length'), $this->input->post('start'));
    //     }

    //     return $this->db->get()->result_array();
    // }

    // public function rangeTanggalPengjualan()
    // {
    //     $this->db->select_min('tbl_order.created_at', 'start_date');
    //     $this->db->select_max('tbl_order.created_at', 'end_date');
    //     $this->db->from('tbl_order_detail');
    //     $this->db->join('tbl_order', 'tbl_order_detail.order_id=tbl_order.id_order', 'left');
    //     $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
    //     $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
    //     $this->db->join('tbl_toko', 'tbl_harga.toko_id=tbl_toko.id_toko', 'left');
    //     $this->db->where('tbl_order.is_active', 1);

    //     return $this->db->get()->row();
    // }
    // --> END BRIAN CODE
}
