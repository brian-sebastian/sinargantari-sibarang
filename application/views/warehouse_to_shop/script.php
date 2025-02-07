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


        $('#gudang').on('change', function() {
            var toko_id = $(this).val();
            $('#daftarTableBarangToko tbody').html('');
            $('#btn-savetlwh').addClass('d-none');
            $('#daftarTableBarangToko').find('input[type="hidden"], input[type="number"], input[type="date"], input[type="checkbox"]').val(''); // Reset input
            $('#daftarTableBarangToko').find('input[type="checkbox"]').prop('checked', false);

            if (toko_id) {

                if (toko_id) {
                    $('#nama_toko').removeClass('d-block');
                    $('#nama_toko').addClass('d-none');
                    $.ajax({
                        url: '<?= site_url('gudang/dist_gudang_toko/getBarangGudang'); ?>',
                        type: 'GET',
                        data: {
                            toko_id: toko_id
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                let barangList = response.data;
                                let htmlContent = '';
                                // Di gudang opsional jangan di hapus barang kali di perlukan pada saat harga jual
                                // $('#daftarTableBarangToko thead').html(`
                                //     <tr>
                                //         <th>No</th>
                                //         <th>Nama Barang</th>
                                //         <th>Qty Barang Toko</th>
                                //         <th>Jml Pindah Gudang</th>
                                //         <th>Harga Jual</th>
                                //         <th>Pilih</th>
                                //     </tr>
                                // `);
                                $('#daftarTableBarangToko thead').html(`
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Qty Barang Gudang</th>
                                        <th>Jml Pindah Toko</th>
                                        <th>Pilih</th>
                                    </tr>
                                `);


                                $.each(barangList, function(index, barang) {
                                // Di gudang opsional jangan di hapus barang kali di perlukan pada saat harga jual
                                //     htmlContent += `
                                // <tr>
                                //     <td>${index + 1}</td>
                                //     <td>${barang.nama_barang}</td>
                                //     <td>${barang.stok_toko}</td>
                                //     <td>
                                //         <input type="number" class="form-control qty_pindah" data-id="${barang.id_barang}" value="0" min="0" />
                                //     </td>
                                //     <td>
                                //         <input type="number" class="form-control harga_jual" data-id="${barang.id_barang}" value="0" min="0" />
                                //     </td>
                                //     <td>
                                //         <input type="checkbox" class="checkbox_data" value="${barang.id_barang}" />
                                //         <input type="hidden" readonly class="form-control id_harga" name="id_harga" id="id_harga" value="${barang.id_harga}" />

                                //     </td>
                                // </tr>`;
                                    htmlContent += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${barang.nama_barang}</td>
                                    <td>${barang.stok_toko}</td>
                                    <td>
                                        <input type="number" class="form-control qty_pindah" data-id="${barang.id_barang}" value="0" min="0" />
                                    </td>
                                    <td>
                                        <input type="checkbox" class="checkbox_data" value="${barang.id_barang}" />
                                        <input type="hidden" readonly class="form-control id_harga" name="id_harga" id="id_harga" value="${barang.id_harga}" />

                                    </td>
                                </tr>`;
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
                var tgl_beli = $(this).closest('tr').find('.tgl_beli').val();
                var qty_pindah = $(this).closest('tr').find('.qty_pindah').val();
                // Di gudang opsional jangan di hapus barang kali di perlukan pada saat harga jual
                // var harga_jual = $(this).closest('tr').find('.harga_jual').val();
                var stok_toko = $(this).closest('tr').find('td').eq(3).text(); // Mengambil stok toko dari kolom
                var id_harga = $(this).closest('tr').find('.id_harga').val();


                barangData.push({
                    id_barang: id_barang,
                    qty_pindah: qty_pindah,
                    // Di gudang opsional jangan di hapus barang kali di perlukan pada saat harga jual
                    // harga_jual: harga_jual,
                    tgl_beli: tgl_beli,
                    gudang_id: gudang_id,
                    toko_id: toko_id,
                    id_harga: id_harga,
                });

            });

            // Jika validasi tidak lolos atau tidak ada barang yang dipilih
            if (!valid || barangData.length == 0) {
                alert('Pilih barang yang ingin disimpan dan pastikan jumlah tidak melebihi stok!');
                return;
            }

            $.ajax({
                url: '<?= site_url('gudang/dist_gudang_toko/saveBarang'); ?>',
                type: 'POST',
                data: {
                    gudang_id: gudang_id,
                    toko_id: toko_id,
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