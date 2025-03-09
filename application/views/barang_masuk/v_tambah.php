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
                    <h5 class="mb-0">Tambah Data Barang Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">

                        <!-- Select Tipe -->
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="basic-default-message">Pilih Tipe</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                   <input class="form-check-input" name="tipe" type="radio" id="antar_toko" value="antar_toko">
                                   <label for="antar_toko" class="form-check-label">Antar Toko Ke Gudang</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tipe" type="radio" id="gudangsupplier" value="gudangsupplier">
                                    <label for="gudangsupplier" class="form-check-label">Supplier Ke Toko</label>
                                </div>
                                <!-- <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tipe" type="radio" id="toko_luar" value="toko_luar">
                                    <label for="toko_luar" class="form-check-label">Toko Luar</label>
                                </div> -->
                                <?= form_error('tipe', '<small class="text-danger">', '</small>') ?>
                            </div>
                        </div>

                        <input type="hidden" name="toko_id" id="toko_id" class="form-control" readonly value="<?= $toko_name['id_toko'] ?>">

                        <!-- tipe antar toko -->
                        <div id="form-barangmasuk-toko" style="display: none;">
                            <div class="row mb-3" id="dari_toko">
                                <label class="col-sm-2 col-form-label" for="dari_toko">Dari Toko</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="dari_toko" id="dari_toko" value="<?= $toko_name['nama_toko'] ?>" readonly>
                                    <?= form_error('dari_toko', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Barang Toko</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="harga_id_toko" id="harga_id_toko" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <?= form_error('harga_id_toko', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3" id="ke_toko">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Ke Toko Atau Gudang</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="ke_toko" id="ke_toko" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($ketoko as $kToko) : ?>
                                            <option value="<?= $kToko['id_toko'] ?>"><?= $kToko['nama_toko'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('ke_toko', '<small class="text-danger">', '</small>') ?>
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
                                    <input type="file" name="bukti_file" class="form-control dropify" data-max-file-size="2M" data-allowed-file-extensions="pdf png jpg jpeg" />
                                    <?= form_error('bukti_file', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>


                        <!-- gudang supplier -->
                        <div id="form-barangmasuk-gudang-supplier" style="display: none;">
                            <div class="row mb-3" id="dari_supplier">
                                <label class="col-sm-2 col-form-label" for="supplier">Dari Supplier</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" name="supplier" id="supplier" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <?= form_error('supplier', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3" id="ke_gudangsupplier">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Ke Toko</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2" <?= $this->session->userdata('toko_id') ? 'disabled' : '' ?>  name="gudangsupplierlist" id="gudangsupplierlist" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <?= form_error('gudangsupplierlist', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Tanggal Pembelian</label>
                                <div class="col-sm-3">
                                    <input class="form-control flatpickr" type="text" name="tanggal_barang_masuk_gudangsupp" id="tanggal_barang_masuk_gudangsupp">
                                    <?= form_error('tanggal_barang_masuk_gudangsupp', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-phone">Bukti Masuk</label>
                                <div class="col-sm-10">
                                    <input type="file" name="bukti_file_gudangsupp" class="form-control dropify" data-max-file-size="2M" data-allowed-file-extensions="pdf png jpg jpeg" />
                                    <?= form_error('bukti_file_gudangsupp', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Barang Gudang Supplier</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="harga_id_barang_gudang_supplier" id="harga_id_barang_gudang_supplier" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                        <?php if($this->session->userdata('toko_id')) : ?>
                                            <?php foreach($barangtoko_current as $barang_toko) : ?>
                                                <option value="<?= $barang_toko['id_harga']?>"><?= $barang_toko['nama_barang']?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?= form_error('harga_id_barang_gudang_supplier', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="table-responsive text-nowrap py-2 px-2">
                                    <table class="table table-bordered" id="daftarTableBarangSementara">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah Barang</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- tipe toko luar -->
                        <div id="form-barangmasuk-tokoluar" style="display: none;">
                            <div class="row mb-3" id="dari_toko">
                                <label class="col-sm-2 col-form-label" for="dari_toko_tokoluar">Dari Toko</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="dari_toko_tokoluar" id="dari_toko_tokoluar">
                                    <?= form_error('dari_toko_tokoluar', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3" id="ke_toko">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Ke Toko</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="ke_toko_tokoluar" id="ke_toko_tokoluar" value="<?= $toko_name['nama_toko'] ?>" readonly>
                                    <input type="hidden" name="ke_toko_tokoluar_id" id="ke_toko_tokoluar_id" value="<?= $toko_name['id_toko'] ?>" readonly>
                                    <?= form_error('ke_toko_tokoluar', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Barang Toko</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="harga_id_toko_toko_luar" id="harga_id_toko_toko_luar" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <?= form_error('harga_id_toko_toko_luar', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Tanggal Pembelian</label>
                                <div class="col-sm-3">
                                    <input class="form-control flatpickr" type="text" name="tanggal_barang_masuk_tokoluar" id="tanggal_barang_masuk_tokoluar">
                                    <?= form_error('tanggal_barang_masuk_tokoluar', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jml_masuk_tokoluar">Jumlah Barang</label>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <input type="number" name="jml_masuk_tokoluar" id="jml_masuk_tokoluar" class="form-control" />
                                        <?= form_error('jml_masuk_tokoluar', '<small class="text-danger">', '</small>') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-phone">Bukti Masuk</label>
                                <div class="col-sm-10">
                                    <input type="file" name="bukti_file_tokoluar" class="form-control dropify" data-max-file-size="2M" data-allowed-file-extensions="pdf png jpg jpeg" />
                                    <?= form_error('bukti_file_tokoluar', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                        </div>


                        <div id="form-barangmasuk-gudang" style="display: none;">

                        </div>

                        <div class="row justify-content-end" id="action-button-todo" style="display: none;">
                            <div class="col-sm-2">
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
    $(document).ready(function() {

        $('#harga_id_barang_gudang_supplier').on('change', function() {
            let selectedBarang = $(this).val();
            let toko_id = $('#gudangsupplierlist').val();
            console.log(toko_id);

            let BASE_URL_HARGAIDGUDANG = '<?= base_url() ?>'

            $.ajax({
                url: BASE_URL_HARGAIDGUDANG + 'barang/masuk/getDataBarangAjax',
                type: 'POST',
                data: {
                    id_harga: selectedBarang,
                    id_toko: toko_id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'berhasil') {
                        let barangList = response.data_sementara;
                        let htmlContent = '';

                        $.each(barangList, function(index, barang) {
                            htmlContent += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${barang.nama_barang}</td>
                                <td>
                                    <input type="number" class="form-control" name="jml_masuk_gudangsupp[]" id="jml_masuk_gudangsupp" data-id="${barang.id_barang_masuk}" value="0" min="0" />
                                    <input type="hidden" readonly class="form-control id_barang_masuk" name="id_barang_masuk[]" id="id_barang_masuk" value="${barang.id_barang_masuk}" />
                                    <input type="hidden" readonly class="form-control harga_id" name="harga_id[]" id="harga_id" value="${barang.harga_id}" />
                                </td>
                                <td>
                                    <a class='btn btn-danger btn-sm text-white' onclick='delete_barang(${barang.id_barang_masuk})'><i class='bx bx-trash me-1'></i> Delete</a>
                                </td>
                            </tr>`;
                        });
                        $('#daftarTableBarangSementara tbody').html(htmlContent);
                    }
                }
            });
        });

        $("input[name='tipe']").change(function() {
            let toko_id = $('#toko_id').val();
            const admin_dan_dev = '<?= $this->session->userdata('role_id') ?>';

            const tipe_barang_masuk = $("input[name='tipe']:checked").val();

            // TODO :: ANTAR TOKO
            if (tipe_barang_masuk == 'antar_toko') {
                <?php
                $barangtoko_current_json = json_encode($barangtoko_current);
                ?>
                let barangtoko_current = <?= $barangtoko_current_json ?>;

                $.each(barangtoko_current, function(index, barang) {
                    let option = $('<option>').val(barang.id_harga).text(barang.nama_barang);
                    $('#harga_id_toko').append(option);
                });

                $('#form-barangmasuk-toko').show();
                $('#form-barangmasuk-gudang').hide();
                $('#form-barangmasuk-gudang-supplier').hide();
                $('#form-barangmasuk-tokoluar').hide();
                $('#action-button-todo').show();
            }


            if (tipe_barang_masuk == 'gudang') {
                $('#form-barangmasuk-gudang').show();
                $('#form-barangmasuk-toko').hide();
                $('#form-barangmasuk-gudang-supplier').hide();
                $('#form-barangmasuk-tokoluar').hide();
                $('#action-button-todo').show();
            }

            if (tipe_barang_masuk == 'gudangsupplier') {
                <?php
                $supplierAll = json_encode($supplier);
                // $gudangTokoAll = json_encode($gudangtoko);
                $tokoAll = json_encode($toko_semua);

                $idToko =  $this->session->userdata('toko_id');

                ?>
                let allSupplier = <?= $supplierAll ?>;
                // let allGudang = <?//= $gudangTokoAll ?>;
                let allToko = <?= $tokoAll ?>;
                let idTk = <?= $idToko ? $idToko : 0 ?>;
                
                $.each(allSupplier, function(index, supplier) {
                    let option = $('<option>').val(supplier.id_supplier).text(supplier.nama_supplier);
                    $('#supplier').append(option);
                });

                // $.each(allGudang, function(index, gudang) {
                //     let option = $('<option>').val(gudang.id_toko).text(gudang.nama_toko);
                //     $('#gudangsupplierlist').append(option);
                // });
                $.each(allToko, function(index, toko) {
                    if(idTk == toko.id_toko){
                        let option = $('<option>').val(idTk).text(toko.nama_toko).prop('selected', true);
                        $('#gudangsupplierlist').append(option);
                    }else{
                        let option = $('<option>').val(toko.id_toko).text(toko.nama_toko);
                        $('#gudangsupplierlist').append(option);
                    }
                });

                // let selectedGudangSupplierId = $('#gudangsupplierlist').val();

                // let selectedGudangSupplierName = $('#gudangsupplierlist option:selected').text();


                $('#form-barangmasuk-gudang-supplier').show();
                $('#form-barangmasuk-toko').hide();
                $('#form-barangmasuk-gudang').hide();
                $('#form-barangmasuk-tokoluar').hide();
                $('#action-button-todo').show();

                $('#gudangsupplierlist').on('change', function() {
                    let selectedGudang = $(this).val();
                
                    let BASE_URL_HARGAIDGUDANG = '<?= base_url() ?>'
                    $.ajax({
                        url: BASE_URL_HARGAIDGUDANG + 'barang/masuk/getDataBarangHargaAjax',
                        type: 'POST',
                        data: {
                            toko_id: selectedGudang
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#harga_id_barang_gudang_supplier').empty();

                            $.each(response, function(index, item) {
                                let option = $('<option>').val(item.id_harga).text(item.nama_barang);
                                $('#harga_id_barang_gudang_supplier').append(option);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });

            }

            if (tipe_barang_masuk == 'toko_luar') {

                <?php
                $barangtoko_current_json = json_encode($barangtoko_current);
                ?>
                let barangtoko_current = <?= $barangtoko_current_json ?>;

                $.each(barangtoko_current, function(index, barang) {
                    let option = $('<option>').val(barang.id_harga).text(barang.nama_barang);
                    $('#harga_id_toko_toko_luar').append(option);
                });

                $('#form-barangmasuk-tokoluar').show();
                $('#form-barangmasuk-toko').hide();
                $('#form-barangmasuk-gudang').hide();
                $('#form-barangmasuk-gudang-supplier').hide();
                $('#action-button-todo').show();

            }

            if (admin_dan_dev == 1 || admin_dan_dev == 2 && tipe_barang_masuk == 'toko_luar') {
                $('#nama_toko_luar').show();
                $('#antar_toko_dalam').show();
                $('#antar_toko_dari').hide();

            }

        })
    })

    function delete_barang(id) {

        let toko_id = $('#gudangsupplierlist').val();

        let BASE_URL_HARGAIDGUDANG = '<?= base_url() ?>';

        $.ajax({
            url: BASE_URL_HARGAIDGUDANG + 'barang/masuk/hapusDataBarangAjax',
            type: 'POST',
            data: {
                id_barang_masuk: id,
                id_toko: toko_id
            },
            dataType: 'json',
            success: function(response) {
                let barangList = response.data_sementara;
                let htmlContent = '';

                $.each(barangList, function(index, barang) {
                    htmlContent += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${barang.nama_barang}</td>
                        <td>
                            <input type="number" class="form-control" name="jml_masuk_gudangsupp[]" id="jml_masuk_gudangsupp" data-id="${barang.id_barang_masuk}" value="0" min="0" />
                            <input type="hidden" readonly class="form-control id_barang_masuk" name="id_barang_masuk[]" id="id_barang_masuk" value="${barang.id_barang_masuk}" />
                            <input type="hidden" readonly class="form-control harga_id" name="harga_id[]" id="harga_id" value="${barang.harga_id}" />
                        </td>
                        <td>
                            <a class='btn btn-danger btn-sm text-white' onclick='delete_barang(${barang.id_barang_masuk})'><i class='bx bx-trash me-1'></i> Delete</a>
                        </td>
                    </tr>`;
                });
                $('#daftarTableBarangSementara tbody').html(htmlContent);
            }
        });
    }

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