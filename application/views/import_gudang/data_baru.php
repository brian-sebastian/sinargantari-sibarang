<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>
    <?php if ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata("gagal") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>
    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-4">Import Stok Barang Baru Ke Gudang</h5>
                    <div class="container-fluid d-flex align-items-center justify-content-between">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group mb-3">
                                <label for="file_barang">Upload file barang : </label>
                                <input type="file" name="file_barang" id="file_barang" class="form-control form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-success">Import</button>
                                <a href="<?= site_url('gudang/import_gudang/simpan_data_baru') ?>" class="btn btn-sm btn-danger">Simpan Hasil Import</a>
                                <a href="<?= site_url('assets/template_import_barang_baru_gudang/template_import_barang_baru_gudang_v1.xlsx') ?>" class="btn btn-sm btn-primary" download>Download Template</a>
                            </div>
                            <small class="text-danger text-bold">* Perhatikan jika barang yang di import sudah ada di gudang sebelumnya, maka jumlah yang akan di import otomatis ditambahkan dengan stok yang ada pada gudang saat ini.</small>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                     <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status</th>
                            <th>Nama Barang</th>
                            <th>Nama Gudang</th>
                            <th>Jumlah</th>
                            <th>Nama Toko Luar</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($data_import_gudang_barus as $data_import_gudang_baru) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= ($data_import_gudang_baru['status']) ? "<span class='text-success'>" . $data_import_gudang_baru['status'] . "<span class='text-success'>" : $data_import_gudang_baru["status"] ?></td>
                                <td><?= $data_import_gudang_baru['nama_barang'] ?></td>
                                <td><?= $data_import_gudang_baru['nama_gudang'] ?></td>
                                <td><?= $data_import_gudang_baru['jumlah_barang'] ?></td>
                                <td><?= $data_import_gudang_baru['nama_toko_luar'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

