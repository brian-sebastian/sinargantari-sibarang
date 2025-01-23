<?php

class Dashboard extends CI_Controller
{

    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model("Dashboard_model", "dashboard");
        $this->load->model("Toko_model", "toko");
    }

    public function index()
    {
        // $data = array(
        //     array(
        //         'id'      => 'sku_123ABC',
        //         'qty'     => 1,
        //         'price'   => 39.95,
        //         'name'    => 'T-Shirt',
        //         'options' => array('Size' => 'L', 'Color' => 'Red')
        //     ),
        //     array(
        //         'id'      => 'sku_567ZYX',
        //         'qty'     => 1,
        //         'price'   => 9.95,
        //         'name'    => 'Coffee Mug'
        //     ),
        //     array(
        //         'id'      => 'sku_965QRS',
        //         'qty'     => 1,
        //         'price'   => 29.95,
        //         'name'    => 'Shot Glass'
        //     )
        // );
        // $this->load->library('cart_purchase');

        // $this->cart_purchase->insert($data);

        // dump($this->cart_purchase->contents());
        // die;

        $this->view["title_menu"]    = "Dashboard";
        $this->view["title"]         = "Dashboard";
        $this->view["data_toko"]     = $this->toko->ambilSemuaToko();
        $this->view["content"]       = "dashboard/v_dashboard_index";

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ambilPenjualan()
    {

        $data["periode"]   = null;
        $data["toko_id"]   = null;

        if (isset($_GET["periode"]) && !empty($_GET["periode"])) {

            $data["periode"] = $_GET["periode"];
        }

        if (isset($_GET["toko_id"]) && !empty($_GET["toko_id"])) {

            $data["toko_id"] = $_GET["toko_id"];
        }

        $res = $this->dashboard->ambilRekapitulasiPenjualan($data);

        $data_json["status"]    = "berhasil";
        $data_json["response"]  = array_map(function ($elemen) {
            return $elemen["total_penjualan"];
        }, $res);

        echo json_encode($data_json);
    }
}
