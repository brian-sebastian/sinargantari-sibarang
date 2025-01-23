<?php

class Setting_model extends CI_Model
{
    public function getSetting()
    {
        return $this->db->get('tbl_setting')->row_array();
    }

    public function updateSetting()
    {
        $this->load->library('Indonesianumber');
        $kode_instansi = htmlspecialchars($this->input->post('kode_instansi'));
        $whatsapp = htmlspecialchars($this->input->post('wa_instansi'));
        $wa_admin = htmlspecialchars($this->input->post('wa_admin'));
        $changeIndonesiaFormat = $this->indonesianumber->changeFormatIndonesiaNumberPhone($whatsapp);
        $waAdminIndonesia = $this->indonesianumber->changeFormatIndonesiaNumberPhone($wa_admin);

        $getDataSetting = $this->getSetting();

        if ($getDataSetting['img_instansi'] == null || empty($getDataSetting['img_instansi']) || $getDataSetting['img_instansi'] == '') {
            $upload_image = $_FILES['img_instansi']['name'];

            if ($_FILES['img_instansi']['error'] == 4 || ($_FILES['img_instansi']['size'] == 0 && $_FILES['img_instansi']['error'] == 0 && $_FILES['img_instansi']['name'] == '')) {
                // img_instansi is empty (and not an error), or no file was uploaded
                $this->session->set_flashdata('message_error', "Silahkan Memilih Logo Instansi Terlebih Dahulu Kemudian Lengkapi Data");
                redirect('setting');
            } else {
                $upload_image = $_FILES['img_instansi']['name'];
                if ($upload_image) {

                    $config['allowed_types'] = 'jpeg|jpg|png|JPEG|JPG|PNG|GIF|gif';
                    $config['max_size']  = 30000;
                    $config['upload_path']  = './assets/be/img/logo/';
                    $config['file_name']  = $kode_instansi . time();
                    $config['overwrite']  = true;

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('img_instansi')) {
                        $data['error'] = $this->upload->display_errors();
                        $this->session->set_flashdata('message_error', $data['error']);
                        clearstatcache();
                        redirect('setting');
                    } else {
                        $dataImage = $this->upload->data();
                        $newimage = $dataImage['file_name'];
                        $this->db->set('img_instansi', $newimage);
                    }
                } else {
                    $this->session->set_flashdata('message_error', "Kamu belum memilih gambar");
                    redirect('setting');
                }
            }
        }

        if ($getDataSetting['img_instansi'] != null) {
            $upload_image = $_FILES['img_instansi']['name'];
            if ($upload_image) {

                $config['allowed_types'] = 'jpeg|jpg|png|JPEG|JPG|PNG|GIF|gif';
                $config['max_size']  = 30000;
                $config['upload_path']  = './assets/be/img/logo/';
                $config['file_name']  = $kode_instansi . time();
                $config['overwrite']  = true;

                $this->load->library('upload');
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('img_instansi')) {
                    $data['error'] = $this->upload->display_errors();
                    $this->session->set_flashdata('message_error', $data['error']);
                    clearstatcache();
                    redirect('setting');
                } else {
                    $dataImage = $this->upload->data();
                    $newimage = $dataImage['file_name'];
                    $this->db->set('img_instansi', $newimage);
                }
            }
        }

        $this->db->set('instansi', htmlspecialchars($this->input->post('instansi')));
        $this->db->set('kode_instansi', $kode_instansi);
        $this->db->set('owner', htmlspecialchars($this->input->post('owner')));
        $this->db->set('wa_instansi', $changeIndonesiaFormat);
        $this->db->set('wa_admin', $waAdminIndonesia);
        $this->db->set('tlp_instansi', htmlspecialchars($this->input->post('tlp_instansi')));
        $this->db->set('ig_instansi', htmlspecialchars($this->input->post('ig_instansi')));
        $this->db->set('fb_instansi', htmlspecialchars($this->input->post('fb_instansi')));
        $this->db->set('email_instansi', htmlspecialchars($this->input->post('email_instansi')));


        if ($this->getSetting() == FALSE) {
            $this->db->insert('tbl_setting');
            return $this->db->affected_rows();
        } else {
            $this->db->update('tbl_setting');
            return $this->db->affected_rows();
        }
    }

    private function uploadLogo($kode_instansi)
    {
        $upload_image = $_FILES['img_instansi']['name'];
        // $upload_image = $_FILES['img_instansi'];
        // dump($_FILES);
        // die;
        if ($upload_image) {
            $config['allowed_types'] = 'jpeg|jpg|png|JPEG|JPG|PNG|GIF|gif';
            $config['max_size']  = 30000;
            $config['upload_path']  = './assets/be/img/logo';
            $config['file_name']  = $kode_instansi . time();
            $config['overwrite']  = true;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img_instansi')) {
                $data['error'] = $this->upload->display_errors();
                $this->session->set_flashdata('message_error', $data['error']);
                clearstatcache();
                redirect('setting');
            } else {
                $dataImage = $this->upload->data();
                $newimage = $dataImage['file_name'];
                date_default_timezone_set('Asia/Jakarta');
                $this->db->set('img_instansi', $newimage);
                $this->db->where('kode_instansi', $kode_instansi);
                $this->db->update('tbl_setting');
                clearstatcache();
                redirect('setting');
            }
        } else {
            $this->session->set_flashdata('message_error', "Kamu belum memilih gambar");
            redirect('setting');
        }
    }
}
