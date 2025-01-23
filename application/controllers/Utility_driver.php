<?php

class Utility_driver extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->helper('download');
    }

    public function index()
    {
        $data['title_menu'] = "Driver Printer";
        $data['title'] = "Driver Printer";

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('utility_driver/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function driver_app()
    {
        $this->doDownloadDriverApp();
    }
    public function driver_thermer()
    {
        $this->doDownloadDriverThermerApp();
    }

    private function doDownloadDriverApp()
    {
        $file_path = FCPATH . 'utility_driver/RawBT-v6.0.1.apk';
        force_download($file_path, NULL);
    }
    private function doDownloadDriverThermerApp()
    {
        $file_path = FCPATH . 'utility_driver/mate-bluetoothprint-152.apk';
        force_download($file_path, NULL);
    }

    public function downloadDriver58()
    {
        $this->driver58mm();
    }
    public function downloadDriver80()
    {
        $this->driver80mm();
    }
    public function downloadDriver120()
    {
        $this->driver120mm();
    }

    private function driver58mm()
    {
        $file_path = FCPATH . 'utility_driver/58mm.zip';
        force_download($file_path, NULL);
    }

    private function driver80mm()
    {
        $file_path = FCPATH . 'utility_driver/80mm.zip';
        force_download($file_path, NULL);
    }

    private function driver120mm()
    {
        $file_path = FCPATH . 'utility_driver/120mm.zip';
        force_download($file_path, NULL);
    }
}
