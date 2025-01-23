<?php

/**
 * @property watzap_library $watzap_library
 */
class Watzap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('watzap_library');
    }

    public function index() //checkKey
    {
        $this->watzap_library->checkKey();
    }

    public function validasi_nomor()
    {
        $this->watzap_library->validateNumber();
    }

    public function group_grab()
    {
        //example
        //groupId = 120363189311618474
        $this->watzap_library->groupGrabber();
    }

    public function send_message()
    {
        $number = "6289515314512";
        $message = "Halo Tes Whatsapp API";
        $this->watzap_library->sendMessage($number, $message);
    }

    public function send_image_url()
    {

        $number = "6281359307334";
        // $number = "6287770062252";
        $message = "Halo Tes Whatsapp API";
        $imageurl = "https://www.ekuatorial.com/wp-content/uploads/2015/04/Macaca_nigra_self-portrait.jpg";
        $this->watzap_library->sendImageByURL($number, $message, $imageurl);
    }

    public function send_file_url()
    {

        $number = "6289515314512";
        $fileUrl = "https://www.ekuatorial.com/wp-content/uploads/2015/04/Macaca_nigra_self-portrait.jpg";
        $this->watzap_library->sendFileByURL($number, $fileUrl);
    }

    public function send_message_group()
    {
        // $groupId = "120363189311618480";
        // $groupId = "120363189311618474";
        $groupId = "120363189311618474";
        $message = "Halo Tes Whatsapp API";
        $this->watzap_library->sendMessageGroup($groupId, $message);
    }
}
