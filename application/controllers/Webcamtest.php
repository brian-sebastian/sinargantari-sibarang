<?php

/**
 * @property Toko_model $toko
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 */
class Webcamtest extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Webcam Test";
        $data['title_menu'] = "Webcam test";
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('webcamtest/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function save()
    {
        $imageData = $this->input->post('imageData');

        // dump($imageData);

        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

        // Save the image to the server (you might want to add more checks and validations)
        $imageName = uniqid() . '.png';
        $imagePath = 'assets/camera/capture/' . $imageName;
        file_put_contents($imagePath, $decodedImage);

        $data = [
            'err_code' => 0,
            'message' => "Berhasil",
        ];
        return json_encode($data);
    }
}
