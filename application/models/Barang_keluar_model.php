<?php

class Barang_keluar_model extends CI_Model
{
    public function getAllBarangKeluar()
    {
        $this->db->select('*, tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_barang_keluar.jenis_keluar, tbl_barang_keluar.created_at, tbl_barang_keluar.updated_at');
        $this->db->from('tbl_barang_keluar');
        $this->db->join('tbl_harga', 'tbl_harga.id_harga = tbl_barang_keluar.harga_id');
        $this->db->join('tbl_order_detail', 'tbl_order_detail.id_order_detail = tbl_barang_keluar.order_detail_id', 'left');
        $this->db->join('tbl_order', 'tbl_order.id_order = tbl_order_detail.order_id', 'left');
        $this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_harga.barang_id');
        $this->db->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id');
        return $this->db->get()->result_array();
    }

    public function ambilSemuaBarangKeluarToko($id_toko)
    {
        $this->db->select('*, tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_barang_keluar.jenis_keluar, tbl_barang_keluar.created_at, tbl_barang_keluar.updated_at');
        $this->db->from('tbl_barang_keluar');
        $this->db->join('tbl_harga', 'tbl_harga.id_harga = tbl_barang_keluar.harga_id');
        $this->db->join('tbl_order_detail', 'tbl_order_detail.id_order_detail = tbl_barang_keluar.order_detail_id', 'left');
        $this->db->join('tbl_order', 'tbl_order.id_order = tbl_order_detail.order_id', 'left');
        $this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_harga.barang_id');
        $this->db->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id');
        $this->db->where('tbl_toko.id_toko', $id_toko);
        return $this->db->get()->result_array();
    }

    public function ambilDataBarangKeluar($where)
    {
        $id_barang_keluar = $where['id_barang_keluar'];
        $this->db->select('*, tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_order.kode_order, tbl_order.tipe_order, tbl_order_detail.qty, tbl_barang_keluar.created_at, tbl_satuan.satuan, tbl_barang_keluar.updated_at');
        $this->db->from('tbl_barang_keluar');
        $this->db->join('tbl_harga', 'tbl_harga.id_harga = tbl_barang_keluar.harga_id');
        $this->db->join('tbl_order_detail', 'tbl_order_detail.id_order_detail = tbl_barang_keluar.order_detail_id', 'left');
        $this->db->join('tbl_order', 'tbl_order.id_order = tbl_order_detail.order_id', 'left');
        $this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_harga.barang_id');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id');
        $this->db->join('tbl_toko', 'tbl_toko.id_toko = tbl_harga.toko_id');
        $this->db->where('tbl_barang_keluar.id_barang_keluar', $id_barang_keluar);
        return $this->db->get()->row_array();
    }

    public function tambahData($data)
    {
        $this->db->insert("tbl_barang_keluar", $data);
        return $this->db->affected_rows();
    }

    public function ubahData($data)
    {
        $this->db->where("id_barang_keluar", $data["id_barang_keluar"]);
        unset($data["id_barang_keluar"]);
        $this->db->update("tbl_barang_keluar", $data);
        return $this->db->affected_rows();
    }

    public function hapusData($id_barang_keluar)
    {
        $this->db->where("id_barang_keluar", $id_barang_keluar);
        $this->db->delete("tbl_barang_keluar");
        return $this->db->affected_rows();
    }

    public function queryAmbilSemuaBarangKeluar()
    {

        $cari = ["tbl_barang.nama_barang", "tbl_toko.nama_toko", "tbl_barang_keluar.jenis_keluar", "tbl_barang_keluar.jml_keluar", "tbl_barang_keluar.is_rollback", "tbl_barang_keluar.created_at", "tbl_barang_keluar.updated_at"];
        $order = [null, "tbl_barang.nama_barang", "tbl_toko.nama_toko", "tbl_barang_keluar.jenis_keluar", "tbl_barang_keluar.jml_keluar", "tbl_barang_keluar.is_rollback"];
        // $order_column   = [null, 'tb_daftar.nocust', 'tb_daftar.nama', 'tb_daftar.hp', null, null, null, null, null];
        // $order          = ['tb_daftar.nocust' => 'desc'];

        $this->db->select("tbl_barang_keluar.id_barang_keluar, tbl_barang.nama_barang, tbl_toko.nama_toko, tbl_barang_keluar.jenis_keluar, tbl_barang_keluar.jml_keluar, tbl_barang_keluar.is_rollback, tbl_barang_keluar.created_at");
        $this->db->from("tbl_barang_keluar");
        $this->db->join("tbl_harga", "tbl_harga.id_harga = tbl_barang_keluar.harga_id");
        $this->db->join("tbl_order_detail", "tbl_order_detail.id_order_detail = tbl_barang_keluar.order_detail_id", "left");
        $this->db->join("tbl_order", "tbl_order.id_order = tbl_order_detail.order_id", "left");
        $this->db->join("tbl_barang", "tbl_barang.id_barang = tbl_harga.barang_id");
        $this->db->join("tbl_toko", "tbl_toko.id_toko = tbl_harga.toko_id");
        $this->db->where("tbl_barang.is_active", 1);
        $this->db->where("tbl_toko.is_active", 1);
        $this->db->where("tbl_harga.is_active", 1);

        $tokoId = $this->session->userdata('toko_id');

        if ($tokoId) {

            $this->db->where("tbl_barang_keluar.toko_id", $tokoId);
        } else {
            $toko_id = $this->input->post('toko_id');
            $this->db->where('tbl_barang_keluar.toko_id', $toko_id);
        }

        $search = $this->input->post("search");

        if ($search && isset($this->input->post("search")['value'])) {

            for ($start = 0; $start < count($cari); $start++) {

                if ($start == 0) {

                    $this->db->group_start();
                    $this->db->like($cari[$start], $this->input->post("search")['value']);
                } else {

                    $this->db->or_like($cari[$start], $this->input->post("search")['value']);
                }

                if ($start == (count($cari) - 1)) {

                    $this->db->group_end();
                }
            }
        }

        if ($this->input->post("order")) {

            if ($this->input->post('order')[0]['column'] > 0) {
                $this->db->order_by($order[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
            }
        } else {

            $this->db->order_by("tbl_barang_keluar.created_at", "ASC");
            $this->db->order_by("tbl_barang_keluar.is_rollback", "ASC");
        }
    }

    public function ajaxAmbilSemuaBarangKeluar()
    {

        $this->queryAmbilSemuaBarangKeluar();
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        return $this->db->get()->result_array();
    }

    public function ajaxAmbilHitungSemuaBarangKeluar()
    {

        $this->queryAmbilSemuaBarangKeluar();
        return $this->db->count_all_results();
    }

    public function ajaxAmbilFilterSemuaBarangKeluar()
    {

        $this->queryAmbilSemuaBarangKeluar();
        return $this->db->get()->num_rows();
    }
}
