<?php

class Logfile_model extends CI_Model
{

    public function insertLog($data)
    {

        $this->db->insert('log_import', $data);
        return $this->db->affected_rows();
    }
}
