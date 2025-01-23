<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Check whether the site is offline or not.
 *
 */
class Maintenance_hook
{
    public function __construct()
    {
        log_message('debug', 'Accessing maintenance hook!');
    }

    public function offline_check()
    {
        if (file_exists(APPPATH . 'config/config.php')) {
            include(APPPATH . 'config/config.php');

            if (isset($config['maintenance_mode']) && $config['maintenance_mode'] === TRUE) {
                // redirect('maintenance');
                // $this->load->view('maintenance/index');
                include(APPPATH . 'views/maintenance/index.php');
                exit;
            }
        }
    }
}
