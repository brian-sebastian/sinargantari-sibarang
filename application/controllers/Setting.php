<?php

/**
 * @property Setting_model $setting
 * @property session $session
 * @property input $input
 * @property form_validation $form_validation
 * @property db $db
 * @property dbutil $dbutil
 */
class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Setting_model', 'setting');
        cek_login();
    }

    public function index()
    {
        $data['title'] = "Setting Instansi";
        $data['title_menu'] = "Setting";
        $data['setting'] = $this->setting->getSetting();
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('setting/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function changeSetting()
    {
        $getSetting = $this->setting->getSetting();
        if ($getSetting == null) {
            $this->form_validation->set_rules(
                'kode_instansi',
                'Kode Instansi',
                'required|max_length[200]|is_unique[tbl_setting.kode_instansi]'
            );
        } else {
            $this->form_validation->set_rules(
                'kode_instansi',
                'Kode Instansi',
                'required|max_length[200]'
            );
        }

        $this->form_validation->set_rules(
            'instansi',
            'Instansi',
            'required|max_length[200]'
        );
        $this->form_validation->set_rules(
            'owner',
            'Owner',
            'required|max_length[200]'
        );
        $this->form_validation->set_rules(
            'wa_instansi',
            'Whatsapp Instansi',
            'required|max_length[13]'
        );
        $this->form_validation->set_rules(
            'wa_admin',
            'Whatsapp Admin',
            'required|max_length[13]'
        );
        $this->form_validation->set_rules(
            'tlp_instansi',
            'Telepon Instansi',
            'required|max_length[10]'
        );
        $this->form_validation->set_rules(
            'email_instansi',
            'Email Instansi',
            'valid_email'
        );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message_error', validation_errors());
            redirect('setting');
        } else {
            $this->setting->updateSetting();
            $this->session->set_flashdata('message', 'Berhasil disimpan');
            redirect('setting');
        }
    }

    public function backupdb()
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->load->dbutil();

        $preference = [
            'format' => 'zip',
            'filename' => 'backup_db_' . $this->db->database . 'on-' . date('Y-m-d-H-i-s') . '.sql',
            // 'add_insert' => TRUE,
            // 'foreign_key_checks' => FALSE,
        ];
        $backup = $this->dbutil->backup($preference);
        $db_name = 'Backup-db-' . $this->db->database . 'on-' . date('Y-m-d-H-i-s') . '.zip';
        $save = './backup/db/' . $db_name;

        $this->load->helper('file');
        write_file($save, $backup);

        // Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download($db_name, $backup);
    }
}
