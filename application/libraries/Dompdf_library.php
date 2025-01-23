<?php

// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';

use Dompdf\Dompdf;


class Dompdf_library
{

    protected $CI;
    private $dompdf;

    public function __construct($parameter)
    {
        $this->CI = &get_instance();
        $this->dompdf = new Dompdf(array('enable_remote' => true));
        $this->case($parameter);
    }

    private function case($data)
    {

        switch ($data['func']) {

            case 'print':
                $this->print($data);
                break;
            case 'cetak':
                $this->cetak($data);
                break;
        }
    }

    private function print($data)
    {

        $this->dompdf->loadHtml($this->CI->load->view($data['isi'], $data, true));

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper($data['paper']['size'], $data['paper']['position']);

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF to Browser
        $this->dompdf->stream($data['filename'], array("Attachment" => false));

        exit(0);
    }


    private function cetak($data)
    {
        $this->dompdf->loadHtml($this->CI->load->view($data['view'], $data, TRUE));
        $this->dompdf->setPaper($data['paper']);

        if (array_key_exists('dpi', $data)) {

            $options = $this->dompdf->getOptions();
            $options->setDpi(intval($data['dpi']));
            $this->dompdf->setOptions($options);
        }

        $this->dompdf->render();
        $this->dompdf->stream($data['filename'], array("Attachment" => false));
    }
}
