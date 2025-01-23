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
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success"><i class="bx bx-upload me-1"></i> Import</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Role</th>
                            <?php if (!$this->session->userdata("toko_id")) : ?>
                                <th class="text-center">Toko</th>
                            <?php endif; ?>
                            <th class="text-center">Username</th>
                            <th class="text-center">Nama karyawan</th>
                            <th class="text-center">Hp</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Bagian</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $no = 1;
                        foreach ($karyawan_temp as $kt) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= (!strpos($kt["role_id"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["role_id"] ?></td>
                                <?php if (!$this->session->userdata("toko_id")) : ?>
                                    <td><?= (!strpos($kt["toko_id"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["toko_id"] ?></td>
                                <?php endif ?>
                                <td><?= (!strpos($kt["username"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["username"] ?></td>
                                <td><?= (!strpos($kt["nama_karyawan"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["nama_karyawan"] ?></td>
                                <td><?= (!strpos($kt["hp_karyawan"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["hp_karyawan"] ?></td>
                                <td><?= (!strpos($kt["alamat_karyawan"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["alamat_karyawan"] ?></td>
                                <td><?= (!strpos($kt["bagian"], "<span ")) ? "<span class='text-success'>valid</span>" : $kt["bagian"] ?></td>
                            </tr>
                        <?php $no++;
                        endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

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

</div>
<!--/ Responsive Table -->