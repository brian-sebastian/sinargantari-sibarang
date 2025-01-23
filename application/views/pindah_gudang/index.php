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
                    <h5 class="mb-0">Tambah Stok Barang Dari Gudang Ke Gudang</h5>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">

                        <!-- tipe antar toko -->
                        <div class="container">
                            <div class="row mb-3">
                                <label class="col-sm-2 mb-3 col-form-label" for="gudang_dari_id">Dari Gudang</label>
                                <div class="col-sm-10 mb-3">
                                    <select class="form-control select2 " name="gudang_dari_id" id="gudang_dari_id" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                         <?php foreach($data_gudangs as $data_gudang):?>
                                            <option value="<?= $data_gudang["id_toko"]?>"><?= $data_gudang["nama_toko"] ?></option>
                                        <?php endforeach?>
                                    </select>
                                     <?= form_error('gudang_dari_id', '<small class="text-danger">', '</small>') ?>
                                     <?= form_error('gudang_dari_id', '<small class="text-danger">', '</small>') ?>
                                </div>
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Ke Gudang</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="gudang_ke_id" id="gudang_ke_id" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <?= form_error('gudang_ke_id', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">List Barang</label>
                                <div class="col-sm-10">
                                    <select class="form-control select2 " name="barang_id" id="barang_id" style="width: 100%;">
                                        <option value="">-- Pilih --</option>
                                    </select>
                                    <?= form_error('barang_id', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Tanggal Perpindahan</label>
                                <div class="col-sm-3">
                                    <input class="form-control flatpickr" type="text" name="tanggal_barang_masuk" id="tanggal_barang_masuk" value="<?= set_value("tanggal_barang_masuk")?>">
                                    <?= form_error('tanggal_barang_masuk', '<small class="text-danger">', '</small>') ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="jml_masuk">Jumlah Barang</label>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-merge">
                                        <input type="number" name="jml_masuk" id="jml_masuk" class="form-control" value="<?= set_value("jml_masuk")?>" />
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

<script>

 $(function(){

    $("body").on("change", "#gudang_dari_id", function(){

        const thisObject = $(this)

        if(thisObject.val()){

            $.ajax({
                type: "post",
                url: "<?= site_url('gudang/pindah_gudang/data_gudang') ?>",
                data: {
                    gudang_dari_id: thisObject.val()
                },
                dataType: "json",
                beforeSend: function(){

                    $("#barang_id").find('option').not(':first').remove();
                    $("#gudang_ke_id").find('option').not(':first').remove();
                },
                success: function (response) {

                    const data_gudang = response.data_gudang
                    const data_barang = response.data_barang
                    
                    data_gudang.forEach(element => {

                        var option = new Option(element.nama_toko, element.id_toko, false, false);
                        $("#gudang_ke_id").append(option)

                    });

                    data_barang.forEach(element => {

                        var option = new Option(element.nama_barang, element.id_barang, false, false);
                        $("#barang_id").append(option)

                    });

                }
            });

        }else{

            $("#barang_id").find('option').not(':first').remove();
            $("#gudang_ke_id").find('option').not(':first').remove();

        }

    })

 })

</script>

