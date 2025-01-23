<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Pilih Rentang Tanggal Laporan Pendapatan</h4>
                            <br>
                            <form>
                                <div class=" row align-items-center">
                                    <div class="col-12 mb-3">
                                        <select class="form-control select2" name="toko_id" id="toko_id">
                                            <option value="">-- Pilih Toko --</option>
                                            <?php foreach ($toko as $dtToko) : ?>
                                                <option value="<?= $dtToko['id_toko'] ?>"><?= $dtToko['nama_toko'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class='fs-4 bx bx-calendar'></i></span>
                                            <input class="form-control is-valid rangepicker" id="tanggal" style="background-color: white;" value="" type="text" autofocus required placeholder="Pilih Tanggal" name="tanggal">
                                        </div>
                                    </div>
                                    <div class="col-2 text-left ">
                                        <button type="button" class="btn btn-sm text-white" id="btn_cari" style="background-color: #213363;">Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-danger alert-dismissible " id="pesan-eror" role="alert">
        Data Tidak Dapat Ditampilkan. Kamu Belum Memilih Rentang Tanggal. Silahkan Memilih Rentang Tanggal
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>

    <!-- Basic Layout -->
    <div class="row d-none" id="row-table">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">

                            <h6 class="text-primary fw-bold" id="rangeTanggal">Per Tanggal : 0</h6>
                            <h6 class="text-primary fw-bold" id="total_pendapatan">Total Keuntungan : 0</h6>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-row-reverse">
                                <div class="page_action mb-4">
                                    <a class="btn btn-sm btn-info" id="btn_excel" href="javascript:void(0)"><img src="<?= base_url() ?>assets/be/img/excel.png" alt="Export Excel" width="40" height="40"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Harga Pokok</th>
                                    <th>Stok Sekarang</th>
                                    <th>Harga Jual</th>
                                    <th>Qty</th>
                                    <th>Diskon</th>
                                    <th>Total</th>
                                    <th>Total Satuan Keuntungan</th>
                                    <th>Total Keuntungan</th>
                                    <th>Omzet</th>
                                    <th>Omzet Bersih</th>
                                    <th>Tanggal Pendapatan</th>
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
                    url: "<?= site_url('laporan/pendapatan/ajax') ?>",
                    type: "post",
                    data: function(data) {

                        data.tanggal = $('#tanggal').val()
                        data.toko_id = $('#toko_id').val()

                    },
                    dataType: "json",
                },
                drawCallback: function(settings) {

                    const toko_id = $('#toko_id').val();
                    const tanggal = $('#tanggal').val();

                    if (toko_id) {
                        $('#row-table').removeClass('d-none');
                        $('#pesan-eror').addClass('d-none');
                    } else {
                        $('#row-table').addClass('d-none');
                    }

                    if (tanggal) {
                        $('#row-table').removeClass('d-none');
                        $('#pesan-eror').addClass('d-none');
                    } else {
                        $('#row-table').addClass('d-none');
                    }



                    $.post("<?= site_url('laporan/pendapatan/ajax/total') ?>", {
                        tanggal: $("#tanggal").val(),
                        toko_id: $('#toko_id').val()

                    }, function(res) {

                        $('#rangeTanggal').html(`Per Tanggal : ${res.rangeTanggal}`)
                        $("#total_pendapatan").html(`Total Pendapatan Rp ${parseInt(res.totalPendapatan).toLocaleString("id-ID")}`)

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
                toko_id: $('#toko_id').val(),
            };

            const encodeData = encodeURIComponent(JSON.stringify(obj));

            window.location = "<?= site_url('laporan/pendapatan/excel') ?>?field=" + encodeData;
        })
    </script>