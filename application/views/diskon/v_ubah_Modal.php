<div class="row">
    <input type="hidden" id="id_diskon" name="id_diskon" value="<?= $data_diskon['id_diskon']; ?>" class="form-control" />
    <div class="col-4 mb-3">
        <label for="type" class="form-label">Nama Diskon</label>
        <input type="text" id="nama_diskon" name="nama_diskon" value="<?= $data_diskon['nama_diskon']; ?>" class="form-control" />
        <small class="text-danger" id="err_nama_diskonU"></small>
    </div>
    <div class="col-4 mb-3">
        <label for="type" class="form-label">Nama Toko</label>
        <?php if ($this->session->userdata('toko_id') == '') { ?>
            <select name="toko_id" id="toko_id_diskon_ubah" class="form-select" style="width: 100%;">
                <option value="<?= $data_diskon['toko_id']; ?>" toko_id="<?= $data_diskon["toko_id"] ?>"><?= $data_diskon['nama_toko']; ?></option>
                <?php foreach ($data_toko as $dt) : ?>
                    <option value="<?= $dt["id_toko"] ?>" toko_id="<?= $dt["id_toko"] ?>">
                        <?= $dt["nama_toko"] ?>
                    </option>
                <?php endforeach ?>
            </select>
        <?php } else { ?>
            <select name="toko_id" id="toko_id_diskon_ubah" class="form-select" style="width: 100%;">
                <option value="<?= $data_toko["id_toko"] ?>" toko_id="<?= $data_toko["id_toko"] ?>" selected>
                    <?= $data_toko["nama_toko"] ?>
                </option>
            </select>
        <?php } ?>
        <small class="text-danger" id="err_toko_id_diskonU"></small>
    </div>
    <div class="col-4 mb-3">
        <label for="type" class="form-label">Barang Toko</label>
        <select name="id_harga" id="id_harga_diskon_ubah" class="form-select" style="width: 100%;">
            <option value="<?= $data_diskon['id_harga']; ?>"><?= $data_diskon['nama_barang']; ?></option>
            <?php foreach ($data_barang as $db) : ?>
                <option value="<?= $db['id_harga']; ?>"><?= $db['nama_barang']; ?></option>
            <?php endforeach ?>
        </select>
        <small class="text-danger" id="err_id_harga_diskonU"></small>
    </div>
    <div class="col-2 mb-3">
        <label for="type" class="form-label">Satuan Pembelian</label>
        <p class="contact-born text-muted satuan"><?= $data_diskon['satuan']; ?></p>
    </div>
    <div class="col-2 mb-3">
        <label for="type" class="form-label">Minimal Pembelian</label>
        <input type="number" id="minimal_beli" name="minimal_beli" value="<?= $data_diskon['minimal_beli']; ?>" class="form-control" />
        <small class="text-danger" id="err_minimal_beliU"></small>
    </div>
    <div class="col-3 mb-3">
        <label for="type" class="form-label">Harga Potongan</label>
        <input type="number" id="harga_potongan" name="harga_potongan" value="<?= $data_diskon['harga_potongan']; ?>" class="form-control" />
        <small class="text-danger" id="err_harga_potonganU"></small>
    </div>
    <div class="col-2 mb-3">
        <label for="type" class="form-label">Tanggal Mulai</label>
        <input type="date" id="tgl_mulai" name="tgl_mulai" value="<?= $data_diskon['tgl_mulai']; ?>" class="form-control" />
        <small class="text-danger" id="err_tgl_mulaiU"></small>
    </div>
    <div class="col-2 mb-3">
        <label for="type" class="form-label">Tanggal Akhir</label>
        <input type="date" id="tgl_akhir" name="tgl_akhir" value="<?= $data_diskon['tgl_akhir']; ?>" class="form-control" />
        <small class="text-danger" id="err_tgl_akhirU"></small>
    </div>
</div>
<script>
    document.getElementById('toko_id_diskon_ubah').addEventListener('change', function() {
        const id = $(this).find('option:selected').attr('toko_id');
        fetch("<?= base_url('toko/diskon/dt_toko/') ?>" + id, {
                type: 'post',
            })
            .then((response) => response.text())
            .then((data) => {
                document.getElementById('id_harga_diskon_ubah').innerHTML = data
            });

    });

    document.getElementById('id_harga_diskon_ubah').addEventListener('change',function(){
        const id_harga = document.getElementById('id_harga_diskon_ubah').value;
        $.ajax({
            url: "<?= base_url('toko/diskon/dt_barang_toko'); ?>",
            type: 'post',
            data: {
                id_harga: id_harga
            },
            success: function(data) {
                const obj = JSON.parse(data);
                $('.satuan').html(obj.satuan);
            }
        });
    });
</script>