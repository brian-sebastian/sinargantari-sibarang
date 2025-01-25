<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

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

        <?php $enkripsi_toko_id = $this->secure->encrypt_url($toko_id); ?>

        <div class="card mt-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h5>Daftar Barang Toko</h5>
                        <br>
                        <form id="cari" method="post">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3">
                                    <label for="toko_id">Kategori : </label>
                                    <select name="kategori_id" id="kategori_id" class="form-control select2">
                                        <option value="">Semua</option>
                                        <?php foreach ($kategori as $kt) : ?>
                                            <option value="<?= $kt["id_kategori"] ?>"><?= $kt["nama_kategori"] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-6 text-left">
                                    <button type="button" class="btn btn-sm text-white" id="btn_cari" style="background-color: #213363;">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-6">
                        <div class="float-end">
                            <a class="btn btn-sm btn-warning" id="btn_excel" href="javascript:void(0)">Export</a>

                            <?php if ($harga_temp) : ?>
                                <a href="<?= site_url('barang/barang_toko/temp/') . $enkripsi_toko_id ?>" class="btn btn-sm btn-warning"><span class="tf-icons bx bx-block"></span>&nbsp; Lihat barang gagal</a>
                            <?php endif ?>
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
                                <th>Nama Toko</th>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th>Stock</th>
                                <?php if($gudang_tampilan) : ?>
                                    <th>Harga jual</th>
                                <?php endif; ?>
                                <th>Berat Barang</th>
                                <th>Barcode Barang</th>
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





    <script>
        $(function() {

            if ($("#dynamicTable").length > 0) {

                initializeTable("server-side", {
                    ajax: {
                        url: "<?= site_url('gudang/barang_toko/ajax') ?>",
                        type: "post",
                        data: function(data) {
                            data.toko_id = "<?= $toko_id ?>";
                            data.kategori_id = $('#kategori_id').val();
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

            const obj = {
                toko_id: "<?= $toko_id ?>",
                kategori_id: $('#kategori_id').val()
            };

            const encodeData = encodeURIComponent(JSON.stringify(obj));

            window.location = "<?= site_url('gudang/barang_toko/excel') ?>?field=" + encodeData;
        })

    </script>

</div>