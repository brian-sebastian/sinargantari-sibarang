<?php

class Telebot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Telegrambot_library');
    }

    public function index()
    {
        $this->telegrambot_library->getMe();
    }
    public function get()
    {
        $this->telegrambot_library->getUpdates();
    }
    public function send()
    {
        $message = "Halo cobain bot";
        $this->telegrambot_library->sendMessage($message);
    }
}
