<?php

class Sales_order extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Sales_order_model", "sales_order");
        $this->load->model("Barang_model", "barang");
        $this->load->model("Harga_model", "harga");
        $this->load->model("Toko_model", "toko");
        $this->load->model("Bank_model", "bank");
        $this->load->model("Payment_model", "payment");
        $this->load->model("Setting_model", "setting");
        $this->load->model("Transaksi_model", "transaksi");
        $this->load->library("watzap_library");
    }

    public function index()
    {
        $this->view["title_menu"]           = "Kasir";
        $this->view["title"]                = "Sales order";
        $this->view["content"]              = "sales_order/v_sales_order_index";
        $this->view["data_toko"]            = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaSalesOrder()
    {

        $newArr = [];
        $data   = $this->sales_order->ajaxAmbilSemuaSalesOrder();

        $no = 1;
        foreach ($data as $d) {

            if ($d["status"] >= 99) {

                $banner = '<div class="btn-group" role="group" aria-label="Basic example">'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("99" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-danger text-white">Kirim notifikasi pembatalan</a>'
                    . '</div>';
            } else if ($d["status"] > 4) {

                $banner = '<div class="btn-group" role="group" aria-label="Basic example">'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("1" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-primary text-white">Kirim notifikasi konfirmasi</a>'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("2" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-info text-white">Kirim notifikasi pembayaran</a>'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("3" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-warning text-white">Kirim notifikasi pengiriman</a>'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("4" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-success text-white">Kirim notifikasi penerimaan</a>'
                    . '</div>';
            } else if ($d["status"] > 3) {

                $banner = '<div class="btn-group" role="group" aria-label="Basic example">'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("1" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-primary text-white">Kirim notifikasi konfirmasi</a>'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("2" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-info text-white">Kirim notifikasi pembayaran</a>'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("3" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-warning text-white">Kirim notifikasi pengiriman</a>'
                    . '</div>';
            } else if ($d["status"] > 2) {

                $banner = '<div class="btn-group" role="group" aria-label="Basic example">'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("1" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-primary text-white">Kirim notifikasi konfirmasi</a>'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("2" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-info text-white">Kirim notifikasi pembayaran</a>'
                    . '</div>';
            } else if ($d["status"] > 1) {

                $banner = '<div class="btn-group" role="group" aria-label="Basic example">'
                    . '<a href="' . site_url('kasir/sales_order/notifikasi/' . $this->secure->encrypt_url("1" . "-" . $d["kode_order"])) . '" class="btn btn-sm btn-primary text-white">Kirim notifikasi konfirmasi</a>'
                    . '</div>';
            }

            $row    = [];
            $row[]  = $no;
            $row[]  = $d["nama_toko"];
            $row[]  = "#<a href='" . site_url('kasir/sales_order/detail/') . $this->secure->encrypt_url($d["kode_order"]) . "' class='text-primary fw-bold'>" . $d['kode_order'] . "</a>" . ((($d["id_transaksi"]) ? " | <a href='" . site_url('kasir/scan/print_nota_raw?transid=' . base64_encode($d["id_transaksi"])) . "' class='btn btn-sm btn-info'><i class='bx bx-printer'></i> Cetak struk</a>" : ""));
            $row[]  = $d["nama_cust"];
            $row[]  = $d["hp_cust"];
            $row[]  = ($d['status'] == 1) ? "<small class='text-danger'>Belum di konfirmasi</small>" : (($d['status'] == 2) ? "<small class='text-success'>Sudah di konfirmasi</small>" : (($d['status'] == 3) ? "<small class='text-primary'>Dikemas</small>" : (($d['status'] == 4) ? "<small class='text-warning'>Dikemas</small>" : (($d['status'] == 5) ? "<small class='text-success'>Selesai</small>" : "<small class='text-danger'>Dibatalkan</small>")))) . (($d["hp_cust"]) ? " | " . $banner : "");
            $row[]  = ($d['paidst']) ? "<small class='text-success'>Lunas</small>" : "<small class='text-danger'>Belum Lunas</small>";
            $row[]  = $d['tipe_order'];
            $row[]  = ($d['tipe_pengiriman']) ? $d['tipe_pengiriman'] : " - ";
            $row[]  = ($d['biaya_kirim']) ? number_format($d['biaya_kirim'], 0, ".", ".") : 0;
            $row[]  = ($d['total_order']) ? number_format($d['total_order'], 0, ".", ".") : 0;
            $row[]  = convertDate($d['created_at']);
            $row[]  = ($d['updated_at']) ? convertDate($d['updated_at']) : "-";

            array_push($newArr, $row);
            $no++;
        }

        $data_json["draw"]              = $this->input->post("draw");
        $data_json["data"]              = $newArr;
        $data_json["recordsTotal"]      = $this->sales_order->ajaxAmbilHitungSemuaSalesOrder();
        $data_json["recordsFiltered"]   = $this->sales_order->ajaxAmbilFilterSemuaSalesOrder();

        echo json_encode($data_json);
    }

    public function detailSalesOrder($kode_order)
    {
        $detail_order = $this->sales_order->ambilDetailOrder($this->secure->decrypt_url($kode_order));

        if ($detail_order) {

            $this->view["title_menu"]               = "Kasir";
            $this->view["title"]                    = "Sales order";
            $this->view["content"]                  = ($detail_order[0]["status"] == 1) ? "sales_order/v_sales_order_detail_s1" : "sales_order/v_sales_order_detail";
            $this->view["data_detail_order"]        = $detail_order;
            $this->view["data_bank"]                = $this->bank->ambilSemuaBank();

            if ($detail_order[0]["status"] == 1) {
                $this->view["data_list_barang"]     = $this->barang->getBarangHargaToko($detail_order[0]["toko_id"]);
            }

            $this->load->view("layout/wrapper", $this->view);
        } else {

            $this->session->set_flashdata("gagal", "Data tidak ada");
            redirect("kasir/sales_order");
        }
    }

    public function ubahSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_harga",
                "label" => "Id harga",
                "rules" => "required|trim"
            ],

            [
                "field" => "qty",
                "label" => "Kuantitas",
                "rules" => "required|trim|numeric|greater_than_equal_to[0]"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data = [

                "id_harga"  => htmlspecialchars($this->input->post("id_harga")),
                "qty"       => htmlspecialchars($this->input->post("qty"))

            ];

            $res = $this->sales_order->perhitunganSalesOrderByHargaItem($data);

            if ($res) {

                $data_json["status"]        = "berhasil";
                $data_json["response"]      = $res;
            } else {

                $data_json["status"]        = "gagal";
                $data_json["response"]      = "Item tidak ada !";
            }
        } else {

            $data_json["status"]          = "error";
            $data_json["error_id_harga"]  = form_error("id_harga");
            $data_json["error_qty"]       = form_error("qty");
        }

        echo json_encode($data_json);
    }

    public function tambahSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_harga",
                "label" => "Id harga",
                "rules" => "required|trim"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id_harga = htmlspecialchars($this->input->post("id_harga"));

            $res = $this->harga->ambilHargaJoinDenganBarang($id_harga);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = $res;
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data tidak ada!";
            }
        } else {

            $data_json["status"]        = "error";
            $data_json["err_id_harga"]  = form_error("id_harga");
        }

        echo json_encode($data_json);
    }

    public function submitSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "nama_cust",
                "label" => "Nama",
                "rules" => "trim"
            ],

            [
                "field" => "hp_cust",
                "label" => "Hp",
                "rules" => "trim|numeric"
            ],

            [
                "field" => "alamat_cust",
                "label" => "Alamat",
                "rules" => "trim"
            ],

            [
                "field" => "tipe_pengiriman",
                "label" => "Tipe pengiriman",
                "rules" => "trim"
            ],

            [
                "field" => "biaya_kirim",
                "label" => "Biaya kirim",
                "rules" => "trim|numeric"
            ],

            [
                "field" => "id_order",
                "label" => "Id order",
                "rules" => "required|trim"
            ],

            [
                "field" => "kode_order",
                "label" => "Kode order",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_qty[]",
                "label" => "Qty",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_diskon[]",
                "label" => "Diskon",
                "rules" => "trim"
            ],

            [
                "field" => "input_sum_diskon[]",
                "label" => "Sum diskon",
                "rules" => "trim"
            ],

            [
                "field" => "input_harga_total[]",
                "label" => "Harga total",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_id_harga[]",
                "label" => "Id harga",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_total",
                "label" => "Total",
                "rules" => "required|trim|numeric"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data = [

                "nama_cust"             => htmlspecialchars($this->input->post("nama_cust")),
                "hp_cust"               => htmlspecialchars($this->input->post("hp_cust")),
                "alamat_cust"           => htmlspecialchars($this->input->post("alamat_cust")),
                "tipe_pengiriman"       => htmlspecialchars($this->input->post("tipe_pengiriman")),
                "biaya_kirim"           => htmlspecialchars($this->input->post("biaya_kirim")),
                "id_order"              => htmlspecialchars($this->input->post("id_order")),
                "kode_order"            => htmlspecialchars($this->input->post("kode_order")),
                "input_qty"             => $this->input->post("input_qty"),
                "input_diskon"          => $this->input->post("input_diskon"),
                "input_sum_diskon"      => $this->input->post("input_sum_diskon"),
                "input_harga_total"     => $this->input->post("input_harga_total"),
                "input_id_harga"        => $this->input->post("input_id_harga"),
                "input_total"           => htmlspecialchars($this->input->post("input_total"))

            ];

            // cek stok first
            $res = $this->sales_order->eksekusiOrderan($data);

            if ($res === 1) {

                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "Data berhasil di submit";
            } else if ($res === 0) {

                $data_json["status"]            = "gagal";
                $data_json["response"]          = "Data gagal di submit";
            } else if (is_array($res)) {

                $data_json["status"]            = "error";
                $data_json["response"]          = json_encode($res);
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_nama_cust"]         = form_error("nama_cust");
            $data_json["err_hp_cust"]           = form_error("hp_cust");
            $data_json["err_alamat_cust"]       = form_error("alamat_cust");
            $data_json["err_tipe_pengiriman"]   = form_error("tipe_pengiriman");
            $data_json["err_id_order"]          = form_error("id_order");
            $data_json["err_input_total"]       = form_error("input_total");
            $data_json["err_kode_error"]        = form_error("kode_error");
        }

        echo json_encode($data_json);
    }

    public function uploadBuktiBayarSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_order",
                "label" => "Id order",
                "rules" => "required|trim"
            ],

            [
                "field" => "id_toko",
                "label" => "Id toko",
                "rules" => "required|trim"
            ],

            [
                "field" => "bank_id",
                "label" => "Bank",
                "rules" => "trim"
            ],

            [
                "field" => "payment_id",
                "label" => "Payment",
                "rules" => "trim"
            ],

            [
                "field" => "nama_cust",
                "label" => "Nama customer",
                "rules" => "trim"
            ],

            [
                "field" => "hp_cust",
                "label" => "Hp customer",
                "rules" => "trim"
            ],

            [
                "field" => "kode_order",
                "label" => "Kode order",
                "rules" => "required|trim"
            ],

            [
                "field" => "total_tagihan",
                "label" => "Total tagihan",
                "rules" => "required|trim|numeric"
            ],

            [
                "field" => "nominal_pembayaran",
                "label" => "Nominal Pembayaran",
                "rules" => "trim"
            ],

            [
                "field" => "nominal_kembalian",
                "label" => "Nominal Kembalian",
                "rules" => "trim"
            ],


            [
                "field" => "total_diskon",
                "label" => "Total diskon",
                "rules" => "trim|numeric"
            ],

            [
                "field" => "tagihan_cart",
                "label" => "tagihan_cart",
                "rules" => "required|trim|numeric"
            ],

            [
                "field" => "tagihan_after_diskon",
                "label" => "tagihan_after_diskon",
                "rules" => "required|trim|numeric"
            ],

            [
                "field" => "total_biaya_kirim",
                "label" => "total_biaya_kirim",
                "rules" => "trim|numeric"
            ],

            [
                "field" => "tanggal_beli",
                "label" => "Tanggal beli",
                "rules" => "trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data = [
                "order_id"              => htmlspecialchars($this->input->post("id_order")),
                "toko_id"               => htmlspecialchars($this->input->post("id_toko")),
                "tipe_order"            => htmlspecialchars($this->input->post("tipe_order")),
                "bank_id"               => htmlspecialchars($this->input->post("bank_id")),
                "payment_id"            => htmlspecialchars($this->input->post("payment_id")),
                "kode_order"            => htmlspecialchars($this->input->post("kode_order")),
                "nama_cust"             => htmlspecialchars($this->input->post("nama_cust")),
                "hp_cust"               => htmlspecialchars($this->input->post("hp_cust")),
                "kembalian"             => htmlspecialchars(removeThousandSeparator($this->input->post("nominal_kembalian"))),
                "total_tagihan"         => htmlspecialchars($this->input->post("total_tagihan")),
                "total_diskon"          => htmlspecialchars($this->input->post("total_diskon")),
                "tagihan_cart"          => htmlspecialchars($this->input->post("tagihan_cart")),
                "tagihan_after_diskon"  => htmlspecialchars($this->input->post("tagihan_after_diskon")),
                "total_biaya_kirim"     => htmlspecialchars($this->input->post("total_biaya_kirim")),
                "tanggal_beli"          => ($this->input->post("tanggal_beli")) ? htmlspecialchars($this->input->post("tanggal_beli")) : date("Y-m-d H:i:s")
            ];

            $tipe_order = $this->input->post("tipe_order");

            $data["bukti_tf"] = "";

            if ($tipe_order == "Marketplace") {
                if (!empty($_FILES["bukti_bayar"]["name"])) {

                    $config["file_name"]        = "bukti-bayar-" . date("Y-m-d-H-i-s");
                    $config['upload_path']      = './assets/file_bukti_bayar';
                    $config['allowed_types']    = 'jpg|jpeg|png';
                    $config['max_size']         = '2500';
                    $config['max_width']        = '800';
                    $config['max_height']       = '1920';

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('bukti_bayar')) {

                        $data["bukti_tf"]   = $this->upload->data("file_name");

                    } else {

                        $data_json["status"]            = "error";
                        $data_json["err_bukti_bayar"]   = $this->upload->display_errors();
                    }
                }
            }

            if ($this->input->post("bank_id")) {
                $data["tipe_transaksi"] = "TRANSFER";
            } else {
                $data["tipe_transaksi"] = "TUNAI";
            }

            $data["terbayar"] = htmlspecialchars($this->input->post("terbayar"));

            if ($this->input->post("nominal_pembayaran")) {
                $data["terbayar"] = htmlspecialchars(removeThousandSeparator($this->input->post("nominal_pembayaran")));
            }

             // Do database query here
            $res = $this->sales_order->uploadBuktiBayarSalesOrder($data);

            if ($res) {

                $data_json["status"]            = "berhasil";
                $data_json["response"]          = "Bukti bayar berhasil di upload";
            } else {

                $data_json["status"]            = "gagal";
                $data_json["response"]          = "Bukti bayar gagal di upload";
            }

        } else {

            $data_json["status"]                = "error";
            $data_json["err_id_order"]          = form_error("id_order");
            $data_json["err_bank_id"]           = form_error("bank_id");
            $data_json["err_payment_id"]        = form_error("payment_id");
            $data_json["err_kode_order"]        = form_error("kode_order");
            $data_json["err_nama_cust"]         = form_error("nama_cust");
            $data_json["err_hp_cust"]           = form_error("hp_cust");
            $data_json["err_nominal_pembayaran"]= form_error("nominal_pembayaran");
            $data_json["err_nominal_kembalian"] = form_error("nominal_kembalian");
            $data_json["err_tanggal_beli"]      = form_error("tanggal_beli");
        }

        echo json_encode($data_json);
    }

    public function rollbackUbahSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_order",
                "label"     => "id order",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "step",
                "label"     => "step",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "status",
                "label"     => "Status Order",
                "rules"     => "trim|required"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["id_order"]           = htmlspecialchars($this->input->post("id_order"));
            $data["step"]               = htmlspecialchars($this->input->post("step"));
            $data["status"]             = htmlspecialchars($this->input->post("status"));
            
            $res = $this->sales_order->rollbackUbahSalesOrder($data);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = "Data berhasil di rollback";
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data gagal di rollback";
            }
        } else {

            $data_json["status"]        = "error";
            $data_json["err_id_order"]  = form_error("id_order");
            $data_json["err_step"]      = form_error("step");
            $data_json["err_status"]   = form_error("status");
        }

        echo json_encode($data_json);
    }

    public function rollbackSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_order",
                "label"     => "id order",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "step",
                "label"     => "step",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "nama_cust",
                "label"     => "Nama",
                "rules"     => "trim"
            ],

            [
                "field"     => "hp_cust",
                "label"     => "Hp",
                "rules"     => "trim"
            ],

            [
                "field"     => "kode_order",
                "label"     => "Kode order",
                "rules"     => "trim|required"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["id_order"]           = htmlspecialchars($this->input->post("id_order"));
            $data["step"]               = htmlspecialchars($this->input->post("step"));
            $data["nama_cust"]          = htmlspecialchars($this->input->post("nama_cust"));
            $data["hp_cust"]            = htmlspecialchars($this->input->post("hp_cust"));
            $data["kode_order"]         = htmlspecialchars($this->input->post("kode_order"));

            $res = $this->sales_order->rollbackSalesOrder($data);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = "Data berhasil di rollback";
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data gagal di rollback";
            }
        } else {

            $data_json["status"]        = "error";
            $data_json["err_id_order"]  = form_error("id_order");
            $data_json["err_step"]      = form_error("step");
        }

        echo json_encode($data_json);
    }

    public function konfirmasiSalesOrder()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_order",
                "label"     => "id order",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "step",
                "label"     => "step",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "kode_order",
                "label"     => "Kode order",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "nama_cust",
                "label"     => "Nama customer",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "hp_cust",
                "label"     => "Hp customer",
                "rules"     => "required|trim"
            ],

            [
                "field"     => "alamat",
                "label"     => "Alamat",
                "rules"     => "trim"
            ],

            [
                "field"     => "waktu",
                "label"     => "Tanggal",
                "rules"     => "required|trim"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data = [
                "id_order"      => htmlspecialchars($this->input->post("id_order")),
                "step"          => htmlspecialchars($this->input->post("step")),
                "nama_cust"     => htmlspecialchars($this->input->post("nama_cust")),
                "hp_cust"       => htmlspecialchars($this->input->post("hp_cust")),
                "kode_order"    => htmlspecialchars($this->input->post("kode_order")),
                "waktu"         => htmlspecialchars($this->input->post("waktu")),
                "alamat"        => htmlspecialchars($this->input->post("alamat")),
            ];

            $res = $this->sales_order->konfirmasiSalesOrder($data);

            if ($res) {

                $data_json["status"]   = "berhasil";
                $data_json["response"] = "Konfirmasi berhasil di lakukan";
            } else {

                $data_json["status"]   = "gagal";
                $data_json["response"] = "Konfirmasi gagal di lakukan";
            }
        } else {


            $data_json["status"]        = "error";
            $data_json["err_id_order"]  = form_error("id_order");
            $data_json["err_step"]      = form_error("step");
            $data_json["err_waktu"]     = form_error("waktu");
        }

        echo json_encode($data_json);
    }

    public function ajaxRekeningBank()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id",
                "label" => "Id",
                "rules" => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $id = htmlspecialchars($this->input->post("id"));

            $res = $this->payment->ambilSemuaPaymentBerdasarkanBankId($id);

            $data_json["status"]    = "berhasil";
            $data_json["response"]  = $res;
        } else {

            $data_json["status"]    = "error";
            $data_json["response"]  = "Parameter tidak sesuai";
        }

        echo json_encode($data_json);
    }

    public function waNotifikasi($enkripsi)
    {
        $enkripsi   = $this->secure->decrypt_url($enkripsi);
        $enkripsi   = explode("-", $enkripsi);

        if (array_key_exists(0, $enkripsi) && array_key_exists(1, $enkripsi) && ($this->sales_order->checkKodeOrder($enkripsi[1]))) {

            $status     = $enkripsi[0];
            $kode_order = $enkripsi[1];

            $options = [];

            switch ($status) {

                case "1":

                    $d = $this->sales_order->ambilDetailPembeli($kode_order);

                    $options = [
                        "nama_customer"         => $d["nama_cust"],
                        "kode_order"            => $kode_order,
                        "kode_order_enkripsi"   => $this->secure->encrypt_url($kode_order),
                        "settings"              => $this->setting->getSetting(),
                        "orders"                => $this->sales_order->ambilDetailOrder($kode_order)
                    ];

                    break;
                case "2":

                    $d = $this->sales_order->ambilDetailPembeli($kode_order);

                    $options = [
                        "nama_customer"         => $d["nama_cust"],
                        "kode_order"            => $kode_order,
                        "kode_order_enkripsi"   => $this->secure->encrypt_url($kode_order),
                        "settings"              => $this->setting->getSetting(),
                        "orders"                => $this->sales_order->ambilDetailOrder($kode_order)
                    ];

                    break;
                case "3":

                    $d = $this->sales_order->ambilDetailPembeli($kode_order);

                    $options = [
                        "nama_customer"         => $d["nama_cust"],
                        "kode_order"            => $kode_order,
                        "settings"              => $this->setting->getSetting(),
                        "waktu"                 => $d["waktu_kirim"],
                        "alamat"                => $d["alamat"]
                    ];
                    break;
                case "4":
                    $d = $this->sales_order->ambilDetailPembeli($kode_order);

                    $options = [
                        "nama_customer"         => $d["nama_cust"],
                        "kode_order"            => $kode_order,
                        "settings"              => $this->setting->getSetting(),
                        "waktu"                 => $d["waktu_terima"],
                        "alamat"                => $d["alamat"]
                    ];

                    break;
                case "99":

                    $d = $this->sales_order->ambilDetailPembeli($kode_order);

                    $options = [
                        "nama_customer"         => $d["nama_cust"],
                        "kode_order"            => $kode_order,
                        "settings"              => $this->setting->getSetting()
                    ];

                    break;
            }

            $message = template_message($status, $options);
            $res = $this->watzap_library->sendMessage($d["hp_cust"], $message);

            if ($res["status"] == "200") {

                $this->session->set_flashdata("berhasil", $res["message"]);
            } else {

                $this->session->set_flashdata("gagal", $res["message"]);
            }

            redirect("kasir/sales_order");
        } else {

            $this->session->set_flashdata("gagal", "Format data tidak sesuai");
            redirect("kasir/sales_order");
        }
    }
}
