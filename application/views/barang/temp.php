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
                    <a href="" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success"><span class='tf-icons bx bx-import'></span>&nbsp; Import ulang</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Nama barang</th>
                            <th class="text-center">Harga Pokok</th>
                            <th class="text-center">Barcode</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php $no = 1;
                        foreach ($barang_temp as $bt) : ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= (!strpos($bt["kategori_id"], "<span ")) ? "<span class='text-success'>valid</span>" : $bt["kategori_id"] ?></td>
                                <td><?= (!strpos($bt["satuan_id"], "<span ")) ? "<span class='text-success'>valid</span>" : $bt["satuan_id"] ?></td>
                                <td><?= (!strpos($bt["nama_barang"], "<span ")) ? "<span class='text-success'>valid</span>" : $bt["nama_barang"] ?></td>
                                <td><?= (!strpos($bt["harga_pokok"], "<span ")) ? "<span class='text-success'>valid</span>" : $bt["harga_pokok"] ?></td>
                                <td><?= (!strpos($bt["barcode_barang"], "<span ")) ? "<span class='text-success'>valid</span>" : $bt["barcode_barang"] ?></td>
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
            <form action="<?= base_url('barang/list/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDoImport">Import Data Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <p>Silahkan download file format excel berikut : <a href="<?= base_url('assets/file_format_import/format_barang_import.xlsx') ?>" class="btn btn-sm btn-info">Download</a></p>
                            </div>
                            <div class="col">
                                <label for="file_barang">File Excel Barang</label>
                                <input type="file" class="form-control" name="file_barang" id="file_barang" max accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!--/ Responsive Table -->