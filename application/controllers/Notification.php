<?php

/**
 * @property input $input
 * @property db $db
 * @property secure $secure
 */
class Notification extends CI_Controller
{
    public function index()
    {
        $getData = $this->input->get('key');

        if ($getData) {
            $dataDecrypt = decrypt_string($getData, KEY_NOTIFICATION);
            $this->doPusherDashboard($dataDecrypt);
        }
    }

    private function doPusherDashboard($data)
    {
        $getOrder = $this->db->get_where('tbl_order', ['id_order' => $data])->row_array();
        $getOrderNumRows = $this->db->get_where('tbl_order', ['id_order' => $data])->num_rows();


        if ($getOrder) {
            $dataResult = [
                'transaction_code' => $getOrder['kode_order'],
                'url' => base_url() . 'kasir/sales_order/detail/' . $this->secure->encrypt_url($getOrder['kode_order']),
                'total_row' => $getOrderNumRows
            ];
            pusherMarketPlace($dataResult);
        }
    }
}
