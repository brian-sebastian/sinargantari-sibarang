<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kasir /</span> Scan</h4>


    <?php if ($this->session->flashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($this->session->flashdata('message_error')) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata('message_error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($this->session->flashdata('flash-swal')) : ?>
        <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash-swal'); ?>"></div>

    <?php elseif ($this->session->flashdata('flash-data-gagal')) : ?>
        <div class="flash-data-gagal" data-flashdata="<?= $this->session->flashdata('flash-data-gagal'); ?>"></div>
    <?php endif ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5>Checkout</h5>
                            <div class="row">
                                <h6>Produk</h6>
                                <table class="table table-striped nowrap py-1 px-1">
                                    <?php $subtotaldiskon = 0; ?>
                                    <thead>
                                        <tr class="text-center">
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Sub Total</th>
                                            <th>Diskon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_content as $cart) : ?>
                                            <tr>
                                                <td>
                                                    <?= $cart['name'] ?> | <?= $cart['qty'] ?>
                                                </td>
                                                <td>
                                                    Rp. <?= number_format($cart['price']) ?>
                                                </td>
                                                <td>
                                                    Rp. <?= number_format($cart['subtotal']) ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    $optionDiskon = (array_key_exists('options', $cart)) ? json_decode($cart['options']['diskon'], true) : '' ?>
                                                    <ul>
                                                        <?php if ($optionDiskon != "") : ?>
                                                            <?php for ($i = 0; $i < count($optionDiskon); $i++) : ?>
                                                                <div class="d-flex justify-content-between">
                                                                    <li>
                                                                        <small><?= $optionDiskon[$i]["nama_diskon"] ?></small>
                                                                        <small>Rp <?= number_format($optionDiskon[$i]["harga_potongan"], 0, ".", ".") ?></small>
                                                                    </li>
                                                                </div>

                                                                <?php $subtotaldiskon += $optionDiskon[$i]["harga_potongan"]; ?>
                                                            <?php endfor ?>
                                                        <?php endif ?>
                                                    </ul>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                        <tr>
                                            <th colspan="2">
                                                Sub Total Produk
                                            </th>
                                            <th>
                                                Rp. <?= number_format($cart_total) ?>
                                            </th>
                                            <th>
                                                Rp. <?= number_format($subtotaldiskon) ?>
                                            </th>
                                        </tr>
                                        <tr class="text-center">
                                            <?php
                                            $grandTotal = $cart_total - $subtotaldiskon;
                                            ?>
                                            <th colspan="4">
                                                GRAND TOTAL
                                                <br>
                                                Rp. <?= number_format($grandTotal)  ?>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body">
                    <div class="row mt-2">
                        <h4 class="text-center">Additional Information</h4>
                        <div id="message">

                        </div>
                        <form class="row g-3" method="post" id="form_customer">

                            <div class="col-12">
                                <label for="inputAddress2" class="form-label">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input radio-inline" type="radio" name="status" id="dikirim" value="<?= DIKIRIM ?>">
                                    <label class="form-check-label" for="dikirim">
                                        DIKIRIM
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input radio-inline" type="radio" name="status" id="selesai" value="<?= SELESAI ?>" checked>
                                    <label class="form-check-label" for="selesai">
                                        SELESAI
                                    </label>
                                </div>
                            </div>

                            <div id="add_information" style="display: none;">
                                <div class="col-12">
                                    <label for="nama_cust" class="form-label">Nama Customer</label>
                                    <input type="hidden" id="toko_id" name="toko_id" readonly value="<?= $this->session->userdata('toko_id') ?>" class="form-control">
                                    <input type="text" id="nama_cust" name="nama_cust" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="hp_cust" class="form-label">HP Customer</label>
                                    <input type="text" id="hp_cust" name="hp_cust" class="form-control">
                                </div>

                                <div class="col-12">
                                    <label for="alamat_cust" class="form-label">Address</label>
                                    <input type="text" class="form-control" name="alamat_cust" id="alamat_cust">
                                </div>

                                <div class="col-12">
                                    <label for="tipe_pengiriman" class="form-label">Tipe Pengiriman</label>
                                    <input type="text" id="tipe_pengiriman" name="tipe_pengiriman" placeholder="JNE, TIKI, GOJEK, GRAB, TOKO" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label for="biaya_kirim" class="form-label">Biaya Kirim</label>
                                    <input oninput="formatInput(this)" onkeydown="limit(this);" onkeyup="limit(this);" type="text" id="biaya_kirim" name="biaya_kirim" class="form-control" value="0">
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="input-group mb-3 ">
                                    <span style="background-color: #FA7070; color: white; font-size: 18px; width: 30%; height: 50px;" class="input-group-text">TOTAL TAGIHAN</span>
                                    <input style="background-color: #FA7070; color: white; font-size: 18px;" type="text" class="form-control" id="total_tagihan" name="total_tagihan" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span style="background-color: #6DB9EF; color: white; font-size: 18px; width: 30%; height: 50px;" class="input-group-text">DISKON</span>
                                    <input oninput="formatInput(this)" onkeydown="limit(this);" onkeyup="limit(this);" style="background-color: #6DB9EF; color: white; font-size: 18px; width: 30%; height: 50px;" type="text" class="form-control" id="contain_discount" name="contain_discount" value="0" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span style="background-color: #6DB9EF; color: white; font-size: 18px; width: 30%; height: 50px;" class="input-group-text">TERBAYARKAN</span>
                                    <input oninput="formatInput(this)" onkeydown="limit(this);" onkeyup="limit(this);" style="background-color: #6DB9EF; color: white; font-size: 18px; width: 30%; height: 50px;" type="text" class="form-control" id="terbayar" name="terbayar" autofocus>
                                </div>
                                <div class="input-group mb-3">
                                    <span style="background-color: #A6CF98; color: white; font-size: 18px; width: 30%; height: 50px;" class="input-group-text">HARGA SETELAH DISKON</span>
                                    <input style="background-color: #A6CF98; color: white; font-size: 18px; width: 30%; height: 50px;" type="text" class="form-control" id="price_after_discount" name="price_after_discount" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span style="background-color: #A6CF98; color: white; font-size: 18px; width: 30%; height: 50px;" class="input-group-text">KEMBALIAN</span>
                                    <input style="background-color: #A6CF98; color: white; font-size: 18px; width: 30%; height: 50px;" type="text" class="form-control" id="kembalian" name="kembalian" readonly>

                                    <input type="hidden" name="tagihan_keranjang" id="tagihan_keranjang" value="<?= $cart_total ?>" placeholder="Tagihan Keranjang" readonly>

                                    <input type="hidden" name="total_diskon" id="total_diskon" value="<?= $subtotaldiskon ?>" readonly>

                                    <input type="hidden" name="grand_total_price" id="grand_total_price" value="<?= $grandTotal ?>" placeholder="(Tagihan Keranjang - Diskon)" readonly>

                                    <input type="hidden" id="total_order" name="total_order" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Pembayaran</label>
                                <br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input metode" type="radio" name="metode_bayar" id="metode_tunai" value="tunai" checked>
                                    <label class="form-check-label" for="tunai">TUNAI</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input metode" type="radio" name="metode_bayar" id="metode_transfer" value="transfer">
                                    <label class="form-check-label" for="transfer">TRANSFER</label>
                                </div>
                            </div>

                            <div class="col-md-12 d-none" id="parentPayment">
                                <div class="form-group w-50 mb-2">
                                    <label for="bank_id">Bank</label>
                                    <select name="bank_id" id="bank_id" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($data_bank as $dbank) : ?>
                                            <option value="<?= $dbank["id_bank"] ?>"><?= $dbank["bank"] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <small class="text-danger" id="err_bank_id"></small>
                                </div>
                                <div class="form-group w-50">
                                    <label for="payment_id">Payment</label>
                                    <select name="payment_id" id="payment_id" class="form-control">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <small class="text-danger" id="err_payment_id"></small>
                                </div>
                            </div>

                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <a href="<?= base_url('kasir/scan') ?>" class="btn btn-danger">Cancel</a>
                                <button type="button" id="checkout" class="btn btn-success">Checkout</button>
                            </div>
                        </form>

                    </div>
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

    function base64Encode(str) {
        return btoa(str);
    }

    function base64Decode(str) {
        return atob(str);
    }

    function md5Hash(text) {
        return CryptoJS.MD5(text).toString();
    }

    $('.radio-inline').change(function() {

        let selected_radio_value = $("input[name=status]:checked").val();

        if (selected_radio_value == 4) {
            $('#add_information').show();
        }
        if (selected_radio_value == 5) {
            $('#add_information').hide();
        }


    });

    let grand_total_price = $('#grand_total_price').val();
    $('#total_tagihan').val(addThousandSeparator(grand_total_price));
    $('#total_order').val(addThousandSeparator(grand_total_price));
    $('#terbayar').val();
    $('#kembalian').val(0);

    $('#price_after_discount').val(addThousandSeparator(grand_total_price));

    // Event listener untuk input pada '#contain_discount'
    $('#contain_discount').on('keyup', function() {
        let contain_discount = $(this).val();

        // Hapus semua karakter non-digit, termasuk pemisah ribuan
        contain_discount = removeThousandSeparator(contain_discount);

        // Setel nilai input ke format dengan pemisah ribuan
        $(this).val(addThousandSeparator(contain_discount));

        // Hitung price_after_discount setelah diskon diinputkan
        calculatePriceAfterDiscount();
    });

    // Fungsi untuk menghitung price_after_discount setelah diskon diinputkan
    function calculatePriceAfterDiscount() {
        let grand_total_price = parseInt(removeThousandSeparator($('#grand_total_price').val()));
        let contain_discount = parseInt(removeThousandSeparator($('#contain_discount').val()));


        // Jika contain_discount kosong atau 0, price_after_discount sama dengan grand_total_price
        let price_after_discount;
        if (isNaN(contain_discount) || contain_discount === 0) {
            price_after_discount = grand_total_price;
        } else {
            price_after_discount = grand_total_price - contain_discount;
        }

        // Menyimpan hasil perhitungan ke dalam input readonly
        $('#price_after_discount').val(addThousandSeparator(price_after_discount));
    }

    function calculateChange(price) {
        //ini lama
        // let total_tagihan = $('#total_tagihan').val();
        // let kembalian = parseInt(removeThousandSeparator(price)) - parseInt(removeThousandSeparator(total_tagihan));
        // $('#kembalian').val(isNaN(kembalian) ? 0 : addThousandSeparator(kembalian));

        //ini baru ada diskon cuy
        let total_tagihan_after_diskon = $('#price_after_discount').val();
        let kembalian = parseInt(removeThousandSeparator(price)) - parseInt(removeThousandSeparator(total_tagihan_after_diskon));
        $('#kembalian').val(isNaN(kembalian) ? 0 : addThousandSeparator(kembalian));



    }

    $('#biaya_kirim').on('keyup', function() {
        let grand_total_price = $('#grand_total_price').val();
        let biaya_kirim = $('#biaya_kirim').val();

        let hasilTagihan = parseInt(removeThousandSeparator(grand_total_price)) + parseInt(removeThousandSeparator(biaya_kirim));
        // $('#total_tagihan').val(addThousandSeparator(hasilTagihan));
        $('#total_tagihan').val(isNaN(hasilTagihan) ? 0 : addThousandSeparator(hasilTagihan));

    });


    $('#terbayar').on('keyup', function() {
        let terbayar = $('#terbayar').val();
        calculateChange(terbayar);

    });

    $(".metode").on("change", function() {

        const status = $(this).val()

        switch (status) {

            case "tunai":

                $("#parentPayment").addClass("d-none")
                $("#parentPayment").toggleClass("d-block")
                break;
            case "transfer":

                $("#parentPayment").addClass("d-block")
                $("#parentPayment").toggleClass("d-none")
                break;

        }

    })

    $('#checkout').on('click', function() {
        let afterResponse;
        Swal.fire({
            title: "Konfirmasi Pesanan?",
            text: "Apakah Pesanan tersebut sudah sesuai?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Pesanan Saya Sudah Sesuai"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('kasir/scan/checkPaymentKembalian') ?>",
                    data: $('#form_customer').serialize(),
                    dataType: "json",
                    success: function(responseCode) {
                        if (responseCode.err_code == 0) {
                            $.ajax({
                                type: "POST",
                                url: "<?= base_url('kasir/scan/doCheckout') ?>",
                                data: $('#form_customer').serialize(),
                                dataType: "json",
                                success: function(response) {
                                    console.log(response[0]);
                                    console.log(response[1]);
                                    if (response.err_code == 1) {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Oops...",
                                            text: "Data Gagal Diinputkan",
                                            timer: 1500
                                        });

                                        let alertError = `
                                        <div class="alert alert-danger" role="alert">
                                        ${response.message}
                                        </div>`;
                                        $('#message').html(alertError);
                                        $('#nama_cust').focus();
                                        afterResponse = 99;
                                        console.log(afterResponse);
                                    } else {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Berhasil",
                                            text: "Data Berhasil Diinputkan",
                                            timer: 1500
                                        });

                                        let alertSuccess = `
                                        <div class="alert alert-success" role="alert">
                                        ${response.message}
                                        </div>`;
                                        $('#message').html(alertSuccess);

                                        let transid = response.transactionid;

                                        let encryptUrl = base64Encode(transid);
                                        // let encryptUrl = md5Hash(transid);

                                        // window.location.href = "<?= base_url('kasir/scan/print_nota_pdf?transid=') ?>" + encryptUrl;

                                        //versi terbuka
                                        // window.location.href = "<?= base_url('kasir/scan/print_nota_raw?transid=') ?>" + encryptUrl;

                                        //open new tab make sense
                                        let printUrl = "<?= base_url('kasir/scan/print_nota_raw?transid=') ?>" + encryptUrl;
                                        window.open(printUrl, '_blank');

                                        //redirect ke kasir scan di halaman yang sekarang
                                        setTimeout(function() {
                                            let redirectUrlPage = "<?= base_url('kasir/scan') ?>";
                                            window.location.href = redirectUrlPage;
                                        }, 1500);

                                        afterResponse = 0;
                                        // console.log(afterResponse);
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: `${responseCode.message}`,
                                timer: 1500
                            });
                        }
                    }
                });


            }
        });

        if (afterResponse == 0) {
            window.setTimeout(function() {
                let redirectUrlPage = "<?= base_url('kasir/scan') ?>";
                window.location = redirectUrlPage;
            }, 1500);
        }



    });

    $("#bank_id").on("change", function() {

        const values = $(this)

        if (values.val()) {

            $.ajax({
                url: "<?= site_url('kasir/scan/rekening') ?>",
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

    function pageRedirect() {
        window.location.href = "<?= base_url('kasir/scan') ?>";
    }
</script>