<?php

class Telegrambot_library
{
    protected static $apiKeyTelegram;
    protected static $chatId;

    public function __construct()
    {
        Telegrambot_library::$apiKeyTelegram = $_ENV['TELEGRAM_SECRET'];
        Telegrambot_library::$chatId = $_ENV['TELEGRAM_CHAT_ID'];
    }

    public function getMe()
    {
        // https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getMe
        $token = self::$apiKeyTelegram;

        $url = "https://api.telegram.org/bot$token/getMe";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        // var_dump($resp);
        echo ($resp);
    }
    public function getUpdates()
    {
        // https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getMe
        $token = self::$apiKeyTelegram;

        $url = "https://api.telegram.org/bot$token/getUpdates";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        // var_dump($resp);
        echo ($resp);
    }

    public function sendMessage($paramsMessage)
    {
        $errCode = 0;
        $message = '';
        if (!$paramsMessage) {
            $message =  "Wrong Parameters";
            $result = [
                'err_code' => $errCode++,
                'message' => $message
            ];
            return $result;
        } else {
            $botToken = self::$apiKeyTelegram; // Replace with your actual bot token
            $chatID = self::$chatId;     // Replace with your actual chat ID
            $messageSend = $paramsMessage; // Replace with the message you want to send

            $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";
            $data = [
                'chat_id' => $chatID,
                'text' => $messageSend,
            ];

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                // echo 'Error: ' . curl_error($ch);
                $message =  'Error: ' . curl_error($ch);
                $result = [
                    'err_code' => $errCode++,
                    'message' => $message
                ];
                return $result;
            } else {
                $errCode =  0;
                $message =  'Message Sent Successfully';
                $result = [
                    'err_code' => $errCode,
                    'message' => $message
                ];
                return $result;
                // echo 'Message sent successfully!';
            }

            curl_close($ch);
        }
    }
}
