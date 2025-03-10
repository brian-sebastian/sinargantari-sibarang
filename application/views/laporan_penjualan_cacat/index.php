<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <h4>Pilih Rentang Tanggal Laporan Penjualan Barang Cacat</h4>
                            <br>
                            <form id="cari" method="post">
                                <div class="row align-items-center">
                                    <?php if (!$this->session->userdata("toko_id")) : ?>
                                        <div class="col-md-6 mb-3">
                                            <label for="toko_id">Toko : </label>
                                            <select name="toko_id" id="toko_id" class="form-control select2">
                                                <option value="">Semua</option>
                                                <?php foreach ($data_toko as $dtok) : ?>
                                                    <option value="<?= $dtok["id_toko"] ?>"><?= $dtok["nama_toko"] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    <?php else : ?>
                                        <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                                    <?php endif ?>
                                    <div class="col-md-6 mb-3">
                                        <label for="toko_id">Barang : </label>
                                        <select name="barang_id" id="barang_id" class="form-control select2">
                                            <option value="">Semua</option>
                                            <?php foreach ($data_barang as $dbar) : ?>
                                                <option value="<?= $dbar["id_barang"] ?>"><?= $dbar["nama_barang"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class='fs-4 bx bx-calendar'></i></span>
                                            <input class="form-control is-valid rangepicker" id="tanggal" style="background-color: white;" value="" type="text" autofocus required placeholder="Pilih Tanggal" name="tanggal">
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <button type="button" class="btn btn-sm text-white" id="btn_cari" style="background-color: #213363;">Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-row-reverse">
                                <div class="page_action mb-4">
                                    <a class="btn btn-sm btn-info" id="btn_excel" href="javascript:void(0)"><img src="<?= base_url() ?>assets/be/img/excel.png" alt="Export Excel" width="40" height="40"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-primary fw-bold" id="total_penjualan">Total penjualan : 0</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Toko</th>
                                    <th>Kode order</th>
                                    <th>Kode transaksi</th>
                                    <th>Nama barang</th>
                                    <th>Harga satuan pokok</th>
                                    <th>Harga satuan jual</th>
                                    <th>Qty</th>
                                    <th>Total harga pokok</th>
                                    <th>Total harga jual</th>
                                    <th>Total diskon</th>
                                    <th>Total keuntungan</th>
                                    <th>Tanggal beli</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            initializeTable("server-side", {
                ajax: {
                    url: "<?= site_url('barang_cacat/lp_penjualan_cacat/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.tanggal = $('#tanggal').val();
                        data.barang_id = $('#barang_id').val();
                        data.toko_id = $("#toko_id").val();
                    },
                    dataType: "json",
                },
                drawCallback: function(setting) {

                    $.post("<?= site_url('barang_cacat/lp_penjualan_cacat/total') ?>", {

                        tanggal: $('#tanggal').val(),
                        barang_id: $('#barang_id').val(),
                        toko_id: $("#toko_id").val(),

                    }, function(res) {

                        $("#total_penjualan").html(`Total penjualan Rp ${parseInt(res.totalPenjualan).toLocaleString("id-ID")}`)

                    }, "json")

                }
            })

        })

        $("#btn_cari").on("click", function() {
            $("#btn_cari").prop("disabled", true)
            $("#btn_cari").html("Mohon tunggu...")
            setTimeout(function() {

                dynamicTable.draw()
                $("#btn_cari").prop("disabled", false)
                $("#btn_cari").html("Cari")

            }, 1000)

        })

        $("#btn_excel").on("click", function() {

            const obj = {
                tanggal: $('#tanggal').val(),
                barang_id: $("#barang_id").val(),
                toko_id: $("#toko_id").val()
            };

            const encodeData = encodeURIComponent(JSON.stringify(obj));

            window.location = "<?= site_url('barang_cacat/lp_penjualan_cacat/excel') ?>?field=" + encodeData;
        })
    </script>