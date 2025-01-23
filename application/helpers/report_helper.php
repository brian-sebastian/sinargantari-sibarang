<?php

if (!function_exists("total_keuntungan")) {


    function total_keuntungan($total_harga_jual, $total_diskon, $total_harga_pokok)
    {
        $total_keuntungan = ($total_harga_jual - $total_diskon) - $total_harga_pokok;
        return $total_keuntungan;
    }
}
