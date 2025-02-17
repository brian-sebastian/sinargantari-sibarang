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