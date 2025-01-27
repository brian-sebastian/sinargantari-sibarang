<?php

class Barang_model extends CI_Model
{
    public function getAllBarang()
    {
        $this->db->select('*,tbl_satuan.id_satuan, tbl_satuan.satuan, tbl_satuan.is_active, tbl_kategori.id_kategori, tbl_kategori.kode_kategori, tbl_kategori.nama_kategori, tbl_kategori.slug_kategori, tbl_kategori.is_active');
        $this->db->from('tbl_barang');
        $this->db->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id');
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->order_by('tbl_barang.id_barang', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllBarangAjax()
    {

        $cari           = ["tbl_barang.nama_barang", "tbl_barang.barcode_barang"];

        $this->db->select('*,tbl_satuan.id_satuan, tbl_satuan.satuan, tbl_satuan.is_active, tbl_kategori.id_kategori, tbl_kategori.kode_kategori, tbl_kategori.nama_kategori, tbl_kategori.slug_kategori, tbl_kategori.is_active');
        $this->db->from('tbl_barang');
        $this->db->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id');
        $this->db->where('tbl_barang.is_active', 1);

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

        $this->db->order_by('tbl_barang.id_barang', 'DESC');
    }

    public function ambilSemuaBarang()
    {

        $this->getAllBarangAjax();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        return $this->db->get()->result_array();
    }

    public function ambilHitungBarang()
    {

        $this->getAllBarangAjax();
        return $this->db->count_all_results();
    }

    public function ambilFilterBarang()
    {
        $this->getAllBarangAjax();
        return $this->db->get()->num_rows();
    }

    public function ambilSemuaBarangTemp()
    {
        $this->db->select("kategori_id, satuan_id, nama_barang, harga_pokok, barcode_barang");
        $query = $this->db->get("tbl_barang_temp");
        return $query;
    }

    public function update_barcode($code, $kode_barang)
    {
        $this->db->set('barcode_barang', $code);
        $this->db->where('kode_barang', $kode_barang);
        $this->db->update('tbl_barang');
        return $this->db->affected_rows();
    }

    public function getAllBarangNotInHarga($toko_id)
    {
        /**
         * !use this
         */
        $this->db->select('barang_id');
        $this->db->from('tbl_harga');
        $this->db->where('toko_id', $toko_id);
        $where_clause = $this->db->get_compiled_select();

        #Create main query
        $this->db->select(
            '*'
        );
        $this->db->from('tbl_barang');
        $this->db->where("`id_barang` NOT IN ($where_clause)", NULL, FALSE);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBarangHargaToko($toko_id)
    {
        $this->db->select(
            'tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_harga.is_active, tbl_kategori.nama_kategori, tbl_satuan.satuan,
            '
        );
        $this->db->from('tbl_harga');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.toko_id', $toko_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBarangCacat($toko_id)
    {

        $this->db->select(
            'tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_barang_cacat.id_barang_cacat, tbl_barang_cacat.barang_id, tbl_barang_cacat.toko_id, tbl_barang_cacat.stok_cacat, tbl_barang_cacat.harga_jual_cacat, tbl_barang_cacat.is_active,
            '
        );
        $this->db->from('tbl_barang_cacat');
        $this->db->join('tbl_toko', 'tbl_barang_cacat.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_barang_cacat.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_barang_cacat.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getHargaBarang()
    {
        $this->db->select(
            'tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_harga.is_active,
            '
        );
        $this->db->from('tbl_harga');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getHargaBarangToko($id_toko)
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
        $this->db->where('tbl_harga.toko_id', $id_toko);
        $this->db->where('tbl_barang.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getHargaBarangTokoAll()
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
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->order_by('tbl_harga.toko_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getHargaBarangFindUsername()
    {
        $username = $this->session->userdata('username');

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
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.is_active', 1);
        $this->db->where('tbl_harga.user_input', $username);
        $this->db->order_by('tbl_harga.toko_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectDataBarangToko($data_toko)
    {
        $toko_id = $data_toko['toko_id'];
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
        $this->db->where('tbl_harga.toko_id', $toko_id);
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.is_active', 1);
        $query = $this->db->get()->result_array();

        if ($query) {
            echo "<option value=''>-Pilih-</option>";
            foreach ($query as $value) {
                echo "<option value ='" . $value["id_harga"] . "'>";
                echo $value['nama_barang'];

                echo "</option>";
            }
        } else {
            echo "<option value=''>-Tidak ada barang toko-</option>";
        }
    }

    public function findTotalRow($search)
    {

        $this->db->select('*');
        $this->db->from('tbl_barang');
        $this->db->like('kode_barang', $search);
        $this->db->like('nama_barang', $search);
        $this->db->like('barcode_barang', $search);
        $this->db->escape_like_str($search);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getBarangLimit($limit, $start, $search = null)
    {
        if ($search) {
            $start = ($start) ? $start : 0;
            $this->db->select('*');
            $this->db->from('tbl_barang');
            $this->db->like('kode_barang', $search);
            $this->db->like('nama_barang', $search);
            $this->db->like('barcode_barang', $search);
            $this->db->escape_like_str($search);
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $start = ($start) ? $start : 0;
            $this->db->select('*');
            $this->db->from('tbl_barang');
            $this->db->limit($limit, $start);
            $query = $this->db->get();
            return $query->result_array();
        }
    }


    public function getFindById($id)
    {
        $query = $this->db->get_where('tbl_barang', ['id_barang' => $id]);

        if ($query->num_rows() > 0) {

            return $query->row_array();
        }

        return 0;
    }

    public function ambilDataBarangIdJoin($id)
    {
        $this->db->select('tbl_barang.id_barang, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active, tbl_satuan.satuan, tbl_kategori.nama_kategori');
        $this->db->from('tbl_barang');
        $this->db->join('tbl_kategori', 'tbl_kategori.id_kategori = tbl_barang.kategori_id');
        $this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_barang.satuan_id');
        $this->db->where('tbl_barang.id_barang', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getKodeBarang()
    {
        return $this->db->query("SELECT MAX(RIGHT(tbl_barang.kode_barang,6)) AS kode_barang FROM tbl_barang");
    }

    public function cekBarangPadaKategori($id_kategori)
    {
        $query = $this->db->get_where("tbl_barang", ["kategori_id"   => $id_kategori, "is_active"    => 1]);
        return $query->num_rows();
    }

    public function cekBarangPadaSatuan($id_satuan)
    {
        $query = $this->db->get_where("tbl_barang", ["satuan_id"   => $id_satuan, "is_active"    => 1]);
        return $query->num_rows();
    }

    public function tambah_data($data)
    {
        // return $this->db->affected_rows();
        $this->db->trans_start();

        $this->db->insert("tbl_barang", $data);
        $last_id_barang = $this->db->insert_id();

        $this->db->set('barang_id', $last_id_barang);
        $this->db->set('kategori_id', $data['kategori_id']);
        $this->db->set('satuan_id', $data['satuan_id']);
        $this->db->set('harga_pokok', $data['harga_pokok']);
        $this->db->set('berat_barang', $data['berat_barang']);
        $this->db->set('tanggal_perubahan', $data['created_at']);
        $this->db->set('user_input', $data['user_input']);
        $this->db->set('created_at', date('Y-m-d H:i:s'));
        $this->db->insert('tbl_barang_harga_history');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function edit_data($id, $data)
    {

        $this->db->trans_start();

        $getCurrentBarang = $this->db->get_where('tbl_barang', ['id_barang' => $id])->row_array();
        $barang_id = $getCurrentBarang['id_barang'];

        $is_changed_code = 0;
        $kategori_id = $getCurrentBarang['kategori_id'];
        $satuan_id = $getCurrentBarang['satuan_id'];
        $harga_pokok = $getCurrentBarang['harga_pokok'];
        $berat_barang = $getCurrentBarang['berat_barang'];

        if ($getCurrentBarang['kategori_id'] != $data['kategori_id']) {
            $kategori_id = $data['kategori_id'];
            $is_changed_code++;
        }

        if ($getCurrentBarang['satuan_id'] != $data['satuan_id']) {
            $satuan_id = $data['satuan_id'];
            $is_changed_code++;
        }

        if ($getCurrentBarang['harga_pokok'] != $data['harga_pokok']) {
            $harga_pokok = $data['harga_pokok'];
            $is_changed_code++;
        }

        if ($getCurrentBarang['berat_barang'] != $data['berat_barang']) {
            $berat_barang = $data['berat_barang'];
            $is_changed_code++;
        }

        if ($is_changed_code > 0) {
            $dataHistory = [
                'barang_id' => $barang_id,
                'kategori_id' => $kategori_id,
                'satuan_id' => $satuan_id,
                'harga_pokok' => $harga_pokok,
                'berat_barang' => $berat_barang,
                'tanggal_perubahan' => $data['updated_at'],
                'user_input' => $data['user_update'],
                'created_at' => $data['updated_at'],
            ];
            $this->db->insert('tbl_barang_harga_history', $dataHistory);
        }

        $this->db->where('id_barang', $id);
        $this->db->update('tbl_barang', $data);

        $this->db->trans_complete();
        // return $this->db->affected_rows();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function hapus_data($id)
    {
        $this->db->where('id_barang', $id);
        $this->db->delete('tbl_barang');
        return $this->db->affected_rows();
    }

    public function getBarangHargaTokoRow($id_harga)
    {

        $this->db->select("tbl_harga.id_harga, tbl_harga.barang_id, ,tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_barang.harga_pokok, tbl_barang.slug_barang, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_kategori.nama_kategori, tbl_satuan.satuan, tbl_harga.stok_toko");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "left");
        $this->db->join("tbl_toko", "tbl_harga.toko_id = tbl_toko.id_toko", "left");
        $this->db->join("tbl_kategori", "tbl_barang.kategori_id = tbl_kategori.id_kategori", "left");
        $this->db->join("tbl_satuan", "tbl_barang.satuan_id = tbl_satuan.id_satuan", "left");
        $this->db->where("tbl_harga.id_harga", $id_harga);
        $this->db->where("tbl_barang.is_active", 1);
        $this->db->where("tbl_harga.is_active", 1);
        $this->db->where("tbl_toko.jenis", "TOKO");

        return $this->db->get()->row_array();
    }

    public function editBarcode($id, $data)
    {
        $this->db->set('barcode_barang', $data['barcode_barang']);
        $this->db->set('user_update', $data['user_update']);
        $this->db->set('updated_at', $data['updated_at']);
        $this->db->where('id_barang', $id);
        $this->db->update('tbl_barang');
        return $this->db->affected_rows();
    }

    public function getBarangHargaMarketplace($id_harga)
    {

        $this->db->select("tbl_harga.id_harga, tbl_harga.harga_jual, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_kategori.nama_kategori, tbl_satuan.satuan, tbl_harga.stok_toko");
        $this->db->from("tbl_harga");
        $this->db->join("tbl_barang", "tbl_harga.barang_id = tbl_barang.id_barang", "inner");
        $this->db->join("tbl_toko", "tbl_harga.toko_id = tbl_toko.id_toko", "inner");
        $this->db->join("tbl_kategori", "tbl_barang.kategori_id = tbl_kategori.id_kategori", "inner");
        $this->db->join("tbl_satuan", "tbl_barang.satuan_id = tbl_satuan.id_satuan", "inner");
        $this->db->where("tbl_harga.id_harga", $id_harga);
        $this->db->where("tbl_barang.is_active", 1);
        $this->db->where("tbl_harga.is_active", 1);
        $this->db->where("tbl_toko.jenis", "MARKETPLACE");
        return $this->db->get()->row_array();
    }

    public function cekStokBarang($id_harga, $qty = 0)
    {
        $this->db->where("id_harga", $id_harga);
        $this->db->where("stok_toko >= ", $qty);
        $this->db->where("is_active", 1);
        $query = $this->db->get("tbl_harga");

        if ($query->num_rows() > 0) {

            return true;
        }

        return false;
    }

    public function ajaxGetAllBarangToko($toko_id, $kategori_id = "")
    {
        $cari           = ["tbl_barang.nama_barang", "tbl_toko.nama_toko", "tbl_kategori.nama_kategori", "tbl_satuan.satuan"];

        $this->db->select(
            'tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
            tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
            tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_harga.is_active, tbl_kategori.nama_kategori, tbl_satuan.satuan, tbl_barang.barcode_barang, (select SUM(stok_toko) from tbl_harga as tbl_harga_subquery inner join tbl_toko as tbl_toko_subquery on tbl_harga_subquery.toko_id = tbl_toko_subquery.id_toko where tbl_harga_subquery.barang_id = tbl_harga.barang_id and tbl_harga_subquery.is_active = 0 and tbl_toko_subquery.jenis = "GUDANG") as stok_gudang, 
            '
        );
        $this->db->from('tbl_harga');
        $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        $this->db->where('tbl_barang.is_active', 1);
        $this->db->where('tbl_harga.toko_id', $toko_id);

        if ($kategori_id) {
            $this->db->where('tbl_barang.kategori_id', $kategori_id);
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

        

        // $cari           = ["tbl_barang.nama_barang", "tbl_toko.nama_toko", "tbl_kategori.nama_kategori", "tbl_satuan.satuan"];

        // $this->db->select(
        //     'tbl_barang.id_barang, tbl_barang.kategori_id, tbl_barang.satuan_id, tbl_barang.kode_barang, tbl_barang.nama_barang, tbl_barang.slug_barang, tbl_barang.barcode_barang, tbl_barang.harga_pokok, tbl_barang.berat_barang, tbl_barang.deskripsi, tbl_barang.gambar, tbl_barang.is_active,
        //     tbl_toko.id_toko, tbl_toko.nama_toko, tbl_toko.alamat_toko, tbl_toko.notelp_toko, tbl_toko.jenis,
        //     tbl_harga.id_harga, tbl_harga.barang_id, tbl_harga.toko_id, tbl_harga.stok_toko, tbl_harga.harga_jual, tbl_harga.is_active, tbl_kategori.nama_kategori, tbl_satuan.satuan, tbl_barang.barcode_barang, 
        //     (SELECT COALESCE(SUM(tbl_barang_masuk.jml_masuk), 0) FROM tbl_barang_masuk WHERE tbl_barang_masuk.harga_id = tbl_harga.id_harga) as jml_masuk, (SELECT COALESCE(SUM(tbl_barang_keluar.jml_keluar), 0) FROM tbl_barang_keluar WHERE tbl_barang_keluar.harga_id = tbl_harga.id_harga) as jml_keluar
        //     '
        // );
        // $this->db->from('tbl_harga');
        // $this->db->join('tbl_toko', 'tbl_harga.toko_id = tbl_toko.id_toko');
        // $this->db->join('tbl_barang', 'tbl_harga.barang_id = tbl_barang.id_barang');
        // $this->db->join('tbl_kategori', 'tbl_barang.kategori_id = tbl_kategori.id_kategori');
        // $this->db->join('tbl_satuan', 'tbl_barang.satuan_id = tbl_satuan.id_satuan');
        // $this->db->where('tbl_barang.is_active', 1);
        // $this->db->where('tbl_harga.toko_id', $toko_id);

        // if ($kategori_id) {
        //     $this->db->where('tbl_barang.kategori_id', $kategori_id);
        // }

        // $search = $this->input->post("search");

        // if ($search && isset($this->input->post("search")['value'])) {

        //     for ($start = 0; $start < count($cari); $start++) {

        //         if ($start == 0) {

        //             $this->db->group_start();
        //             $this->db->like($cari[$start], $this->input->post("search")['value']);
        //         } else {

        //             $this->db->or_like($cari[$start], $this->input->post("search")['value']);
        //         }

        //         if ($start == (count($cari) - 1)) {

        //             $this->db->group_end();
        //         }
        //     }
        // }
    }

    public function ambilSemuaBarangToko($toko_id, $kategori_id = "")
    {
        $this->ajaxGetAllBarangToko($toko_id, $kategori_id);
      
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }

        // $this->db->get()->result_array();

        // $contoh = $this->db->last_query();

        // var_dump($contoh);
        // die();
        
        return $this->db->get()->result_array();
    }

    public function ambilHitungBarangToko($toko_id, $kategori_id = "")
    {
        $this->ajaxGetAllBarangToko($toko_id, $kategori_id);
        return $this->db->count_all_results();
    }

    public function ambilFilterBarangToko($toko_id, $kategori_id = "")
    {

        $this->ajaxGetAllBarangToko($toko_id, $kategori_id);
        return $this->db->get()->num_rows();
    }

    public function ambilSemuaBarangTokoExcel($toko_id = "", $kategori_id = "")
    {

        $this->ajaxGetAllBarangToko($toko_id, $kategori_id);
        return $this->db->get()->result_array();
    }

    public function getSearchBarangAutoComplete($term)
    {
        $this->db->like('barcode_barang', $term, 'both');
        $this->db->order_by('barcode_barang', 'ASC');
        $query = $this->db->get('tbl_barang');
        return $query->result();
    }

    public function getBarcodeByListId($data)
    {
        $this->db->select("barcode_barang, nama_barang");
        $this->db->where_in("id_barang", $data);
        $query = $this->db->get("tbl_barang")->result_array();
        return $query;
    }
}
