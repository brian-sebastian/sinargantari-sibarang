<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Barang /</span> List</h4>


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
                    <h6>Pilih Toko</h6>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="row">
                <form action="" method="get">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Toko</label>
                            <select name="toko" id="toko" class="form-select">
                                <?php foreach ($toko as $shop) : ?>
                                    <option value="<?= $shop['id_toko'] ?>"><?= $shop['nama_toko'] ?></option>
                                <?php endforeach ?>
                            </select>

                            <?= form_error('harga_pokok', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5>Barang</h5>
                </div>
                <div class="col">
                    <div class="float-end">
                        <a href="<?= base_url('barang/list/tambah') ?>" class="btn btn-sm btn-primary">Tambah</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success">Import</a>
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
                            <th>Barang</th>
                            <th>Kategori</th>
                            <th>Harga Pokok</th>
                            <th>Berat Barang</th>
                            <th>Barcode Barang</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($barang as $b) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $b['nama_barang'] ?></td>
                                <td><?= $b['nama_kategori'] ?></td>
                                <td>Rp. <?= number_format($b['harga_pokok']) ?></td>
                                <td><?= $b['berat_barang'] . " " . $b['satuan'] ?></td>
                                <td>
                                    <?php if ($b['barcode_barang'] == null) : ?>
                                        <a class="btn btn-info btn-sm" href="<?= base_url('barang/create_barcode/') . $b['kode_barang'] ?>">Create Barcode</a>
                                    <?php else : ?>
                                        <img src="<?= base_url('assets/barcodes/') . $b['barcode_barang'] . '.png' ?>" alt="" srcset="">
                                    <?php endif ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('barang/tampilan_edit/') . base64_encode($b['id_barang']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('barang/hapus/') . base64_encode($b['id_barang']) ?>" onclick="return confirm('Apakah ingin menghapus <?= $b['nama_barang'] ?>')" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach ?>

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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!--/ Responsive Table -->