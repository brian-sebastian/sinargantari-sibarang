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
                    <h5 class="mb-0">Edit Data Barang Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('kasir/transaksi/edit') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                        <input type="hidden" name="bukti_tf_old" value="<?= $transaksi['bukti_tf'] ?>">

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jml_masuk">Kode Order</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="text" readonly class="form-control" value="<?= $transaksi['kode_order'] ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="col-sm-2" id="tipe_transaksi">Tipe Transaksi</label> :
                            <p class="d-inline" id="tipe_tramsaksi"><?= ($transaksi['tipe_transaksi'] == 'transfer') ? 'TRANSFER' : 'TUNAI' ?></p>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">Bukti Transfer</label>
                            <div class="col-sm-10">
                                <img class="img-thumbnail img-preview" src="<?= base_url('assets/file_bukti_transaksi/') . $transaksi['bukti_tf'] ?>" width="150">
                                <input type="file" name="bukti_transfer" id="bukti_transfer" onchange="previewImg()" class="form-control dropify" data-max-file-size="1M" data-allowed-file-extensions="pdf png jpg jpeg" />
                                <?= form_error('bukti_transfer', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="button" onclick="batal()" class="btn btn-warning btn-sm">Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm">Edit</button>
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
    $('.dropify').dropify();

    function isNumber(event) {
        var keyCode = event.which ? event.which : event.keyCode;
        if (keyCode < 48 || keyCode > 57) {
            return false; // Mencegah karakter selain angka ditampilkan
        }
    }

    function batal() {
        window.location.href = '<?= base_url('kasir/transaksi') ?>'
    }

    function previewImg() {
        const sampul = document.querySelector('#bukti_transfer');
        const imgPreview = document.querySelector('.img-preview');

        //Menampilkan gambar
        const fileSampul = new FileReader();
        fileSampul.readAsDataURL(sampul.files[0]);

        fileSampul.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>