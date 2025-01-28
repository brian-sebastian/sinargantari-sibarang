<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h4>Pilih Rentang Tanggal Laporan Stok</h4>
                            <br>
                            <form id="cari" method="post">
                                <div class="row align-items-center">
                                    <?php if (!$this->session->userdata("toko_id")) : ?>
                                        <div class="col-md-6 mb-3">
                                            <label for="toko_id">Toko : </label>
                                            <select name="toko_id" id="toko_id" class="form-control select2">
                                                <option value="">Semua</option>
                                                <?php foreach ($data_toko as $dtok) : ?>
                                                    <option value="<?= $dtok["id_toko"] ?>"><?= $dtok["nama_toko"] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    <?php else : ?>
                                        <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                                    <?php endif ?>
                                    <div class="col-md-6 mb-3">
                                        <label for="harga_id">Barang : </label>
                                        <select name="harga_id" id="harga_id" class="form-control select2">
                                            <option value="">- Semua -</option>
                                            <?php $role_id = $this->session->userdata('role_id'); ?>
                                            <?php if($role_id) : ?>
                                                <?php foreach($data_barang_toko as $dt) :?>
                                                    <option value="<?= $dt['id_harga']?>"> <?= $dt['nama_barang']?></option>
                                                <?php endforeach; ?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i class='fs-4 bx bx-calendar'></i></span>
                                            <input class="form-control is-valid rangepicker" id="tanggal" style="background-color: white;" value="" type="text" autofocus required placeholder="Pilih Tanggal" name="tanggal">
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-left ">
                                        <button type="button" class="btn btn-sm text-white" id="btn_cari" style="background-color: #213363;">Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-row-reverse">
                                <div class="page_action mb-4">
                                    <a class="btn btn-sm btn-info" id="btn_excel" href="javascript:void(0)"><img src="<?= base_url() ?>assets/be/img/excel.png" alt="Export Excel" width="40" height="40"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <!-- <th>Toko</th> -->
                                    <th>Kode barang</th>
                                    <th>Nama barang</th>
                                    <th>Jumlah ready stok</th>
                                    <th>Jumlah Stok Toko</th>
                                    <th>Jumlah Stok Gudang</th>
                                    <th>Harga Jual</th>
                                    <th>Jumlah masuk</th>
                                    <th>Jumlah keluar</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            initializeTable("server-side", {
                ajax: {
                    url: "<?= site_url('laporan/stok/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.tanggal = $('#tanggal').val();
                        data.harga_id = $('#harga_id').val();
                        data.toko_id = $("#toko_id").val();
                    },
                    dataType: "json",
                }
            })

            $("#toko_id").on("change", function() {

                const id = $(this).val();

                if (id) {
                    $.ajax({
                        url: "<?= site_url('laporan/stok/barang') ?>",
                        type: "post",
                        data: {
                            toko_id: id
                        },
                        dataType: "json",
                        beforeSend: function() {

                            $("#harga_id").find('option').not(':first').remove();

                        },
                        success: function({
                            status,
                            response
                        }) {

                            if (status == "berhasil") {

                                const listBarang = $("#harga_id")

                                for (const {
                                        id_harga,
                                        nama_barang
                                    } of response) {

                                    const option = new Option(nama_barang, id_harga, false, false);
                                    listBarang.append(option).trigger('change');

                                }

                            } else {

                                alert(response)

                            }

                        }
                    });

                } else {

                    $("#harga_id").find('option').not(':first').remove();

                }

            })

        })

        $("#btn_cari").on("click", function() {
            $("#btn_cari").prop("disabled", true)
            $("#btn_cari").html("Mohon tunggu...")
            setTimeout(function() {

                dynamicTable.draw()
                $("#btn_cari").prop("disabled", false)
                $("#btn_cari").html("Cari")

            }, 1000)

        })

        $("#btn_excel").on("click", function() {

            if ($("#toko_id").val()) {

                const obj = {
                    tanggal: $('#tanggal').val(),
                    harga_id: $("#harga_id").val(),
                    toko_id: $("#toko_id").val()
                };

                const encodeData = encodeURIComponent(JSON.stringify(obj));

                window.location = "<?= site_url('laporan/stok/excel') ?>?field=" + encodeData;

            } else {

                alert("Pilih toko terlebih dahulu")
            }
        })
    </script>