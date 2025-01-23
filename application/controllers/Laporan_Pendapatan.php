<?php

/**
 * @property Laporan_pendapatan_model $Laporan_pendapatan_model
 * @property Toko_model $Toko_model
 * @property getAllTransaksi $getAllTransaksi
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property upload $upload
 */

class Laporan_Pendapatan extends CI_Controller
{

    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Laporan_pendapatan_model');
        $this->load->model('Toko_model');
    }
    public function index()
    {
        $this->view['title_menu']   = "Laporan";
        $this->view['title']        = "Laporan Pendapatan";
        $this->view['content']      = "laporan_pendapatan/v_laporan_pendapatan";
        $this->view['pendapatan']   = $this->Laporan_pendapatan_model->getAllPendapatan();
        $this->view['toko']         = $this->Toko_model->ambilSemuaToko();

        $this->load->view('layout/wrapper', $this->view);
    }



    public function ajax_laporan_pendapatan()
    {

        $newArr = [];
        $data = $this->Laporan_pendapatan_model->getAllPendapatan();
        $hitungTotalPendapatan = $this->Laporan_pendapatan_model->ambilHitungTotalPendapatan();
        $filterTotalPendaptan = $this->Laporan_pendapatan_model->ambilFilterTotalPendapatan();

        $no = 1;

        foreach ($data as $d) {

            $jsonString = $d['harga_potongan'];
            preg_match('/\[(.*?)\]/', $jsonString, $matches);
            $arrayData = json_decode($matches[0], true);
            $hargaPotongan = isset($arrayData[0]['harga_potongan']) ? $arrayData[0]['harga_potongan'] : '';

            $total =  ($hargaPotongan == '') ? (int)$d['harga_jual'] + (int)$d['qty'] + 0 : (int)$d['harga_jual'] + (int)$d['qty'] + $hargaPotongan;
            $totalSatuanKeuntungan = ($hargaPotongan == '') ? ((int)$d['harga_jual'] - 0) - (int)$d['harga_pokok'] : ((int)$d['harga_jual'] - $hargaPotongan) - (int)$d['harga_pokok'];

            $totalKeuntungan = (int)$d['qty'] * $totalSatuanKeuntungan;
            $omzet = (int)$d['harga_jual'] * (int)$d['qty'];
            $omzetBersih = ($hargaPotongan == '') ? $omzet - 0 - (int)$d['stok_toko'] : $omzet - $hargaPotongan - (int)$d['stok_toko'];



            $row = [];
            $row[] = $no;
            $row[] = $d['nama_barang'];
            $row[] = 'Rp. ' . number_format($d['harga_pokok']);
            $row[] = $d['stok_toko'];
            $row[] = 'Rp. ' . number_format($d['harga_jual']);
            $row[] = $d['qty'];
            $row[] = 'Rp. ' . number_format(($hargaPotongan == '') ? 0 : $hargaPotongan);
            $row[] = 'Rp. ' . number_format($total);
            $row[] = 'Rp. ' . number_format($totalSatuanKeuntungan);
            $row[] = 'Rp. ' . number_format($totalKeuntungan);
            $row[] = 'Rp. ' . number_format($omzet);
            $row[] = 'Rp. ' . number_format($omzetBersih);
            $row[] = $d['created_at'];

            array_push($newArr, $row);
            $no++;
        }


        $data_json = [
            'draw' => $this->input->post('draw'),
            'data' => $newArr,
            'recordsTotal' => $hitungTotalPendapatan,
            'recordsFiltered' => $filterTotalPendaptan,
        ];

        echo json_encode($data_json);
    }

    public function ajax_total_pendapatan()
    {
        $data = $this->Laporan_pendapatan_model->getAllPendapatan();
        $total = 0;
        $no = 1;

        foreach ($data as $d) {

            $jsonString = $d['harga_potongan'];
            preg_match('/\[(.*?)\]/', $jsonString, $matches);
            $arrayData = json_decode($matches[0], true);
            $hargaPotongan = isset($arrayData[0]['harga_potongan']) ? $arrayData[0]['harga_potongan'] : '';

            $totalSatuanKeuntungan = ($hargaPotongan == '') ? ((int)$d['harga_jual'] - 0) - (int)$d['harga_pokok'] : ((int)$d['harga_jual'] - $hargaPotongan) - (int)$d['harga_pokok'];

            $totalKeuntungan = (int)$d['qty'] * $totalSatuanKeuntungan;

            $total += $totalKeuntungan;

            $no++;
        }

        $rangeTanggalPendapatan = $this->Laporan_pendapatan_model->rangeTanggalPendapatan();
        $tgl = $this->input->post('tanggal');

        if (strlen($tgl) > 10) {
            $rangeTanggal = str_replace('to', 's/d', $tgl);
        }

        $rangeTanggal = ($tgl == '') ? date('Y-m-d', strtotime($rangeTanggalPendapatan->start_date)) . ' s/d ' . date('Y-m-d', strtotime($rangeTanggalPendapatan->end_date))  : $rangeTanggal;

        $data_json = [
            'totalPendapatan' => $total,
            'rangeTanggal' => $rangeTanggal,
        ];


        echo json_encode($data_json);
    }

    public function cetak_excel()
    {

        $res = json_decode($this->input->get('field'), TRUE);
        $toko = $this->Toko_model->findByIdToko($res['toko_id']);

        $data = $this->Laporan_pendapatan_model->getAllDataExcelPendapatan($res);

        $rangeTanggalPendapatan = $this->Laporan_pendapatan_model->rangeTanggalPendapatan();

        $libary_excel = [
            'func' => 'export',
            'jenis' => 'laporan_pendapatan',
            'filename' => 'laporan_pendapatan_' . date('Y_m_d_His') . ".xlsx",
            'data_pendapatan' => $data,
            'toko' => ($toko['nama_toko'] != '') ? $toko['nama_toko'] : '',
            'rangeTanggal' => ($res['tanggal'] == '') ? $rangeTanggalPendapatan->start_date . ' s/d ' . $rangeTanggalPendapatan->end_date : $res['tanggal']
        ];



        if ($data) {
            $this->load->library('Excel_library', $libary_excel);
        }
    }
}
