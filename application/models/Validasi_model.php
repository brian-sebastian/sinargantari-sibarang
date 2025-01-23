<?php

class Validasi_model extends CI_Model
{

    public function check_duplicate($table, $kolom, $value)
    {

        $query = $this->db->get_where($table, [$kolom  => $value]);

        if ($query->num_rows() > 0) {

            return 1;
        }

        return 0;
    }

    public function check_barcode_duplicate($barcode_barang)
    {

        $this->db->where("barcode_barang", $barcode_barang);
        $query = $this->db->get("tbl_barang");

        if ($query->num_rows() > 0) {

            return true;
        }

        return false;
    }

    public function check_regex_name($nama_barang)
    {
        $isRegexValidation = validateOnlyLettersAndSpaces($nama_barang);
        if ($isRegexValidation == true) {
            return true;
        } else {
            return false;
        }
    }


    public function check_barang($table, $kolom, $value)
    {
        $query = $this->db->get_where($table, [$kolom => $value]);

        if ($query->num_rows() > 0) {

            return $query->row_array()['id_barang'];
        }

        return 0;
    }

    public function check_toko($table, $kolom, $value)
    {
        $query = $this->db->get_where($table, [$kolom => $value]);

        if ($query->num_rows() > 0) {
            return $query->row_array()['id_toko'];
        }

        return 0;
    }

    public function check_exist($table, $kolom, $value)
    {
        $query = $this->db->get_where($table, [$kolom  => $value]);

        if ($query->num_rows() === 1) {

            return $query->row_array();
        }

        return 0;
    }

    public function check_kategori_exist($kategori)
    {

        $this->db->select('id_kategori, kode_kategori, nama_kategori');
        $this->db->where('id_kategori', $kategori);
        $this->db->or_where('kode_kategori', $kategori);
        $this->db->or_where('nama_kategori', $kategori);
        $query = $this->db->get('tbl_kategori');

        if ($query->num_rows() > 0) {

            return $query->row_array()['id_kategori'];
        }

        return 0;
    }

    public function check_satuan_exist($satuan)
    {
        $this->db->select('id_satuan, satuan');
        $this->db->where('id_satuan', $satuan);
        $this->db->or_where('satuan', $satuan);
        $query = $this->db->get('tbl_satuan');

        if ($query->num_rows() > 0) {
            return $query->row_array()['id_satuan'];
        }
        return 0;
    }

    public function insert_data($data, $status)
    {

        switch ($status) {

            case 'valid':
                /**
                 * TODO : Insert Batch Berlaku jika tidak ada history harga
                 */
                $this->db->trans_start();

                foreach ($data as $data_barang) {
                    $this->db->set('kategori_id', $data_barang['kategori_id']);
                    $this->db->set('satuan_id', $data_barang['satuan_id']);
                    $this->db->set('kode_barang', $data_barang['kode_barang']);
                    $this->db->set('nama_barang', $data_barang['nama_barang']);
                    $this->db->set('slug_barang', $data_barang['slug_barang']);
                    $this->db->set('barcode_barang', $data_barang['barcode_barang']);
                    $this->db->set('harga_pokok', $data_barang['harga_pokok']);
                    $this->db->set('berat_barang', $data_barang['berat_barang']);
                    $this->db->set('deskripsi', $data_barang['deskripsi']);
                    $this->db->set('is_active', $data_barang['is_active']);
                    $this->db->set('user_input', $data_barang['user_input']);
                    $this->db->set('created_at', $data_barang['created_at']);
                    $this->db->insert("tbl_barang");

                    $id_barang = $this->db->insert_id();
                    $this->db->set('barang_id', $id_barang);
                    $this->db->set('kategori_id', $data_barang['kategori_id']);
                    $this->db->set('satuan_id', $data_barang['satuan_id']);

                    $this->db->set('harga_pokok', $data_barang['harga_pokok']);
                    $this->db->set('berat_barang', $data_barang['berat_barang']);

                    $this->db->set('user_input', $data_barang['user_input']);
                    $this->db->set('created_at', $data_barang['created_at']);
                    $this->db->set('tanggal_perubahan', $data_barang['created_at']);
                    $this->db->insert("tbl_barang_harga_history");
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    return;
                } else {

                    $this->db->trans_commit();
                    return;
                }
                return;
                break;

            case 'valid_barang_toko':

                $this->db->trans_start();

                foreach ($data as $data_barang_toko) {
                    $this->db->set('barang_id', $data_barang_toko['barang_id']);
                    $this->db->set('toko_id', $data_barang_toko['toko_id']);
                    $this->db->set('stok_toko', $data_barang_toko['stok_toko']);
                    $this->db->set('harga_jual', $data_barang_toko['harga_jual']);
                    $this->db->set('is_active', $data_barang_toko['is_active']);
                    $this->db->set('user_input', $data_barang_toko['user_input']);
                    $this->db->set('created_at', $data_barang_toko['created_at']);
                    $this->db->insert("tbl_harga");

                    $id_barang_toko = $this->db->insert_id();
                    $this->db->set('harga_id', $id_barang_toko);
                    $this->db->set('harga_jual', $data_barang_toko['harga_jual']);
                    $this->db->set('is_active', $data_barang_toko['is_active']);
                    $this->db->set('user_input', $data_barang_toko['user_input']);
                    $this->db->set('created_at', $data_barang_toko['created_at']);
                    $this->db->insert("tbl_harga_toko_history");
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    return;
                } else {

                    $this->db->trans_commit();
                    return;
                }
                return;
                break;

            case 'valid_karyawan_toko':

                $this->db->trans_start();

                foreach ($data as $d) {

                    $this->db->set("username", $d["username"]);
                    $this->db->set("nama_user", $d["nama_karyawan"]);
                    $this->db->set("password", password_hash("12345678", PASSWORD_DEFAULT));
                    $this->db->set("role_id", $d["role_id"]);
                    $this->db->set("created_at", date("Y-m-d H:i:s"));
                    $this->db->insert("tbl_user");

                    $last_id = $this->db->insert_id();

                    $this->db->set("user_id", $last_id);
                    $this->db->set("toko_id", $d["toko_id"]);
                    $this->db->set("nama_karyawan", $d["nama_karyawan"]);
                    $this->db->set("hp_karyawan", $d["hp_karyawan"]);
                    $this->db->set("alamat_karyawan", $d["alamat_karyawan"]);
                    $this->db->set("bagian", $d["bagian"]);
                    $this->db->set("user_input", $this->session->userdata("nama_user"));
                    $this->db->set("created_at", date("Y-m-d H:i:s"));
                    $this->db->insert("tbl_karyawan_toko");
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    return;
                } else {

                    $this->db->trans_commit();
                    return;
                }

                return;
                break;

            case 'error':
                $this->db->insert_batch('tbl_barang_temp', $data);
                return;
                break;
            case 'error_karyawan_toko':

                $this->db->insert_batch('tbl_karyawan_toko_temp', $data);
                return;
                break;

            case 'error_barang_toko':

                $this->db->insert_batch('tbl_harga_temp', $data);
                return;
                break;
        }
    }

    public function delete($table, $kolom = "")
    {
        ($kolom) ? $this->db->delete($table, $kolom) : $this->db->delete($table);
        return;
    }

    public function do_truncate($table)
    {
        $this->db->truncate($table);
        return;
    }

    public function do_delete($table, $kolom)
    {

        $this->db->delete($table, $kolom);
        return;
    }
}
