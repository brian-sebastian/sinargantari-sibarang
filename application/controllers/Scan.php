<?php

/**
 * @property Barang_model $barang
 * @property Kategori_model $kategori
 * @property Satuan_model $satuan
 * @property Harga_model $harga
 * @property Transaksi_model $transaksi
 * @property Logfile_model $logfile
 * @property Diskon_model $diskon
 * @property Setting_model $setting
 * @property Toko_model $toko
 * @property Checkout_model $checkout
 * @property form_validation $form_validation
 * @property phone_library $phone_library
 * @property db $db
 * @property input $input
 * @property session $session
 * @property cart $cart
 * @property uuid $uuid
 */
class Scan extends CI_Controller
{
    private $preview;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->library('form_validation');
        $this->load->library('phone_library');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Kategori_model', 'kategori');
        $this->load->model('Satuan_model', 'satuan');
        $this->load->model('Logfile_model', 'logfile');
        $this->load->model('Harga_model', 'harga');
        $this->load->model('Diskon_model', 'diskon');
        $this->load->model('Checkout_model', 'checkout');
        $this->load->model('Transaksi_model', 'transaksi');
        $this->load->model('Toko_model', 'toko');
        $this->load->model('Setting_model', 'setting');
        $this->load->model('Bank_model', 'bank');
        $this->load->model('Payment_model', 'payment');
    }

    public function index()
    {
        $data['title_menu'] = "Kasir";
        $data['title'] = "Scan";
        $data['barang'] = $this->barang->getAllBarang();
        $toko_id_sess = $this->session->userdata('toko_id');
        if ($toko_id_sess) {
            $data['toko_id']        = $toko_id_sess;
            $data['barang_toko']    = $this->harga->ambilBarangBerdasarkanToko($toko_id_sess);
        } else {
            if ($this->input->get('toko_id')) {
                $data['toko_id'] = $this->input->get('toko_id');
            } else {
                $data['toko_id'] = null;
                $data['tokonotmp'] = $this->toko->ambilSemuaTokoNotMarketPlace();
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('order/scan/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function get_data_barang_ajax()
    {
        $toko_id = htmlspecialchars($this->input->post('toko_id'));
        $barcode = htmlspecialchars($this->input->post('barcode'));
        $query = $this->harga->getHargaBarangbyToko($barcode, $toko_id);
        $err_code = 0;
        $status = true;
        $message = '';
        $view = '';
        $view_action = '';

        if ($query) {
            $data = [
                'id_barang' => $query['id_barang'],
                'kategori_id' => $query['kategori_id'],
                'satuan_id' => $query['satuan_id'],
                'nama_kategori' => $query['nama_kategori'],
                'satuan' => $query['satuan'],
                'kode_barang' => $query['kode_barang'],
                'nama_barang' => $query['nama_barang'],
                'barcode_barang' => $query['barcode_barang'],
                'harga_pokok' => $query['harga_pokok'],
                'berat_barang' => $query['berat_barang'],
                'nama_toko' => $query['nama_toko'],
                'jenis' => $query['jenis'],
                'harga_jual' => $query['harga_jual'],
                'stok_toko' => $query['stok_toko'],
                'toko_id' => $query['id_toko'],
                'id_harga' => $query['id_harga'],
                'barang_id' => $query['barang_id'],
            ];
            $isCheckStok = $this->checkStock($data);

            if ($isCheckStok['is_true_false'] == true) {
                $err_code = 0;
                $status = true;
                $message = $isCheckStok['message'];
                $view = $isCheckStok['view'];
                $view_action = $isCheckStok['view_action'];
            } else {
                $err_code++;
                $status = false;
                $message = $isCheckStok['message'];
                $view = $isCheckStok['view'];
                $view_action = $isCheckStok['view_action'];
            }
        } else {
            $err_code++;
            $status = false;
            $message = "Data Barang Tidak Ada";
            $view = '';
            $view_action = '';
        }
        $result = [
            'err_code' => $err_code,
            'status' => $status,
            'message' => $message,
            'view' => $view,
            'view_action' => $view_action,
        ];
        echo json_encode($result);
    }

    private function checkStock($data)
    {
        $id_barang = $data['id_barang'];
        $barcode = $data['barcode_barang'];
        $toko_id = $data['toko_id'];
        $id_harga = $data['id_harga'];
        $barang_id = $data['barang_id'];

        $err_code = 0;
        $message = '';
        $view = '';
        $view_action = '';
        $is_true_false = true;

        $rowid      = 0;
        $qty        = 0;
        $totalQty   = 0;

        $resultBarangHarga = $this->barang->getBarangHargaTokoRow($id_harga);

        if ($resultBarangHarga) {
            $rowid      = 0;
            $qty        = 0;
            $totalQty   = 0;

            foreach ($this->cart->contents() as $cart) {
                if ($cart['id'] == $id_harga) {

                    $rowid  = $cart["rowid"];
                    $qty    = $cart["qty"] + 1;
                    break;
                }
            }

            $stok = $this->barang->cekStokBarang($id_harga, $qty);
            if ($stok) {
                if ($rowid) {
                    $data_update = [
                        "rowid"     => $rowid,
                        "qty"       => $qty,
                        "options" => array('barang_id' => $resultBarangHarga['barang_id'], 'harga_pokok' => $resultBarangHarga['harga_pokok'], 'diskon' => ""),
                    ];
                    //Jika Ada Row Id Update Cart
                    $this->cart->update($data_update);

                    $is_true_false = true;
                    $err_code = 0;
                    $message = "Data Berhasil diupdate";
                    $view = $this->show_cart();
                    $view_action = $this->show_action_detail_cart();
                } else {
                    //insert data 
                    $data_insert = [
                        "id"        => $resultBarangHarga["id_harga"],
                        "name"      => $resultBarangHarga["nama_barang"],
                        "price"     => $resultBarangHarga["harga_jual"],
                        "qty"       => 1,
                        "options" => array('barang_id' => $resultBarangHarga['barang_id'], 'harga_pokok' => $resultBarangHarga['harga_pokok'], 'diskon' => ""),
                    ];

                    $funAddCart = $this->addCartJson($data_insert);

                    foreach ($this->cart->contents() as $item) {

                        $totalQty += $item["qty"];
                    }
                    //Data Barang Berhasil Diinsert
                    $is_true_false = true;
                    $err_code = 0;
                    $message = "Data Berhasil di tambahkan keranjang";
                    $view = $funAddCart;
                    $view_action = $this->show_action_detail_cart();
                }
            } else {
                //Jumlah Melebihi Stok
                $is_true_false = false;
                $err_code++;
                $message = "Jumlah Melebihi Stok";
                $view = $this->show_cart();
                $view_action = $this->show_action_detail_cart();
            }
        } else {
            //Item Tidak Ada
            $is_true_false = false;
            $err_code++;
            $message = "Item Tidak Ada Pada Toko";
            $view = $this->show_cart();
            $view_action = $this->show_action_detail_cart();
        }

        $setResult = [
            'is_true_false' => $is_true_false,
            'err_code' => $err_code,
            'message' => $message,
            'view' => $view,
            'view_action' => $view_action,
        ];

        return $setResult;
    }

    public function checkStockSpecific()
    {

        $id_harga   = htmlspecialchars($this->input->post("id_harga"));
        $qty        = htmlspecialchars($this->input->post("qty"));
        $price      = htmlspecialchars($this->input->post("price"));
        $total_keseluruhan = 0;

        $data_json["response"]  = $this->barang->cekStokBarang($id_harga, $qty);

        if ($data_json["response"]) {

            foreach ($this->cart->contents() as $items) {

                $boolean = false;

                if ($items["id"] == $id_harga) {

                    $data = [
                        "rowid" => $items["rowid"],
                        "qty"   => $qty
                    ];

                    $this->cart->update($data);
                    $data_json["subtotal"]  = number_format((intval($qty) * intval($price)), 0, ",", ",");

                    $total_keseluruhan += intval($qty) * intval($price);

                    $boolean = true;
                }

                if ($boolean == false) {

                    $total_keseluruhan += intval($items["qty"]) * intval($items["price"]);
                }
            }

            $data_json["grand_total"]   = number_format($total_keseluruhan);
        }

        echo json_encode($data_json);
    }

    private function addCartJson($data)
    {

        //change kirim parameter
        $this->cart->insert($data);

        //tampilkan cart setelah added
        return $this->show_cart();
    }
    private function addCart($data)
    {

        //change kirim parameter
        $this->cart->insert($data);

        //tampilkan cart setelah added
        echo $this->show_cart();
    }

    function show_cart_echo()
    {
        //Fungsi untuk menampilkan Cart Setelah Ajax Dengan Echo
        $output = '';
        $no = 0;

        foreach ($this->cart->contents() as $items) {
            $no++;
            $output .= '
                        <tr>
                                <td>' . $items['name'] . '</td>
                                <td>' . number_format($items['price']) . '</td>
                                <td>' . $items['qty'] . '</td>
                                <td>' . number_format($items['subtotal']) . '</td>
                                <td><button type="button" id="' . $items['rowid'] . '" class="hapus_cart btn btn-danger btn-xs">Batal</button></td>
                        </tr>
                ';
        }
        $output .= '
                <tr>
                        <th colspan="3">Total</th>
                        <th colspan="2">' . 'Rp ' . number_format($this->cart->total()) . '</th>
                </tr>
        ';
        echo $output;
    }
    function show_cart()
    {
        //Fungsi untuk menampilkan Cart dari fungsi add_cart()
        $output = '';
        $no = 0;

        foreach ($this->cart->contents() as $items) {
            $no++;
            $output .= '
                        <tr>
                                <td>' . $items['name'] . '</td>
                                <td>' . number_format($items['price']) . '</td>
                                <td class="w-25"><div class="w-75"><span class="badge rounded-pill bg-primary isMinus" style="display:inline-block;"><span class="tf-icons bx bx-minus"></span></span><input class="myQty form-control" style="display:inline-block;" data-old="' . $items['qty'] . '" data-id="' . $items['id'] . '" data-price="' . $items['price'] . '" value="' . $items['qty'] . '"><span class="badge rounded-pill bg-primary isPlus" style="display:inline-block;"><span class="tf-icons bx bx-plus"></span></span></div></td>
                                <td>' . number_format($items['subtotal']) . '</td>
                                <td><button type="button" id="' . $items['rowid'] . '" class="hapus_cart btn btn-danger btn-xs">Batal</button></td>
                        </tr>
                ';
        }
        $output .= '
                <tr>
                        <th colspan="3">Total</th>
                        <th colspan="2" id="grand_total">' . 'Rp ' . number_format($this->cart->total()) . '</th>
                </tr>
        ';
        return $output;
    }
    function show_cart_json()
    {
        //Fungsi untuk menampilkan Cart dari fungsi add_cart()
        $output = '';
        $no = 0;

        foreach ($this->cart->contents() as $items) {
            $no++;
            $output .= '
                        <tr>
                                <td>' . $items['name'] . '</td>
                                <td>' . number_format($items['price']) . '</td>
                                <td>' . $items['qty'] . '</td>
                                <td>' . number_format($items['subtotal']) . '</td>
                                <td><button type="button" id="' . $items['rowid'] . '" class="hapus_cart btn btn-danger btn-xs">Batal</button></td>
                        </tr>
                ';
        }
        $output .= '
                <tr>
                        <th colspan="3">Total</th>
                        <th colspan="2">' . 'Rp ' . number_format($this->cart->total()) . '</th>
                </tr>
        ';
        echo json_encode($output);
    }

    function show_action_detail_cart()
    {
        //fungsi untuk menampilkan button action cart
        $output2 = '';
        if ($this->cart->contents()) {
            $output2 .= '
            <div class="row mt-3 float-end">
                            <div class="col">
                                <button class="btn btn-sm btn-danger" id="button_clear_cart">Remove Cart</button>
                                <a href="' . base_url('kasir/scan/checkout') . '" class="btn btn-sm btn-primary"  id="button_checkout_cart">Checkout</a>
                            </div>
                        </div>';
        }

        return $output2;
    }
    function show_action_detail_cart_checkret()
    {
        //fungsi untuk menampilkan button action cart
        $output2 = '';
        if ($this->cart->contents()) {
            $output2 = '
            <div class="row mt-3 float-end">
                            <div class="col">
                                <button class="btn btn-sm btn-danger" id="button_clear_cart">Remove Cart</button>
                                <a href="' . base_url('kasir/scan/checkout') . '" class="btn btn-sm btn-primary"  id="button_checkout_cart">Checkout</a>
                            </div>
                        </div>';
        } else {
            $output2 = '';
        }

        return $output2;
    }
    function show_action_detail_cart_echo()
    {
        //fungsi untuk menampilkan button action cart
        $output2 = '';
        if ($this->cart->contents()) {
            $output2 .= '
            <div class="row mt-3 float-end">
                            <div class="col">
                                <button class="btn btn-sm btn-danger" id="button_clear_cart">Remove Cart</button>
                                <a href="' . base_url('kasir/scan/checkout') . '" class="btn btn-sm btn-primary"  id="button_checkout_cart">Checkout</a>
                            </div>
                        </div>';
        }

        echo $output2;
    }
    function is_show_action_detail_cart_check()
    {
        $output2 = '';
        if ($this->cart->contents()) {
            $output2 = '
            <div class="row mt-3 float-end">
                            <div class="col">
                                <button class="btn btn-sm btn-danger" id="button_clear_cart">Remove Cart</button>
                                <a href="' . base_url('kasir/scan/checkout') . '" class="btn btn-sm btn-primary"  id="button_checkout_cart">Checkout</a>
                            </div>
                        </div>';
        }
        if (count($this->cart->contents()) === 0) {
            $output2 = '';
            // list is empty.
        }

        echo $output2;
    }

    function load_cart()
    {
        //load data cart
        echo $this->show_cart();
    }
    function load_action_cart()
    {
        //load data cart
        echo $this->show_action_detail_cart();
    }

    function destroy_cart()
    {
        $this->cart->destroy();
        echo $this->show_cart();
    }

    function hapus_cart()
    { //fungsi untuk menghapus item cart
        $data = array(
            'rowid' => $this->input->post('row_id'),
            'qty' => 0,
        );
        $this->cart->update($data);
        echo $this->show_cart();
        echo $this->show_action_detail_cart_checkret();
    }

    private function checkCart()
    {

        if (count($this->cart->contents()) == 0) {

            $this->session->set_flashdata("flash-data-gagal", "Pilih barang terlebih dahulu");
            redirect("kasir/scan", "refresh");
        } else {

            foreach ($this->cart->contents() as $item) {

                // check diskon peritem
                $diskon = $this->diskon->cekDiskonBarang($item["id"], $item["qty"]);

                if (!array_key_exists('options', $item)) {
                    $this->cart->destroy();
                    $this->session->set_flashdata('message_error', 'something error, please restart your browser');
                    redirect('auth/logout');
                }

                if ($diskon) {

                    $data_update["rowid"]                   = $item["rowid"];
                    $data_update["options"]["barang_id"]       = $item["options"]["barang_id"];
                    $data_update["options"]["harga_pokok"]       = $item["options"]["harga_pokok"];
                    $data_update["options"]["diskon"]       = json_encode($diskon);
                } else {

                    $data_update["rowid"]                   = $item["rowid"];
                    $data_update["options"]["barang_id"]       = $item["options"]["barang_id"];
                    $data_update["options"]["harga_pokok"]       = $item["options"]["harga_pokok"];
                    $data_update["options"]["diskon"]       = "";
                }
                $this->cart->update($data_update);
            }
        }
    }

    public function checkout()
    {
        $this->checkCart();
        $this->preview["title_menu"]    = "Kasir";
        $this->preview["title"]         = "Scan";
        $this->preview['cart_content']  = $this->cart->contents();
        $this->preview['cart_total']    = $this->cart->total();
        $this->preview['data_bank']     = $this->bank->ambilSemuaBank();
        $toko_id = $this->session->userdata('toko_id');
        if ($toko_id) {
            $this->preview['toko_id'] = $toko_id;
        } else {
            $this->preview['toko_id'] = null;
        }
        $this->load->view('layout/header', $this->preview);
        $this->load->view('layout/sidebar', $this->preview);
        $this->load->view('layout/navbar', $this->preview);
        $this->load->view('order/scan/checkout', $this->preview);
        $this->load->view('layout/footer', $this->preview);
        $this->load->view('layout/script', $this->preview);
    }

    public function checkPaymentKembalian()
    {
        $data_post = $this->input->post();

        $err_code = 0;
        $message = '';
        $total_tagihan = str_replace(',', '', $data_post['total_tagihan']);
        $terbayar = $data_post['terbayar'];
        $kembalian = $data_post['kembalian'];
        $status = $data_post['status'];
        $toko_id = $data_post['toko_id'];
        $nama_cust = $data_post['nama_cust'];
        $hp_cust = $data_post['hp_cust'];
        $alamat_cust = $data_post['alamat_cust'];

        $checkKurangKembalian = strpos($kembalian, '-');


        if ($kembalian == 0) {
            $err_code = 0;
            $message = "Oke Pas";
        } else {
            if ($checkKurangKembalian === false) {
                $err_code = 0;
                $message = "Oke Bisa Ada Kembali";
            } else {
                $err_code++;
                $message = "Maaf Uang Kamu Kurang";
            }
        }

        $dataResult = [
            'err_code' => $err_code,
            'message' => $message
        ];

        echo json_encode($dataResult);
    }

    public function doCheckout()
    {

        // $tes = [
        // 	'toko_id' => $this->input->post('toko_id'),
        //     'total_tagihan' => $this->input->post('total_tagihan'),
        //     'terbayar' => $this->input->post('terbayar'),
        // 	'kembalian' => $this->input->post('kembalian'),
        //     'total_order' => $this->input->post('total_order'),
        // 	'status' => $this->input->post('status')
        // ];

        // var_dump($tes);
        // die;

        $err_code = 0;
        $msg = "";
        $status = $this->input->post('status');
        $data_post = $this->input->post();
        $this->form_validation->set_rules(
            'toko_id',
            'Toko',
            'required|max_length[100]',
            [
                'required' => "Toko Masih Belum Ada, silahkan Re Login",
            ]
        );

        if ($status == DIKIRIM) {
            $this->form_validation->set_rules(
                'nama_cust',
                'Nama',
                'required|max_length[100]'
            );
            $this->form_validation->set_rules(
                'hp_cust',
                'HP',
                'required|max_length[100]'
            );
            $this->form_validation->set_rules(
                'alamat_cust',
                'Alamat',
                'required|max_length[100]'
            );
        }
        $this->form_validation->set_rules(
            'status',
            'Status',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'total_tagihan',
            'Total Tagihan',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'terbayar',
            'Terbayarkan',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'kembalian',
            'Kembalian',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'total_order',
            'Total Order',
            'required|max_length[100]'
        );

        if ($this->input->post("metode_bayar") == "transfer") {

            $this->form_validation->set_rules(
                'bank_id',
                'Bank',
                'required|max_length[100]'
            );
            $this->form_validation->set_rules(
                'payment_id',
                'Payment',
                'required|max_length[100]'
            );
        }

        if ($this->form_validation->run() == FALSE) {
            $err_code++;
            $msg = validation_errors();
            $dataResult = [
                'err_code' => $err_code,
                'message' => $msg,
            ];
        } else {
            $toko_id = htmlspecialchars($this->input->post('toko_id'));
            $nama_cust = htmlspecialchars($this->input->post('nama_cust'));
            $hp_cust = htmlspecialchars($this->input->post('hp_cust'));
            $formatNumberE16 = $this->phone_library->formatPhoneNumberInternational($hp_cust);
            // $formatNumberE16 = $this->phone_library->formatPhoneE164($hp_cust);
            $alamat_cust = htmlspecialchars($this->input->post('alamat_cust'));
            $status = htmlspecialchars($this->input->post('status'));
            $tagihan_keranjang_normal = htmlspecialchars($this->input->post('tagihan_keranjang'));
            $total_diskon = htmlspecialchars($this->input->post('total_diskon'));
            $grand_total_price = htmlspecialchars($this->input->post('grand_total_price'));
            $biaya_kirim = htmlspecialchars(removeThousandSeparator($this->input->post('biaya_kirim')));
            $total_tagihan = htmlspecialchars(removeThousandSeparator($this->input->post('total_tagihan')));
            $price_after_discount = htmlspecialchars(removeThousandSeparator($this->input->post('price_after_discount')));
            $contain_global_discount = htmlspecialchars(removeThousandSeparator($this->input->post('contain_discount')));
            $terbayar = htmlspecialchars(removeThousandSeparator($this->input->post('terbayar')));
            $kembalian = htmlspecialchars(removeThousandSeparator($this->input->post('kembalian')));
            $total_order = htmlspecialchars(removeThousandSeparator($this->input->post('total_order')));
            $tipe_pengiriman = htmlspecialchars($this->input->post('tipe_pengiriman'));
            $bank_id = ($this->input->post("bank_id")) ? htmlspecialchars($this->input->post("bank_id")) : NULL;
            $payment_id = ($this->input->post("payment_id")) ? htmlspecialchars($this->input->post("payment_id")) : NULL;
            $data_keranjang = $this->cart->contents();
            $dataCustomer = [
                'toko_id' => $toko_id,
                'nama_cust' => $nama_cust,
                'hp_cust' => $formatNumberE16,
                'alamat_cust' => $alamat_cust,
                'status' => $status,
                'tagihan_keranjang' => $tagihan_keranjang_normal,
                'total_diskon' => $total_diskon,
                'tagihan_after_diskon' => $price_after_discount,
                'biaya_kirim' => $biaya_kirim,
                'total_tagihan' => $total_tagihan,
                'contain_global_discount' => $contain_global_discount,
                'terbayar' => $terbayar,
                'kembalian' => $kembalian,
                'total_order' => $total_order,
                'tipe_pengiriman' => $tipe_pengiriman,
                'bank_id'   => $bank_id,
                'payment_id' => $payment_id,
                'tipe_transaksi' => strtoupper($this->input->post("metode_bayar"))
            ];
            $err_code = 0;
            $msg = "Data Berhasil Diinputkan";

            $dataResult = [
                'err_code' => $err_code,
                'message' => $msg,
                'data' => $dataCustomer,
            ];
            $resultCO = $this->checkout->prosesCheckout($data_keranjang, $dataCustomer);

            if ($resultCO['err_code'] == 1) {
                //jika error
                $err_code++;
                $msg = $resultCO['message'];
            } else {
                $err_code = 0;
                $msg = $resultCO['message'];
                $transactionid = $resultCO['transactionid'];
                $dataResult += ['transactionid' => $transactionid];
                pusherTransaction($dataResult);
            }
        }


        echo json_encode($dataResult);
    }

    public function print_nota_raw()
    {
        $getTransaksi = $this->input->get('transid');

        if (!$getTransaksi) {
            $this->session->set_flashdata('flash-data-gagal', "Kesalahan Parameter");
            redirect('kasir/scan');
        }

        if ($getTransaksi) {
            $decodeEncrypt = base64_decode($getTransaksi);
            $dataTransaksi = $this->transaksi->getTransactionOrderDetail($decodeEncrypt);
            $setting = $this->setting->getSetting();
            $data['transaksi'] = $dataTransaksi;
            $data['setting'] = $setting;
            $kode_transaksi = $dataTransaksi[0]['kode_transaksi'];
            $data['title'] = $kode_transaksi;

            $this->load->view('order/scan/print_nota_raw', $data);
        }
    }

    public function print_nota_pdf()
    {
        $getTransaksi = $this->input->get('transid');
        if (!$getTransaksi) {
            $this->session->set_flashdata('flash-data-gagal', "Kesalahan Parameter");
            redirect('kasir/scan');
        }

        if ($getTransaksi) {
            $decodeEncrypt = base64_decode($getTransaksi);
            $dataTransaksi = $this->transaksi->getTransactionOrderDetail($decodeEncrypt);
            $setting = $this->setting->getSetting();
            if ($dataTransaksi) {
                //decode encryption

                $data['transaksi'] = $dataTransaksi;
                $data['setting'] = $setting;
                $kode_transaksi = $dataTransaksi[0]['kode_transaksi'];
                $data = [
                    'func'      => 'cetak',
                    'view'      => 'order/scan/print_nota_pdf',
                    'data'      => $data,
                    'jenis'     => 'data_pembayaran',
                    'paper'     => array(0, 0, 204, 650),
                    'dpi'       => 72,
                    'filename'  => "Cetak-Struk-$kode_transaksi.pdf"
                ];
                $this->load->library('dompdf_library', $data);
            } else {
                $this->session->set_flashdata('flash-data-gagal', "Id Transaksi Tidak Ditemukan");
                redirect('kasir/scan');
            }
        }
    }
    public function print_nota()
    {
        $getTransaksi = $this->input->get('transid');
        if (!$getTransaksi) {
            $this->session->set_flashdata('flash-data-gagal', "Kesalahan Parameter");
            redirect('kasir/scan');
        }

        if ($getTransaksi) {
            $decodeEncrypt = base64_decode($getTransaksi);
            $dataTransaksi = $this->transaksi->getTransactionOrderDetail($decodeEncrypt);
            if ($dataTransaksi) {
                //decode encryption

                $data['transaksi'] = $dataTransaksi;
                $kode_transaksi = $dataTransaksi['kode_transaksi'];
            } else {
                $this->session->set_flashdata('flash-data-gagal', "Id Transaksi Tidak Ditemukan");
                redirect('kasir/scan');
            }

            $data['title'] = "Print Nota";
            $this->load->view('order/scan/print_nota', $data);
        }
    }

    public function searchBarcodeAutoComplete()
    {
        $result = $this->barang->getSearchBarangAutoComplete($_GET['term']);

        $data = array();
        foreach ($result as $row) {
            $data[] = array(
                'label' => $row->nama_barang . " | " . $row->barcode_barang, // replace with the actual column name
                'value' => $row->barcode_barang, // replace with the actual column name
                // add other properties if needed
            );
        }

        echo json_encode($data);
    }

    //!! FUNGSI INI HANYA MENAMBAHKAN CART SEBAGAI DEBUGGING, BUKAN DIPAKAI ASLI PRODUCTION
    public function virtual_cart()
    {
        $data = array(
            array(
                'id'      => '9',
                'qty'     => 1,
                'price'   => 700008,
                'name'    => 'Semen Gresik',
                'options' => array('diskon' => '')
            ),
            array(
                'id'      => '8',
                'qty'     => 1,
                'price'   => 600000,
                'name'    => 'Cat paragon Hijau',
                'options' => array('diskon' => '')
            )
        );
        $this->cart->insert($data);
        $this->session->set_flashdata('flash-swal', "Virtual Cart Berhasil Ditambahkan");
        redirect('kasir/scan');
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
