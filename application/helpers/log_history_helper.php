<?php

function createLogHistoryJSON($namaFileJSON, $dataLog = null)
{
    // Nama file JSON
    // $namaFileJSON = 'log.json';

    // Persiapkan data log (contoh data)
    // $dataLog = array(
    //     'timestamp' => time(),
    //     'pesan' => 'Ini adalah pesan log.'
    // );
    // !! $dataLog => berisikan data Array

    $directoryLog = 'log/';

    // Baca data JSON jika file sudah ada
    if (file_exists($directoryLog . $namaFileJSON)) {
        $fileContent = file_get_contents($directoryLog . $namaFileJSON);
        $existingData = json_decode($fileContent, true);

        // Tambahkan data log ke dalam struktur data yang sudah ada
        $existingData[] = $dataLog;
    } else {
        // Jika file belum ada, buat array baru dengan data log pertama
        $existingData = array($dataLog);
    }

    // Tulis kembali ke file JSON
    file_put_contents($directoryLog . $namaFileJSON, json_encode($existingData, JSON_PRETTY_PRINT));
}
