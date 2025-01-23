<?php

if (!function_exists('convertDate')) {

    function convertDate($tgl)
    {
        if (strlen($tgl) > 10) {

            $tgl = substr($tgl, 0, 10);
        }

        $explode = explode("-", $tgl);

        $bulan = [1 => "januari", "februari", "maret", "april", "mei", "juni", "juli", "agustus", "september", "oktober", "november", "desember"];

        return $explode[2] . " " . $bulan[intval($explode[1])] . " " . $explode[0];
    }
}
