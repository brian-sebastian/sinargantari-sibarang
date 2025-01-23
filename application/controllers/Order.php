<?php

/**
 * @property User_model $user
 * @property Barang_model $barang
 * @property Karyawan_model $karyawan
 * @property Toko_model $toko
 * @property Satuan_model $satuan
 * @property Kategori_model $kategori
 * @property Order_model $order
 * @property input $input
 * @property output $output
 * @property db $db
 * @property pagination $pagination
 * @property uri $uri
 * @property cart $cart
 * @property session $session
 * @property pusher $pusher
 */
class Order extends CI_Controller
{
    public $global_array = [];
    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("User_model", "user");
        $this->load->model("Barang_model", "barang");
        $this->load->model("Karyawan_model", "karyawan");
        $this->load->model("Toko_model", "toko");
        $this->load->model("Satuan_model", "satuan");
        $this->load->model("Kategori_model", "kategori");
        $this->load->model("Order_model", "order");
    }

    public function index()
    {

        $this->view["title_menu"]   = "Kasir";
        $this->view["title"]        = "Order";
        $this->view["content"]      = "order/v_order_index";

        $session = $this->user->session_data();
        $id_user = $session['id_user'];

        $karyawan = $this->karyawan->ambilDetailKaryawandeganIduser($id_user);

        if ($karyawan) {

            //ambil data search
            if ($this->input->post('submit')) {
                $search = $this->input->post('search');
            } else {
                $search = null;
            }

            $this->db->select('*')
                ->from('tbl_harga')
                ->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', "inner")
                ->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id')
                ->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id')
                ->group_start()
                ->like('tbl_barang.kode_barang', $search)
                ->or_like('tbl_barang.nama_barang', $search)
                ->or_like('tbl_barang.barcode_barang', $search)
                ->group_end()
                ->where('tbl_harga.toko_id', $karyawan['toko_id'])
                ->where('tbl_harga.is_active', 1);
            $query = $this->db->get();

            $barang_toko = $query->result_array();

            if ($barang_toko) {

                //config pagination
                $config['base_url']     = 'https://sibara.ardhacodes.com/kasir/order';

                $config['total_rows']   = $query->num_rows();
                $config['per_page']     = 10;

                //styling

                $config['full_tag_open'] = '<div class="mt-2"><ul class="pagination pagination-separated justify-content-center mb-0">';
                $config['full_tag_close'] = '</ul></div>';

                $config['next_link'] = '&gt;';
                $config['next_tag_open'] = '<li class="page-item">';
                $config['next_tag_close'] = '</li>';

                $config['prev_link'] = '&lt;';
                $config['prev_tag_open'] = '<li class="page-item">';
                $config['prev_tag_close'] = '</li>';

                $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
                $config['cur_tag_close'] = '</a></li>';

                $config['num_tag_open'] = '<li class="page-item">';
                $config['num_tag_close'] = '</li>';

                // Produces: class="myclass"
                $config['attributes'] = array('class' => 'page-link');

                //initialize
                $this->pagination->initialize($config);

                $start = $this->uri->segment(3);

                if ($search) {
                    $start = ($start) ? $start : 0;
                    $this->db->select('*')
                        ->from('tbl_harga')
                        ->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', "inner")
                        ->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id')
                        ->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id')
                        ->group_start()
                        ->like('tbl_barang.kode_barang', $search)
                        ->or_like('tbl_barang.nama_barang', $search)
                        ->or_like('tbl_barang.barcode_barang', $search)
                        ->group_end()
                        ->where('tbl_harga.toko_id', $karyawan['toko_id'])
                        ->where('tbl_harga.is_active', 1);
                    $query_join_barang = $this->db->get();
                } else {
                    $start = ($start) ? $start : 0;
                    $this->db->select('*')
                        ->from('tbl_harga')
                        ->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', "inner")
                        ->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id')
                        ->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id')
                        ->limit($config['per_page'], $start)
                        ->where('tbl_harga.toko_id', $karyawan['toko_id'])
                        ->where('tbl_harga.is_active', 1);
                    $query_join_barang = $this->db->get();
                }

                $this->view["data_barang"]      = $query_join_barang->result_array();
                $this->view["data_karyawan"]    = $karyawan;
            } else {
                $this->view["data_barang"]  = 0;

                $this->view["data_karyawan"]  = $karyawan;
            }
        } else {
            $this->view["data_karyawan"]  = 0;
        }

        $this->view["get_kodeOrder"]    = $this->order->get_kodeOrder();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function add_cart()
    {
        $redirect_page  = $this->input->post('redirect_page');
        $data = array(
            'id'            => $this->input->post('id'),
            'qty'           => $this->input->post('qty'),
            'price'         => $this->input->post('price'),
            'name'          => $this->input->post('name'),
            'satuan'        => $this->input->post('satuan')
        );

        $this->cart->insert($data);
        redirect('order');
    }

    public function update_qty()
    {
        $rowid      = $this->input->post('rowid');
        $qty        = $this->input->post('qty');
        $price      = $this->input->post('price');

        $data = $this->cart->get_item($rowid);
        $data = array(
            'rowid' => $data['rowid'],
            'qty'   => $qty,
            'price' => $price,
        );
        $this->cart->update($data);
        $array = array('cart' => $this->cart->update($data));
        echo json_encode($array);
    }

    public function tambah_order()
    {
        $session        = $this->user->session_data();
        $id_user        = $session['id_user'];
        $nama_user      = $session['nama_user'];

        $karyawan        = $this->karyawan->ambilDetailKaryawandeganIduser($id_user);
        $toko_id         = $karyawan['toko_id'];

        $get_kodeOrder   = $this->order->get_kodeOrder();

        $id_barang      = $this->input->post('id_barang');
        $qty            = $this->input->post('qty');
        $price          = $this->input->post('price');
        $subtotal       = $this->input->post('subtotal');

        $total_harga    = $this->input->post('total_harga');

        $data = array(
            'toko_id'           => $toko_id,
            'kode_order'        => $get_kodeOrder,
            'nama_cust'         => 'Customer Offline',
            'tipe_order'        => 'Kasir',
            'tipe_pengiriman'   => 'Offline',
            'jenis_bayar'       => 'Cash',
            'alamat_cust'       => 'Tidak ada',
            'hp_cust'           => 'Tidak ada',
            'total_order'       => $total_harga,
            'biaya_kirim'       => 0,
            'paidst'            => 0,
            'is_active'         => 1,
            'user_input'        => $nama_user,
            'created_at'        => date('Y-m-d H:i:s')
        );

        $this->order->tambahData($data);

        $tbl_order = $this->db->get_where('tbl_order', ['kode_order' => $get_kodeOrder])->row_array();

        if ($tbl_order) {
            $id_order = $tbl_order['id_order'];

            for ($i = 0; $i < count($id_barang); $i++) {
                $data = array(
                    'barang_id'     => $id_barang[$i],
                    'order_id'      => $id_order,
                    'qty'           => $qty[$i],
                    'harga_total'   => $subtotal[$i],
                    'user_input'    => $nama_user,
                    'created_at'    => date('Y-m-d H:i:s')
                );

                array_push($this->global_array, $data);
            }

            $this->db->insert_batch('tbl_order_detail', $this->global_array);
            $this->cart->destroy();
            $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert"> Orderan sudah ditambahkan! </div>');
            redirect('order');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert"> Maaf, Data orderan masih belum ada! </div>');
            redirect('order');
        }
    }

    public function check_ordermodal($id)
    {
        $where      = ['kode_order' => base64_decode($id)];
        $tbl_order  = $this->db->get_where('tbl_order', $where)->row_array();

        if ($tbl_order) {
            $this->view["data_order"]    = $tbl_order;
        } else {
            $this->view["data_order"]    = 0;
        }

        $this->load->view("order/v_order_checkModal", $this->view);
    }

    public function checkOrderAffectedRow()
    {
        $this->load->model('Your_model');

        // Check for changes and get affected rows
        $affected_rows = $this->order->getAffectedRows();

        if ($affected_rows > 0) {
            $this->load->library('pusher');
            $this->pusher->trigger('channel_name', 'data_changed_event', ['message' => 'Data changed']);
        }

        // Respond with the number of affected rows
        $this->output->set_content_type('application/json')->set_output(json_encode(['affected_rows' => $affected_rows]));
    }
}
