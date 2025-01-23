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
                            <th>Nama Toko</th>
                            <th>Alamat</th>
                            <th>No Telp</th>
                            <th>Jenis</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($toko as $shop) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $shop['nama_toko'] ?></td>
                                <td><?= $shop['alamat_toko'] ?></td>
                                <td><?= $shop['notelp_toko'] ?></td>
                                <td><?= $shop['jenis'] ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#ubah_modal<?= $shop['id_toko'] ?>"><span class="tf-icons bx bx-edit-alt"></span>&nbsp; Ubah</a>
                                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapus_modal<?= $shop['id_toko'] ?>"><span class="tf-icons bx bx-trash"></span>&nbsp; Hapus</a>
                                </td>
                            </tr>


                            <!-- Modal Ubah -->
                            <div class="modal fade" id="ubah_modal<?= $shop['id_toko'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="<?= base_url('toko/store_management/ubah') ?>" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Ubah Toko</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Nama Toko</label>
                                                        <input type="text" id="nama_toko" name="nama_toko" class="form-control" value="<?= set_value('nama_toko', $shop['nama_toko']) ?>" />
                                                        <?= form_error('nama_toko', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Alamat</label>
                                                        <textarea name="alamat_toko" id="alamat_toko" class="form-control" cols="30" rows="10"><?= set_value('alamat_toko', $shop['alamat_toko']) ?></textarea>
                                                        <?= form_error('alamat_toko', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Nomor Telepon Toko</label>
                                                        <input type="text" id="notelp_toko" name="notelp_toko" class="form-control" value="<?= set_value('notelp_toko', $shop['notelp_toko']) ?>" />
                                                        <?= form_error('notelp_toko', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="type" class="form-label">Jenis</label>
                                                        <select name="jenis" id="jenis" class="form-control">
                                                            <option value="">--PILIH JENIS TOKO--</option>
                                                            <option value="<?= JENIS_TOKO ?>" <?php if ($shop['jenis'] == JENIS_TOKO) {
                                                                                                    echo "selected";
                                                                                                } ?>><?= JENIS_TOKO ?></option>
                                                            <option value="<?= JENIS_MARKETPLACE ?>" <?php if ($shop['jenis'] == JENIS_MARKETPLACE) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= JENIS_MARKETPLACE ?></option>
                                                            <option value="<?= JENIS_GUDANG ?>" <?php if ($shop['jenis'] == JENIS_GUDANG) {
                                                                                                    echo "selected";
                                                                                                } ?>><?= JENIS_GUDANG ?></option>
                                                        </select>
                                                        <?= form_error('jenis', '<small class="text-danger">', '</small>') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                                <input type="hidden" id="id_toko" name="id_toko" class="form-control" value="<?= set_value('id_toko', $shop['id_toko']) ?>" readonly />
                                                <button type="submit" class="btn btn-warning">Ubah </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- End Modal Ubah -->

                            <!-- staticBackdrop removeModal -->
                            <div class="modal fade" id="hapus_modal<?= $shop['id_toko']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="<?= $shop['id_toko']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">

                                            <form action="<?= base_url('toko/store_management/hapus') ?>" method="post">

                                                <div class="mt-4">
                                                    <h4 class="mb-3">Apakah anda akan menghapus data ini?</h4>
                                                    <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                                                    <div class="hstack gap-2 justify-content-center">
                                                        <input type="hidden" id="id_toko" name="id_toko" class="form-control" placeholder="Enter ID" value="<?= $shop['id_toko'] ?>" />
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
                <form action="<?= base_url('toko/store_management/tambah') ?>" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Toko</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Nama Toko</label>
                                    <input type="text" id="nama_toko" name="nama_toko" class="form-control" />
                                    <?= form_error('nama_toko', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Alamat</label>
                                    <textarea name="alamat_toko" id="alamat_toko" class="form-control" cols="30" rows="10"></textarea>
                                    <?= form_error('alamat_toko', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Nomor Telepon Toko</label>
                                    <input type="text" id="notelp_toko" name="notelp_toko" class="form-control" />
                                    <?= form_error('notelp_toko', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type" class="form-label">Jenis</label>
                                    <select name="jenis" id="jenis" class="form-control">
                                        <option value="">--PILIH JENIS TOKO--</option>
                                        <option value="<?= JENIS_TOKO ?>"><?= JENIS_TOKO ?></option>
                                        <option value="<?= JENIS_MARKETPLACE ?>"><?= JENIS_MARKETPLACE ?></option>
                                        <option value="<?= JENIS_GUDANG ?>"><?= JENIS_GUDANG ?></option>
                                    </select>
                                    <?= form_error('jenis', '<small class="text-danger">', '</small>') ?>
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