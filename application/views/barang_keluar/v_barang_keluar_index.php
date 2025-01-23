<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>
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


    <div class="row" id="pilih-toko">
        <div class="col-xl">

            <?php if ($this->session->userdata('toko_id') == '') : ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4>Pilih Toko</h4>
                                <br>
                                <form>
                                    <div class=" row align-items-center">
                                        <div class="col-10">
                                            <select class="form-control select2" name="toko_id" id="toko_id">
                                                <option value="">-- Pilih Toko --</option>
                                                <?php foreach ($toko as $dtToko) : ?>
                                                    <?php $select = ($this->session->userdata('toko_id') == $dtToko['id_toko']) ? 'selected' : ''; ?>
                                                    <option value="<?= $dtToko['id_toko'] ?>" <?= $select ?>><?= $dtToko['nama_toko'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
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
            <?php endif; ?>

        </div>
    </div>

    <?php if ($this->session->userdata('toko_id') == '') : ?>

        <div class="alert alert-danger alert-dismissible " id="pesan-eror" role="alert">
            Data Tidak Dapat Ditampilkan. Kamu Belum Memilih Toko. Silahkan Memilih Toko
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>

    <?php endif; ?>

    <!-- Basic Layout -->
    <div class="row d-none" id="row-table">
        <div class="col-xl">
            <div class="card mb-4">

                <!-- 
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#tambah_modal">
                            <span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah
                        </button>
                    </div>
                -->

                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table id="dynamicTable" class="table table-striped dt-responsive nowrap py-1 px-1 w-100">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Nama Toko</th>
                                    <th class="text-center">Jenis Keluar</th>
                                    <th class="text-center">Jmlh Keluar</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    </>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="tambah_modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Tambah Barang Keluar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="form_tambah" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <label for="type" class="form-label">Data Request</label>
                                            <select name="request_id" id="request_id" class="form-select" style="width: 100%;">
                                                <?php if ($data_request != 0) { ?>
                                                    <option value="">--Pilih--</option>
                                                    <?php foreach ($data_request as $dr) : ?>
                                                        <option value="<?= $dr["id_request"] ?>" toko_id="<?= $dr["id_request"] ?>">
                                                            <?= $dr["kode_request"] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                <?php } else { ?>
                                                    <option value="">--Pilih--</option>
                                                <?php } ?>
                                            </select>
                                            <small class="text-danger" id="err_request_id"></small>
                                        </div>
                                        <div class="col-8 mb-3">
                                            <table class="table table-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-center">Kode Barang</th>
                                                        <th scope="col" class="text-center">Nama Barang</th>
                                                        <th scope="col" class="text-center">QTY Request</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbl_alat">
                                                    <tr>
                                                        <th scope="row" class="text-center" colspan="3">
                                                            <div class="mb-2">
                                                                <p class="contact-name text-dark fs-13 mb-1">Item Barang Kosong</p>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label for="type" class="form-label">Barang Toko</label>
                                            <select name="harga_id" id="id_harga_keluar" class="form-select" style="width: 100%;">
                                                <?php if ($data_barang != 0) { ?>
                                                    <option value="">--Pilih--</option>
                                                    <?php foreach ($data_barang as $db) : ?>
                                                        <option value="<?= $db["id_harga"] ?>">
                                                            <?= $db["nama_toko"] ?> / <?= $db["nama_barang"] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                <?php } else { ?>
                                                    <option value="">--Pilih--</option>
                                                <?php } ?>
                                            </select>
                                            <small class="text-danger" id="err_id_harga_keluar"></small>
                                        </div>
                                        <div class="col-2 mb-3">
                                            <label for="type" class="form-label">Satuan Barang</label>
                                            <p class="contact-born text-muted satuan"></p>
                                        </div>
                                        <div class="col-2 mb-3">
                                            <label for="type" class="form-label">Jumlah Keluar</label>
                                            <input type="number" id="jml_keluar" name="jml_keluar" class="form-control" />
                                            <small class="text-danger" id="err_jml_keluar"></small>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <label for="type" class="form-label">Jenis Keluar</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" value="DISTRIBUSI" id="jenis_keluar" name="jenis_keluar" checked="checked">
                                                    <label class="form-check-label" for="jenis_keluar">DISTRIBUSI</label>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="col-5 mb-3">
                                            <label for="bukti_keluar" class="form-label">Bukti Keluar</label>
                                            <input class="form-control" type="file" name="bukti_keluar" id="bukti_keluar" />
                                            <small class="text-danger" id="err_bukti_keluar"></small>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btn_tambah">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Tambah -->

            </div>
        </div>
    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="lihatKeluar_Modal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Detail Barang Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="lihatKeluar">
                </div>
            </div>
        </div>
    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="ubahKeluar_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Barang Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_ubah_keluar" method="post">
                    <div class="modal-body" id="ubahKeluar">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn_ubah">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $("#btn_cari").on("click", function() {
            $("#btn_cari").prop("disabled", true)
            $("#btn_cari").html("Mohon tunggu...")
            setTimeout(function() {

                dynamicTable.draw()

                $("#btn_cari").prop("disabled", false)
                $("#btn_cari").html("Cari")

            }, 1000)

        })

        $(function() {

            initializeTable("server-side", {
                "ajax": {
                    url: "<?= site_url('barang/keluar/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.toko_id = $('#toko_id').val()

                    },
                    dataType: "json",
                },
                drawCallback: function(settings) {

                    const toko_id = $('#toko_id').val();
                    const toko = '<?= $this->session->userdata('toko_id') ?>';

                    if (toko.length > 0) {
                        $('#pesan-eror').addClass('d-none');
                        $('#pilih-toko').addClass('d-none');
                        $('#row-table').removeClass('d-none');
                    } else if (toko_id) {
                        $('#row-table').removeClass('d-none');
                        $('#pesan-eror').addClass('d-none');
                    } else {
                        $('#row-table').addClass('d-none');
                    }

                }
            })

            $("#btn_tambah").on("click", function() {

                const data_form = $("#form_tambah").serialize()

                $.ajax({

                    url: "<?= site_url("barang/keluar/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_request_id").html("")
                        $("#err_id_harga_keluar").html("")
                        $("#err_jml_keluar").html("")
                        $("#err_bukti_keluar").html("")

                        // empty element error
                        $("#request_id").val("")
                        $("#request_id").trigger("change")
                        $("#id_harga_keluar").val("")
                        $("#id_harga_keluar").trigger("change")
                        $("#jml_keluar").val("")
                        $("#bukti_keluar").val("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_request_id) {
                                $("#err_request_id").html(result.err_request_id)
                            }

                            if (result.err_id_harga_keluar) {
                                $("#err_id_harga_keluar").html(result.err_id_harga_keluar)
                            }

                            if (result.err_jml_keluar) {
                                $("#err_jml_keluar").html(result.err_jml_keluar)
                            }

                            if (result.err_bukti_keluar) {
                                $("#err_bukti_keluar").html(result.err_bukti_keluar)
                            }

                        } else {

                            window.location.reload()

                        }

                        $("#btn_tambah").prop("disabled", false)
                        $("#btn_tambah").html("Tambah")

                    }
                })
            })

            $("#btn_ubah").on("click", function() {

                const data_form = $("#form_ubah_keluar").serialize()

                $.ajax({
                    url: "<?= site_url('barang/keluar/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_jml_keluarU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_jml_keluarU) {
                                $("#err_jml_keluarU").html(result.err_jml_keluarU)
                            }

                        } else {
                            window.location.reload()
                        }

                        $("#btn_ubah").html("Ubah")
                        $("#btn_ubah").prop("disabled", false)

                    }
                })

            })

        })

        function lihat_keluar(id) {
            //console.log(id)
            $('#lihatKeluar').empty()
            $.get('<?= base_url() ?>barang/keluar/lihat_keluarmodel/' + btoa(id), function(data_keluar) {
                $('#lihatKeluar').html(data_keluar)
                $('#lihatKeluar_Modal').modal('show')
            })
        }

        function ubah_keluar(id) {
            // console.log(id)
            $('#ubahKeluar').empty()
            $.get('<?= base_url() ?>keluar/ubah_keluarmodel/' + btoa(id), function(data_keluar) {
                $('#ubahKeluar').html(data_keluar)
                $('#ubahKeluar_Modal').modal('show')
            })
        }

        document.getElementById('id_harga_keluar').addEventListener('change', function() {
            const id_harga = document.getElementById('id_harga_keluar').value;
            $.ajax({
                url: "<?= base_url('barang/keluar/dt_barang_toko'); ?>",
                type: 'post',
                data: {
                    id_harga: id_harga
                },
                success: function(data) {
                    const obj = JSON.parse(data);
                    $('.satuan').html(obj.satuan);
                }
            });
        });
    </script>