<?php

class Laporan_transaksi_cacat_model extends CI_Model
{

    private function queryAmbilSemuaTransaksi()
    {
        /*
            list tampilkan data transaksi
            1. kode transaksi
            2. kode order
            3. nama toko
            4. nama customer
            5. tipe order
            6. total keranjang
            7. total diskon 
            8. total setelah diskon
            9. total biaya kirim
            10. total tagihan 
            11. terbayar
            12. kembalian
            13. tipe transaksi
        */

        $cari   = ["tbl_transaksi_cacat.kode_transaksi", "tbl_transaksi_cacat.tipe_transaksi", "tbl_order_cacat.kode_order", "tbl_order_cacat.nama_cust"];
        $order  = [null, "tbl_transaksi_cacat.kode_transaksi", "tbl_order_cacat.kode_order", null, "tbl_order_cacat.nama_cust", "tbl_transaksi_cacat.tagihan_cart", "tbl_transaksi_cacat.total_diskon, tbl_transaksi_cacat.tagihan_after_diskon, tbl_transaksi_cacat.total_biaya_kirim, tbl_transaksi_cacat.total_tagihan, tbl_transaksi_cacat.tipe_transaksi", null];

        $this->db->select("tbl_transaksi_cacat.kode_transaksi, tbl_transaksi_cacat.tagihan_cart, tbl_transaksi_cacat.total_diskon, tbl_transaksi_cacat.tagihan_after_diskon, tbl_transaksi_cacat.total_biaya_kirim, tbl_transaksi_cacat.total_tagihan, tbl_transaksi_cacat.terbayar, tbl_transaksi_cacat.kembalian, tbl_transaksi_cacat.tipe_transaksi, tbl_transaksi_cacat.created_at,  tbl_order_cacat.kode_order, tbl_order_cacat.nama_cust, tbl_order_cacat.tipe_order, tbl_toko.nama_toko");
        $this->db->from("tbl_transaksi_cacat");
        $this->db->join("tbl_order_cacat", "tbl_transaksi_cacat.order_cacat_id = tbl_order_cacat.id_order_cacat", "inner");
        $this->db->join("tbl_toko", "tbl_order_cacat.toko_id = tbl_toko.id_toko", "inner");

        if ($this->session->userdata("toko_id")) {

            $this->db->where("tbl_order_cacat.toko_id", $this->session->userdata("toko_id"));
        } else {

            if ($this->input->post("toko_id")) {

                $this->db->where("tbl_order_cacat.toko_id", $this->input->post("toko_id"));
            }
        }

        if ($this->input->post("kode_transaksi")) {
            $this->db->like("tbl_transaksi_cacat.kode_transaksi", htmlspecialchars($this->input->post("kode_transaksi")));
        }

        if ($this->input->post("kode_order")) {
            $this->db->like("tbl_order_cacat.kode_order", htmlspecialchars($this->input->post("kode_order")));
        }

        if ($this->input->post("nama_cust")) {
            $this->db->like("tbl_order_cacat.nama_cust", htmlspecialchars($this->input->post("nama_cust")));
        }

        if ($this->input->post("tipe_transaksi")) {
            $this->db->like("tbl_transaksi_cacat.tipe_transaksi", htmlspecialchars(strtolower($this->input->post("tipe_transaksi"))));
        }

        if ($this->input->post("created_at")) {

            $date = $this->input->post("created_at");

            if (strlen($date) === 10) {

                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') = '$date'");
            } else {

                $date_start = substr($this->input->post("created_at"), 0, 9);
                $date_end = substr($this->input->post("created_at"), 14);

                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') >= '$date_start'");
                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') <= '$date_end'");
            }
        }

        $search = $this->input->post("search");

        if ($search && isset($this->input->post("search")['value'])) {

            for ($start = 0; $start < count($cari); $start++) {

                if ($start == 0) {

                    $this->db->group_start();
                    $this->db->like($cari[$start], $this->input->post("search")['value']);
                } else {

                    $this->db->or_like($cari[$start], $this->input->post("search")['value']);
                }

                if ($start == (count($cari) - 1)) {

                    $this->db->group_end();
                }
            }
        }

        if ($this->input->post("order")) {

            if ($this->input->post('order')[0]['column'] > 0) {
                $this->db->order_by($order[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
            }
        } else {

            $this->db->order_by("tbl_order_cacat.created_at", "ASC");
        }
    }

    public function ajaxAmbilSemuaTotalTransaksi()
    {

        $this->db->select("SUM(tbl_transaksi_cacat.terbayar - tbl_transaksi_cacat.kembalian) as total");
        $this->db->from("tbl_transaksi_cacat");
        $this->db->join("tbl_order_cacat", "tbl_transaksi_cacat.order_cacat_id = tbl_order_cacat.id_order_cacat", "inner");
        $this->db->join("tbl_toko", "tbl_order_cacat.toko_id = tbl_toko.id_toko", "inner");

        if ($this->session->userdata("toko_id")) {

            $this->db->where("tbl_order_cacat.toko_id", $this->session->userdata("toko_id"));
        } else {

            if ($this->input->post("toko_id")) {

                $this->db->where("tbl_order_cacat.toko_id", $this->input->post("toko_id"));
            }
        }

        if ($this->input->post("kode_transaksi")) {
            $this->db->like("tbl_transaksi_cacat.kode_transaksi", htmlspecialchars($this->input->post("kode_transaksi")));
        }

        if ($this->input->post("kode_order")) {
            $this->db->like("tbl_order_cacat.kode_order", htmlspecialchars($this->input->post("kode_order")));
        }

        if ($this->input->post("nama_cust")) {
            $this->db->like("tbl_order_cacat.nama_cust", htmlspecialchars($this->input->post("nama_cust")));
        }

        if ($this->input->post("tipe_transaksi")) {
            $this->db->like("tbl_transaksi_cacat.tipe_transaksi", htmlspecialchars(strtolower($this->input->post("tipe_transaksi"))));
        }

        if ($this->input->post("created_at")) {

            $date = $this->input->post("created_at");

            if (strlen($date) === 10) {

                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') = '$date'");
            } else {

                $date_start = substr($this->input->post("created_at"), 0, 9);
                $date_end = substr($this->input->post("created_at"), 14);

                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') >= '$date_start'");
                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') <= '$date_end'");
            }
        }

        $res = $this->db->get()->row_array();

        return ($res["total"]) ? $res["total"] : 0;
    }

    public function ajaxAmbilSemuaTransaksiGet($data)
    {

        $this->db->select("tbl_transaksi_cacat.kode_transaksi, tbl_transaksi_cacat.tagihan_cart, tbl_transaksi_cacat.total_diskon, tbl_transaksi_cacat.tagihan_after_diskon, tbl_transaksi_cacat.total_biaya_kirim, tbl_transaksi_cacat.total_tagihan, tbl_transaksi_cacat.terbayar, tbl_transaksi_cacat.kembalian, tbl_transaksi_cacat.tipe_transaksi, tbl_transaksi_cacat.created_at,  tbl_order_cacat.kode_order, tbl_order_cacat.nama_cust, tbl_order_cacat.tipe_order, tbl_toko.nama_toko");
        $this->db->from("tbl_transaksi_cacat");
        $this->db->join("tbl_order_cacat", "tbl_transaksi_cacat.order_cacat_id = tbl_order_cacat.id_order_cacat", "inner");
        $this->db->join("tbl_toko", "tbl_order_cacat.toko_id = tbl_toko.id_toko", "inner");

        if ($this->session->userdata("toko_id")) {

            $this->db->where("tbl_order_cacat.toko_id", $this->session->userdata("toko_id"));
        } else {

            if ($data["toko_id"]) {

                $this->db->where("tbl_order_cacat.toko_id", $data["toko_id"]);
            }
        }

        if ($data["kode_transaksi"]) {
            $this->db->like("tbl_transaksi_cacat.kode_transaksi", htmlspecialchars($data["kode_transaksi"]));
        }

        if ($data["kode_order"]) {
            $this->db->like("tbl_order_cacat.kode_order", htmlspecialchars($data["kode_order"]));
        }

        if ($data["nama_cust"]) {
            $this->db->like("tbl_order_cacat.nama_cust", htmlspecialchars($data["nama_cust"]));
        }

        if ($data["tipe_transaksi"]) {
            $this->db->like("tbl_transaksi_cacat.tipe_transaksi", htmlspecialchars(strtolower($data["tipe_transaksi"])));
        }

        if ($data["created_at"]) {

            $date = $data["created_at"];

            if (strlen($date) === 10) {

                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') = '$date'");
            } else {

                $date_start = substr($data["created_at"], 0, 9);
                $date_end = substr($data["created_at"], 14);

                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') >= '$date_start'");
                $this->db->where("DATE_FORMAT(tbl_transaksi_cacat.created_at, '%Y-%m-%d') <= '$date_end'");
            }
        }

        $this->db->order_by("tbl_order_cacat.created_at", "ASC");

        return $this->db->get()->result_array();
    }

    public function ajaxAmbilSemuaTransaksi()
    {

        $this->queryAmbilSemuaTransaksi();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ajaxAmbilFilterSemuaTransaksi()
    {
        $this->queryAmbilSemuaTransaksi();
        return $this->db->get()->num_rows();
    }

    public function ajaxAmbilHitungSemuaTransaksi()
    {
        $this->queryAmbilSemuaTransaksi();
        return $this->db->count_all_results();
    }
}
