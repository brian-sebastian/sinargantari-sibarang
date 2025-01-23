<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <div class="row" id="pilih-toko">
        <div class="col-xl">
            <?php if ($this->session->userdata('toko_id') == '') : ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4>Pilih Toko</h4>
                                <br>
                                <form>
                                    <div class=" row align-items-center">
                                        <div class="col-10">
                                            <select class="form-control select2" name="toko_id" id="toko_id">
                                                <option value="">-- Pilih Toko --</option>
                                                <?php foreach ($toko as $dtToko) : ?>
                                                    <?php $select = ($this->session->userdata('toko_id') == $dtToko['id_toko']) ? 'selected' : ''; ?>
                                                    <option value="<?= $dtToko['id_toko'] ?>" <?= $select ?>><?= $dtToko['nama_toko'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-2 text-left ">
                                            <button type="button" class="btn btn-sm text-white" id="btn_cari" style="background-color: #213363;">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($this->session->userdata('toko_id') == '') : ?>
        <div class="alert alert-danger alert-dismissible " id="pesan-eror" role="alert">
            Data Tidak Dapat Ditampilkan. Kamu Belum Memilih Toko. Silahkan Memilih Toko
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif; ?>

    <!-- Basic Layout -->
    <div class="row d-none" id="row-table">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button type="button" id="add_idtoko" class="btn btn-primary btn-sm"><span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1 w-100" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Nama Toko</th>
                                    <th>Jumlah Stok</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                "ajax": {
                    url: "<?= site_url('barang_cacat/master_cacat/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.toko_id = $('#toko_id').val()

                    },
                    dataType: "json",
                },
                drawCallback: function(settings) {

                    const toko_id = $('#toko_id').val();
                    const toko = '<?= $this->session->userdata('toko_id') ?>';

                    if (toko.length > 0) {
                        $('#pesan-eror').addClass('d-none');
                        $('#pilih-toko').addClass('d-none');
                        $('#row-table').removeClass('d-none');
                    } else if (toko_id) {
                        $('#row-table').removeClass('d-none');
                        $('#pesan-eror').addClass('d-none');
                    } else {
                        $('#row-table').addClass('d-none');
                    }

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
    </script>