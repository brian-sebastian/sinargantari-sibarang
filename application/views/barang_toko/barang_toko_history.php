<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Nama Toko</th>
            <th scope="col">Harga Jual Toko</th>
            <th scope="col">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($history_barang as $hb) : ?>
            <tr>
                <th scope="row"><?= $no++ ?></th>
                <td><?= $hb['nama_barang'] ?></td>
                <td><?= $hb['nama_toko'] ?></td>
                <td>Rp. <?= number_format($hb['harga_jual']) ?></td>
                <td><?= $hb['created_at_history_barang_toko'] ?></td>
            </tr>
        <?php endforeach ?>

    </tbody>
</table>