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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>
    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tambah Data Barang Masuk Cacat</h5>
                </div>
                <div class="card-body">
                    <div class="col-12 mb-4">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" id="toko_id" name="toko_id" class="form-control" value="<?= set_value('toko_id', $toko_id) ?>" required readonly />
                                <div class="col-12 mb-3">
                                    <label for="type" class="form-label">Barang</label>
                                    <select name="id_harga" id="id_harga" class="form-control select2">
                                        <option value="">--PILIH BARANG--</option>
                                        <?php foreach ($barangtoko as $bar) : ?>
                                            <option value="<?= $bar['id_harga'] ?>"><?=  $bar['kode_barang'] . "/" . $bar['nama_barang'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <?= form_error('id_harga', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="type" class="form-label">Jumlah Cacat</label>
                                    <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" />
                                        <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="type" class="form-label">Status</label>
                                    <select name="status_masuk" id="status_masuk" class="form-control form-select">
                                        <option value="">--PILIH Status--</option>
                                        <option value="Stok Cacat">Masuk Stok Cacat</option>
                                        <option value="Dipakai">Dipakai</option>
                                        <option value="Musnah">Musnah</option>
                                    </select>
                                    <?= form_error('status_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="type" class="form-label">Bukti Cacat</label>
                                    <input type="file" name="bukti_masuk" class="form-control dropify" data-max-file-size="2M" data-allowed-file-extensions="pdf png jpg jpeg" />
                                    <?= form_error('bukti_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                            <button type="button" onclick="batal()" class="btn btn-secondary btn-sm">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<script>
    function batal() {
        window.location.href = '<?= base_url('barang_cacat/masuk_cacat') ?>'
    }
</script>