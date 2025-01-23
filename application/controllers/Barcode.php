<?php

/**
 * @property zend $zend
 */
class Barcode extends CI_Controller
{
    public function index()
    {
        //I'm just using rand() function for data example
        $data = [];
        $code = rand(10000, 99999);

        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $code), array())->draw();
        imagepng($imageResource, 'assets/barcodes/' . $code . '.png');

        $data['barcode'] = 'assets/barcodes/' . $code . '.png';
        $this->load->view('welcome', $data);
    }
}
