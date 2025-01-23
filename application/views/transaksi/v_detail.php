<div class="col-md-12">
    <label for="jml_masuk" class="col-sm-3"><b>Kode Order</b></label> :
    <p class="d-inline"><?= $transaksi['kode_order'] ?></p>
</div>
<div class="col-md-12">
    <label for="jml_masuk" class="col-sm-3"><b>Tgl Transaksi</b></label> :
    <p class="d-inline"><?= date('d M Y', strtotime($transaksi['created_at']))   ?></p>
</div>
<div class="col-md-12">
    <label for="jml_masuk" class="col-sm-3"><b>Nama Kustomer</b></label> :
    <p class="d-inline"><?= $transaksi['nama_cust'] ?></p>
</div>
<div class="col-md-12">
    <label for="jml_masuk" class="col-sm-3"><b>Biaya Kirim</b></label> :
    <p class="d-inline"><?= $transaksi['biaya_kirim'] ?></p>
</div>
<div class="col-md-12">
    <label for="jml_masuk" class="col-sm-3"><b>Total Order</b></label> :
    <p class="d-inline"><?= $transaksi['total_order'] ?></p>
</div>
<div class="col-mb-3">
    <label for="terbayar" class="col-sm-3"><b>Terbayar</b></label> :
    <p class="d-inline"><?= $transaksi['terbayar'] ?></p>
</div>
<div class="col-mb-3">
    <label for="kembalian" class="col-sm-3"><b>Kembalian</b></label> :
    <p class="d-inline"><?= $transaksi['kembalian'] ?></p>
</div>
<div class="col-mb-3">
    <label for="basic-default-phone" class="col-sm-3"><b>Bukti Transfer</b></label> :
    <img class="img-thumbnail d-inline" src="<?= base_url('assets/file_bukti_transaksi/') . $transaksi['bukti_tf'] ?>" width="150">
</div>
<div class="col-mb-3">
    <label for="basic-default-message" class="col-sm-3"><b>Tipe Transaksi</b></label> :
    <p class="d-inline"><?= $transaksi['tipe_transaksi'] ?></p>
</div>