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
                            <th>Icon </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($menu as $m) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $m['menu'] ?></td>
                                <td>
                                    <i class="menu-icon tf-icons <?= $m['icon'] ?>"></i>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ubah_modal<?= $m['id_menu'] ?>"><span class="tf-icons bx bx-edit-alt"></span>&nbsp; Ubah</a>
                                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remove_modal<?= $m['id_menu'] ?>"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
                                </td>
                            </tr>


                            <!-- Modal Ubah -->
                            <div class="modal fade" id="ubah_modal<?= $m['id_menu'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('access/menu/ubah') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Ubah Menu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Nama Menu</label>
                                                        <input type="text" menuubah="<?= $m['id_menu'] ?>" data-id="<?= $m['id_menu'] ?>" id="menuubah" name="menu" class="form-control" value="<?= set_value('menu', $m['menu']) ?>" />
                                                        <?= form_error('menu', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Icon</label>
                                                        <input type="text" id="icon" name="icon" class="form-control" value="<?= set_value('icon', $m['icon']) ?>" />
                                                        <small class="text-muted">ex. bx bxl-flask</small> |
                                                        <small class="text-muted">More Icon : <a href="https://boxicons.com/">BOXICONS</a></small>
                                                        <?= form_error('icon', '<small class="text-danger">', '</small>') ?>
                                                    </div>

                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Icon Sekarang</label>
                                                        <i class="menu-icon tf-icons <?= $m['icon'] ?>"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <input type="hidden" id="id" name="id" class="form-control" value="<?= set_value('id', $m['id_menu']) ?>" readonly />
                                                <button type="submit" class="btn btn-warning">Ubah </button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- End Modal Ubah -->

                            <!-- staticBackdrop removeModal -->
                            <div class="modal fade" id="remove_modal<?= $m['id_menu']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="<?= $m['id_menu']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">

                                            <form action="<?= base_url('access/menu/hapus') ?>" method="post">

                                                <div class="mt-4">
                                                    <h4 class="mb-3">Apakah anda akan menghapus data ini?</h4>
                                                    <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <input type="hidden" id="id" name="id" class="form-control" placeholder="Enter ID" value="<?= $m['id_menu'] ?>" />
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
        <div class="modal fade" id="tambah_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="<?= base_url('access/menu/tambah') ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Nama Menu</label>
                                    <input type="text" id="menu" name="menu" class="form-control" value="<?= set_value('menu') ?>" />
                                    <?= form_error('menu', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Icon</label>
                                    <input type="text" id="icon" name="icon" class="form-control" value="<?= set_value('icon') ?>" />
                                    <small class="text-muted">ex. bx bxl-flask</small> |
                                    <small class="text-muted">More Icon : <a href="https://boxicons.com/">BOXICONS</a></small>
                                    <?= form_error('icon', '<small class="text-danger">', '</small>') ?>
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
        <script>
            const menu = document.querySelector("#menu");
            const uri = document.querySelector("#uri");

            menu.addEventListener("keyup", function() {
                let preKodeUnit = menu.value;
                preKodeUnit = preKodeUnit.replace(/\s+/g, '');
                uri.value = preKodeUnit.toLowerCase();
            });
        </script>

    </div>
    <!--/ Basic Bootstrap Table -->


</div>
<!--/ Responsive Table -->