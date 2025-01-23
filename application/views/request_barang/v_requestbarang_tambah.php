<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>
    <?php if ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata("gagal") ?>
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
                    <h5 class="mb-0">Tambah Data Request</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('barang/request_barang/tambah') ?>" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-4 mb-3">
                                <label for="type" class="form-label">Kode Request</label>
                                <input type="text" id="kode_request" name="kode_request" value="<?= set_value('kode_request', $kode_request) ?>" class="form-control" />
                                <?= form_error('kode_request', '<small class="text-danger">', '</small>') ?>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="type" class="form-label">Toko Penerima</label>
                                <?php if ($this->session->userdata('toko_id') == '') { ?>
                                    <select class="form-control select2 " name="penerima_toko_id">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($data_toko as $dt) : ?>
                                            <option value="<?= $dt['id_toko'] ?>"><?= $dt['nama_toko'] ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                <?php } else { ?>
                                    <select name="penerima_toko_id" id="penerima_toko_id" class="form-select" style="width: 100%;">
                                        <option value="<?= $data_toko["id_toko"] ?>" selected>
                                            <?= $data_toko["nama_toko"] ?>
                                        </option>
                                    </select>
                                <?php } ?>
                                <?= form_error('penerima_toko_id', '<small class="text-danger">', '</small>') ?>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="type" class="form-label">Request Ke Toko</label>
                                <select class="form-control select2 " name="request_toko_id">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($toko as $tk) : ?>
                                        <option value="<?= $tk['id_toko'] ?>"><?= $tk['nama_toko'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('request_toko_id', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" onclick="batal()" class="btn btn-secondary btn-sm">Kembali</button>
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

<script>
</script>