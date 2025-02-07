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
    <form action="" method="get">
        <div class="card mb-2 mt-2">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6>Pilih Gudang</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <label for="type" class="form-label">Gudang</label>
                            <select name="gudang" id="gudang" class="form-select mb-2">
                                <option value="">--PILIH GUDANG--</option>
                                <?php foreach ($gudang as $gd) : ?>
                                    <option value="<?= $this->secure->encrypt_url($gd['id_toko']) ?>" 
                                        <?php 
                                            if (isset($_GET["gd"]) && ($this->secure->decrypt_url($_GET["gd"]) == $gd["id_toko"])) 
                                            {
                                                echo "selected";
                                            } 
                                        ?>>
                                        <?= $gd['nama_toko'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6>Pilih Toko</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <label for="type" class="form-label">Toko</label>
                            <select name="toko" id="toko" class="form-select mb-2">
                                <option value="">--PILIH TOKO--</option>
                                <?php foreach ($toko as $shop) : ?>
                                    <option value="<?= $this->secure->encrypt_url($shop['id_toko']) ?>" <?php if (isset($_GET["toko"]) &&  ($this->secure->decrypt_url($_GET["toko"]) == $shop["id_toko"])) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= $shop['nama_toko'] ?></option>
                                <?php endforeach ?>
                                <option value="TOKO_LUAR_TL_NONSHOP">TOKO LUAR (SELAIN DARI DAFTAR TOKO)</option>
                            </select>
                            <input type="text" class="form-control d-none" name="nama_toko" id="nama_toko" placeholder="Nama Toko Beli">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card mb-2 mt-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-sm btn-primary d-none" id="btn-savetlwh" type="button">
                            <span class="tf-icons bx bxs-save"></span>&nbsp; Simpan
                        </button>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control d-none" name="cari_barang" id="cari_barang" placeholder="Cari Nama Barang">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary" id="btn-cari" type="button">
                                    <span class="tf-icons bx bxs-save"></span>&nbsp; cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped nowrap py-1 px-1" id="daftarTableBarangToko">
                            <thead>

                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>





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