<?php

if (!function_exists('greeting')) {

    function greeting()
    {
        $time = date("H");

        if (intval($time) >= 18) {

            return "malam";
        } else if (intval($time) > 15) {

            return "sore";
        } else if (intval($time) > 12) {

            return "siang";
        } else {

            return "pagi";
        }
    }
}
