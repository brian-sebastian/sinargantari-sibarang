<?php

function convertHtmlToText($data)
{
    $html = new \Html2Text\Html2Text($data);
    // echo $html->getText();
    return $html->getText();
}
