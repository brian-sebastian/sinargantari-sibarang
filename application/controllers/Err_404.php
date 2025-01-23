<?php

/**
 * @property output $output
 */
class Err_404 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->output->set_status_header('404');
        $data['title'] = "Error 404 - Page Not Found";
        $this->load->view('auth/layout_auth/header');
        $this->load->view('auth/v_err_404_notfound');
        $this->load->view('auth/layout_auth/footer');
    }
}
