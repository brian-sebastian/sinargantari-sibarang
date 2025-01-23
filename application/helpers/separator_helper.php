<?php

function removeThousandSeparator($number)
{
    $removeNumberSeparator = str_replace(',', '', $number);
    return $removeNumberSeparator;
}

function removeStripe($string)
{
    $removeNumberSeparator = str_replace('-', '', $string);
    return $removeNumberSeparator;
}
