<?php

/**
 * @property Laporan_penjualan_cacat_model $Laporan_penjualan_cacat_model
 * @property getAllTransaksi $getAllTransaksi
 * @property Barang_model $Barang_model
 * @property Toko_model $Toko_model
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property upload $upload
 */


class Laporan_penjualan_cacat extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Laporan_penjualan_cacat_model');
        $this->load->model('Barang_model');
        $this->load->model('Toko_model');
    }
    public function index()
    {
        $this->view['title_menu']   = "Barang Cacat";
        $this->view['title']        = "Laporan Penjualan Cacat";
        $this->view["data_barang"]  = $this->Barang_model->getAllBarang();
        $this->view["data_toko"]    = $this->Toko_model->ambilSemuaToko();
        $this->view['content']      = "laporan_penjualan_cacat/index";

        $this->load->view('layout/wrapper', $this->view);
    }


    public function ajax_laporan_penjualan()
    {

        $newArr = [];
        $data = $this->Laporan_penjualan_cacat_model->ambilSemuaPenjualan();
        $hitungTotalPenjualan = $this->Laporan_penjualan_cacat_model->ambilHitungTotalPenjualan();
        $filterTotalPenjualan = $this->Laporan_penjualan_cacat_model->ambilFilterTotalPenjualan();

        $no = 1;
        foreach ($data as $d) {
            $row = [];
            $row[] = $no;
            $row[] = $d['nama_toko'];
            $row[] = $d['kode_order'];
            $row[] = $d['kode_transaksi'];
            $row[] = $d['nama_barang'];
            $row[] = number_format($d['harga_satuan_pokok']);
            $row[] = number_format($d['harga_satuan_jual']);
            $row[] = $d['qty'];
            $row[] = number_format($d['total_harga_pokok']);
            $row[] = number_format($d['total_harga_jual']);
            $row[] = number_format($d['total_diskon']);
            $row[] = number_format($d['total_keuntungan']);
            $row[] = $d['tanggal_beli'];

            array_push($newArr, $row);
            $no++;
        }

        $data_json = [
            'draw' => $this->input->post('draw'),
            'data' => $newArr,
            'recordsTotal' => $hitungTotalPenjualan,
            'recordsFiltered' => $filterTotalPenjualan,
        ];


        echo json_encode($data_json);
    }

    public function total_laporan_penjualan()
    {
        $data = $this->Laporan_penjualan_cacat_model->ambilSemuaPenjualan();

        $nominal = array_reduce($data, function ($prev, $curr) {
            return $prev += $curr["total_keuntungan"];
        }, 0);

        echo json_encode(["totalPenjualan"  => $nominal]);
    }

    public function cetak_excel()
    {
        $getField = json_decode($this->input->get('field'), TRUE);
        $data = $this->Laporan_penjualan_cacat_model->ambilSemuaPenjualanExcel($getField["tanggal"], $getField["barang_id"], $getField["toko_id"]);
        $minMaxDate = $this->Laporan_penjualan_cacat_model->ambilMinMaxDate();

        $libary_excel = [
            'func' => 'export',
            'jenis' => 'laporan_penjualan_cacat',
            'filename' => 'laporan_penjualan_cacat_' . date('Y_m_d_His') . ".xlsx",
            'data_penjualan_cacat' => $data,
            'rangeTanggal' => ($getField['tanggal'] == '') ? $minMaxDate["minDate"] . ' to ' . $minMaxDate["maxDate"] : $getField['tanggal'],
        ];

        if ($data) {
            $this->load->library('Excel_library', $libary_excel);
        }
    }
}
