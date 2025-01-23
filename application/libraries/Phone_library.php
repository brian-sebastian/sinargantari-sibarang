<?php

require_once 'vendor/autoload.php';

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;


class Phone_library
{
    // Nomor telepon Indonesia tanpa kode negara
    // $phoneNumber = '081234567890';

    // Fungsi untuk memformat nomor telepon
    function formatPhoneNumberInternational($phoneNumber)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            // Lakukan parsing nomor telepon dengan libphonenumber
            $phoneNumberObject = $phoneUtil->parse($phoneNumber, 'ID');

            // Format nomor telepon dengan kode negara dan pemisah
            $formattedPhoneNumber = $phoneUtil->format($phoneNumberObject, PhoneNumberFormat::INTERNATIONAL);

            return $formattedPhoneNumber;
        } catch (NumberParseException $e) {
            // Handle exception jika parsing gagal
            return 0;
        }
    }

    function formatPhoneE164($phoneNumber)
    {

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            // Lakukan parsing nomor telepon dengan libphonenumber
            $phoneNumberObject = $phoneUtil->parse($phoneNumber, 'ID');

            // Format nomor telepon dengan kode negara dan pemisah
            $formattedPhoneNumber = $phoneUtil->format($phoneNumberObject, PhoneNumberFormat::E164);

            return $formattedPhoneNumber;
        } catch (NumberParseException $e) {
            // Handle exception jika parsing gagal
            return 0;
        }
    }
}
