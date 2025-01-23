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
                                    <th>Kode kategori</th>
                                    <th>Nama kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_kategori as $dk) : ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $dk["kode_kategori"] ?></td>
                                        <td><?= $dk["nama_kategori"] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm detail_button" data-bs-toggle="modal" data-bs-target="#ubah_modal" data-id="<?= $dk["id_kategori"] ?>"><i class=" bx bx-edit-alt me-1"></i>&nbsp;Ubah</button>
                                                <a href="<?= site_url('barang/kategori/hapus/' . $this->secure->encrypt_url($dk["id_kategori"])) ?>" onclick="return confirm(`Apakah anda yakin ingin menghapus data ${'<?= $dk['nama_kategori'] ?>'}?`)" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i>Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Kode kategori</th>
                                    <th>Nama kategori</th>
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
                                        <label for="type" class="form-label">Kode kategori</label>
                                        <input type="text" name="kode_kategori" id="kode_kategori" class="form-control" placeholder="Kode kategori">
                                        <small class="text-danger" id="err_kode_kategori"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Nama kategori</label>
                                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" placeholder="Nama kategori">
                                        <small class="text-danger" id="err_nama_kategori"></small>
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
                                        <label for="type" class="form-label">Kode kategori</label>
                                        <input type="text" name="kode_kategori" id="kode_kategoriU" class="form-control" placeholder="Kode kategori">
                                        <small class="text-danger" id="err_kode_kategoriU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Nama kategori</label>
                                        <input type="text" name="nama_kategori" id="nama_kategoriU" class="form-control" placeholder="Nama kategori">
                                        <small class="text-danger" id="err_nama_kategoriU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="id_kategori" id="id_kategoriU" class="form-control">
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

                    url: "<?= site_url("barang/kategori/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_kode_kategori").html("")
                        $("#err_nama_kategori").html("")

                        // empty element error
                        $("#kode_kategori").val("")
                        $("#nama_kategori").val("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_kode_kategori) {
                                $("#err_kode_kategori").html(result.err_kode_kategori)
                            }

                            if (result.err_nama_kategori) {
                                $("#err_nama_kategori").html(result.err_nama_kategori)
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

                const id_kategori = $(this).data("id")

                $.ajax({

                    url: "<?= site_url("barang/kategori/detail") ?>",
                    type: "POST",
                    data: {
                        id_kategori: id_kategori
                    },
                    dataType: "json",
                    beforeSend: function() {

                        $("#btn_ubah").prop("disabled", true)

                        // clear element error
                        $("#err_kode_kategoriU").html("")
                        $("#err_nama_kategoriU").html("")

                        // empty element error
                        $("#kode_kategoriU").val("")
                        $("#nama_kategoriU").val("")

                    },
                    success: function(result) {

                        $("#btn_ubah").prop("disabled", false)

                        if (result.status == "berhasil") {

                            const response = result.response

                            $("#kode_kategoriU").val(response.kode_kategori)
                            $("#nama_kategoriU").val(response.nama_kategori)
                            $("#id_kategoriU").val(response.id_kategori)

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
                    url: "<?= site_url('barang/kategori/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_kode_kategoriU").html("")
                        $("#err_nama_kategoriU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_kode_kategoriU) {
                                $("#err_kode_kategoriU").html(result.err_kode_kategoriU)
                            }

                            if (result.err_nama_kategoriU) {
                                $("#err_nama_kategoriU").html(result.err_nama_kategoriU)
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