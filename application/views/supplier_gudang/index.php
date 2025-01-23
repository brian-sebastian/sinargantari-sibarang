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
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tambah Stok Barang Dari Supplier Ke Gudang</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">

                        <!-- tipe antar toko -->
                        <div class="container">
                            <div class="row mb-3" id="supplier_id">
                                <label class="col-sm-2 col-form-label" for="supplier_id">Dari Supplier</label>
                                <div class="col-sm-10">
                                    <select name="supplier_id" id="supplier_id" class="form-control select2" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach($data_suppliers as $data_supplier):?>
                                            <option value="<?= $data_supplier["nama_supplier"]?>"><?= $data_supplier["nama_supplier"] ?></option>
                                            <?php endforeach?>
                                        </select>
                                        <?= form_error('supplier_id', '<small class="text-danger">', '</small>') ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="basic-default-name">Ke Gudang</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2 " name="gudang_id" id="gudang_id" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                         <?php foreach($data_gudangs as $data_gudang):?>
                                            <option value="<?= $data_gudang["id_toko"]?>"><?= $data_gudang["nama_toko"] ?></option>
                                        <?php endforeach?>
                                    </select>
                                    <?= form_error('gudang_id', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <div class="row mb-3" id="barang_id">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">List Barang</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="barang_id" id="barang_id" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($data_barangs as $data_barang) : ?>
                                            <option value="<?= $data_barang['id_barang'] ?>"><?= $data_barang['nama_barang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('barang_id', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Tanggal Pembelian</label>
                                <div class="col-sm-3">
                                    <input class="form-control flatpickr" type="text" name="tanggal_barang_masuk" id="tanggal_barang_masuk" value="<?= set_value("tanggal_barang_masuk") ?>">
                                    <?= form_error('tanggal_barang_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jml_masuk">Jumlah Barang</label>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <input type="number" name="jml_masuk" id="jml_masuk" class="form-control" value="<?= set_value("jml_masuk") ?>" />
                                    </div>
                                    <?= form_error('jml_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

