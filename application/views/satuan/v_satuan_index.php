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
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_modal">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Satuan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_satuan as $ds) : ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $ds["satuan"] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm detail_button" data-bs-toggle="modal" data-bs-target="#ubah_modal" data-id="<?= $ds["id_satuan"] ?>"><i class=" bx bx-edit-alt me-1"></i>&nbsp;Ubah</button>
                                                <a href="<?= site_url('barang/satuan/hapus/' . $this->secure->encrypt_url($ds["id_satuan"])) ?>" onclick="return confirm(`Apakah anda yakin ingin menghapus data ${'<?= $ds['satuan'] ?>'}?`)" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i> Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Satuan</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
            <!-- Modal Tambah -->
            <div class="modal fade" id="tambah_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="form_tambah" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah <?= $title ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Satuan</label>
                                        <input type="text" name="satuan" id="satuan" class="form-control" placeholder="Satuan">
                                        <small class="text-danger" id="err_satuan"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-primary" id="btn_tambah">Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Modal Tambah -->

            <!-- Modal Ubah -->
            <div class="modal fade" id="ubah_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="form_ubah" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ubah <?= $title ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Satuan</label>
                                        <input type="text" name="satuan" id="satuanU" class="form-control" placeholder="Satuan">
                                        <small class="text-danger" id="err_satuanU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="id_satuan" id="id_satuanU" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-warning" id="btn_ubah">Ubah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Modal Ubah -->

        </div>
    </div>
    <script>
        $(function() {

            $("#btn_tambah").on("click", function() {

                const data_form = $("#form_tambah").serialize()

                $.ajax({

                    url: "<?= site_url("barang/satuan/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_satuan").html("")

                        // empty element error
                        $("#satuan").val("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_satuan) {
                                $("#err_satuan").html(result.err_satuan)
                            }

                        } else {

                            alert(result.response)
                            window.location.reload()
                        }

                        $("#btn_tambah").prop("disabled", false)
                        $("#btn_tambah").html("Tambah")

                    }
                })
            })

            $(".detail_button").on("click", function() {

                const id_satuan = $(this).data("id")

                $.ajax({

                    url: "<?= site_url("barang/satuan/detail") ?>",
                    type: "POST",
                    data: {
                        id_satuan: id_satuan
                    },
                    dataType: "json",
                    beforeSend: function() {

                        $("#btn_ubah").prop("disabled", true)

                        // clear element error
                        $("#err_satuanU").html("")

                        // empty element error
                        $("#satuanU").val("")

                    },
                    success: function(result) {

                        $("#btn_ubah").prop("disabled", false)

                        if (result.status == "berhasil") {

                            const response = result.response

                            $("#satuanU").val(response.satuan)
                            $("#id_satuanU").val(response.id_satuan)

                        } else {

                            alert(result.response)
                            window.location.reload()
                        }

                    }

                })

            })

            $("#btn_ubah").on("click", function() {

                const data_form = $("#form_ubah").serialize()

                $.ajax({
                    url: "<?= site_url('barang/satuan/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_satuanU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_satuanU) {
                                $("#err_satuanU").html(result.err_satuanU)
                            }

                        } else {

                            alert(result.response)
                            window.location.reload()
                        }

                        $("#btn_ubah").html("Ubah")
                        $("#btn_ubah").prop("disabled", false)

                    }
                })

            })

        })
    </script>