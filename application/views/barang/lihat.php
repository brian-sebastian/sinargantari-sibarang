<?php 
    $role_id = $this->session->userdata('role_id');
    $history_harga = !in_array($role_id, [18, 21]);
?>
<div class="row">
    <div class="col-sm-12">
        <img src="<?= base_url('assets/file_barang/') . $data_barang['gambar'] ?>" alt="<?= base_url('assets/file_barang/') . $data_barang['gambar'] ?>" class="img-fluid d-block img-thumbnail mb-3" width="150">
        <table class="table table-striped" style="word-wrap: normal;">
            <tr>
                <th width="25%">Kode Barang</th>
                <td width="1%">:</td>
                <td><?= $data_barang['kode_barang']; ?></td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td>:</td>
                <td><?= $data_barang['nama_barang']; ?></td>
            </tr>
            <tr>
                <th>Slug Barang</th>
                <td>:</td>
                <td><?= $data_barang['slug_barang']; ?></td>
            </tr>
            <tr>
                <th>Kategori Barang</th>
                <td>:</td>
                <td><?= $data_barang['nama_kategori']; ?></td>
            </tr>
            <tr>
                <th>Berat Barang</th>
                <td>:</td>
                <td><?= $data_barang['berat_barang']; ?> <?= $data_barang['satuan']; ?></td>
            </tr>
            <?php if($history_harga):?>
                <tr>
                    <th>Harga Pokok</th>
                    <td>:</td>
                    <td>Rp. <?= number_format($data_barang['harga_pokok']); ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>Deskripsi Barang</th>
                <td>:</td>
                <td>
                    <?= convertHtmlToText($data_barang['deskripsi']) ?>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>:</td>
                <td>
                    <?php if ($data_barang['is_active'] == 1) { ?>
                        <p class="card-text"><span class="badge rounded-pill bg-label-success">Aktif</span></p>
                    <?php } else { ?>
                        <p class="card-text"><span class="badge rounded-pill bg-label-danger">Tidak Aktif</span></p>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
</div>