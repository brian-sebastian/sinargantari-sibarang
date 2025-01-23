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
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Gambar banner</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_banner as $db) : ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $db["judul"] ?></td>
                                        <td><a href="<?= base_url() ?>assets/file_banner/<?= $db["gambar"] ?>" class="btn btn-sm btn-info">Lihat banner</a></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm detail_button" data-bs-toggle="modal" data-bs-target="#ubah_modal" data-id="<?= $db["id_banner"] ?>"><i class="fa fa-trash"></i>Ubah</button>
                                                <a href="<?= site_url('marketplace/banner/hapus/' . $this->secure->encrypt_url($db["id_banner"])) ?>" onclick="return confirm(`Apakah anda yakin ingin menghapus data ${'<?= $db['judul'] ?>'}?`)" class="btn btn-danger btn-sm">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Gambar banner</th>
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
                    <form id="form_tambah" method="post" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah <?= $title ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Judul : </label>
                                        <input type="text" name="judul" id="judul" class="form-control" placeholder="Judul banner">
                                        <small class="text-danger" id="err_judul"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Gambar banner : </label>
                                        <input type="file" name="gambar" id="gambar" class="form-control dropify">
                                        <small class="text-danger" id="err_gambar"></small>
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
                    <form id="form_ubah" method="post" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ubah <?= $title ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Gambar saat ini : </label>
                                        <img id="file_gambarU" class="d-block img-fluid img-thumbnail" width="80">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Judul : </label>
                                        <input type="text" name="judul" id="judulU" class="form-control" placeholder="Judul banner">
                                        <small class="text-danger" id="err_judulU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Gambar banner : </label>
                                        <input type="file" name="gambar" id="gambarU" class="form-control dropify">
                                        <input type="hidden" name="gambar_old" id="gambar_oldU" class="form-control">
                                        <small class="text-danger" id="err_gambarU"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id_banner" id="id_bannerU" class="form-control">
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

            initializeTable("client-side")

            $("#btn_tambah").on("click", function() {

                const data_form = new FormData($("#form_tambah")[0]);

                $.ajax({

                    url: "<?= site_url("marketplace/banner/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_judul").html("")
                        $("#err_gambar").html("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_judul) {
                                $("#err_judul").html(result.err_judul)
                            }

                            if (result.err_gambar) {
                                $("#err_gambar").html(result.err_gambar)
                            }

                        } else {

                            window.location.reload()
                        }

                        $("#btn_tambah").prop("disabled", false)
                        $("#btn_tambah").html("Tambah")

                    }
                })
            })

            $(".detail_button").on("click", function() {

                const id_banner = $(this).data("id")

                $.ajax({

                    url: "<?= site_url("marketplace/banner/detail") ?>",
                    type: "POST",
                    data: {
                        id: id_banner
                    },
                    dataType: "json",
                    beforeSend: function() {

                        $("#btn_ubah").prop("disabled", true)

                        // clear element error
                        $("#err_judulU").html("")
                        $("#err_gambarU").html("")

                        // empty element error
                        $("#id_bannerU").val("");
                        $("#judulU").val("")
                        $("#id_bannerU").val("")
                        $("#gambarU").val("")
                        $("#gambar_oldU").val("")
                        $("#file_gambarU").attr("src", "")
                        $("#file_gambarU").attr("alt", "")

                    },
                    success: function(result) {

                        $("#btn_ubah").prop("disabled", false)

                        if (result.status == "berhasil") {

                            const response = result.response

                            $("#id_bannerU").val(response.id_banner)
                            $("#judulU").val(response.judul)
                            $("#gambar_oldU").val(response.gambar)
                            $("#file_gambarU").attr("src", "<?= base_url() ?>" + "assets/file_banner/" + response.gambar)
                            $("#file_gambarU").attr("alt", "<?= base_url() ?>" + "assets/file_banner/" + response.gambar)

                        } else {

                            window.location.reload()
                        }

                    }

                })

            })

            $("#btn_ubah").on("click", function() {

                const data_form = new FormData($("#form_ubah")[0]);

                $.ajax({
                    url: "<?= site_url('marketplace/banner/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    beforeSend: function() {

                        // clear element error
                        $("#err_judulU").html("")
                        $("#err_gambarU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_judulU) {
                                $("#err_judulU").html(result.err_judulU)
                            }

                            if (result.err_gambarU) {
                                $("#err_gambarU").html(result.err_gambarU)
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
    </script>