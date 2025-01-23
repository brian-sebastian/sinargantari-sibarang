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


        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Request Type </th>
                            <th>Keterangan </th>
                            <th>Request By </th>
                            <th>Request Deleted In </th>
                            <th>Request Date </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        $no = 1;
                        ?>
                        <?php foreach ($request_hapus_barang as $m) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <?php if ($m['barang_id'] == null || $m['barang_id'] == '') : ?>
                                    <!-- JIKA BARANG ID NULL maka AMBIL NAMA BARANG DARI HARGA -->
                                    <td><?= $m['inhrg_nama_barang'] ?></td>
                                <?php elseif ($m['harga_id'] == null || $m['harga_id'] == '') : ?>
                                    <!-- JIKA HARGA ID NULL maka AMBIL NAMA BARANG DARI BARANG -->
                                    <td><?= $m['nama_barang'] ?></td>
                                <?php endif ?>
                                <td>
                                    <?php if ($m['type_request'] == 'delete_barang') : ?>
                                        <?= "HAPUS BARANG" ?>
                                    <?php else : ?>
                                        <?= "HAPUS BARANG TOKO" ?>
                                    <?php endif ?>

                                </td>
                                <td><?= $m['keterangan'] ?></td>
                                <td><?= $m['requested_by'] ?></td>
                                <td><?= $m['nama_toko'] ?></td>
                                <td><?= $m['requested_date'] ?></td>
                                <td>
                                    <a href="<?= base_url('barang/request_hapus_barang/accept_delete/') . $this->secure->encrypt_url($m['id_request']) ?>" class="btn btn-sm btn-success">Setujui Hapus</a>

                                    <a href="<?= base_url('barang/request_hapus_barang/reject_delete/') . $this->secure->encrypt_url($m['id_request']) ?>" class="btn btn-sm btn-danger">Tolak Hapus</a>
                                </td>
                            </tr>




                        <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </div>


    </div>
    <!--/ Basic Bootstrap Table -->


</div>
<!--/ Responsive Table -->