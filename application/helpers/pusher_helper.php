<?php

function pusher($arr = null)
{
    require_once FCPATH . "vendor/autoload.php";
    $a = &get_instance();

    $resultTest = "Hai Gais";
    $json = json_decode($resultTest, true);
    $options = array(
        'cluster' => $_ENV['PUSHER_CLUSTER'], // cluster
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        $_ENV['PUSHER_KEY'], // key
        $_ENV['PUSHER_SECRET'], // secret
        $_ENV['PUSHER_APP_ID'], // app_id
        $options
    );
    $data['data'] = $arr;
    $data['message'] = 'success';
    $pusher->trigger('my-channel', 'my-event', $data);
}

function pusherMessage($channel, $event, $message)
{
    require_once FCPATH . "vendor/autoload.php";
    $a = &get_instance();

    $resultTest = "Hai Gais";
    $json = json_decode($resultTest, true);
    $options = array(
        'cluster' => $_ENV['PUSHER_CLUSTER'], // cluster
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        $_ENV['PUSHER_KEY'], // key
        $_ENV['PUSHER_SECRET'], // secret
        $_ENV['PUSHER_APP_ID'], // app_id
        $options
    );
    $data['message'] = $message;
    $data['status'] = 0;
    $pusher->trigger($channel, $event, $data);
}


function pusherTransaction($data)
{
    require_once FCPATH . "vendor/autoload.php";
    $ci = &get_instance();

    $transactionid = $data['transactionid'];

    $resultTest = "Hai Gais";
    $json = json_decode($resultTest, true);
    $options = array(
        'cluster' => $_ENV['PUSHER_CLUSTER'], // cluster
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        $_ENV['PUSHER_KEY'], // key
        $_ENV['PUSHER_SECRET'], // secret
        $_ENV['PUSHER_APP_ID'], // app_id
        $options
    );

    $getTransaction = $ci->db->get_where('tbl_transaksi', ['id_transaksi' => $transactionid])->row_array();
    $getTransactionCode = $getTransaction['kode_transaksi'];
    $data['transaction_code'] = $getTransactionCode;
    $data['total_row'] = $ci->db->get_where('tbl_transaksi', ['id_transaksi' => $transactionid])->num_rows();
    $data['message'] = 'success';
    $data['err_code'] = 0;
    $pusher->trigger('transaksi_channel', 'transaksi_event', $data);
    return;
}


function showTransactionDateNow()
{
    $ci = &get_instance();
    date_default_timezone_set('Asia/Jakarta');
    $date = new DateTime("now");

    $currentDate = $date->format('Y-m-d');

    $ci->db->where('DATE(created_at)', $currentDate);
    $query = $ci->db->get('tbl_transaksi');
    return $query->result_array();
}
function showNumsTransactionDateNow()
{
    $ci = &get_instance();
    date_default_timezone_set('Asia/Jakarta');
    $date = new DateTime("now");

    $currentDate = $date->format('Y-m-d');


    $ci->db->where('DATE(created_at)', date('Y-m-d'));
    $query = $ci->db->get('tbl_transaksi');
    return $query->num_rows();
}



function pusherMarketPlace($data)
{
    require_once FCPATH . "vendor/autoload.php";
    $ci = &get_instance();

    $options = array(
        'cluster' => $_ENV['PUSHER_CLUSTER'], // cluster
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        $_ENV['PUSHER_KEY'], // key
        $_ENV['PUSHER_SECRET'], // secret
        $_ENV['PUSHER_APP_ID'], // app_id
        $options
    );

    $data['transaction_code'] = $data['transaction_code'];
    $data['url'] = $data['url'];
    $data['total_row'] = $data['total_row'];
    $data['message'] = 'success';
    $data['err_code'] = 0;
    $pusher->trigger('transaksi_channel', 'transaksi_event', $data);
    return;
}

function showOrderDateNow()
{
    $ci = &get_instance();
    date_default_timezone_set('Asia/Jakarta');
    $date = new DateTime("now");

    $currentDate = $date->format('Y-m-d');

    $ci->db->where('status', 1);
    $ci->db->where('tipe_order', 'Marketplace');
    $ci->db->where('DATE(created_at)', $currentDate);
    $query = $ci->db->get('tbl_order');
    return $query->result_array();
}

function showNumsOrderDateNow()
{
    $ci = &get_instance();
    date_default_timezone_set('Asia/Jakarta');
    $date = new DateTime("now");
    $currentDate = $date->format('Y-m-d');
    $ci->db->where('status', 1);
    $ci->db->where('tipe_order', 'Marketplace');
    $ci->db->where('DATE(created_at)', $currentDate);
    $query = $ci->db->get('tbl_order');
    return $query->num_rows();
}
