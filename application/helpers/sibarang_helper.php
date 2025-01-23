<?php

/**
 * @property session $session
 * @property db $db
 */
function cek_login()
{
    $ci3 = get_instance();

    if (!$ci3->session->userdata('username')) {
        if ($ci3->uri->segment(1) != "dashboard") {
            $ci3->session->set_flashdata('message_error', 'Kamu Harus Login Dulu');
        }
        redirect('auth');
    } else {

        $role_id = $ci3->session->userdata('role_id');
        $menu = $ci3->uri->segment(1);

        if ($menu !== "profile") {

            if ($menu) {

                $queryMenu = $ci3->db->get_where('tbl_submenu', ['uri' => $menu])->row_array();

                if ($queryMenu) {

                    $menu_id = $queryMenu['menu_id'];
                    $userAccess = $ci3->db->get_where('tbl_menu_access', [
                        'role_id' => $role_id,
                        'menu_id' => $menu_id
                    ]);

                    if ($userAccess->num_rows() < 1) {
                        redirect('auth/blocked');
                    }
                } else {

                    redirect('auth/blocked');
                }
            }
        }
    }
}

function check_access($id_role, $id_menu)
{
    $ci3 = get_instance();

    $ci3->db->where('role_id', $id_role);
    $ci3->db->where('menu_id', $id_menu);

    $result = $ci3->db->get('tbl_privilege_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function check_submenu($id_role, $id_submenu)
{
    $ci3 = get_instance();

    $querySubmenu = "SELECT * FROM tbl_privilege_submenu
                                        JOIN tbl_submenu ON tbl_privilege_submenu.submenu_id = tbl_submenu.id_submenu
                                        JOIN tbl_privilege_menu ON tbl_privilege_submenu.privilege_id = tbl_privilege_menu.id_privilege
                                        WHERE tbl_privilege_menu.role_id = $id_role
                                        AND tbl_privilege_submenu.submenu_id = $id_submenu";

    $result = $ci3->db->query($querySubmenu);

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

function rupiah($angka)
{
    $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
}
function toko_luar($id_barang_masuk)
{
    $ci3 = get_instance();

    $query = "SELECT tbl_barang_masuk.tipe FROM tbl_barang_masuk WHERE tbl_barang_masuk.id_barang_masuk = $id_barang_masuk";

    $result = $ci3->db->query($query)->row_array();

    if ($result['tipe'] == 'toko_luar') {
        return "checked='checked'";
    }
}

function gudang($id_barang_masuk)
{
    $ci3 = get_instance();

    $query = "SELECT tbl_barang_masuk.tipe FROM tbl_barang_masuk WHERE tbl_barang_masuk.id_barang_masuk = $id_barang_masuk";

    $result = $ci3->db->query($query)->row_array();

    if ($result['tipe'] == 'gudang') {
        return "checked='checked'";
    }
}

function tunai($id_transaksi)
{
    $ci3 = get_instance();

    $query = "SELECT tbl_transaksi.tipe_transaksi FROM tbl_transaksi WHERE tbl_transaksi.id_transaksi = $id_transaksi";
    $result = $ci3->db->query($query)->row_array();
    if ($result['tipe_transaksi'] == 'tunai') {
        return "checked='checked'";
    }
}

function transfer($id_transaksi)
{
    $ci3 = get_instance();

    $query = "SELECT tbl_transaksi.tipe_transaksi FROM tbl_transaksi WHERE tbl_transaksi.id_transaksi = $id_transaksi";
    $result = $ci3->db->query($query)->row_array();
    if ($result['tipe_transaksi'] == 'transfer') {
        return "checked='checked'";
    }
}

function request_delete($id, $keterangan, $type_request)
{
    date_default_timezone_set('Asia/Jakarta');
    $ci3 = &get_instance();
    switch ($type_request) {
        case DELETE_REQUEST_BARANG:
            $ci3->db->set('type_request', DELETE_REQUEST_BARANG);
            $ci3->db->set('barang_id', $id);
            $ci3->db->set('keterangan', $keterangan);
            $ci3->db->set('is_deleted', 0);
            $ci3->db->set('requested_shop', $ci3->session->userdata('toko_id'));
            $ci3->db->set('requested_by', $ci3->session->userdata('nama_user'));
            $ci3->db->set('requested_date', date('Y-m-d H:i:s'));
            $ci3->db->insert('tbl_request_delete_barang');
            $result = [
                'err_code' => 0,
                'message' => "Request Hapus Barang Berhasil",
            ];
            return $result;
            break;

        case DELETE_REQUEST_BARANG_TOKO:
            $ci3->db->set('type_request', DELETE_REQUEST_BARANG_TOKO);
            $ci3->db->set('harga_id', $id);
            $ci3->db->set('keterangan', $keterangan);
            $ci3->db->set('is_deleted', 0);
            $ci3->db->set('requested_shop', $ci3->session->userdata('toko_id'));
            $ci3->db->set('requested_by', $ci3->session->userdata('nama_user'));
            $ci3->db->set('requested_date', date('Y-m-d H:i:s'));
            $ci3->db->insert('tbl_request_delete_barang');
            $result = [
                'err_code' => 0,
                'message' => "Request Hapus Barang Toko Berhasil",
            ];
            return $result;
            break;

        default:
    }
}

function re_request_delete($id, $keterangan, $type_request)
{
    date_default_timezone_set('Asia/Jakarta');
    $ci3 = &get_instance();
    switch ($type_request) {
        case DELETE_REQUEST_BARANG:

            $ci3->db->set('keterangan', $keterangan);
            $ci3->db->set('is_deleted', 0);
            $ci3->db->set('requested_date', date('Y-m-d H:i:s'));
            $ci3->db->where('id_request', $id);
            $ci3->db->update('tbl_request_delete_barang');
            $result = [
                'err_code' => 0,
                'message' => "Request Hapus Barang Berhasil",
            ];
            return $result;
            break;

        case DELETE_REQUEST_BARANG_TOKO:
            $ci3->db->set('keterangan', $keterangan);
            $ci3->db->set('is_deleted', 0);
            $ci3->db->set('requested_date', date('Y-m-d H:i:s'));
            $ci3->db->where('id_request', $id);
            $ci3->db->update('tbl_request_delete_barang');
            $result = [
                'err_code' => 0,
                'message' => "Request Hapus Barang Toko Berhasil",
            ];
            return $result;
            break;

        default:
    }
}

function check_request_delete_barang($id)
{
    $is_contain = 0;
    $ci3 = &get_instance();

    $ci3->db->where('type_request', DELETE_REQUEST_BARANG);
    $ci3->db->where('barang_id', $id);
    $ci3->db->group_start();
    $ci3->db->where('is_deleted', 0);
    $ci3->db->or_where('is_deleted', 1);
    $ci3->db->or_where('is_deleted', 2);
    $ci3->db->group_end();
    $getData = $ci3->db->get('tbl_request_delete_barang');

    if ($getData->num_rows() > 0) {
        $is_contain = 1;
        return $is_contain;
    } else {
        $is_contain = 0;
        return $is_contain;
    }
}

function checkBarangRejectedOrAccepted($id)
{
    $err_code = 0;
    $message = '';
    $data = '';
    $ci3 = &get_instance();

    $ci3->db->where('type_request', DELETE_REQUEST_BARANG);
    $ci3->db->where('barang_id', $id);

    $ci3->db->group_start();
    $ci3->db->where('is_deleted', 0);
    $ci3->db->or_where('is_deleted', 1);
    $ci3->db->or_where('is_deleted', 2);
    $ci3->db->group_end();

    $getData = $ci3->db->get('tbl_request_delete_barang');

    $result = $getData->row_array();

    if ($getData->num_rows() > 0) {
        $err_code = 0;
        $message = 'Gotcha';
        $data = $result;
    } else {
        $err_code++;
        $message = "tidak ada";
    }

    $kiwData = [
        'err_code' => $err_code,
        'message' => $message,
        'data' => $data
    ];
    return $kiwData;
}


function check_request_delete_barang_toko($id)
{
    $is_contain = 0;
    $ci3 = &get_instance();

    $ci3->db->where('type_request', DELETE_REQUEST_BARANG_TOKO);
    $ci3->db->where('harga_id', $id);
    $ci3->db->group_start();
    $ci3->db->where('is_deleted', 0);
    $ci3->db->or_where('is_deleted', 1);
    $ci3->db->or_where('is_deleted', 2);
    $ci3->db->group_end();
    $getData = $ci3->db->get('tbl_request_delete_barang');

    if ($getData->num_rows() > 0) {
        $is_contain = 1;
        return $is_contain;
    } else {
        $is_contain = 0;
        return $is_contain;
    }
}

function checkRejectedOrAccepted($id)
{
    $err_code = 0;
    $message = '';
    $data = '';
    $ci3 = &get_instance();

    $ci3->db->where('type_request', DELETE_REQUEST_BARANG_TOKO);
    $ci3->db->where('harga_id', $id);

    $ci3->db->group_start();
    $ci3->db->where('is_deleted', 0);
    $ci3->db->or_where('is_deleted', 1);
    $ci3->db->or_where('is_deleted', 2);
    $ci3->db->group_end();

    $getData = $ci3->db->get('tbl_request_delete_barang');

    $result = $getData->row_array();

    if ($getData->num_rows() > 0) {
        $err_code = 0;
        $message = 'Gotcha';
        $data = $result;
    } else {
        $err_code++;
        $message = "tidak ada";
    }

    $kiwData = [
        'err_code' => $err_code,
        'message' => $message,
        'data' => $data
    ];
    return $kiwData;
}


function checkRelationTable($table, $column, $condition, $data = null)
{
    $err_code = 0;
    $message = "";
    $ci3 = &get_instance();

    $ci3->db->from($table);
    $ci3->db->where($column, $condition);

    $resultCount = $ci3->db->count_all_results();

    if ($resultCount > 0) {
        $err_code++;
        $message = $data ? $data : "This Data Contain Relation With Another Table";
    } else {
        $err_code = 0;
        $message = "This Data Not Found To Another Table";
    }

    $result = [
        'err_code' => $err_code,
        'message' => $message,
    ];
    return $result;
}
function checkRelationTableMultiple($table, $column, $condition, $data = null)
{

    $err_code = 0;
    $message = "";
    $ci3 = &get_instance();

    $ci3->db->from($table);
    $ci3->db->where_in($column, $condition);

    $resultCount = $ci3->db->count_all_results();

    if ($resultCount > 0) {
        $err_code++;
        $message = $data ? $data : "This Data Contain Relation With Another Table";
    } else {
        $err_code = 0;
        $message = "This Data Not Found To Another Table";
    }

    $result = [
        'err_code' => $err_code,
        'message' => $message,
    ];
    return $result;
}

if (!function_exists('getStokByIdBarangDanTokoId')) {

    function getStokByIdBarangDanTokoId($id_barang, $toko_id)
    {
        $CI3 = &get_instance();
        $CI3->db->select("stok_toko");
        $CI3->db->where("toko_id", $toko_id);
        $CI3->db->where("barang_id", $id_barang);
        $query = $CI3->db->get("tbl_harga");
        return $query->row_array()["stok_toko"];
    }
}

if (!function_exists('getStokByIdHarga')) {

    function getStokByIdHarga($id_harga)
    {
        $CI3 = &get_instance();
        $CI3->db->select("stok_toko");
        $CI3->db->where("id_harga", $id_harga);
        $query = $CI3->db->get("tbl_harga");
        return $query->row_array()["stok_toko"];
    }
}

if (!function_exists('getIdHargaByIdBarangDanTokoId')) {

    function getIdHargaByIdBarangDanTokoId($id_barang, $toko_id)
    {
        $CI3 = &get_instance();
        $CI3->db->select("id_harga");
        $CI3->db->where("toko_id", $toko_id);
        $CI3->db->where("barang_id", $id_barang);
        $query = $CI3->db->get("tbl_harga");
        return $query->row_array()["id_harga"];
    }
}
