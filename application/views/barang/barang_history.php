<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Harga Barang</th>
            <th scope="col">Berat Barang</th>
            <th scope="col">Kategori</th>
            <th scope="col">Satuan</th>
            <th scope="col">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($history_barang as $hb) : ?>
            <tr>
                <th scope="row"><?= $no++ ?></th>
                <td><?= $hb['nama_barang'] ?></td>
                <td>Rp. <?= number_format($hb['harga_pokok']) ?></td>
                <td><?= $hb['berat_barang'] ?></td>
                <td><?= $hb['nama_kategori'] ?></td>
                <td><?= $hb['satuan'] ?></td>
                <td><?= $hb['tanggal_perubahan'] ?></td>
            </tr>
        <?php endforeach ?>

    </tbody>
</table>