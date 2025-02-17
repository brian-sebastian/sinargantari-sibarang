<?php

class Harga_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Diskon_model", "diskon");
    }

    public function getFindHargaBarangDanToko($idToko, $idBarang)
    {

        $roleId = $this->session->userdata('role_id');


        $this->db->select('*');
        $this->db->from('tbl_harga');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', 'left');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_toko.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);

        if ($roleId == 1 || $roleId == 2) {
            $this->db->where('tbl_harga.toko_id', $idToko);
            $this->db->where('tbl_harga.barang_id', $idBarang);
        } else {

            $tokoId = $this->session->userdata("toko_id");
            if ($tokoId) {

                $this->db->where('tbl_harga.toko_id', $tokoId);
                $this->db->where('tbl_harga.barang_id', $idBarang);
            }
        }

        return $this->db->get()->row_array();
    }

    public function findBarangByToko($toko_id)
    {
        $this->db->select('tbl_harga.barang_id, tbl_barang.nama_barang');
        $this->db->from('tbl_harga');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', 'left');
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.toko_id', $toko_id);

        return $this->db->get()->result_array();
    }


    public function getTableHargaId($id)
    {
        return $this->db->get_where('tbl_harga', ['id_harga' => $id])->row_array();
    }

    public function ubahData($data)
    {
        $this->db->where("id_harga", $data["id_harga"]);
        unset($data["id_harga"]);
        $this->db->update("tbl_harga", $data);
        return $this->db->affected_rows();
    }


    public function tambahBarangToko($data)
    {
        $this->db->trans_start();
        $this->db->set('barang_id', $data['barang_id']);
        $this->db->set('toko_id', $data['toko_id']);
        $this->db->set('stok_toko', $data['stok_toko']);
        $this->db->set('harga_jual', $data['harga_jual']);
        $this->db->set('is_active', $data['is_active']);
        $this->db->set('user_input', $data['user_input']);
        $this->db->set('created_at', $data['created_at']);
        $this->db->insert('tbl_harga');

        $last_id_harga = $this->db->insert_id();

        $this->db->set('harga_id', $last_id_harga);
        $this->db->set('harga_jual', $data['harga_jual']);
        $this->db->set('is_active', 1);
        $this->db->set('user_input', $this->session->userdata('username'));
        $this->db->set('created_at', $data['created_at']);
        $this->db->insert('tbl_harga_toko_history');
        $this->db->trans_complete();

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

        // return $this->db->affected_rows();
    }

    public function updateHargaToko($data)
    {


        date_default_timezone_set('Asia/Jakarta');
        $this->db->trans_start();

        $getCurrenttBarangToko = $this->db->get_where('tbl_harga', ['id_harga' => $data['id_harga'], 'barang_id' => $data['barang_id']])->row_array();

        $is_changed_code = 0;
        $harga_hargaJual = $getCurrenttBarangToko['harga_jual'];


        if ($getCurrenttBarangToko['harga_jual'] != $data['harga_jual']) {
            $harga_hargaJual = $data['harga_jual'];
            $is_changed_code++;
        }

        if ($is_changed_code > 0) {

            $dataHistoryBarangToko = [
                'harga_id' => $data['id_harga'],
                'harga_jual' => $harga_hargaJual,
                'user_input' => $this->session->userdata('username'),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->insert('tbl_harga_toko_history', $dataHistoryBarangToko);
        }

        $this->db->set('harga_jual', $data['harga_jual']);
        $this->db->set('stok_toko', $data['stok_toko']);
        $this->db->set('user_edit', $this->session->userdata('nama_user'));
        $this->db->set('updated_at', date('Y-m-d H:i:s'));
        $this->db->where('toko_id', $data['toko_id']);
        $this->db->where('barang_id', $data['barang_id']);
        $this->db->where('id_harga', $data['id_harga']);
        $this->db->update('tbl_harga');

        $this->db->trans_complete();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function ambilBarangBerdasarkanToko($toko_id)
    {

        $this->db->select("tbl_harga.id_harga, tbl_barang.id_barang, tbl_barang.nama_barang, tbl_barang.kode_barang,tbl_barang.barcode_barang, ,tbl_harga.stok_toko");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_harga.barang_id", "inner");
        $this->db->where("tbl_harga.toko_id", $toko_id);
        $this->db->where("tbl_harga.is_active", 1);
        $this->db->order_by("tbl_harga.stok_toko", 'ASC');
        $this->db->order_by("tbl_barang.kode_barang", 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function ambilBarangBerdasarkanGudang($toko_id)
    {

        $this->db->select("tbl_harga.id_harga, tbl_barang.id_barang, tbl_barang.nama_barang, tbl_harga.stok_toko");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_harga.barang_id");
        $this->db->where("tbl_harga.toko_id", $toko_id);
        $this->db->where("tbl_harga.is_active", 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function ambilBarangBerdasarkanTokoDanKecualiIdHarga($data_barang)
    {
        $toko_id = $data_barang['id_toko'];
        $id_barang = $data_barang['id_barang'];

        $this->db->select("tbl_harga.id_harga, tbl_barang.nama_barang, tbl_barang.kode_barang,tbl_barang.barcode_barang, ,tbl_harga.stok_toko");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_harga.barang_id", "inner");
        $this->db->where("tbl_harga.toko_id", $toko_id);
        $this->db->where("tbl_harga.barang_id !=", $id_barang);
        $this->db->where("tbl_harga.is_active", 1);
        $this->db->order_by("tbl_harga.stok_toko", 'ASC');
        $this->db->order_by("tbl_barang.kode_barang", 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getHargaBarangbyToko($barcode, $toko_id)
    {
        $this->db->select('id_harga, barang_id, toko_id, stok_toko, harga_jual, tbl_harga.is_active, tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis, tbl_toko.is_active, tbl_barang.id_barang, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.informasi, tbl_barang.gambar, tbl_barang.satuan_id, tbl_barang.kategori_id ,tbl_barang.is_active, tbl_satuan.id_satuan, tbl_satuan.satuan, tbl_kategori.id_kategori, tbl_kategori.nama_kategori,tbl_kategori.kode_kategori');
        $this->db->from('tbl_harga');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang', 'left');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko', 'left');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan', 'left');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori', 'left');
        $this->db->where("tbl_harga.stok_toko >", 0);
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_barang.barcode_barang', $barcode);
        $this->db->where('toko_id', $toko_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function ambilHargaJoinDenganBarang($id_harga)
    {
        $this->db->select("tbl_harga.harga_jual, tbl_harga.stok_toko, tbl_harga.id_harga, tbl_barang.nama_barang");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_harga.id_harga", $id_harga);
        $this->db->where("tbl_harga.is_active", 1);
        $this->db->where("tbl_barang.is_active", 1);
        $query = $this->db->get();

        if ($query->num_rows() === 1) {

            $data = $query->row_array();

            $diskon = $this->diskon->cekDiskonBarang($data["id_harga"], 1);

            $data["diskon"] = json_encode($diskon);
            $data["diskon_js"] = htmlspecialchars(json_encode($diskon));

            return $data;
        }

        return 0;
    }

    public function getBarangHargaById($id_harga)
    {
        $this->db->select(
            'tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_kategori.id_kategori, tbl_kategori.kode_kategori, tbl_kategori.nama_kategori,
            tbl_satuan.id_satuan, tbl_satuan.satuan,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_harga.is_active,
            '
        );
        $this->db->from('tbl_harga');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_harga.id_harga', $id_harga);
        return $this->db->get()->row_array();
    }

    public function cekHargaPadaOrderDetail($id_harga)
    {
        $this->db->where("id_harga NOT IN (SELECT harga_id FROM tbl_order_detail WHERE harga_id = '$id_harga')");
        $this->db->where("id_harga", $id_harga);
        $query = $this->db->get("tbl_harga");

        if ($query->num_rows() > 0) {

            return 1;
        }

        return 0;
    }

    public function hapusBarangMelaluiHargaId($id_harga)
    {

        $this->db->where("id_harga", $id_harga);
        $this->db->delete("tbl_harga");
        return $this->db->affected_rows();
    }

    public function deleteHargaBySelectedItems($selectedIds, $toko_id)
    {
        $this->db->where_in('id_harga', $selectedIds);
        $this->db->where('toko_id', $toko_id);
        $this->db->delete('tbl_harga');
        return $this->db->affected_rows() > 0;
    }


    public function ubahStatusHargaMelaluiIdDanStatus($param)
    {
        $this->db->set("is_active", $param[0]);
        $this->db->where("id_harga", $param[1]);
        $this->db->update("tbl_harga");
        return $this->db->affected_rows();
    }

    public function ambilBarangTemp($toko_id)
    {
        $this->db->select("barang_id, toko_id, stok_toko, harga_jual");
        $this->db->where("toko_id", $toko_id);
        $query = $this->db->get("tbl_harga_temp");
        return $query;
    }

    public function ambilBarangByHargaId($id_harga)
    {
        $this->db->select("tbl_barang.nama_barang");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        $this->db->where("tbl_harga.id_harga", $id_harga);
        $this->db->where("tbl_harga.is_active", 1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function ambilSemuaBarangTokoSesuaiIdToko($toko_id)
    {
        $this->db->select('tbl_harga.id_harga, tbl_kategori.nama_kategori, tbl_barang.kode_barang, tbl_harga.stok_toko, tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_barang.barcode_barang, (select SUM(stok_toko) from tbl_harga as tbl_harga_subquery inner join tbl_toko as tbl_toko_subquery on tbl_harga_subquery.toko_id = tbl_toko.id_toko where tbl_harga_subquery.barang_id = tbl_harga.barang_id and tbl_harga_subquery.is_active = 0 and tbl_toko_subquery.jenis = "GUDANG") as stok_gudang');
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        $this->db->join("tbl_kategori", "tbl_barang.kategori_id = tbl_kategori.id_kategori", "inner");
        $this->db->join("tbl_toko", "tbl_harga.toko_id = tbl_toko.id_toko", "inner");
        $this->db->where("tbl_harga.toko_id", $toko_id);
        $query = $this->db->get();
        return $query->result_array();
    }
}
