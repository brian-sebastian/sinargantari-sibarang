<?php

class Request_hapus_barang_model extends CI_Model
{

    public function getAllRequest()
    {
        $this->db->select('req.id_request, req.barang_id, req.harga_id, req.type_request, req.keterangan, req.is_deleted, req.requested_shop, req.requested_by, req.requested_date, b.id_barang as barang_id, b.kode_barang, b.nama_barang, h.id_harga, h.barang_id as harga_barang_id, b_harga.kode_barang as inhrg_kode_barang, b_harga.nama_barang as inhrg_nama_barang,  t.id_toko, t.nama_toko');
        $this->db->from('tbl_request_delete_barang as req');

        $this->db->join('tbl_toko as t', 'req.requested_shop = t.id_toko', 'left');

        // Conditional left join based on barang_id and harga_id
        $this->db->group_start();
        $this->db->where('req.barang_id IS NOT NULL', null, false);
        $this->db->join('tbl_barang as b', 'req.barang_id = b.id_barang', 'left');
        $this->db->where('req.is_deleted', 0);
        $this->db->group_end();

        $this->db->or_group_start();
        $this->db->where('req.barang_id IS NULL', null, false);
        $this->db->join('tbl_harga as h', 'req.harga_id = h.id_harga', 'left');
        $this->db->join('tbl_barang as b_harga', 'h.barang_id = b_harga.id_barang', 'left');
        $this->db->where('req.is_deleted', 0);
        $this->db->group_end();
        $query = $this->db->get();
        return $query->result_array();

        // ini_set('xdebug.var_display_max_depth', 12323230);
        // ini_set('xdebug.var_display_max_children', 252323236);
        // ini_set('xdebug.var_display_max_data', 23232323);
        // dump($this->db->last_query());
        // die;
    }



    public function acceptDelete($id)
    {
        $err_code = 0;
        $message = "";
        $getDataRequest = $this->db->get_where('tbl_request_delete_barang', ['id_request' => $id])->row_array();
        $checkRelation = checkRelationTable('tbl_harga', 'barang_id', $getDataRequest['barang_id']);

        if ($checkRelation['err_code'] == 0) {
            $this->db->trans_begin();
            $this->db->set('is_deleted', 1);
            $this->db->where('id_request', $id);
            $this->db->update('tbl_request_delete_barang');

            $this->db->where('id_barang', $getDataRequest['barang_id']);
            $this->db->delete('tbl_barang');
            $this->db->trans_complete();


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $err_code++;
                $message = "Gagal Dihapus";
            } else {
                $this->db->trans_commit();
                $err_code = 0;
                $message = "Berhasil Dihapus";
            }
        } else {
            $err_code++;
            $message = $checkRelation['message'];
        }

        $result = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        return $result;
    }

    public function rejectDelete($id)
    {
        $err_code = 0;
        $message = "";

        $this->db->set('is_deleted', 2);
        $this->db->where('id_request', $id);
        $this->db->update('tbl_request_delete_barang');
        $affectedRow = $this->db->affected_rows();

        if ($affectedRow < 0) {
            $err_code++;
            $message = "Nothing change";
        }


        $result = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        return $result;
    }
}
