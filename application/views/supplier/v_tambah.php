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
                    <h5 class="mb-0">Tambah Data Supplier</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('toko/supplier/tambah') ?>" method="post" enctype="multipart/form-data">


                        <!-- Untuk pembelian dari toko luar di tampilkan -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Nama Supplier</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="nama_supplier" id="nama_supplier" required>
                                <?= form_error('nama_supplier', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>
                        <!-- End untuk pembelian dari toko luar di tampilkan -->

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">No Telpon Supplier</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="no_telpon_supplier" type="text" onkeypress="return isNumber(event);" name="no_telpon_supplier">
                                <?= form_error('no_telpon_supplier', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="alamat_supplier">Alamat</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="alamat_supplier" id="alamat_supplier" rows="10"></textarea>
                                <?= form_error('alamat_supplier', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>


                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="button" onclick="batal()" class="btn btn-secondary btn-sm">Batal</button>
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
    function isNumber(event) {
        var keyCode = event.which ? event.which : event.keyCode;
        if (keyCode < 48 || keyCode > 57) {
            return false; // Mencegah karakter selain angka ditampilkan
        }
    }

    function batal() {
        window.location.href = '<?= base_url('toko/supplier') ?>'
    }
</script>