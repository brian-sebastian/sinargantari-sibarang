<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>
    <?php if ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata("gagal") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#cari_modal">Form pencarian</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Toko</th>
                                    <th>Kode order</th>
                                    <th>Nama customer</th>
                                    <th>Hp customer</th>
                                    <th>Status order</th>
                                    <th>Status pembayaran</th>
                                    <!-- <th>Tipe order</th> -->
                                    <th>Tipe pengiriman</th>
                                    <th>Biaya kirim</th>
                                    <th>Total order</th>
                                    <th>Tanggal order</th>
                                    <th>Tanggal terakhir order</th>
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

    <!-- Modal cari -->
    <div class="modal fade" id="cari_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="form_cari" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="text-secondary">Cari <?= strtolower($title) ?></h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <?php if (!$this->session->userdata("toko_id")) : ?>
                                    <label for="toko_id" class="form-label">Toko</label>
                                    <select name="toko_id" id="toko_id" class="form-control select2-cari-modal" style="width: 100%;">
                                        <option value="">-- pilih --</option>
                                        <?php foreach ($data_toko as $dt) : ?>
                                            <option value="<?= $dt["id_toko"] ?>"><?= $dt["nama_toko"] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                <?php else : ?>
                                    <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                                <?php endif ?>
                                <small class="text-danger" id="err_toko_id"></small>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label for="kode_order" class="form-label">Kode order</label>
                                <input type="text" name="kode_order" id="kode_order" class="form-control" placeholder="Kode order">
                                <small class="text-danger" id="err_kode_order"></small>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="nama_cust" class="form-label">Nama customer</label>
                                <input type="text" name="nama_cust" id="nama_cust" class="form-control" placeholder="Nama customer">
                                <small class="text-danger" id="err_nama_cust"></small>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="hp_cust" class="form-label">Hp customer</label>
                                <input type="text" name="hp_cust" id="hp_cust" class="form-control" placeholder="Hp customer">
                                <small class="text-danger" id="err_hp_cust"></small>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="status" class="form-label">Status order</label>
                                <select name="status" id="status" class="form-control select2-cari-modal" style="width: 100%;">
                                    <option value="">-- pilih --</option>
                                    <option value="1">Belum di konfirmasi</option>
                                    <option value="2">Telah di konfirmasi</option>
                                    <option value="3">Dikemas</option>
                                    <option value="4">Dikirim</option>
                                    <option value="5">Selesai</option>
                                    <option value="99">Batal</option>
                                </select>
                                <small class="text-danger" id="err_status"></small>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="paidst" class="form-label">Status bayar</label>
                                <select name="paidst" id="paidst" class="form-control select2-cari-modal" style="width: 100%;">
                                    <option value="">-- pilih --</option>
                                    <option value="0">Belum lunas</option>
                                    <option value="1">Lunas</option>
                                </select>
                                <small class="text-danger" id="err_paidst"></small>
                            </div>
                            <!-- <div class="col-md-6 mb-4">
                                <label for="tipe_order" class="form-label">Tipe order</label>
                                <select name="tipe_order" id="tipe_order" class="form-control select2-cari-modal" style="width: 100%;">
                                    <option value="">-- pilih --</option>
                                    <option value="Marketplace">Marketplace</option>
                                    <option value="Kasir">Kasir</option>
                                </select>
                                <small class="text-danger" id="err_paidst"></small>
                            </div> -->
                            <div class="col-md-12 mb-4">
                                <label for="tipe_pengiriman" class="form-label">Tipe pengiriman</label>
                                <input type="text" name="tipe_pengiriman" id="tipe_pengiriman" class="form-control" placeholder="Tipe pengiriman">
                                <small class="text-danger" id="err_tipe_pengiriman"></small>
                            </div>
                            <div class="col-md-12 mb-4">
                                <label for="created_at" class="form-label">Range tanggal order</label>
                                <input type="text" name="created_at" id="created_at" class="form-control rangepicker" placeholder="Range tanggal order">
                                <small class="text-danger" id="err_created_at"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="btn_cari">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal cari -->

    <script>
        $(function() {
            initializeTable("server-side", {
                ajax: {
                    url: "<?= site_url('barang_cacat/sales_order_cacat/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.toko_id = $("#toko_id").val()
                        data.kode_order = $("#kode_order").val()
                        data.nama_cust = $("#nama_cust").val()
                        data.hp_cust = $("#hp_cust").val()
                        data.status = $("#status").val()
                        data.paidst = $("#paidst").val()
                        data.tipe_order = $("#tipe_order").val()
                        data.tipe_pengiriman = $("#tipe_pengiriman").val()
                        data.created_at = $("#created_at").val()
                    },
                    dataType: "json",
                }
            })

            $("#btn_cari").on("click", function() {

                $("#btn_cari").prop("disabled", true)
                $("#btn_cari").html("Mohon tunggu...")
                setTimeout(function() {

                    dynamicTable.draw()
                    $("#btn_cari").prop("disabled", false)
                    $("#btn_cari").html("Cari")
                    $('#cari_modal').modal('hide')

                }, 1000)

            })

        })
    </script>