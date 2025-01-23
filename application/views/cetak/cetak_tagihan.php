<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=s, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap'); */

        * {
            padding: 0;
            margin: 0;
            font-family: sans-serif;
            font-size: medium;
        }

        .bg-1 {
            background-color: #bdb6b5;
        }

        .bg-2 {
            background-color: greenyellow;
        }

        .font-700 {
            font-weight: 700;
        }

        .font-italic {

            font-style: italic;

        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .color-success {
            color: greenyellow;
        }

        .color-danger {
            color: red;
        }

        .color-blue {
            color: #2d4387;
        }

        .paddingY {
            padding-top: 7px;
            padding-bottom: 7px;
        }

        .paddingX {

            padding: 0px 10px 0px 10px;

        }

        .w-100 {

            width: 100%;

        }

        .tbl-border {
            border-collapse: collapse;
            border: 1px solid;
        }

        .tbl-border tr {
            border: 1px solid;
        }

        .tbl-border td {
            border: 1px solid;
        }

        .tbl-border th {
            border: 1px solid;
        }

        .header-logo {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .header-content {

            width: 100%;
            margin-bottom: 40px;

        }

        .header-content table {

            width: 100%;
        }

        .w-50 {
            width: 50%;
        }

        .header-content table tr td {

            width: 50%;
        }
    </style>
</head>

<body>
    <div class="header-logo">
        <table class="w-100">
            <tr>
                <td class="paddingX">
                    <h1>INVOICE</h1>
                </td>
            </tr>
            <tr>
                <td class="paddingX">
                    <hr>
                </td>
            </tr>
        </table>
    </div>
    <div class="header-content">
        <table class="w-100">
            <tr>
                <td class="paddingX">
                    <p class="font-700 paddingY">Kepada Yth : </p>
                    <p>Bapak/ibu <?= $data[0]["nama_cust"] ?></p>
                </td>
                <td class="align-right paddingX">
                    <p class="font-700 paddingY">Tanggal : </p>
                    <p><?= convertDate($data[0]["created_at"]) ?></p>
                </td>
            </tr>
            <tr>
                <td class="paddingX">
                    <p class="font-700 paddingY">Status pembayaran : </p>
                    <?php if ($data[0]["paidst"] == 1) : ?>
                        <p class="color-success">Lunas</p>
                    <?php else : ?>
                        <p class="color-danger">Belum lunas</p>
                    <?php endif ?>
                    </p>
                </td>
                <td class="align-right paddingX">
                    <p class="font-700 paddingY">Kode order :</p>
                    <p>#<?= $data[0]["kode_order"] ?></p>
                </td>
            </tr>
            <tr>
                <td class="algin-left paddingX" colspan="2">
                    <p class="font-700 paddingY">Alamat pengiriman : </p>
                    <p><?= $data[0]["alamat_cust"] ?></p>
                </td>
            </tr>
            <tr>
                <td class="algin-left paddingX" colspan="2">
                    <p class="font-700 paddingY">Tipe pengiriman : </p>
                    <p><?= $data[0]["tipe_pengiriman"] ?></p>
                </td>
            </tr>
        </table>
    </div>
    <div class="body-content" style="padding-right: 10px; padding-left: 10px; margin-bottom: 20px;">
        <table class="w-100 tbl-border">
            <thead>
                <tr>
                    <th rowspan="2" class="algin-center paddingX">No</th>
                    <th rowspan="2" class="align-center paddingX">Item</th>
                    <th rowspan="2" class="align-center paddingX">Harga</th>
                    <th rowspan="2" class="align-center paddingX">Qty</th>
                    <th rowspan="2" class="align-center paddingX">Harga total</th>
                    <th rowspan="1" colspan="2" class="align-center paddingX">Diskon</th>
                </tr>
                <tr>
                    <th class="align-center paddingX">Nama</th>
                    <th class="algin-center paddingX">Harga</th>
                </tr>
            </thead>
            <!-- each -->
            <tbody>
                <?php

                $no = 1;
                $total_item     = 0;
                $total_diskon   = 0;

                foreach ($data as $d) : ?>
                    <?php
                    $row = json_decode($d["harga_potongan"], true);
                    ?>
                    <tr>
                        <td rowspan="<?= (count($row) <= 1) ? 1 : count($row) ?>" class="align-center paddingY"><?= $no ?></td>
                        <td rowspan="<?= (count($row) <= 1) ? 1 : count($row) ?>" class="align-center paddingY"><?= $d["nama_barang"] ?></td>
                        <td rowspan="<?= (count($row) <= 1) ? 1 : count($row) ?>" class="align-center paddingY">Rp <?= number_format($d["harga_jual"], 0, ".", ".") ?></td>
                        <td rowspan="<?= (count($row) <= 1) ? 1 : count($row) ?>" class="align-center paddingY"><?= $d["qty"] ?></td>
                        <td rowspan="<?= (count($row) <= 1) ? 1 : count($row) ?>" class="align-center paddingY">Rp <?= number_format($d["harga_total"], 0, ".", ".") ?></td>
                        <?php if (count($row) == 0) : ?>
                            <td class="align-center paddingY">-</td>
                            <td class="align-center paddingY">-</td>
                        <?php elseif (count($row) == 1) : ?>

                            <td class="align-center paddingY"><?= $row[0]["nama_diskon"] ?></td>
                            <td class="align-center paddingY">Rp <?= number_format($row[0]["harga_potongan"], 0, ".", ".") ?></td>

                            <?php $total_diskon += $row[0]["harga_potongan"]; ?>

                        <?php elseif (count($row) > 1) : ?>

                            <td class="align-center paddingY"><?= $row[0]["nama_diskon"] ?></td>
                            <td class="align-center paddingY">Rp <?= number_format($row[0]["harga_potongan"], 0, ".", ".") ?></td>

                            <?php $total_diskon += $row[0]["harga_potongan"]; ?>

                        <?php endif ?>
                    </tr>
                    <?php if (count($row) > 1) : ?>
                        <?php for ($i = 1; $i < count($row); $i++) : ?>
                            <tr>
                                <td class="align-center paddingY"><?= $row[$i]["nama_diskon"] ?></td>
                                <td class="align-center paddingY">Rp <?= number_format($row[$i]["harga_potongan"], 0, ".", ".") ?></td>
                            </tr>

                            <?php $total_diskon += $row[$i]["harga_potongan"]; ?>

                        <?php endfor ?>
                    <?php endif ?>

                <?php
                    $total_item += $d["harga_total"];
                    $no++;
                endforeach; ?>
                <tr class="bg-1">
                    <th colspan="4" class="align-center paddingY">Total</th>
                    <th class="align-center paddingY">Rp <?= number_format($total_item, 0, ".", ".") ?></th>
                    <th colspan="2" class="align-center paddingY">Rp <?= number_format($total_diskon, 0, ".", ".") ?></th>
                </tr>
            </tbody>
            <!-- end each -->
        </table>
    </div>
    <div class="footer-content" style="padding-right: 10px; padding-left: 10px; margin-bottom: 20px;">
        <table class="w-100">
            <tr>
                <td class="align-right paddingY" style="width: 80%;">
                    Sub total :
                </td>
                <td class="align-right paddingY">Rp <?= number_format($total_item - $total_diskon, 0, ".", ".") ?></td>
            </tr>
            <tr>
                <td class="align-right paddingY" style="width: 80%;">
                    Biaya pengiriman :
                </td>
                <td class="align-right paddingY">Rp <?= number_format($data[0]["biaya_kirim"], 0, ".", ".") ?></td>
            </tr>
            <tr>
                <th class="align-right paddingY" style="width: 80%;">
                    Total :
                </th>
                <th class="align-right paddingY">Rp <?= number_format($data[0]["total_order"] + $data[0]["biaya_kirim"], 0, ".", ".") ?></th>
            </tr>
        </table>
    </div>

    <div class="footer-terbilang" style="padding-right: 10px; padding-left: 10px; margin-bottom:20px;">
        <table class="w-100" style="border:solid 1px;">
            <tr>
                <th class="align-center font-italic"># <?= strtoupper(terbilang($data[0]["total_order"] + $data[0]["biaya_kirim"])) ?> RUPIAH #</th>
            </tr>
        </table>
    </div>

    <div class="footer-bank" style="padding-right: 10px; padding-left: 10px;">
        <h3>Silahkan transfer pembayaran pada salah satu rekening dibawah ini : </h3>
        <table class="w-100 tbl-border">
            <thead>
                <tr>
                    <th>Bank</th>
                    <th>Rekening</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_bank as $db) : ?>
                    <tr>
                        <td>
                            <p><?= $db["bank"] ?></p>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($db["payment"] as $p) : ?>
                                    <li>
                                        <p>No rek <?= $p["rekening"] . " /an " . $p["an_rekening"] ?></p>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</body>

</html>