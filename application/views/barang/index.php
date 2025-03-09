<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>


    <?php if ($this->session->flashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($this->session->flashdata('message_error')) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata('message_error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <a href="<?= base_url('barang/list/tambah') ?>" class="btn btn-sm btn-primary"><span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah</a>
                    <a href="" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success"><span class='tf-icons bx bx-import'></span>&nbsp; Import</a>
                    <button type="button" class="btn btn-sm btn-danger" id="printBarcode"><span class='tf-icons bx bx-file'></span>&nbsp; Print barcode</button>
                    <?php if ($barang_temp) : ?>
                        <a href="<?= site_url('barang/list/temp') ?>" class="btn btn-sm btn-warning"><span class="tf-icons bx bx-block"></span>&nbsp; Lihat barang gagal</a>
                    <?php endif ?>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <!-- <table class="table table-striped dt-responsive nowrap datatables py-1 px-1"> -->
                <table class="table table-striped nowrap py-1 px-1" id="dynamicTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Gambar Barang</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Barcode Barang</th>
                            <th class="text-center">Actions</th>
                            <th><input type="checkbox" class="chooseBarcode"></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->


    <!-- Modal -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalDoImport" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('barang/list/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDoImport">Import Data Barang</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <p>Silahkan download file format excel berikut : <a href="<?= base_url('assets/file_format_import/format_barang_import.xlsx') ?>" class="btn btn-sm btn-info">Download</a></p>
                            </div>
                            <div class="col">
                                <label for="file_barang">File Excel Barang</label>
                                <input type="file" class="form-control" name="file_barang" id="file_barang" max accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Barcode <span id="name_barang_barcode"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit_data" method="POST">
                    <input type="hidden" name="id_barang" id="id_barang">
                    <div class="modal-body">

                        <div class="row">
                            <!-- Account -->

                            <div class="mb-3 col-md-12">
                                <label for="barcode_barang" class="form-label">Barcode Barang</label>
                                <input class="form-control" type="text" id="barcode_barang_edit" name="barcode_barang" placeholder="Barcode Barang" autofocus />
                                <input type="hidden" class="form-control" id="barcode_barang_lama" name="barcode_barang_lama" placeholder="Barcode barang lama">
                                <small class="text-danger pl-3" id="err_barcode_barang_edit"></small>
                            </div>

                            <!-- /Account -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-warning" id="simpan_ubah">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade modal_history_table" id="history_barang_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">History Barang</h5>
                    <button type="button" id="close_modal_history" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="content-table-history">

                        <div id="contents-tbldata">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_modal_history" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="lihatBarang_Modal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Detail Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="lihatBarang">
                </div>
            </div>
        </div>
    </div>

    <!-- staticBackdrop removeModal -->
    <div class="modal fade" id="remove_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="mt-4">
                        <h4 class="mb-3">Apakah anda yakin akan menghapus data ini?</h4>
                        <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                        <div class="hstack gap-2 justify-content-center">
                            <form method="post" id="form-delete-description">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Masukkan Alasan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan">
                                    <small class="text-danger" id="err_keterangan"></small>
                                </div>
                                <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                <button type="button" id="removeData" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="remove_again_from_rejected" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <div class="mt-4">
                        <h4 class="mb-3">Apakah anda yakin akan menghapus data ini?</h4>
                        <p class="text-muted mb-4"> Data ini sebelumnya pernah dihapus, tetapi DITOLAK, apa yakin melakukan request hapus lagi?!</p>
                        <div class="hstack gap-2 justify-content-center">
                            <?php if ($this->session->userdata('toko_id')) { ?>
                                <form method="post" id="form-delete-description-request-again">
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Masukkan Alasan</label>
                                        <input type="hidden" class="form-control" id="id_request_reremove" name="id_request_reremove">
                                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                                        <small class="text-danger" id="err_keterangan"></small>
                                    </div>
                                    <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                    <button type="button" id="reRemoveData" class="btn btn-danger">Hapus</button>
                                </form>
                            <?php } else { ?>
                                <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                <button type="button" id="reRemoveData" class="btn btn-danger">Hapus</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--/ Responsive Table -->

<script>
    // function lihat_barang(id) {
    //     // console.log(id)
    //     $('#lihatBarang').empty()
    //     $.get('<?= base_url() ?>barang/list/detail/' + btoa(id), function(data_barang) {
    //         $('#lihatBarang').html(data_barang)
    //         $('#lihatBarang_Modal').modal('show')
    //     })
    // }

    $(function() {

        initializeTable("server-side", {
            ajax: {
                url: "<?= site_url('barang/list/ajax') ?>",
                type: "post",
                dataType: "json",
            },
            "ordering": false,
            "searching": true,
            "info": true,
            "lengthChange": true,
            "paging": true,
        })

        $(".chooseBarcode").on("click", function() {

            const status = $(this).prop('checked')

            if (status) {

                $(".rowBarcode").each(function(index, element) {

                    if (element.checked == false) {

                        element.checked = true

                    }

                })

            } else {

                $(".rowBarcode").each(function(index, element) {

                    if (element.checked == true) {

                        element.checked = false

                    }

                })

            }

        })

        $("#printBarcode").on("click", function() {

            let myBarcode = ""

            $(".rowBarcode").each(function(index, element) {

                if (element.checked == true) {

                    myBarcode += element.value + "_"

                }

            })

            window.location.href = "<?= site_url('barang/list/cetak_barcode?token=') ?>" + btoa(myBarcode)

        })

        $(".table").on("click", ".removes", function() {
            const id = $(this).data("id")
            let BASE_URL = "<?= base_url(); ?>";
            $('body').on('click', '#removeData', function() {
                $.ajax({
                    type: "POST",
                    url: BASE_URL + 'barang/hapus/' + id,
                    data: $('#form-delete-description').serialize(),
                    dataType: "json",
                    success: function(response) {
                        console.error(response);
                        if (response.err_code == 0) {
                            alert(response.message);
                            window.location.reload();
                        } else {
                            $('body #err_keterangan').html(response.message);
                        }
                    }
                });
            });

            // $("#removeData").attr("href", "<?= base_url('barang/hapus/') ?>" + id);
        })

        //proses hapus lagi
        $(".table").on("click", ".re-removes-again", function() {
            const id = $(this).data("id")
            const idreq = $(this).data("idreq")
            $('#id_request_reremove').val(idreq);
            let BASE_URL = "<?= base_url(); ?>";
            $('body').on('click', '#reRemoveData', function() {
                $.ajax({
                    type: "POST",
                    url: BASE_URL + 'barang/request_hapus_again/' + id,
                    data: $('#form-delete-description-request-again').serialize(),
                    dataType: "json",
                    success: function(response) {
                        console.error(response);
                        if (response.err_code == 0) {
                            window.location.reload();
                        } else {
                            $('body #err_keterangan').html(response.message);
                        }
                    }
                });
            });
        })

    })

    function detail_button(id) {

        $('#edit').modal('show');

        $.ajax({

            url: "<?= site_url("barang/list/edit/barcode/") ?>" + btoa(id),
            type: "POST",
            dataType: "JSON",
            beforeSend: function() {

                $("#simpan_ubah").prop("disabled", true)

                $('#err_barcode_barang_edit').html("")
            },
            success: function(result) {
                $("#simpan_ubah").prop("disabled", false)

                if (result.status == "berhasil") {

                    const response = result.response

                    $('#name_barang_barcode').text(response.nama_barang);
                    $("#id_barang").val(response.id_barang)
                    $("#barcode_barang_edit").val(response.barcode_barang)
                    $("#barcode_barang_lama").val(response.barcode_barang)

                } else {

                    alert(result.response)
                    window.location.reload()
                }

            }

        })
    }


    $('#simpan_ubah').on('click', function() {

        const data_form = $('#edit_data').serialize();

        $.ajax({
            url: '<?= base_url('barang/list/edit/barcode') ?>',
            type: "POST",
            data: data_form,
            dataType: "JSON",
            beforeSend: function() {
                $('#err_barcode_barang_edit').html("")

                $("#simpan_ubah").html("Tunggu...")
                $("#simpan_ubah").prop("disabled", true)

            },
            success: function(result) {
                if (result.status == 'error') {

                    if (result.err_barcode_barang_edit) {
                        $('#err_barcode_barang_edit').html(result.err_barcode_barang_edit);
                    }

                } else {
                    alert(result.response);
                    window.location.reload();
                }

                $("#simpan_ubah").html("Ubah")
                $("#simpan_ubah").prop("disabled", false)
            }
        })
    })

    /**
     * TODO : Load History berdasarkan Barang ID
     */
    $('body').on('click', '#history_barang', function() {
        let data_id = $(this).attr('data-id');
        let BASE_URL = "<?= base_url(); ?>";
        // alert(data_id);
        // $('.modal_history_table #idtblshistory').html(data_id);

        $.ajax({
            type: "GET",
            url: BASE_URL + 'barang/getHistoryPriceBarang',
            data: {
                barang_id: data_id
            },
            dataType: "json",
            success: function(response) {
                // console.log(response)
                if (response.total > 0) {
                    $('.modal_history_table #contents-tbldata').html(response.view);
                }
                if (response.total == 0) {
                    let contentModal = `<div class="alert alert-danger" role="alert">
                                        Tidak ada data history
                                        </div>`;
                    $('.modal_history_table #contents-tbldata').html(contentModal);

                }
            }
        });

        $('#history_barang_modal').modal('show')

        // $('#idtblshistory').parent().html(data_id);
    });

    /**
     * TODO : Close Modal History When Click Close
     */
    $('body').on('click', '#close_modal_history', function() {
        $('#history_barang_modal').modal('hide');
    });


    $('body').on('click', '#detail_barang', function() {
        let getAttribute = $(this).attr('data-id');
        let BASE_URL = "<?= base_url(); ?>";
        $.ajax({
            type: "GET",
            url: BASE_URL + 'barang/detail',
            data: {
                id_barang: getAttribute
            },
            dataType: "json",
            success: function(response) {
                console.log(response);
                if (response.err_code == 0) {
                    $('#lihatBarang').html(response.view);
                }
            }
        });
    });
</script>