<?php

class Sales_order_cacat_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Diskon_model", "diskon");
        $this->load->model("Setting_model", "setting");
        $this->load->library("watzap_library");
    }

    private function queryAmbilSemuaSalesOrderCacat()
    {

        $cari           = ["tbl_toko.nama_toko", "tbl_order_cacat.kode_order", "tbl_order_cacat.nama_cust", "tbl_order_cacat.hp_cust", "tbl_order_cacat.tipe_order", "tbl_order_cacat.tipe_pengiriman", "tbl_order_cacat.biaya_kirim", "tbl_order_cacat.total_order", "tbl_order_cacat.paidst", "tbl_order_cacat.status", "tbl_order_cacat.created_at", "tbl_order_cacat.updated_at"];
        $order          = [null, "tbl_toko.nama_toko", "tbl_order_cacat.kode_order", "tbl_order_cacat.nama_customer", "tbl_order_cacat.nama_cust", "tbl_order_cacat.hp_cust", "tbl_order_cacat.tipe_order", "tbl_order_cacat.tipe_pengiriman", "tbl_order_cacat.biaya_kirim", "tbl_order_cacat.total_order", "tbl_order_cacat.paidst", "tbl_order_cacat.status", "tbl_order_cacat.created_at", "tbl_order_cacat.updated_at"];

        $this->db->select("tbl_toko.nama_toko, tbl_order_cacat.kode_order, tbl_order_cacat.nama_cust, tbl_order_cacat.hp_cust, tbl_order_cacat.tipe_order, tbl_order_cacat.tipe_pengiriman, tbl_order_cacat.biaya_kirim, tbl_order_cacat.total_order, tbl_order_cacat.paidst, tbl_order_cacat.status, tbl_order_cacat.created_at, tbl_order_cacat.updated_at");
        $this->db->from("tbl_order_cacat");
        $this->db->join("tbl_toko", "tbl_order_cacat.toko_id = tbl_toko.id_toko", "inner");
        $this->db->where("tbl_order_cacat.is_active", 1);

        if ($this->session->userdata("toko_id")) {

            $this->db->where("tbl_order_cacat.toko_id", $this->session->userdata("toko_id"));
        } else {

            if ($this->input->post("toko_id")) {
                // do query toko id
                $this->db->where("tbl_order_cacat.toko_id", htmlspecialchars($this->input->post("toko_id")));
            }
        }

        if ($this->input->post("kode_order")) {
            // do query kode_order
            $this->db->like("tbl_order_cacat.kode_order", htmlspecialchars($this->input->post("kode_order")));
        }

        if ($this->input->post("nama_cust")) {
            // do query nama_cust
            $this->db->like("tbl_order_cacat.nama_cust", htmlspecialchars($this->input->post("nama_cust")));
        }

        if ($this->input->post("hp_cust")) {
            // do query hp_cust
            $this->db->like("tbl_order_cacat.hp_cust", htmlspecialchars($this->input->post("hp_cust")));
        }

        if ($this->input->post("status")) {
            // do query status
            $this->db->where("tbl_order_cacat.status", htmlspecialchars($this->input->post("status")));
        }

        if ($this->input->post("paidst") !== "") {
            // do query paidst
            $this->db->where("tbl_order_cacat.paidst", htmlspecialchars($this->input->post("paidst")));
        }

        if ($this->input->post("tipe_order")) {
            // do query tipe_order
            $this->db->where("tbl_order_cacat.tipe_order", htmlspecialchars($this->input->post("tipe_order")));
        }

        if ($this->input->post("tipe_pengiriman")) {
            // do query tipe_pengiriman
            $this->db->where("tbl_order_cacat.tipe_pengiriman", htmlspecialchars($this->input->post("tipe_pengiriman")));
        }

        if ($this->input->post("created_at")) {
            // do query created_at
            $date = $this->input->post("created_at");

            if (strlen($date) === 10) {

                $this->db->where("DATE_FORMAT(tbl_order_cacat.created_at, '%Y-%m-%d') = '$date'");
            } else {

                $date_start = substr($this->input->post("created_at"), 0, 9);
                $date_end = substr($this->input->post("created_at"), 14);

                $this->db->where("DATE_FORMAT(tbl_order_cacat.created_at, '%Y-%m-%d') >= '$date_start'");
                $this->db->where("DATE_FORMAT(tbl_order_cacat.created_at, '%Y-%m-%d') <= '$date_end'");
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
            $this->db->order_by("tbl_order_cacat.status", "ASC");
        }
    }

    public function ambilSemuaOrder()
    {
        $this->db->select("tbl_toko.nama_toko, tbl_order_cacat.kode_order, tbl_order_cacat.nama_cust, tbl_order_cacat.hp_cust, tbl_order_cacat.tipe_order, tbl_order_cacat.tipe_pengiriman, tbl_order_cacat.biaya_kirim, tbl_order_cacat.total_order, tbl_order_cacat.paidst, tbl_order_cacat.status, tbl_order_cacat.created_at, tbl_order_cacat.updated_at");
        $this->db->from("tbl_order_cacat");
        $this->db->join("tbl_toko", "tbl_order_cacat.toko_id = tbl_toko.id_toko", "inner");
        $this->db->where("tbl_order_cacat.is_active", 1);
        if ($this->session->userdata("toko_id")) {
            $this->db->where("toko_id", $this->session->userdata("toko_id"));
        }
        $this->db->order_by("tbl_order_cacat.created_at", "ASC");
        $this->db->order_by("tbl_order_cacat.status", "ASC");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function ajaxAmbilSemuaSalesOrderCacat()
    {

        $this->queryAmbilSemuaSalesOrderCacat();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ajaxAmbilFilterSemuaSalesOrderCacat()
    {

        $this->queryAmbilSemuaSalesOrderCacat();
        return $this->db->get()->num_rows();
    }

    public function ajaxAmbilHitungSemuaSalesOrderCacat()
    {

        $this->queryAmbilSemuaSalesOrderCacat();
        return $this->db->count_all_results();
    }

    public function ambilDetailOrderCacat($kode_order)
    {
        $this->db->select("tbl_order_cacat.id_order_cacat, tbl_order_detail_cacat.id_order_detail_cacat, tbl_order_cacat.kode_order, tbl_order_cacat.nama_cust, tbl_order_cacat.hp_cust, tbl_order_cacat.toko_id,  
        tbl_order_cacat.alamat_cust, tbl_order_cacat.tipe_order, tbl_order_cacat.tipe_pengiriman, tbl_order_cacat.biaya_kirim, tbl_order_cacat.total_order, tbl_order_cacat.paidst, tbl_order_cacat.status, tbl_order_cacat.created_at, 
        tbl_order_cacat.updated_at, tbl_order_detail_cacat.qty_cacat, tbl_order_detail_cacat.barang_cacat_id, tbl_order_detail_cacat.harga_detail_cacat, tbl_order_detail_cacat.sub_total_cacat, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang_cacat.harga_jual_cacat, tbl_barang_cacat.stok_cacat, tbl_barang_cacat.toko_id, tbl_transaksi_cacat.terbayar, tbl_transaksi_cacat.kembalian, tbl_transaksi_cacat.tipe_transaksi, tbl_transaksi_cacat.bukti_tf");
        $this->db->from("tbl_order_cacat");
        $this->db->join("tbl_order_detail_cacat", "tbl_order_cacat.id_order_cacat = tbl_order_detail_cacat.order_cacat_id", "inner");
        $this->db->join("tbl_barang_cacat", "tbl_order_detail_cacat.barang_cacat_id = tbl_barang_cacat.id_barang_cacat", "inner");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_barang_cacat.barang_id", "inner");
        $this->db->join("tbl_transaksi_cacat", "tbl_order_cacat.id_order_cacat = tbl_transaksi_cacat.order_cacat_id", "left");
        $this->db->where("tbl_order_cacat.kode_order", $kode_order);
        $this->db->where("tbl_order_cacat.is_active", 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function perhitunganSalesOrderCacatByHargaItem($data)
    {
        $this->db->select("stok_cacat, harga_jual_cacat");
        $this->db->where("id_barang_cacat", $data["id_barang_cacat"]);
        $query = $this->db->get("tbl_barang_cacat");
        if ($query->num_rows() === 1) {

            $data_harga_barang = $query->row_array();

            return $data_harga_barang;
        }

        return 0;
    }

    public function eksekusiOrderanCacat($data)
    {

        // --> looping setiap stok pada barang untuk cek stok terbaru dan mendapatkan nilai terbaru dari stok
        // --> hapus order_detail by id_order pada tbl_order_detail
        // --> insert order_detail terbaru pada tbl_order_detail
        // --> update_batch untuk pengurangan stok 
        // --> update status menjadi 2 pada tbl_order 
        // --> update total order menjadi (sumsubtotal - sumsubdiskon) pada tbl_order 
        // --> update tipe pengiriman pada tbl_order 

        $arr_insert     = [];
        $arr_update     = [];
        $arr_err        = [];
        $err            = 0;
        $sum_subtotal   = 0;
        $sum_diskon     = 0;

        for ($i = 0; $i < count($data["input_id_barang_cacat"]); $i++) {

            $this->db->select("stok_cacat");
            $this->db->where("id_barang_cacat", $data["input_id_barang_cacat"][$i]);
            $this->db->where("stok_cacat >=", $data["input_qty_cacat"][$i]);
            $query = $this->db->get("tbl_barang_cacat");

            if ($query->num_rows() === 1) {

                array_push($arr_update, ["id_barang_cacat" => $data["input_id_barang_cacat"][$i], "stok_cacat" => ($query->row_array()["stok_cacat"] - $data["input_qty_cacat"][$i])]);
                array_push($arr_insert, ["barang_cacat_id" => $data["input_id_barang_cacat"][$i], "order_cacat_id"  => $data["id_order_cacat"], "qty_cacat" => $data["input_qty_cacat"][$i], "harga_detail_cacat" => $data["input_harga_detail_cacat"][$i], "sub_total_cacat" => $data["sub_total_cacat"][$i], "user_input"  => $this->session->userdata("username"), "created_at"  => date("Y-m-d H:i:s")]);
            } else {

                $this->db->select("stok_cacat");
                $this->db->where("id_barang_cacat", $data["input_id_barang_cacat"][$i]);
                $query = $this->db->get("tbl_barang_cacat");

                array_push($arr_err, [

                    "id_barang_cacat"  => $data["input_id_barang_cacat"][$i],
                    "stok_cacat" => $query->row_array()["stok_cacat"],
                    "message"   => "Jumlah kuantitas melebihi stok",

                ]);

                $err++;
            }
        }

        if (!$err) {

            $this->db->trans_begin();

            $this->db->where("order_cacat_id", $data["id_order_cacat"]);
            $this->db->delete("tbl_order_detail_cacat");

            $this->db->insert_batch("tbl_order_detail_cacat", $arr_insert);
            $this->db->update_batch("tbl_barang_cacat", $arr_update, "id_barang_cacat");

            $this->db->set("status", 2);
            $this->db->set("alamat_cust", $data["alamat_cust"]);
            $this->db->set("total_order", $data["input_total"]);
            $this->db->set("tipe_pengiriman", $data["tipe_pengiriman"]);
            $this->db->set("biaya_kirim", $data["biaya_kirim"]);
            $this->db->set("user_edit", $this->session->userdata("username"));
            $this->db->set("updated_at", date("Y-m-d H:i:s"));
            $this->db->where("id_order_cacat", $data["id_order_cacat"]);
            $this->db->update("tbl_order_cacat");

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                return 0;
            } else {

                $this->db->trans_commit();
                return 1;
            }
        }

        return $arr_err;
    }

    public function rollbackSalesOrderCacat($data)
    {
        switch ($data["step"]) {

            case 1:

                // --> ambil list detail order dari tbl_order_detail_cacat melalui id_order dan tampung kedalam sebuah array dengan kolom barang_cacat_id = barang_cacat_id dan stok_cacat = qty_cacat
                // --> lakukan loop dengan memanggil query update stok_cacat sesuai dengan barang_cacat_id dan juga set stok_cacat(database) + stok_cacat(field) = stok_baru
                // --> lakukan update pada tbl_barang_cacat pada setiap loop
                // --> lakukan update status ke 1

                $this->db->select("barang_cacat_id, qty_cacat");
                $this->db->where("order_cacat_id", $data["id_order_cacat"]);
                $query = $this->db->get("tbl_order_detail_cacat");

                $this->db->trans_begin();

                foreach ($query->result_array() as $dt) {

                    $this->db->set('stok_cacat', 'stok_cacat + ' . $dt["qty_cacat"], FALSE);
                    $this->db->where("id_barang_cacat", $dt["barang_cacat_id"]);
                    $this->db->update("tbl_barang_cacat");
                }

                $this->db->set("status", 1);
                $this->db->where("id_order_cacat", $data["id_order_cacat"]);
                $this->db->update("tbl_order_cacat");

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    return 0;
                } else {

                    $this->db->trans_commit();
                    return 1;
                }

                break;

            case 2:

                /*
                    1. update data dari table barang keluar yang dimana order_detailnya berada pada order_detail milik order_cacat_id menjadi is_rollback 1
                    2. ambil data dengan query yang sama namun hanya beberapa kolom, jml_keluar dan barang_cacat_id 
                    3. loop data dari result_array lakukan update satu persatu pada tbl_barang_cacat dengan menambahkan kolom stok yang ada pada table harga + jumlah_keluar
                    4. update pada table order dengan set status menjadi 99 sesuai dengan order idnya
                */

                $id_order_cacat = $data["id_order_cacat"];

                $this->db->trans_begin();

                $this->db->set("is_rollback", 1);
                $this->db->where("order_detail_cacat_id IN (SELECT id_order_detail_cacat FROM tbl_order_detail_cacat WHERE order_cacat_id = '$id_order_cacat')");
                $this->db->update("tbl_keluar_cacat");

                $this->db->set("is_rollback", 1);
                $this->db->where("order_cacat_id", $id_order_cacat);
                $this->db->update("tbl_report_penjualan_cacat");

                $this->db->select("jml_keluar_cacat, barang_cacat_id");
                $this->db->where("order_detail_cacat_id IN (SELECT id_order_detail_cacat FROM tbl_order_detail_cacat WHERE order_cacat_id = '$id_order_cacat')");
                $query = $this->db->get("tbl_keluar_cacat");

                $newArr = $query->result_array();

                foreach ($newArr as $na) {

                    $this->db->set('stok_cacat', 'stok_cacat + ' . $na["jml_keluar_cacat"], FALSE);
                    $this->db->where("id_barang_cacat", $na["barang_cacat_id"]);
                    $this->db->update("tbl_barang_cacat");
                }

                $this->db->set("status", 99);
                $this->db->where("id_order_cacat", $id_order_cacat);
                $this->db->update("tbl_order_cacat");

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    return 0;
                } else {

                    $this->db->trans_commit();
                    return 1;
                }

                break;

            default:
                return 0;
                break;
        }
    }

    public function uploadBuktiBayarSalesOrderCacat($data)
    {

        /*
            1. insert kedalam transaksi terlebih dahulu
            2. ambil semua order list yang dimiliki oleh id_order
            3. insert kedalam barang keluar
            4. update orderan dengan status menjadi 3 
            5. update orderdan dengan paidst 1
        */

        unset($data["kode_order"]);
        unset($data["nama_cust"]);
        unset($data["hp_cust"]);

        $user = $this->session->userdata("nama_user");
        $date = date("Y-m-d H:i:s");

        $this->db->select("id_order_detail_cacat as order_detail_cacat_id, barang_cacat_id, qty_cacat as jml_keluar_cacat, 'TERJUAL' as jenis_keluar_cacat, '$user' as user_input, '$date' as created_at");
        $this->db->where("order_cacat_id", $data["order_cacat_id"]);
        $query = $this->db->get("tbl_order_detail_cacat");
        $result = $query->result_array();

        $this->db->select("tbl_order_detail_cacat.barang_cacat_id, tbl_order_detail_cacat.order_cacat_id, tbl_barang_cacat.harga_jual_cacat as harga_satuan_jual, tbl_barang.harga_pokok as harga_satuan_pokok, tbl_barang.id_barang as barang_id, tbl_order_detail_cacat.qty_cacat as qty, (tbl_order_detail_cacat.qty_cacat * tbl_barang.harga_pokok) as total_harga_pokok, tbl_order_detail_cacat.sub_total_cacat as total_harga_jual");
        $this->db->from("tbl_order_detail_cacat");
        $this->db->join("tbl_barang_cacat", "tbl_order_detail_cacat.barang_cacat_id = tbl_barang_cacat.id_barang_cacat", "inner");
        $this->db->join("tbl_barang", "tbl_barang_cacat.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_order_detail_cacat.order_cacat_id", $data["order_cacat_id"]);

        $query = $this->db->get();
        $result2 = $query->result_array();

        $this->db->trans_begin();

        $toko_id = $data["toko_id"];
        unset($data["toko_id"]);

        $data["user_input"] = $user;
        $data["created_at"] = $date;
        $data["kode_transaksi"] = makeTransCodeCacat();

        $this->db->insert("tbl_transaksi_cacat", $data);
        $lastIdTransaksi = $this->db->insert_id();

        $report_penjualan = array_map(function ($index) use ($lastIdTransaksi, $data, $toko_id) {

            $index["transaksi_cacat_id"] = $lastIdTransaksi;
            $index["toko_id"]       = $toko_id;
            $index["tanggal_beli"]  = $data["tanggal_beli"];
            $index["created_at"]    = date("Y-m-d H:i:s");

            $index["total_keuntungan"] = total_keuntungan($index["total_harga_jual"], 0, $index["total_harga_pokok"]);

            return $index;
        }, $result2);

        $this->db->insert_batch("tbl_report_penjualan_cacat", $report_penjualan);
        $this->db->insert_batch("tbl_keluar_cacat", $result);

        $this->db->set("status", 3);
        $this->db->set("paidst", 1);
        $this->db->where("id_order_cacat", $data["order_cacat_id"]);
        $this->db->update("tbl_order_cacat");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return 0;
        } else {

            $this->db->trans_commit();
            return 1;
        }
    }

    public function konfirmasiSalesOrderCacat($data)
    {
        switch ($data["step"]) {

            case 3:
            case 4:

                $this->db->set("status", (intval($data["step"]) + 1));
                $this->db->set(($data["step"] == 3) ? "waktu_kirim" : "waktu_terima", $data["waktu"]);
                $this->db->where("id_order_cacat", $data["id_order_cacat"]);
                $this->db->update("tbl_order_cacat");

                return $this->db->affected_rows();

                break;
            default:

                return 0;

                break;
        }
    }
}
