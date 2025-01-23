<?php

class Mysecret
{
    public static function setApiKeyWhatsapp()
    {
        $ApiKeyWhatsapp = $_ENV['WATZAP_APIKEY'];
        return $ApiKeyWhatsapp;
    }

    public static function setNumberKeyWhatsapp()
    {
        $NumApiKeyWhatsapp = $_ENV['WATZAP_NUMBERKEY'];
        return $NumApiKeyWhatsapp;
    }
}
