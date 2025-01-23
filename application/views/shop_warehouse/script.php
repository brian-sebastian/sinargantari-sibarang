<script>
    $(document).ready(function() {
        function formatRupiah(angka) {
            var number_string = angka.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return 'Rp ' + rupiah;
        }


        $('#daftarTableBarangToko').on('change', '.checkbox_data', function() {
            if ($('.checkbox_data:checked').length > 0) {
                $('#btn-savetlwh').removeClass('d-none');
            } else {
                $('#btn-savetlwh').addClass('d-none');
            }
        });


        $('#toko').on('change', function() {
            var toko_id = $(this).val();
            $('#daftarTableBarangToko tbody').html('');
            $('#btn-savetlwh').addClass('d-none');
            $('#daftarTableBarangToko').find('input[type="hidden"], input[type="number"], input[type="date"], input[type="checkbox"]').val(''); // Reset input
            $('#daftarTableBarangToko').find('input[type="checkbox"]').prop('checked', false);

            if (toko_id) {

                if (toko_id == 'TOKO_LUAR_TL_NONSHOP') {
                    $('#nama_toko').removeClass('d-none');
                    $('#nama_toko').addClass('d-block');
                    $.ajax({
                        url: '<?= site_url('gudang/toko_gudang/getBarangLuar'); ?>',
                        type: 'GET',
                        data: {
                            toko_id: toko_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                let barangList = response.data;
                                let htmlContent = '';

                                $('#daftarTableBarangToko thead').html(`
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Jml Beli</th>
                                        <th>Tgl Beli</th>
                                        <th>Pilih</th>
                                    </tr>
                                `);
                                $.each(barangList, function(index, barang) {
                                    htmlContent += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${barang.nama_barang}</td>
                                            <td>
                                                <input type="number" class="form-control qty_beli" data-id="${barang.id_barang}" value="0" min="0" />
                                            </td>
                                            <td>
                                                <input type="date" class="form-control tgl_beli" data-id="${barang.id_barang}" />
                                            </td>
                                            <td>
                                                <input type="checkbox" class="checkbox_data" value="${barang.id_barang}" />
                                            </td>
                                        </tr>
                                    `;
                                });

                                $('#daftarTableBarangToko tbody').html(htmlContent);
                            } else {
                                $('#daftarTableBarangToko tbody').html('<tr><td colspan="5" class="text-center">Tidak ada barang di toko ini</td></tr>');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat memuat data barang');
                        }
                    });
                } else {
                    $('#nama_toko').removeClass('d-block');
                    $('#nama_toko').addClass('d-none');
                    $.ajax({
                        url: '<?= site_url('gudang/toko_gudang/getBarangToko'); ?>',
                        type: 'GET',
                        data: {
                            toko_id: toko_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                let barangList = response.data;
                                let htmlContent = '';

                                $('#daftarTableBarangToko thead').html(`
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Harga Jual</th>
                                        <th>Qty Barang Toko</th>
                                        <th>Jml Pindah Gudang</th>
                                        <th>Pilih</th>
                                    </tr>
                                `);


                                $.each(barangList, function(index, barang) {
                                    htmlContent += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${barang.nama_barang}</td>
                                    <td>${formatRupiah(barang.harga_jual)}</td> 
                                    <td>${barang.stok_toko}</td>
                                    <td>
                                        <input type="number" class="form-control qty_pindah" data-id="${barang.id_barang}" value="0" min="0" />
                                    </td>
                                    <td>
                                        <input type="checkbox" class="checkbox_data" value="${barang.id_barang}" />
                                        <input type="hidden" readonly class="form-control id_harga" name="id_harga" id="id_harga" value="${barang.id_harga}" />

                                    </td>
                                </tr>
                            `;
                                });

                                $('#daftarTableBarangToko tbody').html(htmlContent);

                                $('.qty_pindah').on('input', function() {
                                    var stok_toko = parseInt($(this).closest('tr').find('td').eq(3).text());
                                    var qty_pindah = parseInt($(this).val());

                                    if (qty_pindah > stok_toko) {
                                        alert('Jumlah yang dipilih tidak boleh lebih dari stok yang tersedia!');
                                        $(this).val(stok_toko);
                                    } else if (qty_pindah < 1) {
                                        $(this).val(1);
                                    }
                                });

                            } else {
                                $('#daftarTableBarangToko tbody').html('<tr><td colspan="6" class="text-center">Tidak ada barang di toko ini</td></tr>');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat memuat data barang');
                        }
                    });
                }



            } else {
                $('#daftarTableBarangToko tbody').html('');
            }
        });



        // save
        $('#btn-savetlwh').on('click', function() {
            var toko_id = $('#toko').val();
            var gudang_id = $('#gudang').val();

            if (!toko_id || !gudang_id) {
                alert('Pilih toko dan gudang terlebih dahulu!');
                return;
            }

            var barangData = [];
            var valid = true;

            $('.checkbox_data:checked').each(function() {
                var id_barang = $(this).val();
                var qty_beli = $(this).closest('tr').find('.qty_beli').val();
                var tgl_beli = $(this).closest('tr').find('.tgl_beli').val();
                var qty_pindah = $(this).closest('tr').find('.qty_pindah').val();
                var stok_toko = $(this).closest('tr').find('td').eq(3).text(); // Mengambil stok toko dari kolom
                var id_harga = $(this).closest('tr').find('.id_harga').val();


                barangData.push({
                    id_barang: id_barang,
                    qty_beli: qty_beli,
                    qty_pindah: qty_pindah,
                    tgl_beli: tgl_beli,
                    gudang_id: gudang_id,
                    id_harga: id_harga,
                });

            });

            // Jika validasi tidak lolos atau tidak ada barang yang dipilih
            if (!valid || barangData.length == 0) {
                alert('Pilih barang yang ingin disimpan dan pastikan jumlah tidak melebihi stok!');
                return;
            }

            $.ajax({
                url: '<?= site_url('gudang/toko_gudang/saveBarang'); ?>',
                type: 'POST',
                data: {
                    toko_id: toko_id,
                    gudang_id: gudang_id,
                    barang_data: barangData
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        alert('Data berhasil disimpan!');
                        location.reload();
                    } else {
                        alert('Terjadi kesalahan saat menyimpan data!');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat memproses data!');
                }
            });
        });
    });
</script>