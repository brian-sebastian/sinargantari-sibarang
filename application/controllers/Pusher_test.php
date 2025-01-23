<?php

// require_once(APPPATH . 'vendor/autoload.php');
// require __DIR__ . '/vendor/autoload.php';

class Pusher_test extends CI_Controller
{
    private $app_id;
    private $app_key;
    private $app_secret;
    private $app_cluster;
    public function __construct()
    {
        parent::__construct();
        $this->app_id = $_ENV['PUSHER_APP_ID'];
        $this->app_key = $_ENV['PUSHER_KEY'];
        $this->app_secret = $_ENV['PUSHER_SECRET'];
        $this->app_cluster = $_ENV['PUSHER_CLUSTER'];
    }

    public function index()
    {
        require_once(FCPATH . 'vendor/autoload.php');
        $options = array(
            'cluster' => $this->app_cluster,
            'useTLS' => true
        );
        $pusher = new Pusher\Pusher($this->app_key, $this->app_secret, $this->app_id, $options);

        $pusher->trigger('my-channel', 'my-event', array('message' => 'success'));
    }
    public function goPush()
    {
        pusherMessage('dashboard', 'test-event', 'Bisa Cuy');
    }
}
