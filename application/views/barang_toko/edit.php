<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Barang /</span> Edit </h4>


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
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h5>Barang</h5>
                </div>
                <div class="col">
                    <div class="float-end">
                        <a href="<?= base_url('barang/list') ?>" class="btn btn-sm btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="col-12 mb-4">
                <form action="<?= base_url('barang/edit') ?>" method="post">
                    <input type="hidden" name="id_barang" value="<?= $barang['id_barang'] ?>">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Kode Barang</label>
                            <input type="text" id="kode_barang" name="kode_barang" class="form-control" value="<?= $barang['kode_barang'] ?>" readonly required />
                            <?= form_error('kode_barang', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="<?= $barang['nama_barang'] ?>" required />
                            <?= form_error('nama_barang', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Slug Barang</label>
                            <input type="text" id="slug_barang" name="slug_barang" class="form-control" value="<?= $barang['slug_barang'] ?>" required readonly />
                            <?= form_error('slug_barang', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Kategori Barang</label>
                            <select name="kategori_id" id="kategori_id" class="form-control select2">
                                <option value="">--PILIH KATEGORI--</option>
                                <?php foreach ($kategori as $kat) : ?>
                                    <?php $selected = ($barang['kategori_id'] == $kat['id_kategori']) ? 'selected' : ''; ?>
                                    <option value="<?= $kat['id_kategori'] ?>" <?= $selected ?>><?= $kat['nama_kategori'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <?= form_error('kategori_id', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Berat Barang</label>
                            <input type="number" id="berat_barang" name="berat_barang" class="form-control" value="<?= $barang['berat_barang'] ?>" required />
                            <?= form_error('berat_barang', '<small class="text-danger">', '</small>') ?>
                        </div>
                        <div class="col mb-3">
                            <label for="type" class="form-label">Satuan Barang</label>
                            <select name="satuan_id" id="satuan_id" class="form-control select2">
                                <option value="">--PILIH SATUAN--</option>
                                <?php foreach ($satuan as $sat) : ?>
                                    <?php $selected = ($barang['satuan_id'] == $sat['id_satuan']) ? 'selected' : ''; ?>
                                    <option value="<?= $sat['id_satuan'] ?>" <?= $selected ?>><?= $sat['satuan'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <?= form_error('satuan_id', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Harga Pokok Barang</label>
                            <input type="text" id="harga_pokok" name="harga_pokok" class="form-control CurrencyInput" data-type="currency" value="<?= $barang['harga_pokok'] ?>" required />
                            <?= form_error('harga_pokok', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="type" class="form-label">Deskripsi Barang</label>
                            <textarea name="deskripsi" id="editor" cols="30" rows="10">
                                <?= $barang['deskripsi'] ?>
                            </textarea>

                            <?= form_error('deskripsi', '<small class="text-danger">', '</small>') ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>

                <script>
                    const nama_barang = document.querySelector("#nama_barang");
                    const slug_barang = document.querySelector("#slug_barang");

                    nama_barang.addEventListener("keyup", function() {
                        let preslug = nama_barang.value;
                        preslug = preslug.replace(/ /g, "-");
                        slug_barang.value = preslug.toLowerCase();
                    });
                </script>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->


</div>
<!--/ Responsive Table -->