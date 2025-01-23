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


    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="card">

                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5>Scan Barcode Barang</h5>
                        </div>
                    </div>
                </div>



                <?php if ($toko_id) : ?>
                    <div class="card-body">
                        <form action="" method="post" id="barcodeForm">
                            <!-- <input type="text" class="form-control" name="barcode" id="barcode" placeholder="Kode barcode" autofocus> -->
                            <div class="ui-widget">
                                <input type="text" class="form-control barcodeAutoCompleteScan" name="barcode" id="barcode" placeholder="Kode barcode" autofocus>
                            </div>

                            <input type="text" name="toko_id" id="toko_id" value="<?= $toko_id ?>" class="form-control" hidden readonly>
                            <button type="button" name="submit" id="submit" class="add_cart btn btn-primary btn-sm mt-2">Add Cart</button>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            Maaf kamu tidak ada akses untuk melakukan scan, hanya kasir toko yang memiliki akses.
                            Jika Kamu Super Admin silahkan set toko untuk scan
                        </div>
                        <form action="" method="get">
                            <select name="toko_id" id="toko_id" class="form-control">
                                <?php foreach ($tokonotmp as $tnmp) : ?>
                                    <option value="<?= $tnmp['id_toko'] ?>"><?= $tnmp['nama_toko'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Set Toko</button>
                        </form>
                    </div>
                <?php endif ?>

            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div id="pesan-error">

                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5>Cart</h5>
                        </div>
                    </div>
                </div>



                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <!-- <table class="table table-striped dt-responsive nowrap datatables py-1 px-1"> -->
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_cart">

                            </tbody>
                        </table>
                        <div id="action_detail_cart">

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php if ($this->session->userdata("toko_id")) : ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        List barang toko
                    </div>
                    <div class="card-body">
                        <table class="table table-striped dt-responsive nowrap py-1 datatables px-1">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">Action</th>
                                    <th style="width: 50px;">Nama barang</th>
                                    <!-- <th>Kode barang</th> -->
                                    <th>Barcode</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($barang_toko as $bt) : ?>
                                    <tr>
                                        <td><button data-barcode="<?= $bt["barcode_barang"] ?>" class="btn btn-sm btn-warning myChoice">Pilih</button></td>
                                        <td><?= $bt["nama_barang"] ?></td>
                                        <!-- <td>
                                            <? //= $bt["kode_barang"] 
                                            ?>
                                        </td> -->
                                        <td>
                                            <?= $bt["barcode_barang"]
                                            ?>
                                        </td>
                                        <td><?= $bt["stok_toko"] ?></td>
                                    </tr>
                                <?php $no++;
                                endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<!-- ONSCAN JS -->
<script src="<?= base_url() ?>assets/be/vendor/libs/onscan_js/onscan.min.js"></script>

<!--  SCRIPT FOR CONTROLLER SCAN -->
<script>
    //https://www.npmjs.com/package/onscan.js
    // Initialize with options
    onScan.attachTo(document, {
        // enter-key expected at the end of a scan
        suffixKeyCodes: [13],
        // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
        reactToPaste: true,
        // Alternative to document.addEventListener('scan')
        onScan: function(sCode, iQty) {
            // console.log('Scanned: ' + iQty + 'x ' + sCode);
            $("#barcode").val(sCode);
            $('#barcode').trigger('change');
            $('#submit').trigger('click');

        },
        onKeyDetect: function(iKeyCode) {
            // output all potentially relevant key events - great for debugging!
            // console.log('Pressed: ' + iKeyCode);
        },

    });

    // Simulate a scan programmatically - e.g. to test event handlers
    // onScan.simulate(document, '1234567890123');
    onScan.simulate(document, sCode);

    // Simulate raw keyCodes
    onScan.simulate(document, [48, 49, 50]);

    // Simulate keydown events
    onScan.simulate(document, [{
        keyCode: 80,
        key: 'P',
        shiftKey: true
    }, {
        keyCode: 49,
        key: '1'
    }]);

    // Change options on-the-fly
    onScan.setOptions(document, {
        singleScanQty: 5 // change the quantity to 5 for every scan
    });

    // Remove onScan.js from a DOM element completely
    onScan.detachFrom(document);
</script>

<script type="text/javascript">
    $(document).ready(function() {

        initializeTable("client-side", {
            "ordering": false,
            "searching": false,
            "info": false,
            "lengthChange": false,
            "paging": false,
        })

        let toastMixin = Swal.mixin({
            toast: true,
            icon: 'success',
            title: 'General Title',
            animation: false,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        $('.add_cart').click(function() {
            let barcode = $('#barcode').val();
            let toko_id = $('#toko_id').val();
            $.ajax({
                url: "<?= base_url('kasir/scan/get_data_barang_ajax') ?>",
                method: "POST",
                data: {
                    barcode: barcode,
                    toko_id: toko_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.err_code == 0) {
                        Swal.fire({
                            title: "Success",
                            text: `${response.message}`,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('#detail_cart').html(response.view);
                        $('#action_detail_cart').html(response.view_action);
                        $('#barcode').val('');
                        $('#barcode').empty();
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: `${response.message}`,
                            icon: "error",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                        $('#barcode').val('');
                        $('#barcode').empty();
                    }
                },
                error: function(result) {
                    Swal.fire({
                        title: "Error",
                        text: "Product Not Registered",
                        icon: "error",
                        timer: 1500,
                        timerProgressBar: true,
                    });
                    $('#barcode').val('');
                    $('#barcode').empty();
                },
            });
        });

        // Load shopping cart
        $('#detail_cart').load("<?= base_url('kasir/scan/load_cart'); ?>");
        $('#action_detail_cart').load("<?= base_url('kasir/scan/is_show_action_detail_cart_check'); ?>");


        //Hapus Item Cart
        $(document).on('click', '.hapus_cart', function() {
            let row_id = $(this).attr("id");
            //mengambil row_id dari artibut id
            Swal.fire({
                title: "Apakah Yakin Menghapus dari keranjang",
                text: "Data Yang dihapus dari keranjang tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#db2121",
                cancelButtonColor: "#7a7a7a",
                confirmButtonText: "Hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('kasir/scan/hapus_cart'); ?>",
                        method: "POST",
                        data: {
                            row_id: row_id
                        },
                        success: function(data) {
                            console.log(data)
                            Swal.fire({
                                title: "Deleted!",
                                text: "Data Telah Dihapus dari keranjang",
                                icon: "success"
                            });
                            $('#detail_cart').html(data);

                            console.log($("#detail_cart").find("tr"))

                            if ($("#detail_cart").find("tr").length <= 1) {

                                $("#action_detail_cart").empty();

                            }

                        }
                    });

                }
            });

        });

        //Destroy Item Cart
        $(document).on('click', '#button_clear_cart', function() {

            Swal.fire({
                title: "Apakah Yakin Hapus Semua Keranjang",
                text: "Data Yang dihapus dari keranjang tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#db2121",
                cancelButtonColor: "#7a7a7a",
                confirmButtonText: "Hapus"
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= base_url('kasir/scan/destroy_cart'); ?>",
                        method: "POST",
                        success: function(data) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Semua Data Telah Dihapus dari keranjang",
                                icon: "success"
                            });
                            $('#detail_cart').html(data);
                            $('#action_detail_cart').empty();
                        }
                    });


                }
            });



        });

        $("body").on("change", ".myQty", function() {

            let objs = $(this);

            (objs.val() <= 0) ? objs.val(1): ''

            $.ajax({

                url: "<?= site_url('kasir/scan/cek_stok') ?>",
                type: "post",
                data: {
                    id_harga: objs.data("id"),
                    qty: objs.val(),
                    price: parseFloat(objs.data("price"))
                },
                dataType: "json",
                success: function(result) {

                    if (result.response) {

                        objs.data("old", objs.val())
                        objs.parent().parent().parent().find("td")[3].innerHTML = result.subtotal
                        $("#grand_total").html(`Rp ${result.grand_total}`)

                    } else {

                        alert("Barang diluar dari stok")
                        objs.val(objs.data("old"))
                    }

                }

            })
        })

        $("body").on("click", ".isMinus", function() {

            // do minus
            const inputObject = $(this).parent().find("input")
            const valueNow = parseInt(inputObject.val()) - 1;

            if (valueNow > 0) {

                inputObject.val(valueNow).trigger("change")

            } else {

                alert("Quantity Minimal 1")
            }

        })

        $("body").on("click", ".isPlus", function() {

            // do minus
            const inputObject = $(this).parent().find("input")
            const valueNow = parseInt(inputObject.val()) + 1;
            inputObject.val(valueNow).trigger("change")
        })

        $("body").on('click', ".myChoice", function() {

            const barcode = $(this).data("barcode");
            $("#barcode").val(barcode);
            $('#barcode').trigger('change');
            $('#submit').trigger('click');
        })
    });
</script>