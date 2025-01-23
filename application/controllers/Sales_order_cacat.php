<?php

class Sales_order_cacat extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("Sales_order_cacat_model", "sales_order_cacat");
        $this->load->model("Barang_model", "barang");
        $this->load->model("Harga_model", "harga");
        $this->load->model("Toko_model", "toko");
        $this->load->model("Bank_model", "bank");
        $this->load->model("Payment_model", "payment");
        $this->load->model("Setting_model", "setting");
    }

    public function index()
    {
        $this->view["title_menu"]           = "Barang Cacat";
        $this->view["title"]                = "Sales Order Cacat";
        $this->view["content"]              = "sales_order_cacat/v_sales_order_cacat_index";
        $this->view["data_toko"]            = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaSalesOrderCacat()
    {

        $newArr = [];
        $data   = $this->sales_order_cacat->ajaxAmbilSemuaSalesOrderCacat();

        $no = 1;
        foreach ($data as $d) {

            $row    = [];
            $row[]  = $no;
            $row[]  = $d["nama_toko"];
            $row[]  = "#<a href='" . site_url('barang_cacat/sales_order_cacat/detail/') . $this->secure->encrypt_url($d["kode_order"]) . "' class='text-primary fw-bold'>" . $d['kode_order'] . "</a>";
            $row[]  = $d["nama_cust"];
            $row[]  = $d["hp_cust"];
            $row[]  = ($d['status'] == 1) ? "<small class='text-danger'>Belum di konfirmasi</small>" : (($d['status'] == 2) ? "<small class='text-success'>Sudah di konfirmasi</small>" : (($d['status'] == 3) ? "<small class='text-primary'>Dikemas</small>" : (($d['status'] == 4) ? "<small class='text-warning'>Dikemas</small>" : (($d['status'] == 5) ? "<small class='text-success'>Selesai</small>" : "<small class='text-danger'>Dibatalkan</small>"))));
            $row[]  = ($d['paidst']) ? "<small class='text-success'>Lunas</small>" : "<small class='text-danger'>Belum Lunas</small>";
            // $row[]  = $d['tipe_order'];
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
        $data_json["recordsTotal"]      = $this->sales_order_cacat->ajaxAmbilHitungSemuaSalesOrderCacat();
        $data_json["recordsFiltered"]   = $this->sales_order_cacat->ajaxAmbilFilterSemuaSalesOrderCacat();

        echo json_encode($data_json);
    }

    public function detailSalesOrderCacat($kode_order)
    {
        $detail_order = $this->sales_order_cacat->ambilDetailOrderCacat($this->secure->decrypt_url($kode_order));

        if ($detail_order) {

            $this->view["title_menu"]               = "Barang Cacat";
            $this->view["title"]                    = "Sales Order Cacat";
            $this->view["content"]                  = ($detail_order[0]["status"] == 1) ? "sales_order_cacat/v_sales_order_cacat_detail_s1" : "sales_order_cacat/v_sales_order_cacat_detail";
            $this->view["data_detail_order"]        = $detail_order;
            $this->view["data_bank"]                = $this->bank->ambilSemuaBank();

            if ($detail_order[0]["status"] == 1) {
                $this->view["data_list_barang"]     = $this->barang->getBarangCacat($detail_order[0]["toko_id"]);
            }

            $this->load->view("layout/wrapper", $this->view);
        } else {

            $this->session->set_flashdata("gagal", "Data tidak ada");
            redirect("barang_cacat/sales_order_cacat");
        }
    }

    public function ubahSalesOrderCacat()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_barang_cacat",
                "label" => "Id barang cacat",
                "rules" => "required|trim"
            ],

            [
                "field" => "qty_cacat",
                "label" => "Kuantitas",
                "rules" => "required|trim|numeric|greater_than_equal_to[0]"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data = [

                "id_barang_cacat"  => htmlspecialchars($this->input->post("id_barang_cacat")),
                "qty_cacat"       => htmlspecialchars($this->input->post("qty_cacat"))

            ];

            $res = $this->sales_order_cacat->perhitunganSalesOrderCacatByHargaItem($data);

            if ($res) {

                $data_json["status"]        = "berhasil";
                $data_json["response"]      = $res;
            } else {

                $data_json["status"]        = "gagal";
                $data_json["response"]      = "Item tidak ada !";
            }
        } else {

            $data_json["status"]          = "error";
            $data_json["error_id_barang_cacat"]  = form_error("id_barang_cacat");
            $data_json["error_qty_cacat"]       = form_error("qty_cacat");
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

    public function submitSalesOrderCacat()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "nama_cust",
                "label" => "Nama",
                "rules" => "required|trim"
            ],

            [
                "field" => "hp_cust",
                "label" => "Hp",
                "rules" => "required|trim|numeric|min_length[10]|max_length[14]"
            ],

            [
                "field" => "alamat_cust",
                "label" => "Alamat",
                "rules" => "required|trim"
            ],

            [
                "field" => "tipe_pengiriman",
                "label" => "Tipe pengiriman",
                "rules" => "required|trim"
            ],

            [
                "field" => "biaya_kirim",
                "label" => "Biaya kirim",
                "rules" => "trim|numeric"
            ],

            [
                "field" => "id_order_cacat",
                "label" => "Id order cacat",
                "rules" => "required|trim"
            ],

            [
                "field" => "kode_order",
                "label" => "Kode order",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_qty_cacat[]",
                "label" => "Qty",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_harga_detail_cacat[]",
                "label" => "Harga detail cacat",
                "rules" => "required|trim"
            ],

            [
                "field" => "sub_total_cacat[]",
                "label" => "Sub total cacat",
                "rules" => "required|trim"
            ],

            [
                "field" => "input_id_barang_cacat[]",
                "label" => "Id barang cacat",
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
                "id_order_cacat"        => htmlspecialchars($this->input->post("id_order_cacat")),
                "kode_order"            => htmlspecialchars($this->input->post("kode_order")),
                "input_qty_cacat"       => $this->input->post("input_qty_cacat"),
                "input_harga_detail_cacat" => $this->input->post("input_harga_detail_cacat"),
                "sub_total_cacat"       => $this->input->post("sub_total_cacat"),
                "input_id_barang_cacat" => $this->input->post("input_id_barang_cacat"),
                "input_total"           => htmlspecialchars($this->input->post("input_total"))

            ];

            // cek stok first
            $res = $this->sales_order_cacat->eksekusiOrderanCacat($data);

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
            $data_json["err_id_order_cacat"]    = form_error("id_order_cacat");
            $data_json["err_input_harga_detail_cacat"] = form_error("input_harga_detail_cacat");
            $data_json["err_kode_order"]        = form_error("kode_order");
        }

        echo json_encode($data_json);
    }

    public function uploadBuktiBayarSalesOrderCacat()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field" => "id_order_cacat",
                "label" => "Id order cacat",
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
                "rules" => "required|trim"
            ],

            [
                "field" => "payment_id",
                "label" => "Payment",
                "rules" => "required|trim"
            ],

            [
                "field" => "nama_cust",
                "label" => "Nama customer",
                "rules" => "required|trim"
            ],

            [
                "field" => "hp_cust",
                "label" => "Hp customer",
                "rules" => "required|trim"
            ],

            [
                "field" => "kode_order",
                "label" => "Kode order",
                "rules" => "required|trim"
            ],

            [
                "field" => "tipe_transaksi",
                "label" => "Tipe transaksi",
                "rules" => "required|trim"
            ],

            [
                "field" => "terbayar",
                "label" => "Terbayar",
                "rules" => "required|trim|numeric"
            ],

            [
                "field" => "total_tagihan",
                "label" => "Total tagihan",
                "rules" => "required|trim|numeric"
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
                "rules" => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data = [
                "order_cacat_id"        => htmlspecialchars($this->input->post("id_order_cacat")),
                "toko_id"               => htmlspecialchars($this->input->post("id_toko")),
                "bank_id"               => htmlspecialchars($this->input->post("bank_id")),
                "payment_id"            => htmlspecialchars($this->input->post("payment_id")),
                "kode_order"            => htmlspecialchars($this->input->post("kode_order")),
                "nama_cust"             => htmlspecialchars($this->input->post("nama_cust")),
                "hp_cust"               => htmlspecialchars($this->input->post("hp_cust")),
                "tipe_transaksi"        => htmlspecialchars($this->input->post("tipe_transaksi")),
                "terbayar"              => htmlspecialchars($this->input->post("terbayar")),
                "total_tagihan"         => htmlspecialchars($this->input->post("total_tagihan")),
                "total_diskon"          => htmlspecialchars($this->input->post("total_diskon")),
                "tagihan_cart"          => htmlspecialchars($this->input->post("tagihan_cart")),
                "tagihan_after_diskon"  => htmlspecialchars($this->input->post("tagihan_after_diskon")),
                "total_biaya_kirim"     => htmlspecialchars($this->input->post("total_biaya_kirim")),
                "tanggal_beli"          => ($this->input->post("tanggal_beli")) ? htmlspecialchars($this->input->post("tanggal_beli")) : date("Y-m-d H:i:s")
            ];

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

                    // Do database query here
                    $res = $this->sales_order_cacat->uploadBuktiBayarSalesOrderCacat($data);

                    if ($res) {

                        $data_json["status"]            = "berhasil";
                        $data_json["response"]          = "Bukti bayar berhasil di upload";
                    } else {

                        $data_json["status"]            = "gagal";
                        $data_json["response"]          = "Bukti bayar gagal di upload";
                    }
                } else {

                    $data_json["status"]            = "error";
                    $data_json["err_bukti_bayar"]   = $this->upload->display_errors();
                }
            } else {

                $data_json["status"]            = "error";
                $data_json["err_bukti_bayar"]   = "File wajib di isi";
            }
        } else {

            $data_json["status"]                = "error";
            $data_json["err_id_order_cacat"]    = form_error("id_order_cacat");
            $data_json["err_tipe_transaksi"]    = form_error("tipe_transaksi");
            $data_json["err_bank_id"]           = form_error("bank_id");
            $data_json["err_payment_id"]        = form_error("payment_id");
            $data_json["err_kode_order"]        = form_error("kode_order");
            $data_json["err_nama_cust"]         = form_error("nama_cust");
            $data_json["err_hp_cust"]           = form_error("hp_cust");
            $data_json["err_tanggal_beli"]      = form_error("tanggal_beli");
        }

        echo json_encode($data_json);
    }

    public function rollbackSalesOrderCacat()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_order_cacat",
                "label"     => "id order cacat",
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
                "rules"     => "trim|required"
            ],

            [
                "field"     => "hp_cust",
                "label"     => "Hp",
                "rules"     => "trim|required"
            ],

            [
                "field"     => "kode_order",
                "label"     => "Kode order",
                "rules"     => "trim|required"
            ],

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data["id_order_cacat"]     = htmlspecialchars($this->input->post("id_order_cacat"));
            $data["step"]               = htmlspecialchars($this->input->post("step"));
            $data["nama_cust"]          = htmlspecialchars($this->input->post("nama_cust"));
            $data["hp_cust"]            = htmlspecialchars($this->input->post("hp_cust"));
            $data["kode_order"]         = htmlspecialchars($this->input->post("kode_order"));

            $res = $this->sales_order_cacat->rollbackSalesOrderCacat($data);

            if ($res) {

                $data_json["status"]    = "berhasil";
                $data_json["response"]  = "Data berhasil di rollback";
            } else {

                $data_json["status"]    = "gagal";
                $data_json["response"]  = "Data gagal di rollback";
            }
        } else {

            $data_json["status"]        = "error";
            $data_json["err_id_order_cacat"]  = form_error("id_order_cacat");
            $data_json["err_step"]      = form_error("step");
        }

        echo json_encode($data_json);
    }

    public function konfirmasiSalesOrderCacat()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "id_order_cacat",
                "label"     => "id order cacat",
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
                "id_order_cacat" => htmlspecialchars($this->input->post("id_order_cacat")),
                "step"          => htmlspecialchars($this->input->post("step")),
                "nama_cust"     => htmlspecialchars($this->input->post("nama_cust")),
                "hp_cust"       => htmlspecialchars($this->input->post("hp_cust")),
                "kode_order"    => htmlspecialchars($this->input->post("kode_order")),
                "waktu"         => htmlspecialchars($this->input->post("waktu")),
                "alamat"        => htmlspecialchars($this->input->post("alamat")),
            ];

            $res = $this->sales_order_cacat->konfirmasiSalesOrderCacat($data);

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
}
