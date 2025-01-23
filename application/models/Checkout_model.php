<?php

class Checkout_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("uuid");
    }

    public function reduceStok($id_harga, $stok_toko)
    {

        $tableHarga = $this->db->get_where('tbl_harga', ['id_harga' => $id_harga])->row_array();

        $qtyCart = $tableHarga['stok_toko'];

        // Hitung stok baru setelah pengurangan
        $new_stock = $qtyCart - $stok_toko;

        // Update stok dalam database
        $this->db->where('id_harga', $id_harga);
        $this->db->update('tbl_harga', ['stok_toko' => $new_stock]);

        // Return true jika pengurangan berhasil
        return true;
    }


    public function prosesCheckout($data_keranjang, $dataCustomer)
    {
        $err_code = 0;
        $message = "";
        $total_order = 0;

        $kode_order = str_replace("-", "", $this->uuid->v4());

        $data_order_detail = [];
        $data_report_penjualan = [];

        //init diskon 
        $all_total_diskon_item_with_global = 0;
        $all_total_diskon_item_with_global_into_transaction = 0;

        foreach ($data_keranjang as $dk) {

            $total_order += ($dk["price"] * $dk["qty"]);

            if ($dk["options"]["diskon"]) {

                $data_diskon = json_decode($dk["options"]["diskon"], TRUE);

                foreach ($data_diskon as $dd) {

                    $total_order -= $dd["harga_potongan"];
                }
            }
        }


        $this->db->trans_begin();

        /**
         * TODO : Insert To Order
         */
        $this->db->set("toko_id", $dataCustomer['toko_id']);
        $this->db->set("kode_order", $kode_order);
        $this->db->set("nama_cust", $dataCustomer['nama_cust']);
        $this->db->set("hp_cust", $dataCustomer['hp_cust']);
        $this->db->set("alamat_cust", $dataCustomer['alamat_cust']);
        $this->db->set("tipe_order", 'Kasir');
        $this->db->set("tipe_pengiriman", $dataCustomer['tipe_pengiriman']);
        $this->db->set("biaya_kirim", $dataCustomer['biaya_kirim']);
        // $this->db->set("total_order", $total_order);
        $this->db->set("total_order", $dataCustomer['total_order']);
        $this->db->set("paidst", 1);
        $this->db->set("status", $dataCustomer['status']);
        $this->db->set("user_input", $this->session->userdata("nama_user"));
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_order");
        $last_id_order = $this->db->insert_id();


        /**
         * TODO : Insert To Transaksi
         */
        $this->db->set("order_id", $last_id_order);
        $this->db->set("kode_transaksi", makeTransCode());
        $this->db->set("terbayar", $dataCustomer['terbayar']);
        $this->db->set("kembalian", $dataCustomer['kembalian']);
        $this->db->set("tagihan_cart", $dataCustomer['tagihan_keranjang']);
        // $this->db->set("total_diskon", $dataCustomer['total_diskon']);
        $this->db->set("total_diskon", $dataCustomer['contain_global_discount'] + $dataCustomer['total_diskon']);
        $this->db->set("tagihan_after_diskon", $dataCustomer['tagihan_after_diskon']);
        $this->db->set("total_biaya_kirim", $dataCustomer['biaya_kirim']);
        $this->db->set("total_tagihan", $dataCustomer['total_tagihan']);
        $this->db->set("bank_id", $dataCustomer["bank_id"]);
        $this->db->set("payment_id", $dataCustomer["payment_id"]);
        $this->db->set("tipe_transaksi", $dataCustomer["tipe_transaksi"]);
        $this->db->set("is_active", 1);
        $this->db->set("user_input", $this->session->userdata("nama_user"));
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->insert('tbl_transaksi');
        $last_id_transaksi = $this->db->insert_id();



        /**
         * TODO : Insert To Order Detail
         */
        foreach ($data_keranjang as $dk) {

            $keranjang["harga_id"]          = $dk["id"];
            $keranjang["order_id"]          = $last_id_order;
            $keranjang["qty"]               = $dk["qty"];
            $keranjang["harga_total"]       = ($dk["price"] * $dk["qty"]);
            $keranjang["harga_potongan"]    = (($dk["options"]["diskon"]) ? $dk["options"]["diskon"] : json_encode([]));
            $keranjang["user_input"]        = $this->session->userdata("nama_user");
            $keranjang["created_at"]        = date("Y-m-d H:i:s");

            array_push($data_order_detail, $keranjang);
        }
        $this->db->insert_batch("tbl_order_detail", $data_order_detail);


        foreach ($data_keranjang as $dk_report) {
            $keranjangReport["toko_id"]          = $dataCustomer['toko_id'];
            $keranjangReport["order_id"]          = $last_id_order;
            $keranjangReport["transaksi_id"]          = $last_id_transaksi;
            $keranjangReport["harga_id"]          = $dk_report["id"];
            $keranjangReport["barang_id"]          = $dk_report["options"]["barang_id"];
            $keranjangReport["harga_satuan_pokok"]          = $dk_report["options"]["harga_pokok"];
            $keranjangReport["harga_satuan_jual"]  = $dk_report['price'];
            $keranjangReport["qty"]  = $dk_report['qty'];
            $keranjangReport["total_harga_pokok"]  = (intval($dk_report["options"]["harga_pokok"]) * intval($dk_report['qty']));
            $keranjangReport["total_harga_jual"]  = (intval($dk_report['price']) * intval($dk_report['qty']));

            // $keranjangReport["total_diskon"] = ($dk_report["options"]["diskon"] != "")  ? array_reduce(json_decode($dk_report["options"]["diskon"], TRUE), function ($prev, $current) {
            //     return $prev += $current['harga_potongan'];
            // }) : 0;

            if (!empty($dk_report["options"]["diskon"])) {
                $data_diskon = json_decode($dk_report["options"]["diskon"], TRUE);
                $all_total_diskon_item_with_global = array_reduce($data_diskon, function ($prev, $current) {
                    return $prev += $current['harga_potongan'];
                }, 0);
            }

            // Tambahkan contain_global_discount jika ada
            $all_total_diskon_item_with_global += $dataCustomer['contain_global_discount'];

            $keranjangReport["total_diskon"] = $all_total_diskon_item_with_global;


            $keranjangReport["total_keuntungan"]  = total_keuntungan($keranjangReport["total_harga_jual"], $keranjangReport["total_diskon"], $keranjangReport["total_harga_pokok"]);

            $keranjangReport["tanggal_beli"]        = date('Y-m-d H:i:s');
            $keranjangReport["created_at"]        = date("Y-m-d H:i:s");
            array_push($data_report_penjualan, $keranjangReport);
        }
        $this->db->insert_batch("tbl_report_penjualan", $data_report_penjualan);

        /**
         * TODO : Pengurangan Stok Ke Table Harga
         */
        foreach ($data_keranjang as $dataCart) {
            $this->reduceStok($dataCart['id'], $dataCart['qty']);
        }

        $user = $this->session->userdata('nama_user');
        $date = date('Y-m-d H:i:s');
        $this->db->select("id_order_detail as order_detail_id, harga_id, qty as jml_keluar, 'TERJUAL' as jenis_keluar, '$user' as user_input, '$date' as created_at");
        $this->db->where("order_id", $last_id_order);
        $query = $this->db->get("tbl_order_detail");
        $resultOrderDetail = $query->result_array();
        $this->db->insert_batch("tbl_barang_keluar", $resultOrderDetail);


        /**
         * TODO : Buatkan Sebuah JSON untuk Log History Cetak Struk Atau Nota
         */
        $getDataOrderLast = $this->db->get_where('tbl_order', ['id_order' => $last_id_order])->row_array();
        $getDataTransaksiLast = $this->db->get_where('tbl_transaksi', ['id_transaksi' => $last_id_transaksi])->row_array();
        $kodeOrderLastToJson = $getDataOrderLast['kode_order'];
        $kodeTransaksiLastToJson = $getDataTransaksiLast['kode_transaksi'];
        $userInputLastToJson = $getDataTransaksiLast['user_input'];
        $createdLastToJson = $getDataTransaksiLast['created_at'];

        $nameFileJson = NAME_LOG_PRINT_NOTA_HISTORY;
        $dataLogJson = [
            'kode_order' => $kodeOrderLastToJson,
            'kode_transaksi' => $kodeTransaksiLastToJson,
            'user_input' => $userInputLastToJson,
            'created_at' => $createdLastToJson,
        ];
        createLogHistoryJSON($nameFileJson, $dataLogJson);


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            $err_code++;
            $message = "Data Gagal Diinsert";
            $resultResponse = [
                'err_code' => $err_code,
                'message' => $message,
                'transactionid' => '',
            ];
            // echo json_encode($resultResponse);
            // return 1;
            return $resultResponse;
        } else {
            $this->db->trans_commit();
            $err_code = 0;
            $message = "Data Berhasil Diinsert";
            $resultResponse = [
                'err_code' => $err_code,
                'message' => $message,
                'transactionid' => $last_id_transaksi
            ];

            //TODO : hilangkan cart
            $this->cart->destroy();
            return $resultResponse;
        }
    }
}
