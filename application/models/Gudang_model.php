<?php

class Gudang_model extends CI_Model{

    public function getGudang(){

        $this->db->select("id_toko, nama_toko");
        $this->db->where("jenis", "GUDANG");
        // $this->db->where("is_active", 1);
        $query = $this->db->get("tbl_toko");
        return $query->result_array();
        
    }

    public function createBarangMasuk(){

        $supplier_id = $this->input->post("supplier_id");
        $gudang_id = $this->input->post("gudang_id");
        $barang_id = $this->input->post("barang_id");
        $tanggal_barang_masuk = $this->input->post("tanggal_barang_masuk");
        $jml_masuk = $this->input->post("jml_masuk");

        $this->db->trans_begin();

        $this->db->where("barang_id", $barang_id);
        $this->db->where("toko_id", $gudang_id);
        $query = $this->db->get("tbl_harga");
        
        if(!$query->num_rows()){

            // buat harga id baru
            $this->db->set("barang_id", $barang_id);
            $this->db->set("toko_id", $gudang_id);
            $this->db->set("stok_toko", $jml_masuk);
            $this->db->set("harga_jual", 0);
            $this->db->set("is_active", 0);
            $this->db->set("user_input", $this->session->userdata("username"));
            $this->db->set("created_at", date("Y-m-d H:i:s"));
            $this->db->insert("tbl_harga");
            $harga_id = $this->db->insert_id();

        }else{

            $data_harga = $query->row_array();
            // ambil harga id lama 
            $harga_id = $data_harga["id_harga"];
            // tambah jumlah stoknya
            $this->db->set("stok_toko", intval($data_harga["stok_toko"]) + intval($jml_masuk));
            $this->db->set("updated_at", date("Y-m-d H:i:s"));
            // update harga id lama
            $this->db->where("id_harga", $harga_id);
            $this->db->update("tbl_harga");

        }

        $this->db->set("harga_id", $harga_id);
        $this->db->set("jml_masuk", $jml_masuk);
        $this->db->set("tipe", "supplier_gudang");
        $this->db->set("tanggal_barang_masuk", $tanggal_barang_masuk);
        $this->db->set("user_input", $this->session->userdata("username"));
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_barang_masuk"); 

        if($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();
            return FALSE;

        }

        $this->db->trans_commit();
        return TRUE;
        
    }

    public function createBarangMasukGudangToGudang(){

        $gudang_dari = $this->input->post("gudang_dari_id");
        $gudang_ke = $this->input->post("gudang_ke_id");
        $barang_id = $this->input->post("barang_id");
        $tanggal_barang_masuk = $this->input->post("tanggal_barang_masuk");
        $jml_masuk = $this->input->post("jml_masuk");

        $this->db->trans_begin();

        $this->db->where("toko_id", $gudang_ke);
        $this->db->where("barang_id", $barang_id);
        $query = $this->db->get("tbl_harga");

        if(!$query->num_rows()){

            // buat harga id baru
            $this->db->set("barang_id", $barang_id);
            $this->db->set("toko_id", $gudang_ke);
            $this->db->set("stok_toko", $jml_masuk);
            $this->db->set("harga_jual", 0);
            $this->db->set("is_active", 0);
            $this->db->set("user_input", $this->session->userdata("username"));
            $this->db->set("created_at", date("Y-m-d H:i:s"));
            $this->db->insert("tbl_harga");
            $harga_id = $this->db->insert_id();

        }else{

            // update
            $data_harga = $query->row_array();
            // ambil harga id lama 
            $harga_id = $data_harga["id_harga"];
            // tambah jumlah stoknya
            $this->db->set("stok_toko", intval($data_harga["stok_toko"]) + intval($jml_masuk));
            $this->db->set("updated_at", date("Y-m-d H:i:s"));
            // update harga id lama
            $this->db->where("id_harga", $harga_id);
            $this->db->update("tbl_harga");
        }

        $this->db->where("toko_id", $gudang_dari);
        $this->db->where("barang_id", $barang_id);
        $query2 = $this->db->get("tbl_harga")->row_array();

        $this->db->set("stok_toko", intval($query2["stok_toko"]) - intval($jml_masuk));
        $this->db->set("updated_at", date("Y-m-d H:i:s"));
        // update harga id lama
        $this->db->where("id_harga", $query2["id_harga"]);
        $this->db->update("tbl_harga");

        $this->db->set("harga_id", $harga_id);
        $this->db->set("jml_masuk", $jml_masuk);
        $this->db->set("tipe", "gudang");
        $this->db->set("tanggal_barang_masuk", $tanggal_barang_masuk);
        $this->db->set("user_input", $this->session->userdata("username"));
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_barang_masuk"); 

        $this->db->set("harga_id", $query2["id_harga"]);
        $this->db->set("jml_keluar", $jml_masuk);
        $this->db->set("jenis_keluar", "DISTRIBUSI");
        $this->db->set("tanggal_barang_keluar", $tanggal_barang_masuk);
        $this->db->set("user_input", $this->session->userdata("username"));
        $this->db->set("created_at", date("Y-m-d H:i:s"));
        $this->db->insert("tbl_barang_keluar"); 

        if($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();
            return FALSE;

        }else{

            $this->db->trans_commit();
            return TRUE;
        }

    }

    public function getImportGudangBaru(){

        return $this->db->get("tbl_import_gudang_baru")->result_array();

    }

    public function processCreateDataBarangGudangBaru(){

        $query = $this->db->get("tbl_import_gudang_baru");

        if($query->num_rows()){

            $this->db->where_not_in("status", ["valid"]);
            $query2 = $this->db->get("tbl_import_gudang_baru");

            if(!$query2->num_rows()){

                $this->db->trans_begin();

                $datas = $query->result_array();

                foreach($datas as $data){

                    $this->db->where("LOWER(nama_toko)", strtolower($data["nama_gudang"]));
                    $this->db->where("jenis", "GUDANG");
                    $toko_id = $this->db->get("tbl_toko")->row_array()["id_toko"];

                    $this->db->where("LOWER(nama_barang)", strtolower($data["nama_barang"]));
                    $barang_id = $this->db->get("tbl_barang")->row_array()["id_barang"];

                    $this->db->where("barang_id", $barang_id);
                    $this->db->where("toko_id", $toko_id);
                    $query = $this->db->get("tbl_harga");
                    
                    if(!$query->num_rows()){

                        // buat harga id baru
                        $this->db->set("barang_id", $barang_id);
                        $this->db->set("toko_id", $toko_id);
                        $this->db->set("stok_toko", $data["jumlah_barang"]);
                        $this->db->set("harga_jual", 0);
                        $this->db->set("is_active", 0);
                        $this->db->set("user_input", $this->session->userdata("username"));
                        $this->db->set("created_at", date("Y-m-d H:i:s"));
                        $this->db->insert("tbl_harga");
                        $harga_id = $this->db->insert_id();

                    }else{

                        $data_harga = $query->row_array();
                        // ambil harga id lama 
                        $harga_id = $data_harga["id_harga"];
                        // tambah jumlah stoknya
                        $this->db->set("stok_toko", intval($data_harga["stok_toko"]) + intval($data["jumlah_barang"]));
                        $this->db->set("updated_at", date("Y-m-d H:i:s"));
                        // update harga id lama
                        $this->db->where("id_harga", $harga_id);
                        $this->db->update("tbl_harga");

                    }

                    $this->db->set("harga_id", $harga_id);
                    $this->db->set("jml_masuk", $data["jumlah_barang"]);
                    $this->db->set("tipe", "import_gudang");
                    $this->db->set("tanggal_barang_masuk", date("Y-m-d H:i:s"));
                    $this->db->set("nama_toko_beli", $data["nama_toko_luar"]);
                    $this->db->set("user_input", $this->session->userdata("username"));
                    $this->db->set("created_at", date("Y-m-d H:i:s"));
                    $this->db->insert("tbl_barang_masuk"); 

                }

                
                if($this->db->trans_status() === FALSE){
                    
                    $this->db->trans_rollback();
                    return "Gagal menyimpan hasil import";

                }else{

                    $this->db->truncate('tbl_import_gudang_baru');
                    $this->db->trans_commit();
                    return true;
                }

            }

            return "Terdapat data yang masih salah";

        }else{

            return "Tidak ada data yang disimpan";
        }

    }

}

?>