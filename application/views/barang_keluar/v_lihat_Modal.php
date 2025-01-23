<div class="row">
	<div class="col-sm-12">
        <table>
            <tr>
                <th width="30%">Nama Barang</th>
                <td width="3%">:</td>
                <td><?= $data_barang_keluar['nama_barang']; ?></td>
            </tr>
            <tr>
                <th>Nama Toko</th>
                <td>:</td>
                <td><?= $data_barang_keluar['nama_toko']; ?></td>
            </tr>
            <tr>
                <th>Kode Order</th>
                <td>:</td>
                <td><?= $data_barang_keluar['kode_order']; ?></td>
            </tr>
            <tr>
                <th>Tipe Order</th>
                <td>:</td>
                <td><?= $data_barang_keluar['tipe_order']; ?></td>
            </tr>
            <tr>
                <th>QTY Pembelian</th>
                <td>:</td>
                <td><?= $data_barang_keluar['qty']; ?>/<?= $data_barang_keluar['satuan']; ?></td>
            </tr>
            <tr>
                <th>Jumlah Keluar</th>
                <td>:</td>
                <td><?= $data_barang_keluar['jml_keluar']; ?>/<?= $data_barang_keluar['satuan']; ?></td>
            </tr>
            <tr>
                <th>Jenis Keluar</th>
                <td>:</td>
                <td><?= $data_barang_keluar['jenis_keluar']; ?></td>
            </tr>
            <tr>
                <th>Bukti Keluar</th>
                <td>:</td>
                <td><?= $data_barang_keluar['bukti_keluar']; ?></td>
            </tr>
            <tr>
                <th>Tgl Keluar</th>
                <td>:</td>
                <td><?= date('d/m/Y', strtotime($data_barang_keluar['created_at'])); ?></td>
            </tr>
        </table>
    </div>
</div>