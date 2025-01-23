<?php

class Barang_library
{
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->library('zend');
        $this->CI->load->model('Barang_model', 'barang');
    }


    public function createKodeBarang()
    {
        $query_max_pendaftaran =  $this->CI->barang->getKodeBarang();
        $kode = "";
        if ($query_max_pendaftaran->num_rows() > 0) {
            //cek kode jika telah tersedia    
            foreach ($query_max_pendaftaran->result_array() as $k) {
                $tmp = ((int)$k['kode_barang']) + 1;
                $kode = sprintf("%06s", $tmp);
            }
        } else {
            $kode = "000001";
        }

        $batas = str_pad($kode, 6, "0", STR_PAD_LEFT);
        // $prefix = "BRG-000001";
        $prefix = "BRG-";

        $kodebaru = $prefix . $batas;
        return $kodebaru;
    }

    public function createKodeBarangLoop($index = 0)
    {
        $query_max_pendaftaran =  $this->CI->barang->getKodeBarang();
        $kode = "";
        if ($query_max_pendaftaran->num_rows() > 0) {
            //cek kode jika telah tersedia    
            foreach ($query_max_pendaftaran->result_array() as $k) {
                if ($index) {

                    $tmp = ((int)$k['kode_barang']) + $index;
                    $kode = sprintf("%06s", $tmp);
                } else {

                    $tmp = ((int)$k['kode_barang']) + 1;
                    $kode = sprintf("%06s", $tmp);
                }
            }
        } else {

            if ($index) {

                $kode = str_pad($index, 6, "0", STR_PAD_LEFT);
            } else {

                $kode = "000001";
            }
        }

        $batas = str_pad($kode, 6, "0", STR_PAD_LEFT);
        // $prefix = "BRG-000001";
        $prefix = "BRG-";

        $kodebaru = $prefix . $batas++;
        return $kodebaru++;
    }

    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function createBarcodeBarang($custom_barcode = null)
    {
        $data = [];
        $code = ($custom_barcode) ? $custom_barcode : $this->generateCode(12);
        // $this->load->library('zend');
        // $this->zend->load('Zend/Barcode');
        $this->CI->zend->load('Zend/Barcode');
        $imageResource = Zend_Barcode::factory('code128', 'image', ['text' => $code], array())->draw();
        imagepng($imageResource, 'assets/barcodes/' . $code . '.png');

        $data['barcode'] = 'assets/barcodes/' . $code . '.png';
        return $code;
    }

    private function generateCode($limit)
    {
        $code = '';
        for ($i = 0; $i < $limit; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }
}
