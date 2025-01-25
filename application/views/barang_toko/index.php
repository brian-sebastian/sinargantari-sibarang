<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $subtitle ?></h4>

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

    <?php if ($toko_id) : ?>

        <?php  $enkripsi_toko_id = $this->secure->encrypt_url($toko_id); ?>
        
        <div class="card mt-2">
            <div class="card-header">
                <div class="row">
                    <div class="col mb-2">
                        <h5>Daftar Barang Toko</h5>
                    </div>
                    <div class="col mb-2">
                        <div class="float-end">
                            <a href="<?= base_url('barang/barang_toko/tambah') . "?toko=" . $enkripsi_toko_id ?>" class="btn btn-sm btn-primary">Tambah</a>
                            <a href="<?= site_url('barang/barang_toko/export') . ((isset($_GET["toko"]) && !empty($_GET["toko"])) ? '?toko=' . $_GET["toko"] : '') ?>" class="btn btn-sm btn-warning">Export</a>
                            <a href="" data-bs-toggle="modal" data-bs-target="#modalImport" class="btn btn-sm btn-success">Import</a>
                            <button type="button" class="btn btn-sm btn-danger" id="deleteSelected">Hapus Barang Terpilih</button>
                            <?php if ($harga_temp) : ?>
                                <a href="<?= site_url('barang/barang_toko/temp/') . $enkripsi_toko_id ?>" class="btn btn-sm btn-warning"><span class="tf-icons bx bx-block"></span>&nbsp; Lihat barang gagal</a>
                            <?php endif ?>
                        </div>
                    </div>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-8">
                            <?php if($kategori_barang):?>
                                <div class="form-group">
                                    <select name="category" class="form-control form-control-sm form-select">
                                        <option>-- Pilih --</option>
                                        <?php foreach($kategori_barang as $kategori):?>
                                            <option value="<?= $kategori["id_kategori"]?>"><?= $kategori["nama_kategori"]?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive text-nowrap py-2 px-2">
                    <table class="table table-striped dt-responsive nowrap py-1 px-1" id="dynamicTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th><input class="form-check-input" type="checkbox" value="" id="selectAllCheckbox"></th>
                                <th>Barcode Barang</th>
                                <th>Barang</th>
                                <th>Kategori</th>

                                <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2) { ?>
                                <th>Harga Pokok</th>
                                <?php } ?>

                                <th>Harga Toko</th>
                                <th>Stok Toko</th>
                                <th>Stok Gudang</th>
                                <th>Stok Tersedia</th>
                                <th>Berat Barang</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h6>Pilih Toko</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <form action="" method="get">
                        <label for="type" class="form-label">Toko</label>
                        <select name="toko" id="toko" class="form-select mb-2">
                            <?php foreach ($toko as $shop) : ?>
                                <option value="<?= $this->secure->encrypt_url($shop['id_toko']) ?>" <?php if (isset($_GET["toko"]) &&  ($this->secure->decrypt_url($_GET["toko"]) == $shop["id_toko"])) {
                                                                                                        echo "selected";
                                                                                                    } ?>><?= $shop['nama_toko'] ?></option>
                            <?php endforeach ?>
                        </select>

                        <button type="submit" class="btn btn-sm btn-primary">Pilih Toko</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif  ?>

    <!-- Modal -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalDoImport" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= base_url('barang/barang_toko/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDoImport">Import Data Barang Toko</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <p>Silahkan download file format excel berikut : <a href="<?= base_url('assets/file_format_import/format_barang_toko_import_2.xlsx') ?>" class="btn btn-sm btn-info">Download</a></p>
                            </div>
                            <div class="col">
                                <label for="file_barang">File Excel Barang Toko</label>
                                <input type="file" class="form-control" name="file_barang" id="file_barang_toko" max accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $toko_id ?>" required>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade modal_history_barangtoko_table" id="history_barang_toko_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">History Barang Toko</h5>
                    <button type="button" id="close_modal_history_barang_toko" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="content-table-history-barang-toko">

                        <div id="contents-tbldatabarangtoko">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close_modal_history_barang_toko" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
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
                            <?php if ($this->session->userdata('toko_id')) { ?>
                                <form method="post" id="form-delete-description">
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Masukkan Alasan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan">
                                        <small class="text-danger" id="err_keterangan"></small>
                                    </div>
                                    <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                    <button type="button" id="removeData" class="btn btn-danger">Hapus</button>
                                </form>
                            <?php } else { ?>
                                <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                <button type="button" id="removeData" class="btn btn-danger">Hapus</button>
                            <?php } ?>
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



    <script>
        $(function() {

            if ($("#dynamicTable").length > 0) {

                initializeTable("server-side", {
                    ajax: {
                        url: "<?= site_url('barang/barang_toko/ajax') ?>",
                        type: "post",
                        data: function(data) {
                            data.kategori_id = "<?= $kategori_id?>"
                            data.toko_id = "<?= $toko_id ?>"
                        },
                        dataType: "json",
                    },
                    "ordering": false,
                    "searching": true,
                    "info": true,
                    "lengthChange": true,
                    "paging": true,
                })

                $(".table").on("click", ".removes", function() {
                    const id = $(this).data("id")
                    let BASE_URL = "<?= base_url(); ?>";
                    $('body').on('click', '#removeData', function() {
                        $.ajax({
                            type: "POST",
                            url: BASE_URL + 'barang/barang_toko/hapus/' + id,
                            data: $('#form-delete-description').serialize(),
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
                            url: BASE_URL + 'barang/barang_toko/request_hapus_again/' + id,
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

                    // $("#removeData").attr("href", "<?= base_url('barang/hapus/') ?>" + id);
                })

            }

            $('body').on('click', '#history_barang_toko', function() {
                let id_harga = $(this).attr('data-idharga');
                let toko_id = $(this).attr('data-idtoko');
                let barang_id = $(this).attr('data-barangid');

                let BASE_URL = "<?= base_url(); ?>";

                $.ajax({
                    type: "GET",
                    url: BASE_URL + 'barang/barang_toko/getHistoryPriceBarangToko',
                    data: {
                        id_harga: id_harga,
                        toko_id: toko_id,
                        barang_id: barang_id,
                    },
                    dataType: "json",
                    success: function(response) {
                        // console.log(response)
                        if (response.total > 0) {
                            $('.modal_history_barangtoko_table #contents-tbldatabarangtoko').html(response.view);
                        }
                        if (response.total == 0) {
                            let contentModal = `<div class="alert alert-danger" role="alert">
                                        Tidak ada data history
                                        </div>`;
                            $('.modal_history_barangtoko_table #contents-tbldatabarangtoko').html(contentModal);

                        }
                    }
                });

                $('#history_barang_toko_modal').modal('show')
            });

            /**
             * TODO : Close Modal History When Click Close
             */
            $('body').on('click', '#close_modal_history_barang_toko', function() {
                $('#history_barang_toko_modal').modal('hide');
            });


            /**
             * TODO : Checked All
             * 
             */

            // Check All Checkbox
            $('#selectAllCheckbox').on('change', function() {
                $('.checkbox_data').prop('checked', $(this).prop('checked'));
            });

            // Individual Checkbox
            $('.checkbox_data').on('change', function() {
                $('#selectAllCheckbox').prop('checked', $('.checkbox_data:checked').length === $('.checkbox_data').length);
            });

            // Delete Selected Button
            $('#deleteSelected').on('click', function() {

                let BASE_URL = "<?= base_url(); ?>";
                const searchParams = new URLSearchParams(window.location.search);

                // console.log(searchParams.has('toko'));

                if (searchParams.has('toko')) {
                    let dataToko = searchParams.get('toko');
                    Swal.fire({
                        title: "Apakah Yakin barang toko terpilih?",
                        text: "Data Yang dihapus dari daftar barang toko, tidak dapat dikembalikan",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#db2121",
                        cancelButtonColor: "#7a7a7a",
                        confirmButtonText: "Hapus"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let selectedItems = $('.checkbox_data:checked').map(function() {
                                return this.value;
                            }).get();

                            if (selectedItems.length > 0) {
                                $.ajax({
                                    url: BASE_URL + 'barang/barang_toko/deleteSelectedItems',
                                    method: 'POST',
                                    data: {
                                        ids: selectedItems,
                                        toko: dataToko,
                                    },
                                    success: function(response) {
                                        // Handle success
                                        let responseData = JSON.parse(response);

                                        if (responseData.err_code == 0) {
                                            Swal.fire({
                                                icon: "success",
                                                title: "Success",
                                                text: `${responseData.message}`,
                                                timer: 1500
                                            }).then((result) => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Gagal hapus data",
                                                text: `${responseData.message}`,
                                            });
                                        }
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal hapus",
                                    text: "Kamu belum memilih data yang akan dihapus",
                                });
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal ",
                        text: "Wrong Parameter",
                    });
                }



            });


        })
    </script>

</div>