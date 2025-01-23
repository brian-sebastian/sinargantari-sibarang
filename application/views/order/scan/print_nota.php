<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data pembayaran <?= $transaksi[0]['kode_transaksi'] ?></title>
    <style>
        html {

            margin: 10px;
            padding: 0;

        }

        body {

            font-family: Arial, Helvetica, sans-serif;
        }

        .header {
            font-size: medium;
            text-align: center;
        }

        .profile {
            font-size: x-small;
        }

        .tagihan {

            font-size: x-small;
        }

        .footer {
            font-size: x-small;
            margin-bottom: 50px;
        }

        .admin {
            font-size: x-small;
            text-align: center;
        }

        /* .admin p {

            margin-bottom: 30px;

        } */

        table {

            width: 100%;

        }

        table .awal {
            font-weight: 600;
        }

        table .akhir {

            text-align: right;

        }

        hr {

            border-top: 1px dotted black;
            border-bottom: none;

        }
    </style>
</head>
<?php
$nama_cust = $transaksi[0]['nama_cust'];
$hp_cust = $transaksi[0]['hp_cust'];
$alamat_cust = $transaksi[0]['alamat_cust'];
$kode_order = $transaksi[0]['kode_order'];
$total_diskon = $transaksi[0]['total_diskon'];
$total_biaya_kirim = $transaksi[0]['total_biaya_kirim'];

$terbayar = $transaksi[0]['terbayar'];
$kembalian = $transaksi[0]['kembalian'];

?>

<?php if ($nama_cust == null || $nama_cust == "") : ?>

    <body>
        <div class="header">
            <p><?= $this->session->userdata('unit_kerja') ?></p>
        </div>
        <hr>
        <div class="profile">
            <table>
                <tr>
                    <td class="awal">Kode Transaksi</td>
                    <td>: <?= $transaksi[0]['kode_order'] ?> | <?= $transaksi['0']['kode_transaksi'] ?></td>
                </tr>
                <tr>
                    <td class="awal">Tgl bayar</td>
                    <td>: <?= $transaksi[0]['created_at'] ?></td>
                </tr>
                <tr>
                    <td class="awal">Terbayarkan</td>
                    <td>: Rp. <?= number_format($terbayar) ?></td>
                </tr>
                <tr>
                    <td class="awal">Kembalian</td>
                    <td>: Rp. <?= number_format($kembalian) ?></td>
                </tr>
            </table>
        </div>
        <hr>
        <div class="tagihan">
            <table>
                <?php
                $total = 0;
                $subtotal = 0;
                ?>

                <?php foreach ($transaksi as $d) : ?>
                    <tr>
                        <td class="awal">
                            <?= $d['nama_barang'] ?>
                        </td>
                        <td class="awal">
                            <?= $d['qty'] ?>
                        </td>
                        <td class="awal">
                            Rp. <?= number_format($d['harga_jual']) ?>
                        </td>
                        <td class="akhir">
                            <?php
                            // $subtotal = doubleval($d['harga_jual']) * doubleval($d['qty']);
                            $subtotal = $d['harga_total'];
                            ?>

                            Rp. <?= number_format($subtotal) ?>
                        </td>
                    </tr>
                    <?php
                    $total += $subtotal;
                    ?>
                <?php endforeach; ?>
            </table>
        </div>
        <hr>
        <div class="footer">
            <table>
                <tr>
                    <td class="awal">
                        Total Keranjang
                    </td>
                    <td class="akhir">
                        Rp <?= number_format($total, 0, '.', '.') ?>
                    </td>
                </tr>
            </table>
        </div>

        <hr>
        <div class="footer">
            <table>
                <?php if ($total_diskon) : ?>
                    <tr>
                        <td class="awal">
                            Total Diskon
                        </td>
                        <td class="akhir">
                            Rp <?= number_format($total_diskon, 0, '.', '.') ?>
                        </td>
                    </tr>
                    <?php $total += $total_diskon ?>
                <?php endif ?>
                <?php if ($total_biaya_kirim) : ?>
                    <tr>
                        <td class="awal">
                            Total Biaya Kirim
                        </td>
                        <td class="akhir">
                            Rp <?= number_format($total_biaya_kirim, 0, '.', '.') ?>
                        </td>
                    </tr>
                    <?php $total += $total_biaya_kirim ?>
                <?php endif ?>

                <tr>
                    <td class="awal">
                        Grand Total
                    </td>
                    <td class="akhir">
                        Rp <?= number_format($total, 0, '.', '.') ?>
                    </td>
                </tr>
            </table>
        </div>



        <div class="admin">
            <small>(<?= $transaksi[0]['user_input'] ?>)</small>
            <p><?= $transaksi[0]['created_at'] ?></p>
        </div>
    </body>


<?php else : ?>

    <body>
        <div class="header">
            <p><?= $this->session->userdata('unit_kerja') ?></p>
        </div>
        <hr>
        <div class="profile">
            <table>
                <tr>
                    <td class="awal">Nama</td>
                    <td>: <?= $transaksi[0]['nama_cust'] ?></td>
                </tr>
                <tr>
                    <td class="awal">HP</td>
                    <td>: <?= $transaksi[0]['hp_cust'] ?></td>
                </tr>
                <tr>
                    <td class="awal">Alamat</td>
                    <td>: <?= $transaksi[0]['alamat_cust'] ?></td>
                </tr>
                <tr>
                    <td class="awal">Kode Transaksi</td>
                    <td>: <?= $transaksi[0]['kode_order'] ?> | <?= $transaksi['0']['kode_transaksi'] ?></td>
                </tr>
                <tr>
                    <td class="awal">Tgl bayar</td>
                    <td>: <?= $transaksi[0]['created_at'] ?></td>
                </tr>
                <tr>
                    <td class="awal">Terbayarkan</td>
                    <td>: Rp. <?= number_format($terbayar) ?></td>
                </tr>
                <tr>
                    <td class="awal">Kembalian</td>
                    <td>: Rp. <?= number_format($kembalian) ?></td>
                </tr>
            </table>
        </div>
        <hr>
        <div class="tagihan">
            <table>
                <?php
                $total = 0;
                $subtotal = 0;
                ?>

                <?php foreach ($transaksi as $d) : ?>
                    <tr>
                        <td class="awal">
                            <?= $d['nama_barang'] ?>
                        </td>
                        <td class="awal">
                            <?= $d['qty'] ?>
                        </td>
                        <td class="awal">
                            Rp. <?= number_format($d['harga_jual']) ?>
                        </td>
                        <td class="akhir">
                            <?php
                            // $subtotal = doubleval($d['harga_jual']) * doubleval($d['qty']);
                            $subtotal = $d['harga_total'];
                            ?>
                            Rp. <?= number_format($subtotal) ?>
                        </td>
                    </tr>
                    <?php
                    $total += $subtotal;
                    ?>
                <?php endforeach; ?>
            </table>
        </div>
        <hr>
        <div class="footer">
            <table>
                <tr>
                    <td class="awal">
                        Total Keranjang
                    </td>
                    <td class="akhir">
                        Rp <?= number_format($total, 0, '.', '.') ?>
                    </td>
                </tr>
            </table>
        </div>

        <hr>
        <div class="footer">
            <table>
                <?php if ($total_diskon) : ?>
                    <tr>
                        <td class="awal">
                            Total Diskon
                        </td>
                        <td class="akhir">
                            Rp <?= number_format($total_diskon, 0, '.', '.') ?>
                        </td>
                    </tr>
                    <?php $total += $total_diskon ?>
                <?php endif ?>
                <?php if ($total_biaya_kirim) : ?>
                    <tr>
                        <td class="awal">
                            Total Biaya Kirim
                        </td>
                        <td class="akhir">
                            Rp <?= number_format($total_biaya_kirim, 0, '.', '.') ?>
                        </td>
                    </tr>
                    <?php $total += $total_biaya_kirim ?>
                <?php endif ?>

                <tr>
                    <td class="awal">
                        Grand Total
                    </td>
                    <td class="akhir">
                        Rp <?= number_format($total, 0, '.', '.') ?>
                    </td>
                </tr>
            </table>
        </div>



        <div class="admin">
            <small>(<?= $transaksi[0]['user_input'] ?>)</small>
            <p><?= $transaksi[0]['created_at'] ?></p>
        </div>
    </body>

<?php endif ?>



</html>