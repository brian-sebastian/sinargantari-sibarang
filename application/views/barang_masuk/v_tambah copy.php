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
                    <h5 class="mb-0">Tambah Data Barang Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('barang/masuk/tambah') ?>" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-message">Dari</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tipe" type="radio" id="antar_toko" value="antar_toko">
                                    <label for="antar_toko" class="form-check-label">Antar Toko</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tipe" type="radio" id="gudang" value="gudang">
                                    <label for="gudang" class="form-check-label">Gudang</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tipe" type="radio" id="toko_luar" value="toko_luar">
                                    <label for="toko_luar" class="form-check-label">Toko Luar</label>
                                </div>
                                <?= form_error('tipe', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <!-- Untuk pembelian dari toko luar di tampilkan -->
                        <div class="row mb-3" id="nama_toko_luar" style="display: none;">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Nama Toko</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="nama_toko_beli" id="nama_toko_beli">
                                <?= form_error('nama_toko_beli', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>
                        <!-- End untuk pembelian dari toko luar di tampilkan -->
                        <div class="row mb-3" id="antar_toko_dari" style="display: none;">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Dari Toko</label>
                            <div class="col-sm-10">

                                <select class="form-control select2 " name="dari_toko_id" id="dari_toko_id" style="width: 100;">

                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($toko as $dtToko) : ?>
                                        <?php $selected = ($dtToko['id_toko'] == $this->session->userdata('toko_id')) ? 'selected' : '' ?>
                                        <option value="<?= $dtToko['id_toko'] ?>" <?= $selected ?>><?= $dtToko['nama_toko'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('harga_id', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Barang</label>
                            <div class="col-sm-10">

                                <select class="form-control select2 " name="barang_id" id="barang_id">

                                    <option value="">-- Pilih --</option>
                                </select>
                                <?= form_error('barang_id', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>
                        <div class="row mb-3" id="antar_toko_dalam" style="display: none;">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Ke Toko</label>
                            <div class="col-sm-10">

                                <select class="form-control select2 " name="toko_id" id="toko_id" style="width: 100;">

                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($toko as $dtToko) : ?>

                                        <option value="<?= $dtToko['id_toko'] ?>"><?= $dtToko['nama_toko'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('harga_id', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Nama Supplier</label>
                            <div class="col-sm-10">

                                <select class="form-control select2" name="nama_sales" id="nama_sales">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($supplier as $d) : ?>
                                        <option value="<?= $d['id_supplier'] ?>"><?= $d['nama_supplier'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('nama_sales', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-name">Tanggal Pembelian</label>
                            <div class="col-sm-3">
                                <input class="form-control flatpickr" type="text" name="tanggal_barang_masuk" id="tanggal_barang_masuk">
                                <?= form_error('tanggal_barang_masuk', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="jml_masuk">Jumlah Barang</label>
                            <div class="col-sm-3">
                                <div class="input-group input-group-merge">
                                    <input type="number" name="jml_masuk" id="jml_masuk" class="form-control" />
                                    <?= form_error('jml_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-phone">Bukti Masuk</label>
                            <div class="col-sm-10">
                                <input type="file" name="bukti_file" class="form-control dropify" data-max-file-size="1M" data-allowed-file-extensions="pdf png jpg jpeg" />
                                <?= form_error('bukti_file', '<small class="text-danger">', '</small>') ?>
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
    $("input[name='tipe']").change(function() {
        const admin_dan_dev = '<?= $this->session->userdata('role_id') ?>';

        const tipe_barang_masuk = $("input[name='tipe']:checked").val();

        if (tipe_barang_masuk == 'antar_toko') {
            $('#nama_toko_luar').hide();
            $('#antar_toko_dari').show();
            $('#antar_toko_dalam').show();
        }

        if (tipe_barang_masuk == 'gudang') {
            $('#nama_toko_luar').hide();
            $('#antar_toko_dari').hide();
            $('#antar_toko_dalam').show();
        }

        if (admin_dan_dev == 1 || admin_dan_dev == 2 && tipe_barang_masuk == 'toko_luar') {
            $('#nama_toko_luar').show();
            $('#antar_toko_dalam').show();
            $('#antar_toko_dari').hide();

        } else if (tipe_barang_masuk == 'toko_luar') {
            $('#nama_toko_luar').show();
            $('#antar_toko_dalam').hide();
            $('#antar_toko_dari').hide();

        }
    })


    $(document).ready(function() {

        const toko = $('#toko_id').val();
        const barang = $('#barang_id')
        barang.empty()

        if (toko != '') {
            $.ajax({
                url: '<?= base_url('') ?>barang/masuk/barang/harga/' + toko,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    if (data.length == 0) {
                        barang.append('<option value="">-- Pilih --</option>')
                    } else {
                        var options = '<option value="">-- Pilih --</option>';
                        $.each(data, function(key, value) {
                            options += '<option value="' + value.barang_id + '">' + value.nama_barang + '</option>'
                            barang.html(options);
                        })
                    }

                }
            })
        } else {
            barang.append('<option value="">-- Pilih --</option>')
        }
        $('#dari_toko_id').change(function() {
            const pilihToko = $(this).val();
            console.log(pilihToko)
            const pilihBarang = $('#barang_id')

            pilihBarang.empty()

            if (pilihToko != '') {
                $.ajax({
                    url: '<?= base_url('') ?>barang/masuk/barang/harga/' + pilihToko,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data)
                        if (data.length == 0) {
                            pilihBarang.append('<option value="">-- Pilih --</option>')
                        } else {
                            var options = '<option value="">-- Pilih --</option>';
                            $.each(data, function(key, value) {
                                console.log(value.nama_barang)
                                options += '<option value="' + value.barang_id + '">' + value.nama_barang + '</option>'
                                pilihBarang.html(options);
                            })
                        }

                    }
                })
            }

        })
    })



    function isNumber(event) {
        var keyCode = event.which ? event.which : event.keyCode;
        if (keyCode < 48 || keyCode > 57) {
            return false; // Mencegah karakter selain angka ditampilkan
        }
    }

    function batal() {
        window.location.href = '<?= base_url('barang/masuk') ?>'
    }
</script>