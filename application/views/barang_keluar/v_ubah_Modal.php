<div class="row">
    <div class="col-sm-12" style="border-bottom: dashed; padding-bottom: 3%;">
        <table>
            <tr>
                <th width="30%">Nama Barang</th>
                <td width="1%">:</td>
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
                <th>QTY Pembelian</th>
                <td>:</td>
                <td><?= $data_barang_keluar['qty']; ?>/<?= $data_barang_keluar['satuan']; ?></td>
            </tr>
            <tr>
                <th>Jenis Keluar</th>
                <td>:</td>
                <td><?= $data_barang_keluar['jenis_keluar']; ?></td>
            </tr>
            <tr>
                <th>Tgl Keluar</th>
                <td>:</td>
                <td><?= date('d/m/Y', strtotime($data_barang_keluar['created_at'])); ?></td>
            </tr>
        </table>
    </div>
    <input type="hidden" id="id_barang_keluar" name="id_barang_keluar" value="<?= $data_barang_keluar['id_barang_keluar']; ?>" class="form-control" />
    <div class="col-3 mt-3 mb-3">
        <label for="type" class="form-label">Jumlah Keluar</label>
        <input type="number" id="jml_keluar" name="jml_keluar" value="<?= $data_barang_keluar['jml_keluar']; ?>" class="form-control" />
        <small class="text-danger" id="err_jml_keluarU"></small>
    </div>
</div>