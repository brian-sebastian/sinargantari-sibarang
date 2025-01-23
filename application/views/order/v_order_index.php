<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>
    <?= $this->session->flashdata('pesan'); ?>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="contact-name text-dark fs-13 mb-1">Cari Barang</h5>
                </div>

                <div class="card-body">
                    <form method="post" action="<?= base_url('kasir/order'); ?>">
                        <div class="row gx-3 gy-2 align-items-center">
                            <div class="col-md-9">
                                <input class="search form-control" placeholder="Search" name="search" autocomplete="off" autofocus />
                            </div>
                            <div class="col-md-3">
                                <button id="showToastPlacement" class="btn btn-primary d-block" type="submit" name="submit" value="1">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

                <?= $this->session->flashdata('pesan'); ?>

                <div class="card-body">
                    <div class="row gx-3 gy-2 align-items-center">

                        <?php if ($data_karyawan != 0) { ?>

                            <?php if ($data_barang != 0) { ?>

                                <?php foreach ($data_barang as $key => $barang) { ?>
                                    <div class="mx-1">
                                        <ul class="list list-group list-group-flush mb-1">
                                            <form method="post" action="<?= base_url('kasir/cart'); ?>">
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-start">

                                                        <div class="flex-shrink-0 me-3">
                                                            <div>
                                                                <img class="image avatar-xs rounded-circle" alt="" src="<?= base_url('assets/upload/') . $barang['gambar'] ?>">
                                                            </div>
                                                        </div>

                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h6 class="contact-name text-dark fs-13 mb-1"><?= $barang['kode_barang'] ?> / <?= $barang['nama_barang'] ?></h6>
                                                            <p class="contact-born text-muted mb-0"><?= rupiah($barang['harga_jual']); ?> / <?= $barang['satuan'] ?></p>
                                                        </div>

                                                        <input type="hidden" name="id" value="<?= $barang['id_barang']; ?>">
                                                        <input type="hidden" name="name" value="<?= $barang['nama_barang']; ?>">
                                                        <input type="hidden" class="form-control" value="1" name="qty" id="qty<?= $barang['id_barang']; ?>" />
                                                        <input type="hidden" name="price" value="<?= $barang['harga_jual']; ?>">
                                                        <input type="hidden" name="satuan" value="<?= $barang['satuan']; ?>">

                                                        <div class="flex-shrink-0 ms-2">
                                                            <div class="fs-11 text-muted">
                                                                <button type="submit" class="btn btn-primary btn-label left ms-auto nexttab">
                                                                    <i class="ri-check-line label-icon align-middle fs-16"></i>Pilih
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </li>
                                            </form>
                                        </ul>
                                    </div>
                                <?php } ?>

                                <?= $this->pagination->create_links(); ?>

                            <?php } else { ?>
                                <div class="mx-1">
                                    <ul class="list list-group list-group-flush mb-1">
                                        <li class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <p class="card-text mb-1">Barang toko belum dimasukkan! </p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="mx-1">
                                <ul class="list list-group list-group-flush mb-1">
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <p class="card-text mb-1">User ini masih belum menjadi karyawan toko! </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>

        <?php
        $cart = $this->cart->contents();
        $jml_item = 0;
        $jml_produk = count($cart);

        foreach ($cart as $key => $value) {
            $jml_item = $jml_item + $value['qty'];
        }
        ?>

        <?php if (empty($cart)) { ?>
            <div class="col-xl">
                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="contact-name text-dark fs-13 mb-1">Keranjang</h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th style="width: 150px;" scope="col">Barang</th>
                                    <th style="width: 100px;" scope="col" class="text-center">QTY</th>
                                    <th style="width: 50px;" scope="col" class="text-center">Satuan</th>
                                    <th scope="col" class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <h6 class="fs-14 text-dark">Item pesanan kosong!</h6>
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <th>Produk :</th>
                                    <td class="text-end" colspan="3">
                                        <span class="fw-semibold">
                                            <?= $jml_produk ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <th colspan="3">Item :</th>
                                    <td class="text-end">
                                        <span class="fw-semibold">
                                            <?= $jml_item; ?>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        <?php } else { ?>
            <div class="col-xl">
                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="contact-name text-dark fs-13 mb-1">Keranjang</h5>
                    </div>

                    <div class="card-body">
                        <form method="post" action="<?= base_url('kasir/chek_out') ?>">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th style="width: 150px;" scope="col">Barang</th>
                                        <th style="width: 100px;" scope="col" class="text-center">QTY</th>
                                        <th style="width: 10px;" scope="col" class="text-center">Satuan</th>
                                        <th scope="col" class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $key => $value) { ?>
                                        <tr>
                                            <td>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h6 class="fs-14 text-dark"><?= $value['name'] ?></h6>
                                                    <p class="contact-born text-muted mb-0"><?= rupiah($value['price']); ?></p>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" style="width: 100px;" class="form-control" value="<?= $value['qty']; ?>" data-rowid="<?= $value['rowid']; ?>" name="qty[]" id="qty<?= $value['rowid']; ?>" required>
                                            </td>
                                            <td class="text-center"><?= $value['satuan']; ?></td>
                                            <td class="text-end"><?= rupiah($value['subtotal']); ?></td>
                                            <input type="hidden" name="id_barang[]" id="id_barang<?= $value['rowid']; ?>" value="<?= $value['id']; ?>">
                                            <input type="hidden" name="price[]" id="harga_jual<?= $value['rowid']; ?>" value="<?= $value['price']; ?>">
                                            <input type="hidden" name="subtotal[]" id="subtotal" value="<?= $value['subtotal']; ?>">
                                        </tr>
                                    <?php } ?>
                                    <tr class="table-active">
                                        <th>Produk :</th>
                                        <td class="text-end" colspan="3">
                                            <span class="fw-semibold">
                                                <?= $jml_produk ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <th>Item :</th>
                                        <td class="text-end" colspan="3">
                                            <span class="fw-semibold">
                                                <?= $jml_item ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="table-active">
                                        <th>TOTAL :</th>
                                        <td class="text-end" colspan="3">
                                            <span class="fw-semibold">
                                                <?= rupiah($this->cart->total()); ?>
                                            </span>
                                        </td>
                                        <input type="hidden" name="total_harga" id="total_harga_order" value="<?= $this->cart->total(); ?>">
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                </div>

                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="contact-name text-dark fs-13 mb-1">Pembayaran</h5>
                    </div>

                    <div class="card-body">
                        <div class="mb-3 row">
                            <label for="html5-number-input" class="col-md-4 col-form-label">Jenis Pembayaran</label>
                            <div class="col-md-8">
                                <select class="form-select" name="jenis_bayar" id="jenis_bayar">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="terbayar" class="col-md-4 col-form-label">Terbayar</label>
                            <div class="col-md-8">
                                <input class="form-control" type="number" id="terbayar_order" name="terbayar" placeholder="0" required="required" />
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="kembalian" class="col-md-4 col-form-label">Kembali</label>
                            <div class="col-md-8">
                                <input class="form-control bg-light border-0" type="number" id="kembalian_order" placeholder="0" name="kembalian" readonly="readonly" required="required" />
                            </div>
                        </div>
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <a onclick="check_order(<?= $get_kodeOrder ?>)">
                                <button type="submit" class="btn btn-success">
                                    Periksa Pesanan
                                </button>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>

    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="check_orderModal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Nota Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="check_order">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function check_order(id) {
            alert('test')
            // console.log(id)
            $('#check_order').empty()
            $.get('<?= base_url() ?>kasir/check_ordermodal/' + btoa(id), function(data_role) {
                $('#check_order').html(data_role)
                $('#check_orderModal').modal('show')
            })
        }


        document.getElementById('terbayar_order').addEventListener('change', function() {
            const terbayar_order = document.getElementById('terbayar_order').value;
            const total_harga_order = document.getElementById('total_harga_order').value;

            var kembalian = terbayar_order - total_harga_order;

            document.getElementById('kembalian_order').value = kembalian;
        });
    </script>