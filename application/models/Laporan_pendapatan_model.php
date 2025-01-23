<?php


class Laporan_pendapatan_model extends CI_Model
{

    public function getAllPendapatan()
    {
        $tgl = $this->input->post('tanggal');
        $toko_id = $this->input->post('toko_id');


        if ($tgl) {
            $tglPecah = explode('to', $tgl);
            $tgl1 = date('Y-m-d', strtotime($tglPecah[0]));
            $tgl2 = date('Y-m-d', strtotime($tglPecah[1]));
        } else {
            $tgl1 = null;
            $tgl2 = null;
        }



        $this->db->select('tbl_order_detail.qty, tbl_barang.harga_pokok, tbl_order_detail.harga_potongan, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_order_detail.created_at');

        $this->db->from('tbl_order_detail');
        $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);


        if ($tgl && $toko_id) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($toko_id) {
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($tgl) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
        }


        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ambilHitungTotalPendapatan()
    {

        $tgl = $this->input->post('tanggal');
        $toko_id = $this->input->post('toko_id');

        if ($tgl) {
            $tglPecah = explode('to', $tgl);
            $tgl1 = date('Y-m-d', strtotime($tglPecah[0]));
            $tgl2 = date('Y-m-d', strtotime($tglPecah[1]));
        } else {
            $tgl1 = null;
            $tgl2 = null;
        }


        $this->db->select('tbl_order_detail.qty, tbl_barang.harga_pokok, tbl_order_detail.harga_potongan, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_order_detail.created_at');
        $this->db->from('tbl_order_detail');
        $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);

        if ($tgl && $toko_id) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");

            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($toko_id) {
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($tgl) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
        }

        return $this->db->count_all_results();
    }

    public function ambilFilterTotalPendapatan()
    {


        $tgl = $this->input->post('tanggal');
        $toko_id = $this->input->post('toko_id');

        if ($tgl) {
            $tglPecah = explode('to', $tgl);
            $tgl1 = date('Y-m-d', strtotime($tglPecah[0]));
            $tgl2 = date('Y-m-d', strtotime($tglPecah[1]));
        } else {
            $tgl1 = null;
            $tgl2 = null;
        }


        $this->db->select('tbl_order_detail.qty, tbl_barang.harga_pokok, tbl_order_detail.harga_potongan, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_order_detail.created_at');
        $this->db->from('tbl_order_detail');
        $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);

        if ($tgl && $toko_id) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($toko_id) {
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($tgl) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
        }

        return $this->db->get()->num_rows();
    }

    public function getAllDataExcelPendapatan($res)
    {


        $toko_id = $res['toko_id'];
        $tgl = $res['tanggal'];
        if ($res['tanggal']) {
            $tglPecah = explode('to', $res['tanggal']);
            $tgl1 = date('Y-m-d', strtotime($tglPecah[0]));
            $tgl2 = date('Y-m-d', strtotime($tglPecah[1]));
        } else {
            $tgl1 = null;
            $tgl2 = null;
        }

        $this->db->select('tbl_order_detail.qty, tbl_barang.harga_pokok, tbl_order_detail.harga_potongan, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_order_detail.created_at');
        $this->db->from('tbl_order_detail');
        $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);
        if ($tgl && $toko_id) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($toko_id) {
            $this->db->where("tbl_harga.toko_id ", $toko_id);
        } elseif ($tgl) {
            $this->db->where("DATE_FORMAT(tbl_order_detail.created_at, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2'");
        }

        return $this->db->get()->result_array();
    }

    public function rangeTanggalPendapatan()
    {
        $this->db->select_min('tbl_order_detail.created_at', 'start_date');
        $this->db->select_max('tbl_order_detail.created_at', 'end_date');
        $this->db->from('tbl_order_detail');
        $this->db->join('tbl_harga', 'tbl_order_detail.harga_id=tbl_harga.id_harga', 'left');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id=tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);


        return $this->db->get()->row();
    }
}
