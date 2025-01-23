<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

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

    <div class="card mt-2">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5>Daftar Barang Toko</h5>
                </div>
                <div class="col">
                    <div class="float-end">
                        <a href="" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success">Import</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Stok</th>
                            <th>Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $no = 1;
                        foreach ($harga_temp as $ht) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= (!strpos($ht["barang_id"], "<span ")) ? "<span class='text-success'>valid</span>" : $ht["barang_id"]  ?></td>
                                <td><?= (!strpos($ht["stok_toko"], "<span ")) ? "<span class='text-success'>valid</span>" : $ht["stok_toko"]  ?></td>
                                <td><?= (!strpos($ht["harga_jual"], "<span ")) ? "<span class='text-success'>valid</span>" : $ht["harga_jual"]  ?></td>
                            </tr>
                        <?php $no++;
                        endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalDoImport" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('barang/barang_toko/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDoImport">Import Data Barang Toko</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <p>Silahkan download file format excel berikut : <a href="<?= base_url('assets/file_format_import/format_barang_toko_import_2.xlsx') ?>" class="btn btn-sm btn-info">Download</a></p>
                            </div>
                            <div class="col">
                                <label for="file_barang">File Excel Barang Toko</label>
                                <input type="file" class="form-control" name="file_barang" id="file_barang_toko" max accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $toko_id ?>" required>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>