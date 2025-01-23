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
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h5><?= $title ?></h5>
                        </div>
                        <div class="col">
                            <div class="float-end">
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_modal"><span class="tf-icons bx bxs-plus-circle"></span>&nbsp;Tambah</a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success"><i class="bx bx-upload me-1"></i> Import</a>
                                <?php if ($karyawan_temp) : ?>
                                    <a href="<?= site_url('toko/karyawan/temp') ?>" class="btn btn-sm btn-warning"><span class="tf-icons bx bx-block"></span>&nbsp; Lihat karyawan gagal</a>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Toko</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Peran</th>
                                    <th>Hp</th>
                                    <th>Alamat</th>
                                    <th>Bagian</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_karyawan as $dk) : ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $dk["nama_toko"] ?></td>
                                        <td><?= $dk["username"] ?></td>
                                        <td><?= $dk["nama_karyawan"] ?></td>
                                        <td><?= $dk["role"] ?></td>
                                        <td><?= $dk["hp_karyawan"] ?></td>
                                        <td><?= $dk["alamat_karyawan"] ?></td>
                                        <td><?= $dk["bagian"] ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm detail_button" data-bs-toggle="modal" data-bs-target="#ubah_modal" data-id="<?= $dk["id_karyawan"] ?>"><i class=" bx bx-edit-alt me-1"></i>&nbsp; Ubah</button>
                                                <a href="<?= site_url('toko/karyawan/hapus/' . $this->secure->encrypt_url($dk["id_karyawan"])) ?>" onclick="return confirm(`Apakah anda yakin ingin menghapus data ${'<?= $dk['nama_karyawan'] ?>'}?`)" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i> Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalDoImport" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= base_url('toko/karyawan/import') ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalDoImport">Import Data <?= $title ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <p>Silahkan download file format excel berikut : <a href="<?= ($this->session->userdata("toko_id")) ? base_url('assets/file_format_import/format_karyawan_toko_session_import.xlsx') : base_url('assets/file_format_import/format_karyawan_toko_import.xlsx') ?>" class="btn btn-sm btn-info">Download</a></p>
                                    </div>
                                    <div class="col">
                                        <label for="file_barang">File Excel karyawan</label>
                                        <input type="file" class="form-control" name="file_barang" id="file_barang" max accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Import</button>
                            </div>
                        </div>
                    </form>
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
                                        <?php if (!$this->session->userdata("toko_id")) : ?>
                                            <label for="type" class="form-label">Toko</label>
                                            <select name="toko_id" id="toko_id" class="form-control select2-tambah-modal" style="width: 100%;">
                                                <option value="">Pilih</option>
                                                <?php foreach ($data_toko as $dt) : ?>
                                                    <option value="<?= $dt["id_toko"] ?>"><?= $dt["nama_toko"] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <small class="text-danger" id="err_toko_id"></small>
                                        <?php else : ?>
                                            <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                                        <?php endif ?>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Role</label>
                                        <select name="role_id" id="role_id" class="form-control select2-tambah-modal" style="width: 100%;">
                                            <option value="">Pilih</option>
                                            <?php foreach ($data_role as $dr) : ?>
                                                <option value="<?= $dr["id_role"] ?>"><?= $dr["role"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <small class="text-danger" id="err_role_id"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Bagian karyawan</label>
                                        <select name="bagian" id="bagian" class="form-control select2-tambah-modal" style="width: 100%;">
                                            <option value="">Pilih</option>
                                            <option value="tetap">Tetap</option>
                                            <option value="kontrak">Kontrak</option>
                                            <option value="harian">Harian</option>
                                        </select>
                                        <small class="text-danger" id="err_bagian"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Username</label>
                                        <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                                        <small class="text-danger" id="err_username"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                        <small class="text-danger" id="err_password"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Nama karyawan</label>
                                        <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" placeholder="Nama karyawan">
                                        <small class="text-danger" id="err_nama_karyawan"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Hp karyawan</label>
                                        <input type="text" name="hp_karyawan" id="hp_karyawan" class="form-control" placeholder="Hp karyawan">
                                        <small class="text-danger" id="err_hp_karyawan"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Alamat karyawan</label>
                                        <textarea name="alamat_karyawan" id="alamat_karyawan" class="form-control" cols="30" rows="10" placeholder="Alamat karyawan"></textarea>
                                        <small class="text-danger" id="err_alamat_karyawan"></small>
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
                                    <?php if (!$this->session->userdata("toko_id")) : ?>
                                        <div class="col-md-12 mb-4">
                                            <label for="type" class="form-label">Toko</label>
                                            <select name="toko_id" id="toko_idU" class="form-control select2-ubah-modal" style="width: 100%;">
                                                <option value="">Pilih</option>
                                                <?php foreach ($data_toko as $dt) : ?>
                                                    <option value="<?= $dt["id_toko"] ?>"><?= $dt["nama_toko"] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                            <small class="text-danger" id="err_toko_idU"></small>
                                        </div>
                                    <?php else : ?>
                                        <input type="hidden" name="toko_id" id="toko_idU" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                                    <?php endif ?>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Role</label>
                                        <select name="role_id" id="role_idU" class="form-control select2-ubah-modal" style="width: 100%;">
                                            <option value="">Pilih</option>
                                            <?php foreach ($data_role as $dr) : ?>
                                                <option value="<?= $dr["id_role"] ?>"><?= $dr["role"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <small class="text-danger" id="err_role_idU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Bagian karyawan</label>
                                        <select name="bagian" id="bagianU" class="form-control select2-ubah-modal" style="width: 100%;">
                                            <option value="">Pilih</option>
                                            <option value="tetap">Tetap</option>
                                            <option value="kontrak">Kontrak</option>
                                            <option value="harian">Harian</option>
                                        </select>
                                        <small class="text-danger" id="err_bagianU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Username</label>
                                        <input type="text" name="username" id="usernameU" class="form-control" placeholder="Username">
                                        <small class="text-danger" id="err_usernameU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Password</label>
                                        <input type="password" name="password" id="passwordU" class="form-control" placeholder="Password">
                                        <small class="text-danger" id="err_password"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Nama karyawan</label>
                                        <input type="text" name="nama_karyawan" id="nama_karyawanU" class="form-control" placeholder="Nama karyawan">
                                        <small class="text-danger" id="err_nama_karyawanU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Hp karyawan</label>
                                        <input type="text" name="hp_karyawan" id="hp_karyawanU" class="form-control" placeholder="Hp karyawan">
                                        <small class="text-danger" id="err_hp_karyawanU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="type" class="form-label">Alamat karyawan</label>
                                        <textarea name="alamat_karyawan" id="alamat_karyawanU" class="form-control" cols="30" rows="10" placeholder="Alamat karyawan"></textarea>
                                        <small class="text-danger" id="err_alamat_karyawanU"></small>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <input type="hidden" name="id_karyawan" id="id_karyawanU" class="form-control">
                                        <input type="hidden" name="user_id" id="user_idU" class="form-control">
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

                    url: "<?= site_url("toko/karyawan/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_toko_id").html("")
                        // $("#err_user_id").html("")
                        $("#err_role_id").html("")
                        $("#err_username").html("")
                        $("#err_password").html("")
                        $("#err_nama_karyawan").html("")
                        $("#err_hp_karyawan").html("")
                        $("#err_alamat_karyawan").html("")
                        $("#err_bagian").html("")

                        // // empty element error
                        // $("#toko_id").val("")
                        // $("#toko_id").trigger("change")
                        // // $("#user_id").val("")
                        // $("#role_id").val("").trigger("change")
                        // $("username").val("")
                        // $("#password").val("")
                        // $("#user_id").trigger("change")
                        // $("#nama_karyawan").val("")
                        // $("#hp_karyawan").val("")
                        // $("#alamat_karyawan").html("")
                        // $("#bagian").val("")
                        // $("#bagian").trigger("change")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_toko_id) {
                                $("#err_toko_id").html(result.err_toko_id)
                            }

                            if (result.err_role_id) {
                                $("#err_role_id").html(result.err_role_id)
                            }

                            // if (result.err_user_id) {
                            //     $("#err_user_id").html(result.err_user_id)
                            // }

                            if (result.err_username) {
                                $("#err_username").html(result.err_username)
                            }

                            if (result.err_password) {
                                $("#err_password").html(result.err_password)
                            }

                            if (result.err_bagian) {
                                $("#err_bagian").html(result.err_bagian)
                            }

                            if (result.err_nama_karyawan) {
                                $("#err_nama_karyawan").html(result.err_nama_karyawan)
                            }

                            if (result.err_hp_karyawan) {
                                $("#err_hp_karyawan").html(result.err_hp_karyawan)
                            }

                            if (result.err_alamat_karyawan) {
                                $("#err_alamat_karyawan").html(result.err_alamat_karyawan)
                            }

                        } else {

                            window.location.reload()
                        }

                        $("#btn_tambah").prop("disabled", false)
                        $("#btn_tambah").html("Tambah")

                    }
                })
            })

            $("body").on("click", '.detail_button', function() {

                const id_karyawan = $(this).data("id")

                $.ajax({

                    url: "<?= site_url("toko/karyawan/detail") ?>",
                    type: "POST",
                    data: {
                        id_karyawan: id_karyawan
                    },
                    dataType: "json",
                    beforeSend: function() {

                        $("#btn_ubah").prop("disabled", true)

                        // clear element error
                        $("#err_toko_idU").html("")
                        // $("#err_user_idU").html("")
                        $("#err_role_idU").html("")
                        $("#err_usernameU").html("")
                        $("#err_passwordU").html("")
                        $("#err_nama_karyawanU").html("")
                        $("#err_hp_karyawanU").html("")
                        $("#err_alamat_karyawanU").html("")
                        $("#err_bagianU").html("")

                        // empty element error
                        $("#toko_idU").val("")
                        $("#toko_idU").trigger("change")
                        // $("#user_idU").val("")
                        // $("#user_idU").trigger("change")
                        $("#role_idU").val("").trigger("change")
                        $("#usernameU").val("")
                        $("#passwordU").val("")
                        $("#nama_karyawanU").val("")
                        $("#hp_karyawanU").val("")
                        $("#alamat_karyawanU").html("")
                        $("#bagianU").val("")
                        $("#bagianU").trigger("change")
                        $("#id_karyawanU").val("")
                        $("#user_idU").val("")

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        $("#btn_ubah").prop("disabled", false)

                        if (result.status == "berhasil") {

                            const response = result.response
                            const toko_id = "<?= $this->session->userdata('toko_id') ?>";

                            $("#toko_idU").val(response.toko_id)
                            $("#toko_idU").trigger("change")
                            $("#bagianU").val(response.bagian)
                            $("#bagianU").trigger("change")
                            $("#role_idU").val(response.role_id).trigger("change")
                            $("#usernameU").val(response.username)
                            $("#nama_karyawanU").val(response.nama_karyawan)
                            $("#hp_karyawanU").val(response.hp_karyawan)
                            $("#alamat_karyawanU").val(response.alamat_karyawan)
                            $("#id_karyawanU").val(response.id_karyawan)
                            $("#user_idU").val(response.user_id)

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
                    url: "<?= site_url('toko/karyawan/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_toko_idU").html("")
                        // $("#err_user_idU").html("")
                        $("#err_nama_karyawanU").html("")
                        $("#err_usernameU").html("")
                        $("#err_hp_karyawanU").html("")
                        $("#err_alamat_karyawanU").html("")
                        $("#err_bagianU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_toko_idU) {
                                $("#err_toko_idU").html(result.err_toko_idU)
                            }

                            if (result.err_usernameU) {
                                $("#err_usernameU").html(result.err_usernameU)
                            }

                            if (result.err_bagianU) {
                                $("#err_bagianU").html(result.err_bagianU)
                            }

                            if (result.err_nama_karyawanU) {
                                $("#err_nama_karyawanU").html(result.err_nama_karyawanU)
                            }

                            if (result.err_hp_karyawanU) {
                                $("#err_hp_karyawanU").html(result.err_hp_karyawanU)
                            }

                            if (result.err_alamat_karyawanU) {
                                $("#err_alamat_karyawanU").html(result.err_alamat_karyawanU)
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