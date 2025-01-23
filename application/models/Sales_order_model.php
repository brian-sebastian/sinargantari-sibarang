<?php

class Sales_order_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Diskon_model", "diskon");
        $this->load->model("Setting_model", "setting");
        $this->load->library("watzap_library");
    }

    private function queryAmbilSemuaSalesOrder()
    {

        $cari           = ["tbl_toko.nama_toko", "tbl_order.kode_order", "tbl_order.nama_cust", "tbl_order.hp_cust", "tbl_order.tipe_order", "tbl_order.tipe_pengiriman", "tbl_order.biaya_kirim", "tbl_order.total_order", "tbl_order.paidst", "tbl_order.status", "tbl_order.created_at", "tbl_order.updated_at"];
        $order          = [null, "tbl_toko.nama_toko", "tbl_order.kode_order", "tbl_order.nama_customer", "tbl_order.nama_cust", "tbl_order.hp_cust", "tbl_order.tipe_order", "tbl_order.tipe_pengiriman", "tbl_order.biaya_kirim", "tbl_order.total_order", "tbl_order.paidst", "tbl_order.status", "tbl_order.created_at", "tbl_order.updated_at"];


        $this->db->select("tbl_toko.nama_toko, tbl_order.kode_order, tbl_order.nama_cust, tbl_order.hp_cust, tbl_order.tipe_order, tbl_order.tipe_pengiriman, tbl_order.biaya_kirim, tbl_order.total_order, tbl_order.paidst, tbl_order.status, tbl_order.created_at, tbl_order.updated_at, tbl_transaksi.id_transaksi");
        $this->db->from("tbl_order");
        $this->db->join("tbl_toko", "tbl_order.toko_id = tbl_toko.id_toko", "inner");
        $this->db->join("tbl_transaksi", "tbl_order.id_order = tbl_transaksi.order_id", "left");
        $this->db->where("tbl_order.is_active", 1);

        if ($this->session->userdata("toko_id")) {

            $this->db->where("tbl_order.toko_id", $this->session->userdata("toko_id"));
        } else {

            if ($this->input->post("toko_id")) {
                // do query toko id
                $this->db->where("tbl_order.toko_id", htmlspecialchars($this->input->post("toko_id")));
            }
        }

        if ($this->input->post("kode_order")) {
            // do query kode_order
            $this->db->like("tbl_order.kode_order", htmlspecialchars($this->input->post("kode_order")));
        }

        if ($this->input->post("nama_cust")) {
            // do query nama_cust
            $this->db->like("tbl_order.nama_cust", htmlspecialchars($this->input->post("nama_cust")));
        }

        if ($this->input->post("hp_cust")) {
            // do query hp_cust
            $this->db->like("tbl_order.hp_cust", htmlspecialchars($this->input->post("hp_cust")));
        }

        if ($this->input->post("status")) {
            // do query status
            $this->db->where("tbl_order.status", htmlspecialchars($this->input->post("status")));
        }

        if ($this->input->post("paidst") !== "") {
            // do query paidst
            $this->db->where("tbl_order.paidst", htmlspecialchars($this->input->post("paidst")));
        }

        if ($this->input->post("tipe_order")) {
            // do query tipe_order
            $this->db->where("tbl_order.tipe_order", htmlspecialchars($this->input->post("tipe_order")));
        }

        if ($this->input->post("tipe_pengiriman")) {
            // do query tipe_pengiriman
            $this->db->where("tbl_order.tipe_pengiriman", htmlspecialchars($this->input->post("tipe_pengiriman")));
        }

        if ($this->input->post("created_at")) {
            // do query created_at
            $date = $this->input->post("created_at");

            if (strlen($date) === 10) {

                $this->db->where("DATE_FORMAT(tbl_order.created_at, '%Y-%m-%d') = '$date'");
            } else {

                $date_start = substr($this->input->post("created_at"), 0, 9);
                $date_end = substr($this->input->post("created_at"), 14);

                $this->db->where("DATE_FORMAT(tbl_order.created_at, '%Y-%m-%d') >= '$date_start'");
                $this->db->where("DATE_FORMAT(tbl_order.created_at, '%Y-%m-%d') <= '$date_end'");
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

            $this->db->order_by("tbl_order.created_at", "ASC");
            $this->db->order_by("tbl_order.status", "ASC");
        }
    }

    public function ambilSemuaOrder()
    {
        $this->db->select("tbl_toko.nama_toko, tbl_order.kode_order, tbl_order.nama_cust, tbl_order.hp_cust, tbl_order.tipe_order, tbl_order.tipe_pengiriman, tbl_order.biaya_kirim, tbl_order.total_order, tbl_order.paidst, tbl_order.status, tbl_order.created_at, tbl_order.updated_at");
        $this->db->from("tbl_order");
        $this->db->join("tbl_toko", "tbl_order.toko_id = tbl_toko.id_toko", "inner");
        $this->db->where("tbl_order.is_active", 1);
        if ($this->session->userdata("toko_id")) {
            $this->db->where("toko_id", $this->session->userdata("toko_id"));
        }
        $this->db->order_by("tbl_order.created_at", "ASC");
        $this->db->order_by("tbl_order.status", "ASC");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function ajaxAmbilSemuaSalesOrder()
    {

        $this->queryAmbilSemuaSalesOrder();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ajaxAmbilFilterSemuaSalesOrder()
    {

        $this->queryAmbilSemuaSalesOrder();
        return $this->db->get()->num_rows();
    }

    public function ajaxAmbilHitungSemuaSalesOrder()
    {

        $this->queryAmbilSemuaSalesOrder();
        return $this->db->count_all_results();
    }

    public function ambilDetailOrder($kode_order)
    {
        $this->db->select("tbl_order.id_order, tbl_order_detail.id_order_detail, tbl_order.kode_order, tbl_order.nama_cust, tbl_order.hp_cust, tbl_order.toko_id,  
        tbl_order.alamat_cust, tbl_order.tipe_order, tbl_order.tipe_pengiriman, tbl_order.biaya_kirim, tbl_order.total_order, tbl_order.paidst, tbl_order.status, tbl_order.created_at, 
        tbl_order.updated_at, tbl_order_detail.qty, tbl_order_detail.harga_id, tbl_order_detail.harga_total, tbl_order_detail.harga_potongan, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_harga.harga_jual, tbl_harga.stok_toko, tbl_harga.toko_id, tbl_transaksi.terbayar, tbl_transaksi.kembalian, tbl_transaksi.tipe_transaksi, tbl_transaksi.bukti_tf");
        $this->db->from("tbl_order");
        $this->db->join("tbl_order_detail", "tbl_order.id_order = tbl_order_detail.order_id", "inner");
        $this->db->join("tbl_harga", "tbl_order_detail.harga_id = tbl_harga.id_harga", "inner");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_harga.barang_id", "left");
        $this->db->join("tbl_transaksi", "tbl_order.id_order = tbl_transaksi.order_id", "left");
        $this->db->where("tbl_order.kode_order", $kode_order);
        $this->db->where("tbl_order.is_active", 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function perhitunganSalesOrderByHargaItem($data)
    {
        $this->db->select("stok_toko, harga_jual");
        $this->db->where("id_harga", $data["id_harga"]);
        $query = $this->db->get("tbl_harga");
        if ($query->num_rows() === 1) {

            $data_harga_barang = $query->row_array();

            $res = $this->diskon->cekDiskonBarang($data["id_harga"], $data["qty"]);

            $data_harga_barang["diskon"]    = json_encode($res);

            return $data_harga_barang;
        }

        return 0;
    }

    public function eksekusiOrderan($data)
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

        for ($i = 0; $i < count($data["input_id_harga"]); $i++) {

            $this->db->select("stok_toko");
            $this->db->where("id_harga", $data["input_id_harga"][$i]);
            $this->db->where("stok_toko >=", $data["input_qty"][$i]);
            $query = $this->db->get("tbl_harga");

            if ($query->num_rows() === 1) {

                array_push($arr_update, ["id_harga" => $data["input_id_harga"][$i], "stok_toko" => ($query->row_array()["stok_toko"] - $data["input_qty"][$i])]);
                array_push($arr_insert, ["harga_id" => $data["input_id_harga"][$i], "order_id"  => $data["id_order"], "qty" => $data["input_qty"][$i], "harga_total" => $data["input_harga_total"][$i], "harga_potongan" => ($data["input_diskon"][$i]) ? $data["input_diskon"][$i] : json_encode([]), "user_input"  => $this->session->userdata("username"), "created_at"  => date("Y-m-d H:i:s")]);

                $sum_subtotal   += $data["input_harga_total"][$i];
                $sum_diskon     += $data["input_sum_diskon"][$i];
            } else {

                $this->db->select("stok_toko");
                $this->db->where("id_harga", $data["input_id_harga"][$i]);
                $query = $this->db->get("tbl_harga");

                array_push($arr_err, [

                    "id_harga"  => $data["input_id_harga"][$i],
                    "stok_toko" => $query->row_array()["stok_toko"],
                    "message"   => "Jumlah kuantitas melebihi stok",

                ]);

                $err++;
            }
        }

        if (!$err) {

            $this->db->trans_begin();

            $this->db->where("order_id", $data["id_order"]);
            $this->db->delete("tbl_order_detail");

            $this->db->insert_batch("tbl_order_detail", $arr_insert);
            $this->db->update_batch("tbl_harga", $arr_update, "id_harga");

            $this->db->set("status", 2);
            $this->db->set("alamat_cust", $data["alamat_cust"]);
            $this->db->set("total_order", $data["input_total"]);
            $this->db->set("tipe_pengiriman", $data["tipe_pengiriman"]);
            $this->db->set("biaya_kirim", $data["biaya_kirim"]);
            $this->db->set("user_edit", $this->session->userdata("username"));
            $this->db->set("updated_at", date("Y-m-d H:i:s"));
            $this->db->where("id_order", $data["id_order"]);
            $this->db->update("tbl_order");

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                return 0;
            } else {

                $this->db->trans_commit();

                $message = template_message("1", [
                    "nama_customer"         => $data["nama_cust"],
                    "kode_order"            => $this->secure->decrypt_url($data["kode_order"]),
                    "kode_order_enkripsi"   => $data["kode_order"],
                    "settings"              => $this->setting->getSetting(),
                    "orders"                => $this->ambilDetailOrder($this->secure->decrypt_url($data["kode_order"]))
                ]);

                $res = $this->watzap_library->sendMessage($data["hp_cust"], $message);

                // do write json to get status send (later)

                return 1;
            }
        }

        return $arr_err;
    }

    public function rollbackUbahSalesOrder($data)
    {

        $this->db->select("id_order_detail, harga_id, qty");
        $this->db->where("order_id", $data["id_order"]);
        $query = $this->db->get("tbl_order_detail");

        $this->db->trans_begin();

        foreach ($query->result_array() as $dt) {

            $this->db->set('stok_toko', 'stok_toko + ' . $dt["qty"], FALSE);
            $this->db->where("id_harga", $dt["harga_id"]);
            $this->db->update("tbl_harga");

            $this->db->where("order_detail_id", $dt['id_order_detail']);
            $this->db->delete("tbl_barang_keluar");
        }

        $this->db->where("order_id", $data['id_order']);
        $this->db->delete("tbl_report_penjualan");

        $this->db->where("order_id", $data['id_order']);
        $this->db->delete("tbl_transaksi");

        $this->db->set("status", 1);
        $this->db->where("id_order", $data["id_order"]);
        $this->db->update("tbl_order");

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return 0;
        } else {

            $this->db->trans_commit();
            return 1;
        }
                
    }

    public function rollbackSalesOrder($data)
    {
        switch ($data["step"]) {

            case 1:

                // --> ambil list detail order dari tbl_order_detail melalui id_order dan tampung kedalam sebuah array dengan kolom harga_id = harga_id dan stok_toko = qty
                // --> lakukan loop dengan memanggil query update stok_toko sesuai dengan harga_id dan juga set stok_toko(database) + stok_toko(field) = stok_baru
                // --> lakukan update pada tbl_harga pada setiap loop
                // --> lakukan update status ke 1

                $this->db->select("harga_id, qty");
                $this->db->where("order_id", $data["id_order"]);
                $query = $this->db->get("tbl_order_detail");

                $this->db->trans_begin();

                foreach ($query->result_array() as $dt) {

                    $this->db->set('stok_toko', 'stok_toko + ' . $dt["qty"], FALSE);
                    $this->db->where("id_harga", $dt["harga_id"]);
                    $this->db->update("tbl_harga");
                }

                $this->db->set("status", 1);
                $this->db->where("id_order", $data["id_order"]);
                $this->db->update("tbl_order");

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
                    1. update data dari table barang keluar yang dimana order_detailnya berada pada order_detail milik order_id menjadi is_rollback 1
                    2. ambil data dengan query yang sama namun hanya beberapa kolom, jml_keluar dan harga_id 
                    3. loop data dari result_array lakukan update satu persatu pada tbl_harga dengan menambahkan kolom stok yang ada pada table harga + jumlah_keluar
                    4. update pada table order dengan set status menjadi 99 sesuai dengan order idnya
                */

                $id_order = $data["id_order"];

                $this->db->trans_begin();

                $this->db->set("is_rollback", 1);
                $this->db->where("order_detail_id IN (SELECT id_order_detail FROM tbl_order_detail WHERE order_id = '$id_order')");
                $this->db->update("tbl_barang_keluar");

                $this->db->set("is_rollback", 1);
                $this->db->where("order_id", $id_order);
                $this->db->update("tbl_report_penjualan");

                $this->db->select("jml_keluar, harga_id");
                $this->db->where("order_detail_id IN (SELECT id_order_detail FROM tbl_order_detail WHERE order_id = '$id_order')");
                $query = $this->db->get("tbl_barang_keluar");

                $newArr = $query->result_array();

                foreach ($newArr as $na) {

                    $this->db->set('stok_toko', 'stok_toko + ' . $na["jml_keluar"], FALSE);
                    $this->db->where("id_harga", $na["harga_id"]);
                    $this->db->update("tbl_harga");
                }

                $this->db->set("status", 99);
                $this->db->where("id_order", $id_order);
                $this->db->update("tbl_order");

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    return 0;
                } else {

                    $this->db->trans_commit();

                    $message = template_message("99", [
                        "nama_customer"         => $data["nama_cust"],
                        "kode_order"            => $this->secure->decrypt_url($data["kode_order"]),
                        "settings"              => $this->setting->getSetting()
                    ]);

                    return 1;
                }

                break;

            default:
                return 0;
                break;
        }
    }

    public function uploadBuktiBayarSalesOrder($data)
    {

        /*
            1. insert kedalam transaksi terlebih dahulu
            2. ambil semua order list yang dimiliki oleh id_order
            3. insert kedalam barang keluar
            4. update orderan dengan status menjadi 3 
            5. update orderdan dengan paidst 1
        */

        $nama_customer          = $data["nama_cust"];
        $hp_customer            = $data["hp_cust"];
        $tipe_order             = $data["tipe_order"];
        $kode_order             = $this->secure->decrypt_url($data["kode_order"]);
        $kode_order_enkripsi    = $data["kode_order"];

        unset($data["kode_order"]);
        unset($data["nama_cust"]);
        unset($data["hp_cust"]);
        unset($data["tipe_order"]);

        $user = $this->session->userdata("nama_user");
        $date = date("Y-m-d H:i:s");

        $this->db->select("id_order_detail as order_detail_id, harga_id, qty as jml_keluar, 'TERJUAL' as jenis_keluar, '$user' as user_input, '$date' as created_at");
        $this->db->where("order_id", $data["order_id"]);
        $query = $this->db->get("tbl_order_detail");
        $result = $query->result_array();

        $this->db->select("tbl_order_detail.harga_id, tbl_order_detail.order_id, tbl_harga.harga_jual as harga_satuan_jual, tbl_barang.harga_pokok as harga_satuan_pokok, tbl_barang.id_barang as barang_id, tbl_order_detail.qty, (tbl_order_detail.qty * tbl_barang.harga_pokok) as total_harga_pokok, tbl_order_detail.harga_total as total_harga_jual, tbl_order_detail.harga_potongan as diskon");
        $this->db->from("tbl_order_detail");
        $this->db->join("tbl_harga", "tbl_order_detail.harga_id = tbl_harga.id_harga", "inner");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_order_detail.order_id", $data["order_id"]);

        $query = $this->db->get();
        $result2 = $query->result_array();

        $this->db->trans_begin();

        $toko_id = $data["toko_id"];
        unset($data["toko_id"]);

        $data["user_input"] = $user;
        $data["created_at"] = $date;
        $data["kode_transaksi"] = makeTransCode();

        $this->db->insert("tbl_transaksi", $data);
        $lastIdTransaksi = $this->db->insert_id();

        $report_penjualan = array_map(function ($index) use ($lastIdTransaksi, $data, $toko_id) {

            $index["transaksi_id"]  = $lastIdTransaksi;
            $index["toko_id"]       = $toko_id;
            $index["tanggal_beli"]  = $data["tanggal_beli"];
            $index["created_at"]    = date("Y-m-d H:i:s");

            if ($index["diskon"] && $index["diskon"] != "[]") {

                $index["total_diskon"] = array_sum(array_map(function ($in) {
                    return doubleval($in["harga_potongan"]);
                }, json_decode($index["diskon"], true)));
            } else {

                $index["total_diskon"] = 0;
            }

            $index["total_keuntungan"] = total_keuntungan($index["total_harga_jual"], $index["total_diskon"], $index["total_harga_pokok"]);

            unset($index["diskon"]);

            return $index;
        }, $result2);

        $this->db->insert_batch("tbl_report_penjualan", $report_penjualan);
        $this->db->insert_batch("tbl_barang_keluar", $result);

        if ($tipe_order == "Marketplace") {
            $this->db->set("status", 3);
            $this->db->set("paidst", 1);
            $this->db->where("id_order", $data["order_id"]);
            $this->db->update("tbl_order");
        } else {
            $this->db->set("status", 5);
            $this->db->set("paidst", 1);
            $this->db->where("id_order", $data["order_id"]);
            $this->db->update("tbl_order");
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return 0;
        } else {

            $this->db->trans_commit();

            $message = template_message("2", [
                "nama_customer"         => $nama_customer,
                "kode_order"            => $kode_order,
                "kode_order_enkripsi"   => $kode_order_enkripsi,
                "settings"              => $this->setting->getSetting(),
                "orders"                => $this->ambilDetailOrder($kode_order)
            ]);

            $res = $this->watzap_library->sendMessage($hp_customer, $message);

            return 1;
        }
    }

    public function konfirmasiSalesOrder($data)
    {
        switch ($data["step"]) {

            case 3:
            case 4:

                $this->db->set("status", (intval($data["step"]) + 1));
                $this->db->set(($data["step"] == 3) ? "waktu_kirim" : "waktu_terima", $data["waktu"]);
                $this->db->where("id_order", $data["id_order"]);
                $this->db->update("tbl_order");

                $message = template_message($data["step"], [

                    "nama_customer"         => $data["nama_cust"],
                    "kode_order"            => $this->secure->decrypt_url($data["kode_order"]),
                    "settings"              => $this->setting->getSetting(),
                    "waktu"                 => $data["waktu"],
                    "alamat"                => $data["alamat"]

                ]);

                $res = $this->watzap_library->sendMessage($data["hp_cust"], $message);

                return $this->db->affected_rows();

                break;
            default:

                return 0;

                break;
        }
    }

    public function ambilDetailPembeli($kode_order)
    {
        $this->db->select("nama_cust, hp_cust, alamat_cust, waktu_kirim, waktu_terima");
        $this->db->from("tbl_order");
        $this->db->where("kode_order", $kode_order);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function checkKodeOrder($kode_order)
    {
        $query = $this->db->get_where("tbl_order", ["kode_order" => $kode_order]);

        if ($query->num_rows() > 0) {

            return 1;
        }

        return 0;
    }
}
