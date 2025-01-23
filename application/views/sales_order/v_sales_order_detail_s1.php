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
                                                <input type="text" name="nama_cust" id="nama_cust" class="form-control" value="<?= $data_detail_order[0]["nama_cust"] ?>">
                                                <small class="text-danger" id="err_nama_cust"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="hp_cust">No hp : </label>
                                                <input type="text" name="hp_cust" id="hp_cust" class="form-control" value="<?= $data_detail_order[0]["hp_cust"] ?>">
                                                <small class="text-danger" id="err_hp_cust"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="alamat_cust">Alamat : </label>
                                                <textarea name="alamat_cust" id="alamat_cust" cols="30" rows="5" class="form-control"><?= $data_detail_order[0]["alamat_cust"] ?></textarea>
                                                <small class="text-danger" id="err_alamat_cust"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label for="tipe_pengiriman">Tipe pengiriman : </label>
                                                <select name="tipe_pengiriman" id="tipe_pengiriman" class="form-control">
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
                                                <small class="text-danger" id="err_tipe_pengiriman"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div class="form-group">
                                                <label for="biaya_kirim">Biaya pengiriman : </label>
                                                <input type="text" class="form-control" id="biaya_kirim" name="biaya_kirim" value="<?= $data_detail_order[0]["biaya_kirim"] ?>" placeholder="Biaya kirim">
                                                <small class="text-danger" id="err_biaya_kirim"></small>
                                                <input type="hidden" class="form-control" id="id_order" name="id_order" value="<?= $data_detail_order[0]["id_order"] ?>">
                                                <input type="hidden" class="form-control" id="kode_order" name="kode_order" value="<?= $this->secure->encrypt_url($data_detail_order[0]["kode_order"]) ?>">
                                            </div>
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
                                            <th>Stok</th>
                                            <th>Hapus</th>
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
                                                <td><input type="number" name="input_qty[]" class="input_qty form-control form-control-sm w-100" value="<?= $ddo["qty"] ?>" placeholder="Kuantitas" min="0"><small class="text-danger err_input_qty"></small></td>
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
                                                    <input type="hidden" name="input_diskon[]" class="input_diskon form-control" value="<?= htmlspecialchars($ddo["harga_potongan"]) ?>">
                                                    <input type="hidden" name="input_sum_diskon[]" class="input_sum_diskon form-control" value="<?= $sum_diskon ?>">
                                                </td>
                                                <td>
                                                    <small class="harga_total"><?= number_format($ddo["harga_total"], 0, ".", ".") ?></small>
                                                    <input type="hidden" name="input_harga_total[]" class="input_harga_total form-control" value="<?= $ddo["harga_total"] ?>">
                                                </td>
                                                <td>
                                                    <small class="stok_toko"><?= $ddo["stok_toko"] ?></small>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm fw-bold cancel">x</button>
                                                    <input type="hidden" name="input_id_harga[]" class="input_id_harga" value="<?= $ddo["harga_id"] ?>">
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
                                                <input type="hidden" class="form-control" id="input_total" value="<?= ($sum_subtotal - $sum_subtotal_diskon) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="border p-4 overflow-auto mb-2">
                                <legend class="float-none w-auto fw-bold">Barang toko</legend>
                                <table class="table table-striped w-100 datatables">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data_list_barang as $dlb) : ?>
                                            <tr>
                                                <td><?= $dlb["nama_barang"] ?></td>
                                                <td><?= number_format($dlb["harga_jual"], 0, ".", ".") ?></td>
                                                <td><?= $dlb["stok_toko"] ?></td>
                                                <th><button class="btn btn-primary btn-sm tambahBarang" data-id="<?= $dlb["id_harga"] ?>">+</button></th>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button class="btn btn-info text-white" id="konfirmasiPesanan">Konfirmasi pesanan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $("#data_sales_order").on("change", ".input_qty", function() {

                // cek kuantitas jika lebih kecil dari 0
                ($(this).val() <= 0 || $(this).val() == "") ? $(this).val(1): '';

                // parent element
                const tr = $(this).parent().parent()

                // jumlah kuantitas
                const qty = $(this).val()

                // field jumlah kuantitas
                const qtyField = $(this)

                // list kolom pada table
                const colDiskon = tr.find("td")[3]
                const colTotal = tr.find("td")[4]
                const colStok = tr.find("td")[5]
                const colAction = tr.find("td")[6]

                $.ajax({
                    url: "<?= site_url('kasir/sales_order/ubah') ?>",
                    type: "post",
                    data: {
                        id_harga: colAction.querySelector(".input_id_harga").value,
                        qty: qty,
                    },
                    dataType: "json",
                    beforeSend: function() {

                        // hapus jika terdapat elemen ul
                        if (colDiskon.querySelector("ul")) {
                            colDiskon.querySelector("ul").remove();
                        }

                        // disabled field qty
                        qtyField.prop("disabled", true)

                    },
                    success: function(res) {

                        if (res.status == "berhasil") {

                            // set variable sum diskon 
                            let sum_diskon = 0;

                            // set total
                            colTotal.querySelector(".harga_total").innerHTML = (parseInt(res.response.harga_jual) * qty).toLocaleString('id-ID');
                            colTotal.querySelector(".input_harga_total").value = (parseInt(res.response.harga_jual) * qty)

                            // set stok toko
                            colStok.querySelector(".stok_toko").innerHTML = (parseInt(res.response.stok_toko))

                            // set diskon
                            if (res.response.diskon != "[]") {

                                // convert string json menjadi object
                                const d = JSON.parse(res.response.diskon)
                                // buat elemen ul
                                const elemen_ul = document.createElement("ul")

                                // set field diskon
                                colDiskon.querySelector(".input_diskon").value = res.response.diskon

                                // tambahkan elemen ul ke kolom diskon
                                colDiskon.append(elemen_ul)

                                // lakukan perulangan fo untuk mengambil setiap diskon 
                                for (let i = 0; i < d.length; i++) {

                                    // buat elemen li
                                    const elemen_li = document.createElement("li")

                                    // buat elemen small
                                    const elemen_small = document.createElement("small")

                                    // buat text pada elemen small
                                    const text_small = document.createTextNode(`${d[i].nama_diskon} - ${parseInt(d[i].harga_potongan).toLocaleString('id-ID')}`);

                                    // tambahkan text kedalam elemen small
                                    elemen_small.append(text_small)

                                    // tambahkan elemen small kedalam elemen li
                                    elemen_li.append(elemen_small)

                                    // tambahkan elemen li kedalam elemen ul
                                    colDiskon.querySelector("ul").append(elemen_li)

                                    // tambahkan setiap harga potongan ke variable sum_diskon
                                    sum_diskon += parseInt(d[i].harga_potongan)
                                }

                            } else {

                                colDiskon.querySelector(".input_diskon").value = ""

                            }

                            // set value total diskon
                            colDiskon.querySelector(".input_sum_diskon").value = sum_diskon

                            // jalankan function hitung semua
                            hitungSemua()

                        } else {

                            // do swal

                        }

                        // enabled field qty
                        qtyField.prop("disabled", false)

                    }
                })

            })

            $("#data_sales_order").on("click", ".cancel", function() {

                // konfirmasi apakah barang mau dihilangkan
                if (confirm("Apakah anda yakin ingin menghapus item tersebut ? ")) {

                    // hapus baris
                    const tr = $(this).parent().parent()
                    tr.remove()

                    // set function hitung semua
                    hitungSemua()

                }

            })

            $("#biaya_kirim").on("keyup", function() {

                ($(this).val() < 0 || $(this).val() == "") ? $(this).val(0): $(this).val(parseInt($(this).val()));

                // set function hitung semua
                hitungSemua(true)

            })

            $(".tambahBarang").on("click", function() {

                // ambil attribute data id
                const id_harga = $(this).data("id")

                // set variable barang lama
                let barangLama = 0;

                // cek apakah item sudah pernah ditambahkan
                $(".input_id_harga").each(function() {

                    // cek apakah terdapat id_harga yang telah di tambahkan
                    if ($(this).val() == id_harga) {

                        // ambil parent dari elemen
                        const parent = $(this).parent().parent()

                        // buat variable hasClass
                        const hasClass = parent.find(".input_qty")

                        // set nilai terbaru dengan + 1 lalu trigger chage
                        hasClass.val(parseInt(hasClass.val()) + 1).trigger("change")

                        // tambah 1 barangLama
                        barangLama++;

                    }

                })

                // cek jika barang lama tidak ada
                if (!barangLama) {

                    $.ajax({

                        url: "<?= site_url('kasir/sales_order/tambah') ?>",
                        type: "post",
                        data: {
                            id_harga: id_harga
                        },
                        dataType: "json",
                        success: function(res) {

                            if (res.status == "berhasil") {

                                // set variable
                                let sum_diskon = 0;
                                let get_elemen_ul = '';

                                if (res.response.diskon != "[]") {

                                    // parsing string json menjadi object
                                    const d = JSON.parse(res.response.diskon)

                                    // buat elemen ul
                                    const elemen_ul = document.createElement("ul")

                                    // loop semua diskon
                                    for (let i = 0; i < d.length; i++) {

                                        // buat elemen li
                                        const elemen_li = document.createElement("li")

                                        // buat elemen small
                                        const elemen_small = document.createElement("small")

                                        // buat content pada elemen small
                                        const text_small = document.createTextNode(`${d[i].nama_diskon} - ${parseInt(d[i].harga_potongan).toLocaleString('id-ID')}`);

                                        // tambahkan text kedalam elemen small
                                        elemen_small.append(text_small)

                                        // tambahkan elemen small kedalam elemen li
                                        elemen_li.append(elemen_small)

                                        // tambahkan elemen li kedalam elemen ul
                                        elemen_ul.append(elemen_li)

                                        // jumlah setiap nominal diskon
                                        sum_diskon += parseInt(d[i].harga_potongan)
                                    }

                                    // tampung elemen ul ke dalam variable get_elemen_ul
                                    get_elemen_ul = elemen_ul

                                }

                                // buat row col
                                const elemen = `<tr>
                                                    <td><small>${res.response.nama_barang}</small></td>
                                                    <td><small>${parseInt(res.response.harga_jual).toLocaleString("id-ID")}</small></td>
                                                    <td><input type="number" name="input_qty[]" class="input_qty form-control form-control-sm w-100" value="1" placeholder="Kuantitas" min="0"><small class="text-danger err_input_qty"></small></td>
                                                    <td>
                                                        ${(get_elemen_ul) ? get_elemen_ul.outerHTML : ''}
                                                        <input type="hidden" name="input_diskon[]" class="input_diskon form-control" value="${(res.response.diskon != "[]") ? res.response.diskon_js : ""}">
                                                        <input type="hidden" name="input_sum_diskon[]" class="input_sum_diskon form-control" value="${sum_diskon}">
                                                    </td>
                                                    <td>
                                                        <small class="harga_total">${(1 * parseInt(res.response.harga_jual)).toLocaleString("id-ID")}</small>
                                                        <input type="hidden" name="input_harga_total[]" class="input_harga_total form-control" value="${(1 * parseInt(res.response.harga_jual))}">
                                                    </td>
                                                    <td>
                                                        <small class="stok_toko">${res.response.stok_toko}</small>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm fw-bold cancel">x</button>
                                                        <input type="hidden" name="input_id_harga[]" class="input_id_harga" value="${res.response.id_harga}">
                                                    </td>
                                                </tr>`

                                // tambahkan variable elemen kedalam elemen data_sales_order
                                $("#data_sales_order").append(elemen).ready(function() {

                                    // set function hitung semua
                                    hitungSemua()

                                })

                            } else {

                                // do swal
                            }

                        }

                    })

                }

            })

            $("#konfirmasiPesanan").on("click", function() {

                if (confirm("Apakah anda ingin mengkonfirmasi pesanan ini ?")) {

                    const nama_cust = $("#nama_cust").val()
                    const hp_cust = $("#hp_cust").val()
                    const alamat_cust = $("#alamat_cust").val()
                    const tipe_pengiriman = $("#tipe_pengiriman").val()
                    const biaya_kirim = $("#biaya_kirim").val()
                    const id_order = $("#id_order").val()
                    const kode_order = $("#kode_order").val()
                    // const input_qty = $("input[name='input_qty[]']").val()
                    // const input_diskon = $("input[name='input_diskon[]']").val()
                    // const input_sum_diskon = $("input[name='input_sum_diskon[]']").val()
                    // const input_harga_total = $("input[name='input_harga_total[]']").val()
                    // const input_id_harga = $("input[name='input_id_harga[]']").val()
                    const input_qty = $("input[name='input_qty[]']").map(function() {
                        return $(this).val();
                    }).get();
                    const input_diskon = $("input[name='input_diskon[]']").map(function() {
                        return $(this).val();
                    }).get();
                    const input_sum_diskon = $("input[name='input_sum_diskon[]']").map(function() {
                        return $(this).val();
                    }).get();
                    const input_harga_total = $("input[name='input_harga_total[]']").map(function() {
                        return $(this).val();
                    }).get()
                    const input_id_harga = $("input[name='input_id_harga[]']").map(function() {
                        return $(this).val();
                    }).get();
                    const input_total = $("#input_total").val()

                    $.ajax({
                        url: "<?= site_url('kasir/sales_order/submit') ?>",
                        type: "post",
                        data: {
                            nama_cust: nama_cust,
                            hp_cust: hp_cust,
                            alamat_cust: alamat_cust,
                            tipe_pengiriman: tipe_pengiriman,
                            biaya_kirim: biaya_kirim,
                            id_order: id_order,
                            kode_order: kode_order,
                            input_qty: input_qty,
                            input_diskon: input_diskon,
                            input_sum_diskon: input_sum_diskon,
                            input_harga_total: input_harga_total,
                            input_id_harga: input_id_harga,
                            input_total: input_total,
                        },
                        dataType: "JSON",
                        beforeSend: function() {

                            $("#konfirmasiPesanan").prop("disabled", true);
                            $("#konfirmasiPesanan").html("Loading...");
                            $("#err_nama_cust").html("")
                            $("#err_hp_cust").html("")
                            $("#err_alamat_cust").html("")
                            $("#err_tipe_pengiriman").html("")
                            $("#err_id_order").html("")
                            $("#err_kode_order").html("")
                            $("#err_input_total").html("")
                            $(".err_input_qty").html("")

                        },
                        success: function(res) {

                            if (res.status == "error") {

                                alert("Terdapat field yang tidak sesuai!")

                                if (res.err_nama_cust) {
                                    $("#err_nama_cust").html(res.err_nama_cust)
                                }

                                if (res.err_hp_cust) {
                                    $("#err_hp_cust").html(res.err_hp_cust)
                                }

                                if (res.err_alamat_cust) {
                                    $("#err_alamat_cust").html(res.err_alamat_cust)
                                }

                                if (res.err_tipe_pengiriman) {
                                    $("#err_tipe_pengiriman").html(res.err_tipe_pengiriman)
                                }

                                if (res.err_id_order) {
                                    $("#err_id_order").html(res.err_id_order)
                                }

                                if (res.err_kode_order) {
                                    $("#err_kode_order").html(res.err_kode_order)
                                }

                                if (res.err_input_total) {
                                    $("#err_input_total").html(res.err_input_total)
                                }

                                if (res.response) {

                                    const obj = JSON.parse(res.response)

                                    for (let i = 0; i < obj.length; i++) {

                                        const elemenInput = $('input[value="' + obj[i].id_harga + '"].input_id_harga');

                                        if (elemenInput.length > 0) {

                                            // let htmlElemenInput = elemenInput[0].outerHTML;

                                            // Akses parent dari elemen input
                                            const parentElemenInput = elemenInput.parent().parent();

                                            const col2 = parentElemenInput.find("td")[2];

                                            const col5 = parentElemenInput.find("td")[5];

                                            col2.querySelector(".err_input_qty").innerHTML = obj[i].message
                                            col5.querySelector(".stok_toko").innerHTML = obj[i].stok_toko


                                        }
                                    }

                                }

                                $("#konfirmasiPesanan").prop("disabled", false);
                                $("#konfirmasiPesanan").html("Konfirmasi pesanan");

                            } else {

                                // alert(res.response);
                                window.location.reload()

                            }

                        }

                    })

                }

            })

            function hitungSemua(is_pengiriman = false) {

                // set variable
                let biaya_diskon = 0;
                let biaya_subtotal = 0;

                // sum biaya kirim
                $("#biaya-kirim").html(parseInt($("#biaya_kirim").val()).toLocaleString("id-ID"))

                // sum diskon
                $(".input_sum_diskon").each(function(i, e) {
                    biaya_diskon += parseInt(e.value)
                })
                $("#biaya-diskon").html(biaya_diskon.toLocaleString("id-ID"))

                // sum subtotal
                $(".input_harga_total").each(function(i, e) {
                    biaya_subtotal += parseInt(e.value)
                })
                $("#subtotal").html(biaya_subtotal.toLocaleString("id-ID"))

                // cek jika mengisi pengiriman
                // if (is_pengiriman) {
                //     if ((biaya_subtotal - biaya_diskon) < $("#biaya_kirim").val()) {
                //         alert("Biaya pengiriman lebih besar dari total")
                //         $("#biaya_kirim").val(0)
                //         $("#biaya-kirim").html(0)
                //     }
                // }

                // sum total
                $("#total").html(((biaya_subtotal - biaya_diskon) + parseInt($("#biaya_kirim").val())).toLocaleString("id-ID"))
                $("#input_total").val((biaya_subtotal - biaya_diskon))

                return;
            }

        })
    </script>