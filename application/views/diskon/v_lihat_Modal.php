<div class="row">
	<div class="col-sm-12">
        <table>
            <tr>
                <th width="50%">Nama Diskon</th>
                <td width="1%">:</td>
                <td><?= $data_diskon['nama_diskon']; ?></td>
            </tr>
            <tr>
                <th>Nama Toko</th>
                <td>:</td>
                <td><?= $data_diskon['nama_toko']; ?></td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td>:</td>
                <td><?= $data_diskon['nama_barang']; ?></td>
            </tr>
            <tr>
                <th>Harga Potongan</th>
                <td>:</td>
                <td><?= rupiah($data_diskon['harga_potongan']); ?></td>
            </tr>
            <tr>
                <th>Satuan Pembelian</th>
                <td>:</td>
                <td><?= $data_diskon['satuan']; ?></td>
            </tr>
            <tr>
                <th>Minimal Pembelian</th>
                <td>:</td>
                <td><?= $data_diskon['minimal_beli']; ?></td>
            </tr>
            <tr>
                <th>Tgl Awal Diskon</th>
                <td>:</td>
                <td><?= date('d/m/Y', strtotime($data_diskon['tgl_mulai'])); ?></td>
            </tr>
            <tr>
                <th>Tgl Berakhir Diskon</th>
                <td>:</td>
                <td><?= date('d/m/Y', strtotime($data_diskon['tgl_akhir'])); ?></td>
            </tr>
        </table>
    </div>
</div>