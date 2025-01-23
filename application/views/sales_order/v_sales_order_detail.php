<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>
    <?php if ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata("gagal") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center overflow-auto">
                    <h4 class="text-secondary fw-bold">#<?= $data_detail_order[0]["kode_order"] ?></h4>
                    <a href="<?= site_url('kasir/sales_order') ?>" class="btn btn-sm btn-secondary"> Kembali</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <fieldset class="border p-3">
                                <legend class="float-none w-auto fw-bold">Data customer</legend>
                                <form>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="nama_cust">Nama : </label>
                                                <input type="text" name="nama_cust" id="nama_cust" class="form-control" value="<?= $data_detail_order[0]["nama_cust"] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="hp_cust">No hp : </label>
                                                <input type="text" name="hp_cust" id="hp_cust" class="form-control" value="<?= $data_detail_order[0]["hp_cust"] ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="alamat_cust">Alamat : </label>
                                                <textarea name="alamat_cust" id="alamat_cust" cols="30" rows="5" class="form-control" disabled><?= $data_detail_order[0]["alamat_cust"] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label for="tipe_pengiriman">Tipe pengiriman : </label>
                                                <select name="tipe_pengiriman" id="tipe_pengiriman" class="form-control" disabled>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="JNE" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "JNE") {
                                                                            echo "selected";
                                                                        } ?>>JNE</option>
                                                    <option value="JNT" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "JNT") {
                                                                            echo "selected";
                                                                        } ?>>JNT</option>
                                                    <option value="TIKI" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "TIKI") {
                                                                                echo "selected";
                                                                            } ?>>TIKI</option>
                                                    <option value="GOJEK" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "GOJEK") {
                                                                                echo "selected";
                                                                            } ?>>GOJEK</option>
                                                    <option value="GRAB" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "GRAB") {
                                                                                echo "selected";
                                                                            } ?>>GRAB</option>
                                                    <option value="KANTOR_POS" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "KANTOR_POS") {
                                                                                    echo "selected";
                                                                                } ?>>KANTOR_POS</option>
                                                    <option value="LAINNYA" <?php if ($data_detail_order[0]["tipe_pengiriman"] == "LAINNYA") {
                                                                                echo "selected";
                                                                            } ?>>LAINNYA</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label for="biaya_kirim">Biaya pengiriman : </label>
                                                <input type="text" class="form-control" id="biaya_kirim" name="biaya_kirim" value="<?= $data_detail_order[0]["biaya_kirim"] ?>" placeholder="Biaya kirim" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="hidden" name="kode_order" id="kode_order" class="form-control" value="<?= $this->secure->encrypt_url($data_detail_order[0]["kode_order"]) ?>">
                                            <input type="hidden" name="status" id="status" class="form-control" value="<?= $data_detail_order[0]["status"] ?>">
                                        </div>
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>

                    <fieldset class="border p-4 overflow-auto mb-2">
                        <legend class="float-none w-auto fw-bold">Orderan customer</legend>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <table class="table table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Kuantitas</th>
                                            <th>Diskon</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_sales_order">

                                        <?php
                                        $sum_subtotal = 0;
                                        $sum_subtotal_diskon = 0;
                                        ?>

                                        <?php foreach ($data_detail_order as $ddo) : ?>
                                            <tr>
                                                <td><small><?= $ddo["nama_barang"] ?></small></td>
                                                <td><small><?= number_format($ddo["harga_jual"], 0, ".", ".") ?></small></td>
                                                <td><small><?= $ddo["qty"] ?></small></td>
                                                <td>
                                                    <ul>

                                                        <?php
                                                        $sum_diskon = 0;
                                                        ?>

                                                        <?php foreach (json_decode($ddo["harga_potongan"], true) as $potongan) : ?>
                                                            <li><small><?= $potongan["nama_diskon"] ?> - <?= number_format($potongan["harga_potongan"], 0, ".", ".") ?></small></li>

                                                            <?php
                                                            $sum_diskon += $potongan["harga_potongan"];
                                                            $sum_subtotal_diskon += $potongan["harga_potongan"];
                                                            ?>

                                                        <?php endforeach ?>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <small class="harga_total"><?= number_format($ddo["harga_total"], 0, ".", ".") ?></small>
                                                </td>
                                            </tr>
                                        <?php $sum_subtotal += $ddo["harga_total"];
                                        endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <div class="card border-1">
                                    <div class="card-header">
                                        <h4 class="fw-bold">Total orderan</h4>
                                        <hr>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Biaya pengiriman</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p id="biaya-kirim"><?= number_format($data_detail_order[0]["biaya_kirim"], 0, ".", ".") ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Diskon</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p id="biaya-diskon"><?= number_format($sum_subtotal_diskon, 0, ".", ".") ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Sub total</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p id="subtotal"><?= number_format($sum_subtotal, 0, ".", ".") ?></p>
                                            </div>
                                            <div class="md-12">
                                                <hr>
                                            </div>
                                            <div class="col-md-6">
                                                <h5>Total</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 id="total"><?= number_format(($sum_subtotal - $sum_subtotal_diskon) + $data_detail_order[0]["biaya_kirim"], 0, ".", ".") ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row">
                        <?php if ($data_detail_order[0]["status"] == 2 && $data_detail_order[0]["tipe_order"] == "Kasir") : ?>
                            <form id="formPembayaran" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="border p-4 overflow-auto mb-2">
                                            <legend class="float-none w-auto fw-bold">Pembayaran Transfer</legend>
                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="bank_id">Bank pembayaran : </label>
                                                        <select name="bank_id" id="bank_id" class="form-control select2">
                                                            <option value="">-- Pilih --</option>
                                                            <?php foreach ($data_bank as $db) : ?>
                                                                <option value="<?= $db["id_bank"] ?>"><?= $db["bank"] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <small class="text-danger" id="err_bank_id"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="payment_id">Rekening : </label>
                                                        <select name="payment_id" id="payment_id" class="form-control select2">
                                                            <option value="">-- Pilih --</option>
                                                        </select>
                                                        <small class="text-danger" id="err_payment_id"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="tanggal_beli">Tanggal pembayaran : </label>
                                                <input type="text" class="form-control flatdatetimepickr" id="tanggal_beli" name="tanggal_beli" placeholder="Tanggal pembayaran">
                                                <small class="text-danger" id="err_tanggal_beli"></small>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="tipe_order" id="tipe_order" class="form-control" value="<?= $data_detail_order[0]["tipe_order"] ?>">
                                                <input type="hidden" name="id_order" id="id_order" class="form-control" value="<?= $data_detail_order[0]["id_order"] ?>">
                                                <input type="hidden" name="id_toko" id="id_toko" class="form-control" value="<?= $data_detail_order[0]['toko_id'] ?>">
                                                <input type="hidden" name="terbayar" id="terbayar" class="form-control" value="<?= ($sum_subtotal - $sum_subtotal_diskon) + $data_detail_order[0]["biaya_kirim"] ?>">
                                                <input type="hidden" name="total_tagihan" id="total_tagihan" class="form-control" value="<?= ($sum_subtotal - $sum_subtotal_diskon) + $data_detail_order[0]["biaya_kirim"] ?>">
                                                <input type="hidden" name="total_diskon" id="total_diskon" class="form-control" value="<?= $sum_subtotal_diskon ?>">
                                                <input type="hidden" name="tagihan_cart" id="tagihan_cart" class="form-control" value="<?= $sum_subtotal ?>">
                                                <input type="hidden" name="tagihan_after_diskon" id="tagihan_after_diskon" class="form-control" value="<?= ($sum_subtotal - $sum_subtotal_diskon) ?>">
                                                <input type="hidden" name="total_biaya_kirim" id="total_biaya_kirim" class="form-control" value="<?= $data_detail_order[0]["biaya_kirim"] ?>">
                                            </div>

                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="border p-4 overflow-auto mb-2">
                                            <legend class="float-none w-auto fw-bold">Pembayaran Tunai</legend>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nominal_pembayaran">Nominal Pembayaran</label>
                                                        <input oninput="formatInput(this)" onkeydown="limit(this);" onkeyup="limit(this);" type="text" name="nominal_pembayaran" id="nominal_pembayaran" autofocus>
                                                        <small class="text-danger" id="err_nominal_pembayaran"></small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nominal_kembalian">Nominal Kembalian</label>
                                                        <input type="text" name="nominal_kembalian" id="nominal_kembalian">
                                                        <small class="text-danger" id="err_nominal_kembalian"></small>
                                                    </div>
                                                </div>
                                            </div>

                                        </fieldset>
                                    </div> 
                                </div>
                            </form>
                        <?php elseif ($data_detail_order[0]["status"] == 2) : ?>
                            <form id="formPembayaran" enctype="multipart/form-data">
                                <div class="col-md-6">
                                    <fieldset class="border p-4 overflow-auto mb-2">
                                        <legend class="float-none w-auto fw-bold">Upload Pembayaran</legend>
                                        
                                        <div class="form-group mb-3">
                                            <label for="bukti_bayar">Bukti pembayaran</label>
                                            <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control dropify" data-max-file-size="2M" data-allowed-file-extensions="PNG JPG JPEG png jpg jpeg">
                                            <small class="text-danger" id="err_bukti_bayar"></small>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bank_id">Bank pembayaran : </label>
                                                    <select name="bank_id" id="bank_id" class="form-control select2">
                                                        <option value="">-- Pilih --</option>
                                                        <?php foreach ($data_bank as $db) : ?>
                                                            <option value="<?= $db["id_bank"] ?>"><?= $db["bank"] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <small class="text-danger" id="err_bank_id"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="payment_id">Rekening : </label>
                                                    <select name="payment_id" id="payment_id" class="form-control select2">
                                                        <option value="">-- Pilih --</option>
                                                    </select>
                                                    <small class="text-danger" id="err_payment_id"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_beli">Tanggal pembayaran : </label>
                                            <input type="text" class="form-control flatdatetimepickr" id="tanggal_beli" name="tanggal_beli" placeholder="Tanggal pembayaran">
                                            <small class="text-danger" id="err_tanggal_beli"></small>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="tipe_order" id="tipe_order" class="form-control" value="<?= $data_detail_order[0]["tipe_order"] ?>">
                                            <input type="hidden" name="id_order" id="id_order" class="form-control" value="<?= $data_detail_order[0]["id_order"] ?>">
                                            <input type="hidden" name="id_toko" id="id_toko" class="form-control" value="<?= $data_detail_order[0]['toko_id'] ?>">
                                            <input type="hidden" name="terbayar" id="terbayar" class="form-control" value="<?= ($sum_subtotal - $sum_subtotal_diskon) + $data_detail_order[0]["biaya_kirim"] ?>">
                                            <input type="hidden" name="total_tagihan" id="total_tagihan" class="form-control" value="<?= ($sum_subtotal - $sum_subtotal_diskon) + $data_detail_order[0]["biaya_kirim"] ?>">
                                            <input type="hidden" name="total_diskon" id="total_diskon" class="form-control" value="<?= $sum_subtotal_diskon ?>">
                                            <input type="hidden" name="tagihan_cart" id="tagihan_cart" class="form-control" value="<?= $sum_subtotal ?>">
                                            <input type="hidden" name="tagihan_after_diskon" id="tagihan_after_diskon" class="form-control" value="<?= ($sum_subtotal - $sum_subtotal_diskon) ?>">
                                            <input type="hidden" name="total_biaya_kirim" id="total_biaya_kirim" class="form-control" value="<?= $data_detail_order[0]["biaya_kirim"] ?>">
                                        </div>

                                    </fieldset>
                                </div>
                            </form>
                        <?php else : ?>
                            <div class="col-md-6">
                                <fieldset class="border p-4 overflow-auto mb-2">
                                    <legend class="float-none w-auto fw-bold">List bukti pembayaran</legend>
                                    <?php if ($data_detail_order[0]["status"] != 5 && $data_detail_order[0]["status"] != "99") : ?>
                                        <div class="form-group mb-2">
                                            <?php if ($data_detail_order[0]["status"] == 3) : ?>
                                                <label for="waktu">Tanggal pengiriman</label>
                                            <?php elseif ($data_detail_order[0]["status"] == 4) : ?>
                                                <label for="waktu">Tanggal penerimaan</label>
                                            <?php endif ?>
                                            <input type="text" name="waktu" id="waktu" class="form-control flatdatetimepickr">
                                            <small class="text-danger" id="err_waktu"></small>
                                        </div>
                                    <?php endif ?>
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="<?= base_url('assets/file_bukti_bayar/' . $data_detail_order[0]["bukti_tf"]) ?>" target="_blank" class="btn btn-sm btn-secondary">Lihat bukti bayar</a>
                                                <!-- <button class="btn btn-sm btn-danger">x</button> -->
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <?php if ($data_detail_order[0]["status"] == 2 && $data_detail_order[0]["tipe_order"] == "Kasir") : ?>
                            <button class="btn btn-warning text-white" id="rollbackKonfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-step="1">Ubah pesanan</button>
                            <button class="btn btn-primary text-white" id="konfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-status="2">Konfirmasi Pembayaran</button>
                        <?php elseif ($data_detail_order[0]["status"] == 2) : ?>
                            <button class="btn btn-warning text-white" id="rollbackKonfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-step="1">Ubah pesanan</button>
                            <button class="btn btn-primary text-white" id="konfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-status="2">Konfirmasi dikemas</button>
                        <?php elseif ($data_detail_order[0]["status"] == 3) : ?>
                            <button class="btn btn-danger text-white" id="rollbackKonfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-step="2">Batalkan pesanan</button>
                            <button class="btn btn-warning text-white" id="konfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-status="3">Konfirmasi dikirim</button>
                        <?php elseif ($data_detail_order[0]["status"] == 4) : ?>
                            <button class="btn btn-danger text-white" id="rollbackKonfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-step="2">Batalkan pesanan</button>
                            <button class="btn btn-success text-white" id="konfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-status="4">Konfirmasi selesai</button>
                        <?php elseif ($data_detail_order[0]["status"] == 5) : ?>
                            <button class="btn btn-danger text-white" id="rollbackKonfirmasiPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-step="2">Batalkan pesanan</button>
                        <?php endif ?>

                        <?php if ($data_detail_order[0]["tipe_order"] == "Kasir" && $data_detail_order[0]["status"] == 5) { ?>
                            <button class="btn btn-warning text-white" id="ubahPesanan" data-id="<?= $data_detail_order[0]["id_order"] ?>" data-step="1">Ubah pesanan</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function formatInput(input) {
            let value = input.value.replace(/[^\d]/g, ''); // Hapus karakter non-digit
            input.value = addThousandSeparator(value);
        }

        function addThousandSeparator(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function removeThousandSeparator(formattedNumber) {
            // Hapus semua koma dari string
            return formattedNumber.replace(/,/g, '');
        }

        function limit(element) {
            let max_chars = 10;

            if (element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }

        function addToLocale(number) {
            return number.toLocaleString();
        }

        function addThousandSeparatorWithNegative(number) {
            // Pisahkan angka menjadi bagian desimal dan bagian non-desimal
            let parts = number.toString().split('.');

            // Tangani bagian non-desimal
            let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            // Tangani bagian desimal (jika ada)
            let decimalPart = parts.length > 1 ? '.' + parts[1] : '';

            // Gabungkan kembali kedua bagian dengan menangani tanda minus
            return (number < 0 ? '-' : '') + integerPart + decimalPart;
        }

        function calculateChange(price) {
            //ini lama
            // let total_tagihan = $('#total_tagihan').val();
            // let kembalian = parseInt(removeThousandSeparator(price)) - parseInt(removeThousandSeparator(total_tagihan));
            // $('#kembalian').val(isNaN(kembalian) ? 0 : addThousandSeparator(kembalian));

            //ini baru ada diskon cuy
            let total_tagihan_after_diskon = $('#tagihan_after_diskon').val();
            let kembalian = parseInt(removeThousandSeparator(price)) - parseInt(removeThousandSeparator(total_tagihan_after_diskon));
            $('#nominal_kembalian').val(isNaN(kembalian) ? 0 : addThousandSeparator(kembalian));

        }

        $('#nominal_pembayaran').on('keyup', function() {
            let nominal_pembayaran = $('#nominal_pembayaran').val();
            calculateChange(nominal_pembayaran);

        });

        $(function() {

            $("#ubahPesanan").on("click", function() {

                const id_order = $(this).data("id")
                const step = $(this).data("step")

                if (confirm("Apakah anda ingin melakukan perubahan pada pesanan ini ?")) {

                    if (id_order && step) {

                        $.ajax({

                            url: "<?= site_url('kasir/sales_order/rollbackUbah') ?>",
                            type: "post",
                            data: {
                                id_order: id_order,
                                step: step,
                                status: $("#status").val(),
                            },
                            dataType: "json",
                            beforeSend: function() {

                                $("#ubahPesanan").prop("disabled", true)
                                $("#ubahPesanan").html("Loading...")
                            },
                            success: function(res) {

                                if (res.status == "error") {

                                    if (res.err_id_order) {

                                        alert(res.err_id_order);

                                    }

                                    if (res.err_step) {

                                        alert(res.err_step);

                                    }

                                    if (res.err_status) {

                                        alert(res.err_status);

                                    }

                                } else {

                                    if (step == 2) {

                                        window.location.replace("<?= site_url('kasir/sales_order') ?>")
                                    } else {

                                        window.location.reload()
                                    }

                                }

                            }

                        })

                    } else {

                        alert("Parameter tidak sesuai!")

                    }

                }

            })

            $("#rollbackKonfirmasiPesanan").on("click", function() {

                const id_order = $(this).data("id")
                const step = $(this).data("step")

                if (confirm("Apakah anda ingin melakukan perubahan pada pesanan ini ?")) {

                    if (id_order && step) {

                        $.ajax({

                            url: "<?= site_url('kasir/sales_order/rollback') ?>",
                            type: "post",
                            data: {
                                id_order: id_order,
                                step: step,
                                nama_cust: $("#nama_cust").val(),
                                hp_cust: $("#hp_cust").val(),
                                kode_order: $("#kode_order").val(),
                            },
                            dataType: "json",
                            beforeSend: function() {

                                $("#rollbackKonfirmasiPesanan").prop("disabled", true)
                                $("#rollbackKonfirmasiPesanan").html("Loading...")
                            },
                            success: function(res) {

                                if (res.status == "error") {

                                    if (res.err_id_order) {

                                        alert(res.err_id_order);

                                    }

                                    if (res.err_step) {

                                        alert(res.err_step);

                                    }
                                    
                                } else {

                                    if (step == 2) {

                                        window.location.replace("<?= site_url('kasir/sales_order') ?>")
                                    } else {

                                        window.location.reload()
                                    }

                                }

                            }

                        })

                    } else {

                        alert("Parameter tidak sesuai!")

                    }

                }

            })

            $("#konfirmasiPesanan").on("click", function() {

                const id = $(this).data("id")
                const status = $(this).data("status")
                const nama_cust = $("#nama_cust").val()
                const hp_cust = $("#hp_cust").val()
                const kode_order = $("#kode_order").val()

                if (confirm("Apakah anda ingin konfirmasi pesanan ini ?")) {

                    switch (status) {

                        case 2:

                            let formPembayaran = new FormData($("#formPembayaran")[0])

                            formPembayaran.append("nama_cust", nama_cust);
                            formPembayaran.append("hp_cust", hp_cust);
                            formPembayaran.append("kode_order", kode_order);

                            $.ajax({

                                url: "<?= site_url("kasir/sales_order/upload") ?>",
                                type: "post",
                                data: formPembayaran,
                                dataType: "json",
                                processData: false,
                                contentType: false,
                                beforeSend: function() {
                                    $("#konfirmasiPesanan").prop("disabled", true)
                                    $("#konfirmasiPesanan").html("Loading...")
                                },
                                success: function(res) {

                                    if (res.status == "error") {

                                        alert("Terdapat field yang tidak sesuai")

                                        if (res.err_bukti_bayar) {

                                            $("#err_bukti_bayar").html(res.err_bukti_bayar)

                                        }

                                        if (res.err_bank_id) {

                                            $("#err_bank_id").html(res.err_bank_id)

                                        }

                                        if (res.err_payment_id) {

                                            $("#err_payment_id").html(res.err_payment_id)

                                        }

                                        if (res.err_tanggal_beli) {

                                            $("#err_tanggal_beli").html(res.err_tanggal_beli)

                                        }

                                        if (res.err_nominal_pembayaran) {

                                            $("#err_nominal_pembayaran").html(res.err_nominal_pembayaran)

                                        }

                                        if (res.err_nominal_kembalian) {

                                            $("#err_nominal_kembalian").html(res.err_nominal_kembalian)

                                        }

                                        $("#konfirmasiPesanan").prop("disabled", false)
                                        $("#konfirmasiPesanan").html("Konfirmasi pesanan")

                                    } else {

                                        (res.status != "berhasil") ? alert(res.response): ""

                                        window.location.reload()

                                    }

                                }

                            })

                            break;
                        case 3:
                        case 4:

                            $.ajax({
                                url: "<?= site_url('kasir/sales_order/konfirmasi') ?>",
                                type: "post",
                                data: {
                                    id_order: id,
                                    step: status,
                                    nama_cust: $("#nama_cust").val(),
                                    hp_cust: $("#hp_cust").val(),
                                    alamat: $("#alamat_cust").val(),
                                    kode_order: $("#kode_order").val(),
                                    waktu: $("#waktu").val()
                                },
                                dataType: "json",
                                success: function(res) {

                                    if (res.status == "error") {

                                        if (res.err_step) {
                                            $("#err_step").html(res.err_step)
                                        }

                                        if (res.err_waktu) {
                                            $("#err_waktu").html(res.err_waktu)
                                        }

                                    } else {

                                        (res.status != "berhasil") ? alert(res.response): ""

                                        window.location.reload()

                                    }

                                }
                            });

                            break;
                        default:

                            alert("Status not found!")

                            break;

                    }

                }

            })

            $("#bank_id").on("change", function() {

                const values = $(this)

                if (values.val()) {

                    $.ajax({
                        url: "<?= site_url('kasir/sales_order/rekening') ?>",
                        type: "post",
                        data: {
                            id: values.val()
                        },
                        beforeSend: function() {

                            $("#payment_id").find("option").not(":first").remove();
                        },
                        success: function({
                            status,
                            response
                        }) {

                            if (status == "berhasil") {

                                for (const elemen of response) {

                                    const option = new Option(elemen.rekening, elemen.id_payment, false, false);
                                    $("#payment_id").append(option).trigger('change');

                                }

                            } else {

                                alert(response)
                            }
                        },
                        dataType: "json"
                    })

                } else {

                    $("#payment_id").find("option").not(":first").remove();

                }

            })

        })
    </script>