<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>


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
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#tambah_modal">
                        <span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah
                    </button>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Menu</th>
                            <th>Judul Sub Menu</th>
                            <th>URI Segment </th>
                            <th>URL </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($submenu as $sm) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $sm['menu'] ?></td>
                                <td><?= $sm['title'] ?></td>
                                <td><?= $sm['uri'] ?></td>
                                <td><?= $sm['url'] ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ubah_modal<?= $sm['id_submenu'] ?>"><span class="tf-icons bx bx-edit-alt"></span>&nbsp; Ubah</a>
                                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapus_modal<?= $sm['id_submenu'] ?>"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
                                </td>
                            </tr>


                            <!-- Modal Ubah -->
                            <div class="modal fade" id="ubah_modal<?= $sm['id_submenu'] ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('access/submenu/ubah') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Ubah Sub Menu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Nama Menu</label>
                                                        <select name="menu_id" id="menu_id" class="form-select" aria-label="Default select example">
                                                            <option value="">--PILIH MENU--</option>
                                                            <?php foreach ($menu as $m) : ?>
                                                                <option value="<?= $m['id_menu'] ?>" <?php if ($m['id_menu'] == $sm['menu_id']) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= $m['menu'] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <?= form_error('menu_id', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Title</label>
                                                        <input type="text" id="title" name="title" class="form-control" value="<?= set_value('title', $sm['title']) ?>" />
                                                        <?= form_error('title', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">URI <span class="text-danger"> (Pastikan Sesuai dengan segment 1 routes)</span></label>
                                                        <input type="text" id="uri" name="uri" class="form-control" value="<?= set_value('uri', $sm['uri']) ?>" />
                                                        <?= form_error('uri', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">URL</label>
                                                        <input type="text" id="url" name="url" class="form-control" value="<?= set_value('url', $sm['url']) ?>" />
                                                        <?= form_error('url', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <input type="hidden" id="id" name="id" class="form-control" value="<?= set_value('id', $sm['id_submenu']) ?>" readonly />
                                                <button type="submit" class="btn btn-warning">Ubah </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- End Modal Ubah -->

                            <!-- staticBackdrop removeModal -->
                            <div class="modal fade" id="hapus_modal<?= $sm['id_submenu']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="<?= $sm['id_submenu']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">

                                            <form action="<?= base_url('access/submenu/hapus') ?>" method="post">

                                                <div class="mt-4">
                                                    <h4 class="mb-3">Apakah anda akan menghapus data ini?</h4>
                                                    <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <input type="hidden" id="id" name="id" class="form-control" placeholder="Enter ID" value="<?= $sm['id_submenu'] ?>" />
                                                        <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                                        <button type="submit" class="btn btn-danger">Hapus </button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div class="modal fade" id="tambah_modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="<?= base_url('access/submenu/tambah') ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Sub Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Nama Menu</label>
                                    <br>
                                    <select name="menu_id" id="menu_id" class="select2-tambah-modal" style="width: 100%;">
                                        <option value="">--PILIH MENU--</option>
                                        <?php foreach ($menu as $m) : ?>
                                            <option value="<?= $m['id_menu'] ?>" <?= set_select('menu_id') ?>><?= $m['menu'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <?= form_error('menu_id', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" value="<?= set_value('title') ?>" />
                                    <?= form_error('title', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">URI <span class="text-danger"> (Pastikan Sesuai dengan segment 1 routes)</span></label>
                                    <input type="text" id="uri" name="uri" class="form-control" value="<?= set_value('uri') ?>" />
                                    <?= form_error('uri', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">URL</label>
                                    <input type="text" id="url" name="url" class="form-control" value="<?= set_value('url') ?>" />
                                    <?= form_error('url', '<small class="text-danger">', '</small>') ?>
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