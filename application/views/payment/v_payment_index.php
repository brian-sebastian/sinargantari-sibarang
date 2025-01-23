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
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambah_modal">Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bank</th>
                                    <th>Rekening</th>
                                    <th>Atas nama</th>
                                    <th>Nomor kartu</th>
                                    <th>Tanggal expired</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_payment as $dp) : ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $dp["bank"] ?></td>
                                        <td><?= $dp["rekening"] ?></td>
                                        <td><?= $dp["an_rekening"] ?></td>
                                        <td><?= ($dp["no_kartu"]) ? $dp["no_kartu"] : "-" ?></td>
                                        <td><?= (substr($dp["expired_date"], 0, 1)) ? $dp["expired_date"] : "-"  ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm detail_button" data-bs-toggle="modal" data-bs-target="#ubah_modal" data-id="<?= $dp["id_payment"] ?>"><i class=" bx bx-edit-alt me-1"></i>&nbsp;Ubah</button>
                                                <a href="<?= site_url('setting/payment/hapus/' . $this->secure->encrypt_url($dp["id_payment"])) ?>" onclick="return confirm(`Apakah anda yakin ingin menghapus data ${'<?= $dp['bank'] . ' - ' . $dp['rekening'] ?>'}?`)" class="btn btn-danger btn-sm"><i class="bx bx-trash me-1"></i>Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $no++;
                                endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Bank</th>
                                    <th>Rekening</th>
                                    <th>Atas nama</th>
                                    <th>Nomor kartu</th>
                                    <th>Tanggal expired</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
            <!-- Modal Tambah -->
            <div class="modal fade" id="tambah_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="form_tambah" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah <?= $title ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="bank_id"><span class="text-danger"> * </span> Bank</label>
                                        <select name="bank_id" id="bank_id" class="form-control select2-tambah-modal w-100">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data_bank as $db) : ?>
                                                <option value="<?= $db["id_bank"] ?>"><?= $db["bank"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <small class="text-danger" id="err_bank_id"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="rekening"><span class="text-danger"> * </span> Rekening : </label>
                                        <input type="text" name="rekening" id="rekening" class="form-control" placeholder="Rekening">
                                        <small class="text-danger" id="err_rekening"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="an_rekening"><span class="text-danger"> * </span> Atas nama : </label>
                                        <input type="text" name="an_rekening" id="an_rekening" class="form-control" placeholder="Atas nama">
                                        <small class="text-danger" id="err_an_rekening"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="no_kartu"> No kartu : </label>
                                        <input type="text" name="no_kartu" id="no_kartu" class="form-control" placeholder="Nomor kartu">
                                        <small class="text-danger" id="err_no_kartu"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="expired_date"> Tanggal expired : </label>
                                        <input type="text" name="expired_date" id="expired_date" class="form-control flatpickr" placeholder="Nomor kartu">
                                        <small class="text-danger" id="err_expired_date"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-primary" id="btn_tambah">Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Modal Tambah -->

            <!-- Modal Ubah -->
            <div class="modal fade" id="ubah_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="form_ubah" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ubah <?= $title ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <label for="bank_id"><span class="text-danger"> * </span> Bank</label>
                                        <select name="bank_id" id="bank_idU" class="form-control select2-ubah-modal w-100">
                                            <option value="">-- Pilih --</option>
                                            <?php foreach ($data_bank as $db) : ?>
                                                <option value="<?= $db["id_bank"] ?>"><?= $db["bank"] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        <small class="text-danger" id="err_bank_idU"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="rekening"><span class="text-danger"> * </span> Rekening : </label>
                                        <input type="text" name="rekening" id="rekeningU" class="form-control" placeholder="Rekening">
                                        <small class="text-danger" id="err_rekeningU"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="an_rekening"><span class="text-danger"> * </span> Atas nama : </label>
                                        <input type="text" name="an_rekening" id="an_rekeningU" class="form-control" placeholder="Atas nama">
                                        <small class="text-danger" id="err_an_rekeningU"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="no_kartu"> No kartu : </label>
                                        <input type="text" name="no_kartu" id="no_kartuU" class="form-control" placeholder="Nomor kartu">
                                        <small class="text-danger" id="err_no_kartuU"></small>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="expired_date"> Tanggal expired : </label>
                                        <input type="text" name="expired_date" id="expired_date" class="form-control flatpickr" placeholder="Nomor kartu">
                                        <small class="text-danger" id="err_expired_dateU"></small>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" id="id_paymentU" name="id_payment" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-warning" id="btn_ubah">Ubah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Modal Ubah -->

        </div>
    </div>
    <script>
        $(function() {

            $("#btn_tambah").on("click", function() {

                const data_form = $("#form_tambah").serialize()

                $.ajax({

                    url: "<?= site_url("setting/payment/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_bank_id").html("")
                        $("#err_rekening").html("")
                        $("#err_an_rekening").html("")
                        $("#err_no_kartu").html("")
                        $("#err_expired_date").html("")

                        // empty element error
                        $("#bank_id").val("")
                        $("#rekening").val("")
                        $("#an_rekening").val("")
                        $("#no_kartu").val("")
                        $("#expired_date").val("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_bank_id) {
                                $("#err_bank_id").html(result.err_bank_id)
                            }
                            if (result.err_rekening) {
                                $("#err_rekening").html(result.err_rekening)
                            }
                            if (result.err_an_rekening) {
                                $("#err_an_rekening").html(result.err_an_rekening)
                            }
                            if (result.err_no_kartu) {
                                $("#err_no_kartu").html(result.err_no_kartu)
                            }
                            if (result.err_expired_date) {
                                $("#err_expired_date").html(result.err_expired_date)
                            }

                        } else {

                            window.location.reload()
                        }

                        $("#btn_tambah").prop("disabled", false)
                        $("#btn_tambah").html("Tambah")

                    }
                })
            })

            $(".detail_button").on("click", function() {

                const id = $(this).data("id")

                $.ajax({

                    url: "<?= site_url("setting/payment/detail") ?>",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    beforeSend: function() {

                        $("#btn_ubah").prop("disabled", true)

                        // clear element error
                        $("#err_bank_idU").html("")
                        $("#err_rekeningU").html("")
                        $("#err_an_rekeningU").html("")
                        $("#err_no_kartuU").html("")
                        $("#err_expired_dateU").html("")

                        // empty element error
                        $("#bank_idU").val("")
                        $("#rekeningU").val("")
                        $("#an_rekeningU").val("")
                        $("#no_kartuU").val("")
                        $("#expired_dateU").val("")
                        $("#id_paymentU").val("")

                    },
                    success: function(result) {

                        $("#btn_ubah").prop("disabled", false)

                        if (result.status == "berhasil") {

                            const response = result.response

                            $("#bank_idU").val(response.bank_id).trigger("change")
                            $("#rekeningU").val(response.rekening)
                            $("#an_rekeningU").val(response.an_rekening)
                            $("#no_kartuU").val(response.no_kartu)
                            $("#expired_dateU").val(response.expired_date)
                            $("#id_paymentU").val(response.id_payment)

                        } else {

                            window.location.reload()
                        }

                    }

                })

            })

            $("#btn_ubah").on("click", function() {

                const data_form = $("#form_ubah").serialize()

                $.ajax({
                    url: "<?= site_url('setting/payment/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_bank_idU").html("")
                        $("#err_rekeningU").html("")
                        $("#err_an_rekeningU").html("")
                        $("#err_no_kartuU").html("")
                        $("#err_expired_dateU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_bank_idU) {
                                $("#err_bank_idU").html(result.err_bank_idU)
                            }
                            if (result.err_rekeningU) {
                                $("#err_rekeningU").html(result.err_rekeningU)
                            }
                            if (result.err_an_rekeningU) {
                                $("#err_an_rekeningU").html(result.err_an_rekeningU)
                            }
                            if (result.err_no_kartuU) {
                                $("#err_no_kartuU").html(result.err_no_kartuU)
                            }
                            if (result.err_expired_dateU) {
                                $("#err_expired_dateU").html(result.err_expired_dateU)
                            }

                        } else {

                            window.location.reload()
                        }

                        $("#btn_ubah").html("Ubah")
                        $("#btn_ubah").prop("disabled", false)

                    }
                })

            })

        })
    </script>