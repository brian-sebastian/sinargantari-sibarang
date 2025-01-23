<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <!-- Basic Layout -->

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Pilih Rentang Tanggal Transaksi</h4>
                    <br>
                    <form action="" method="post">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class='fs-4 bx bx-calendar'></i></span>
                                    <input class="form-control is-valid rangepicker" style="background-color: white;" value="" type="text" autofocus required placeholder="Pilih Tanggal" name="tanggal">
                                </div>
                            </div>
                            <div class="col-2 text-left ">
                                <button type="submit" class="btn btn-sm text-white" style="background-color: #213363;">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($this->input->post('tanggal') == NULL) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            Data Tidak Dapat Ditampilkan. Kamu Belum Memilih Rentang Tanggal. Silahkan Memilih Rentang Tanggal
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php else : ?>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">

                    <div class="card-body">
                        <div class="table-responsive text-nowrap py-2 px-2">
                            <table class="table table-striped dt-responsive datatables nowrap py-1 px-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Terbayar</th>
                                        <th>Kembalian</th>
                                        <th>Tipe Transaksi</th>
                                        <th>Bukti Transfer</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($transaksi as $data) : ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $data['kode_order'] ?></td>
                                            <td><?= $data['created_at'] ?></td>
                                            <td><?= $data['terbayar'] ?></td>
                                            <td><?= $data['kembalian'] ?></td>
                                            <td><?= $data['tipe_transaksi'] ?></td>
                                            <td><img src="<?= base_url('assets/be/upload/bukti_bayar/') . $data['bukti_tf'] ?>" width="50" height="50"></td>
                                            <td>
                                                <a onclick="lihat_transaksi(<?= $data['id_transaksi'] ?>)">
                                                    <button type="button" class="btn btn-sm btn-info">
                                                        <span class="tf-icons bx bx-show-alt"></span>&nbsp; Lihat
                                                    </button>
                                                </a>
                                                <?php if ($data['tipe_transaksi'] == 'transfer') : ?>
                                                    <a href="<?= base_url('kasir/transaksi/edit/') . base64_encode($data['id_transaksi']) ?>" class="btn btn-warning btn-sm"><i class=" bx bx-edit-alt me-1"></i>&nbsp; Edit</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php $no++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="modal_lihat_transaksi" tabindex="-1" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="lihatDiskon">

                </div>
            </div>
        </div>
    </div>

    <script>
        function lihat_transaksi(id) {

            $('#lihatDiskon').empty()
            $.get('<?= site_url('kasir/transaksi/detail/') ?>' + btoa(id), function(data) {
                $('#lihatDiskon').html(data)
                $('#modal_lihat_transaksi').modal('show')
            })

        }

        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    </script>