<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Access /</span> Role</h4>


    <?php if ($this->session->flashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($this->session->flashdata('message_error')) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata('message_error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5>Role</h5>

                </div>
                <div class="col">
                    <div class="float-end">
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_modal">Tambah</a>
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
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($role as $r) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $r['role'] ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info">Akses</a>
                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ubah_modal<?= $r['id_role'] ?>">Edit</a>
                                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapus_modal<?= $r['id_role'] ?>">Hapus</a>
                                </td>
                            </tr>


                            <!-- Modal Ubah -->
                            <div class="modal fade" id="ubah_modal<?= $r['id_role'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('access/role/ubah') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Ubah Role</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Role</label>
                                                        <input type="text" id="role" name="role" class="form-control" value="<?= set_value('role', $r['role']) ?>" />
                                                        <?= form_error('role', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <input type="hidden" id="id" name="id" class="form-control" value="<?= set_value('id', $r['id_role']) ?>" readonly />
                                                <button type="submit" class="btn btn-warning">Ubah </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- End Modal Ubah -->

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="hapus_modal<?= $r['id_role'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('access/role/hapus') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Hapus Sub Menu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <p>Apakah anda yakin menghapus data <b><?= $r['role'] ?> ?</b> </p>
                                                        <p>Data yang dihapus tidak dapat dikembalikan.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" id="id" name="id" class="form-control" placeholder="Enter ID" value="<?= $r['id_role'] ?>" />
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="submit" class="btn btn-danger">Hapus </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal Hapus -->

                        <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div class="modal fade" id="tambah_modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="<?= base_url('access/role/tambah') ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Role</label>
                                    <input type="text" id="role" name="role" class="form-control" value="<?= set_value('role') ?>" />
                                    <?= form_error('role', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal Tambah -->


    </div>
    <!--/ Basic Bootstrap Table -->


</div>
<!--/ Responsive Table -->