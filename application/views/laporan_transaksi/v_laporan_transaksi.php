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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#cari_modal">Form pencarian</button>
                    <a href="#" class="btn btn-sm btn-info" id="export_excel"><img src="<?= base_url() ?>assets/be/img/excel.png" alt="<?= base_url() ?>assets/be/img/excel.png" width="40px"></a>
                </div>
                <div class="card-header">
                    <?php if ($this->session->userdata("role_id") == 2) : ?>
                        <h6 class="text-primary fw-bold" id="total_transaksi">Total transaksi : 0</h6>
                    <?php endif ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Kode transaksi</th>
                                    <th>Kode order</th>
                                    <th>Nama toko</th>
                                    <th>Nama customer</th>
                                    <th>Tipe order</th>
                                    <th>Detail Order</th>
                                    <th>Total diskon</th>
                                    <th>Total setelah diskon</th>
                                    <th>Total biaya kirim</th>
                                    <th>Total tagihan</th>
                                    <th>Terbayar</th>
                                    <th>Kembalian</th>
                                    <th>Tipe transaksi</th>
                                    <th>Tanggal transaksi</th>
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

    <!-- Modal detail barang -->
    <div class="modal fade" id="detail_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-secondary">Detail <?= strtolower($title) ?> barang</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="showDetail">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal detail barang -->

    <!-- Modal cari -->
    <div class="modal fade" id="cari_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="form_cari" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="text-secondary">Cari <?= strtolower($title) ?></h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <?php if (!$this->session->userdata("toko_id")) : ?>
                                    <label for="toko_id" class="form-label">Toko</label>
                                    <select name="toko_id" id="toko_id" class="form-control select2-cari-modal" style="width: 100%;">
                                        <option value="">-- pilih --</option>
                                        <?php foreach ($data_toko as $dt) : ?>
                                            <option value="<?= $dt["id_toko"] ?>"><?= $dt["nama_toko"] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php else : ?>
                                    <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                                <?php endif ?>
                                <small class="text-danger" id="err_toko_id"></small>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="kode_order" class="form-label">Kode order</label>
                                <input type="text" name="kode_order" id="kode_order" class="form-control" placeholder="Kode order">
                                <small class="text-danger" id="err_kode_order"></small>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="kode_transaksi" class="form-label">Kode transaksi</label>
                                <input type="text" name="kode_transaksi" id="kode_transaksi" class="form-control" placeholder="Kode transaksi">
                                <small class="text-danger" id="err_kode_transaksi"></small>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="nama_cust" class="form-label">Nama customer</label>
                                <input type="text" name="nama_cust" id="nama_cust" class="form-control" placeholder="Nama customer">
                                <small class="text-danger" id="err_nama_cust"></small>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="tipe_order" class="form-label">Tipe order</label>
                                <select name="tipe_order" id="tipe_order" class="form-control select2-cari-modal" style="width: 100%;">
                                    <option value="">-- pilih --</option>
                                    <option value="Marketplace">Marketplace</option>
                                    <option value="Kasir">Kasir</option>
                                </select>
                                <small class="text-danger" id="err_tipe_order"></small>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="tipe_transaksi" class="form-label">Tipe transaksi</label>
                                <select name="tipe_transaksi" id="tipe_transaksi" class="form-control select2-cari-modal" style="width: 100%;">
                                    <option value="">-- pilih --</option>
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                                <small class="text-danger" id="err_tipe_transaksi"></small>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label for="created_at" class="form-label">Range tanggal transaksi</label>
                                <input type="text" name="created_at" id="created_at" class="form-control rangepicker" placeholder="Range tanggal transaksi">
                                <small class="text-danger" id="err_created_at"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="btn_cari">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal cari -->

    <script>
        $(function() {
            initializeTable("server-side", {
                ajax: {
                    url: "<?= site_url('laporan/transaksi/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.toko_id = $("#toko_id").val()
                        data.kode_order = $("#kode_order").val()
                        data.kode_transaksi = $("#kode_transaksi").val()
                        data.nama_cust = $("#nama_cust").val()
                        data.tipe_order = $("#tipe_order").val()
                        data.tipe_transaksi = $("#tipe_transaksi").val()
                        data.created_at = $("#created_at").val()
                    },
                    dataType: "json",
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0]
                }],
                drawCallback: function(settings) {

                    $.post("<?= site_url('laporan/transaksi/ajax/total') ?>", {

                        toko_id: $("#toko_id").val(),
                        kode_order: $("#kode_order").val(),
                        kode_transaksi: $("#kode_transaksi").val(),
                        nama_cust: $("#nama_cust").val(),
                        tipe_order: $("#tipe_order").val(),
                        tipe_transaksi: $("#tipe_transaksi").val(),
                        created_at: $("#created_at").val(),

                    }, function(res) {

                        $("#total_transaksi").html(`Total transaksi Rp ${parseInt(res.totalTransaksi).toLocaleString("id-ID")}`)

                    }, "json")
                }
            })

            $("table").on("click", ".itsDetail", function() {

                const kode_order = $(this).data("order");

                $.ajax({
                    url: "<?= site_url('laporan/transaksi/detail_barang') ?>",
                    type: "POST",
                    data: {
                        kode_order: kode_order
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            $('#detail_modal').modal('show');
                            $("#showDetail").html(response.view);
                        } else {
                            alert(response.message)
                            $('#detail_modal').modal('hidden');
                        }

                    }
                });

            })

            $("#btn_cari").on("click", function() {

                $("#btn_cari").prop("disabled", true)
                $("#btn_cari").html("Mohon tunggu...")

                setTimeout(function() {

                    dynamicTable.draw()
                    $("#btn_cari").prop("disabled", false)
                    $("#btn_cari").html("Cari")
                    $('#cari_modal').modal('hide')

                }, 1000)

            })

            $("#export_excel").on("click", function() {

                const obj = {

                    toko_id: $("#toko_id").val(),
                    kode_order: $("#kode_order").val(),
                    kode_transaksi: $("#kode_transaksi").val(),
                    nama_cust: $("#nama_cust").val(),
                    tipe_order: $("#tipe_order").val(),
                    tipe_transaksi: $("#tipe_transaksi").val(),
                    created_at: $("#created_at").val(),

                }

                const encodedData = encodeURIComponent(JSON.stringify(obj));
                window.location = "<?= site_url("laporan/transaksi/export/excel") ?>?field=" + encodedData;

            })

        })
    </script>