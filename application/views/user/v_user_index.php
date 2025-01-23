<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?>/</span> <?= $title ?></h4>

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
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#tambah_modal">
                        <span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($users as $data) : ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $data['nama_user'] ?></td>
                                        <td><?= $data['username'] ?></td>
                                        <td>
                                            <button class="btn btn-secondary btn-sm password_button" data-bs-toggle="modal" data-bs-target="#changeEditPassword" data-id="<?= $data['id_user'] ?>">
                                                <i class="bx bx-lock-alt"></i> Ubah Password
                                            </button>
                                            <button class="btn btn-warning btn-sm detail_button" data-bs-toggle="modal" data-bs-target="#edit" data-id="<?= $data['id_user'] ?>">
                                                <i class=" bx bx-edit-alt me-1"></i>&nbsp; Ubah
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remove_modal<?= $data['id_user']; ?>">
                                                <span class="tf-icons bx bx-trash"></span>&nbsp; Hapus
                                            </button>

                                        </td>
                                    </tr>

                                    <!-- staticBackdrop removeModal -->
                                    <div class="modal fade" id="remove_modal<?= $data['id_user']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="<?= $data['id_user']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body text-center p-5">

                                                    <div class="mt-4">
                                                        <h4 class="mb-3">Apakah anda yakin akan menghapus data ini?</h4>
                                                        <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                                                        <div class="hstack gap-2 justify-content-center">
                                                            <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                                            <a href="<?= base_url('user/hapus/') . base64_encode($data['id_user']) ?>" class="btn btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php $no++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    </>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambah_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- <form id="tambah_data" action="user/tambah" method="post"> -->
                <form id="tambah">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="modal-body">
                        <div class="row">
                            <!-- Account -->

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control" type="text" required id="usename" name="username" placeholder="username" autofocus />
                                    <small class="text-danger pl-3" id="err_username"></small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nama_user" class="form-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" required name="nama_user" id="nama_user" placeholder="Nama lengkap" />
                                    <small class="text-danger pl-3" id="err_nama_user"></small>
                                </div>
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">New Password</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" required class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger pl-3" id="err_password"></small>
                                </div>
                                <div class="mb-3 col-md-6 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password Confirm</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password2" required class="form-control" name="password2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger pl-3" id="err_password2"></small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="country">Akses</label>
                                    <select id="country" name="role_id" required id="role_id" class="select form-select">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($role as $data) : ?>
                                            <option value="<?= $data['id_role'] ?>"><?= $data['role'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-danger pl-3" id="err_role_id"></small>

                                </div>
                            </div>


                            <!-- /Account -->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btn_tambah">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit_data" method="POST">
                    <input type="hidden" name="id_user_edit" id="id_user_edit">

                    <div class="modal-body">

                        <div class="row">
                            <!-- Account -->

                            <div class="row">

                                <div class="mb-3 col-md-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input class="form-control" type="text" id="username_edit" name="username" placeholder="username" readonly autofocus />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="nama_user" class="form-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" required name="nama_user_edit" id="nama_user_edit" placeholder="Nama lengkap" />
                                    <small class="text-danger pl-3" id="err_nama_user_edit"></small>

                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="country">Akses</label>
                                    <select id="role_id_edit" required name="role_id_edit" class="select form-select">
                                        <?php foreach ($role as $dtRole) : ?>
                                            <option value="<?= $dtRole['id_role'] ?>"><?= $dtRole['role'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-danger pl-3" id="err_role_id_edit"></small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="type" class="form-label">User Aktif</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="1" id="is_active_aktif_edit" name="is_active">
                                            <label class="form-check-label" for="inlineRadio1">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" value="0" id="is_active_tidak_edit" name="is_active">
                                            <label class="form-check-label" for="inlineRadio1">Tidak Aktif</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- /Account -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="simpan_ubah">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="changeEditPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="data_edit_password" method="post">

                    <div class="modal-body">
                        <input type="hidden" name="id_user_edit_password" id="id_user_edit_password">

                        <div class="row">
                            <!-- Account -->

                            <div class="row">
                                <div class="mb-3 col-md-12 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">New Password</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" required name="password_edit" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger pl-3" id="err_password_edit"></small>

                                </div>
                                <div class="mb-3 col-md-12 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password Confirm</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password2" class="form-control" required name="password2_edit" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger pl-3" id="err_password2_edit"></small>
                                </div>
                            </div>

                            <!-- /Account -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" id="simpan_edit_password" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $('#btn_tambah').on('click', function() {
            const data_form = $('#tambah').serialize();
            $.ajax({
                type: "POST",
                url: "<?= base_url('user/tambah') ?>",
                dataType: "JSON",
                data: data_form,
                beforeSend: function() {

                    $("#btn_tambah").prop("disabled", true)
                    $("#btn_tambah").html("Tunggu...")

                    $('#err_username').html("")
                    $('#err_nama_user').html("")
                    $('#err_password').html("")
                    $('#err_password2').html("")
                    $('#err_role_id').html("")
                },
                success: function(result) {
                    if (result.status == 'error') {
                        if (result.err_username) {
                            $('#err_username').html(result.err_username);
                        }
                        if (result.err_nama_user) {
                            $('#err_nama_user').html(result.err_nama_user);
                        }
                        if (result.err_password) {
                            $('#err_password').html(result.err_password);
                        }
                        if (result.err_password2) {
                            $('#err_password2').html(result.err_password2);
                        }
                        if (result.err_role_id) {
                            $('#err_role_id').html(result.err_role_id);
                        }

                    } else {

                        window.location.reload();

                    }
                    $("#btn_tambah").prop("disabled", false)
                    $("#btn_tambah").html("Tambah")
                }
            });
        })

        $('.detail_button').on('click', function() {
            const id_user = $(this).data("id")

            $.ajax({

                url: "<?= site_url("user/tampilan/edit") ?>",
                type: "POST",
                data: {
                    id_user: id_user
                },
                dataType: "JSON",
                beforeSend: function() {

                    $("#simpan_ubah").prop("disabled", true)

                    $('#err_nama_user_edit').html("")
                    $('#err_role_id_edit').html("")

                },
                success: function(result) {

                    $("#simpan_ubah").prop("disabled", false)

                    if (result.status == "berhasil") {

                        const response = result.response
                        if (response.is_active == 1) {
                            document.querySelector("#is_active_aktif_edit").checked = true;
                        } else {
                            document.querySelector("#is_active_tidak_edit").checked = true;
                        }
                        $("#id_user_edit").val(response.id_user)
                        $("#username_edit").val(response.username)
                        $("#nama_user_edit").val(response.nama_user)
                        $("#role_id_edit").val(response.role_id)
                        $("#role_id_edit").trigger("change")

                    } else {

                        alert(result.response)
                        window.location.reload()
                    }

                }

            })
        })

        $('#simpan_ubah').on('click', function() {

            const data_form_edit = $('#edit_data').serialize();
            $.ajax({
                url: '<?= base_url('user/edit') ?>',
                type: "POST",
                data: data_form_edit,
                dataType: "JSON",
                beforeSend: function() {
                    $('#err_nama_user_edit').html("")
                    $('#err_role_id_edit').html("")

                    $("#simpan_ubah").html("Tunggu...")
                    $("#simpan_ubah").prop("disabled", true)

                },
                success: function(result) {
                    if (result.status == 'error') {
                        if (result.err_nama_user_edit) {
                            $('#err_nama_user_edit').html(result.err_nama_user_edit);
                        }
                        if (result.err_role_id_edit) {
                            $('#err_role_id_edit').html(result.err_role_id_edit);
                        }
                    } else {
                        window.location.reload();
                    }

                    $("#simpan_ubah").html("Ubah")
                    $("#simpan_ubah").prop("disabled", false)
                }
            })
        })

        $('.password_button').on('click', function() {
            const id_user = $(this).data("id")

            $.ajax({

                url: "<?= site_url("user/tampilan/edit") ?>",
                type: "POST",
                data: {
                    id_user: id_user
                },
                dataType: "JSON",
                beforeSend: function() {

                    $("#simpan_edit_password").prop("disabled", true)

                    $('#err_nama_user_edit').html("")
                    $('#err_role_id_edit').html("")

                },
                success: function(result) {
                    $("#simpan_edit_password").prop("disabled", false)

                    if (result.status == "berhasil") {

                        const response = result.response

                        $("#id_user_edit_password").val(response.id_user)


                    } else {

                        window.location.reload()
                    }

                }

            })

        })

        $('#simpan_edit_password').on('click', function() {
            const data_form = $('#data_edit_password').serialize();

            $.ajax({
                url: '<?= base_url('user/change_password') ?>',
                type: "POST",
                data: data_form,
                dataType: "JSON",
                beforeSend: function() {
                    $('#err_password_edit').html("")
                    $('#err_password2_edit').html("")

                    $("#simpan_edit_password").html("Tunggu...")
                    $("#simpan_edit_password").prop("disabled", true)

                },
                success: function(result) {
                    if (result.status == 'error') {
                        if (result.err_password_edit) {
                            $('#err_password_edit').html(result.err_password_edit);
                        }
                        if (result.err_password2_edit) {
                            $('#err_password2_edit').html(result.err_password2_edit);
                        }
                    } else {
                        // const tes = result.response
                        // console.log(tes);
                        window.location.reload();
                    }

                    $("#simpan_edit_password").html("Simpan")
                    $("#simpan_edit_password").prop("disabled", false)
                }
            })
        })
    </script>