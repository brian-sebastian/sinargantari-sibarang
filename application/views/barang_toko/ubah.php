<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Barang /</span> Ubah </h4>


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
                    <h5>Barang</h5>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="col-12 mb-4">
                <form action="" method="post">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="<?= set_value('nama_barang', $barang['nama_barang'] . " (Harga pokok : Rp" . number_format($barang['harga_pokok']) . ")") ?>" required readonly />
                            <?= form_error('barang_id', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <input type="hidden" id="id_harga" name="id_harga" class="form-control" value="<?= set_value('id_harga', $harga['id_harga']) ?>" required readonly />
                            <input type="hidden" id="barang_id" name="barang_id" class="form-control" value="<?= set_value('barang_id', $barang['id_barang']) ?>" required readonly />
                            <input type="hidden" id="toko_id" name="toko_id" class="form-control" value="<?= set_value('toko_id', $toko_id) ?>" required readonly />
                            <?= form_error('toko_id', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual Barang</label>
                            <input type="number" id="harga_jual" name="harga_jual" class="form-control" value="<?= set_value('harga_jual', $harga['harga_jual']) ?>" required />
                            <?= form_error('harga_jual', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="stok_toko" class="form-label">Stok Barang</label>
                            <input type="number" id="stok_toko" name="stok_toko" class="form-control" value="<?= set_value('stok_toko', $harga['stok_toko']) ?>" required />
                            <?= form_error('stok_toko', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-md btn-primary">Simpan Perubahan</button>
                    <?php if ($this->session->userdata("toko_id")) : ?>
                        <a href="<?= base_url('barang/barang_toko') ?>" class="btn btn-md btn-secondary">Cancel</a>
                    <?php else : ?>
                        <a href="<?= base_url('barang/barang_toko?toko=') . $this->input->get('toko') ?>" class="btn btn-md btn-secondary">Cancel</a>
                    <?php endif ?>
                </form>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->


</div>
<!--/ Responsive Table -->
