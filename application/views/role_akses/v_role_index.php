<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php elseif ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata("gagal") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php elseif ($this->session->flashdata('flash-swal')) : ?>
        <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash-swal'); ?>"></div>

    <?php elseif ($this->session->flashdata('flash-data-gagal')) : ?>
        <div class="flash-data-gagal" data-flashdata="<?= $this->session->flashdata('flash-data-gagal'); ?>"></div>
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
                                    <th class="text-center">No</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_role as $dr) : ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $dr["role"] ?></td>
                                        <td class="text-center">
                                            <a onclick="akses_role(<?= $dr['id_role'] ?>)">
                                                <button type="button" class="btn btn-sm btn-info">
                                                    <span class="tf-icons bx bxs-user-check"></span>&nbsp; Akses
                                                </button>
                                            </a>
                                            <a onclick="ubah_role(<?= $dr['id_role'] ?>)">
                                                <button type="button" class="btn btn-sm btn-warning">
                                                    <span class="tf-icons bx bx-edit-alt"></span>&nbsp; Edit
                                                </button>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remove_modal<?= $dr['id_role']; ?>">
                                                <span class="tf-icons bx bx-trash"></span>&nbsp; Hapus
                                            </button>
                                        </td>

                                        <!-- staticBackdrop removeModal -->
                                        <div class="modal fade" id="remove_modal<?= $dr['id_role']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="<?= $dr['id_role']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center p-5">

                                                        <div class="mt-4">
                                                            <h4 class="mb-3">Apakah anda akan menghapus data ini?</h4>
                                                            <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                                                            <div class="hstack gap-2 justify-content-center">
                                                                <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                                                <a href="<?= base_url('role_akses/hapus_role/') . $dr['id_role'] ?>" class="btn btn-danger">Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="tambah_modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Tambah Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="form_tambah" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label for="type" class="form-label">Nama Role</label>
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf-token" style="display: none">
                                            <input type="text" id="role" name="role" class="form-control" />
                                            <small class="text-danger" id="err_role"></small>
                                        </div>
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
    <div class="modal fade" id="ubahRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_ubah" method="post">
                    <div class="modal-body" id="ubahRole">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn_ubah">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="aksesrole_Modal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="aksesRole">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $("#btn_tambah").on("click", function() {

                const data_form = $("#form_tambah").serialize()

                $.ajax({

                    url: "<?= site_url("role_akses/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_role").html("")

                        // empty element error
                        $("#role").val("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_role) {
                                $("#err_role").html(result.err_role)
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

                const data_form = $("#form_ubah").serialize()

                $.ajax({
                    url: "<?= site_url('role_akses/update_role') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_roleU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_roleU) {
                                $("#err_roleU").html(result.err_roleU)
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

        function ubah_role(id) {
            // console.log(id)
            $('#ubahRole').empty()
            $.get('<?= base_url() ?>role_akses/ubah_rolemodel/' + btoa(id), function(data_role) {
                $('#ubahRole').html(data_role)
                $('#ubahRoleModal').modal('show')
            })
        }


        function akses_role(id) {
            // console.log(id)
            $('#aksesRole').empty()
            $.get('<?= base_url() ?>role_akses/akses_rolemodel/' + btoa(id), function(data_role) {
                $('#aksesRole').html(data_role)
                $('#aksesrole_Modal').modal('show')
            })
        }
    </script>