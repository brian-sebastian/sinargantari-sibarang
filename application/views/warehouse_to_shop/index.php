<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

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
        <div class="card mb-2 mt-2">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6>Pilih Gudang</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <label for="type" class="form-label">Gudang</label>
                            <select name="gudang" id="gudang" class="form-select mb-2">
                                <option value="">--PILIH GUDANG--</option>
                                <?php foreach ($gudang as $gd) : ?>
                                    <option value="<?= $this->secure->encrypt_url($gd['id_toko']) ?>" 
                                        <?php 
                                            if (isset($_GET["gd"]) && ($this->secure->decrypt_url($_GET["gd"]) == $gd["id_toko"])) 
                                            {
                                                echo "selected";
                                            } 
                                        ?>>
                                        <?= $gd['nama_toko'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6>Pilih Toko</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <label for="type" class="form-label">Toko</label>
                            <select name="toko" id="toko" class="form-select mb-2">
                                <option value="">--PILIH TOKO--</option>
                                <?php foreach ($toko as $shop) : ?>
                                    <option value="<?= $this->secure->encrypt_url($shop['id_toko']) ?>" <?php if (isset($_GET["toko"]) &&  ($this->secure->decrypt_url($_GET["toko"]) == $shop["id_toko"])) {
                                                                                                            echo "selected";
                                                                                                        } ?>><?= $shop['nama_toko'] ?></option>
                                <?php endforeach ?>
                                <option value="TOKO_LUAR_TL_NONSHOP">TOKO LUAR (SELAIN DARI DAFTAR TOKO)</option>
                            </select>
                            <input type="text" class="form-control d-none" name="nama_toko" id="nama_toko" placeholder="Nama Toko Beli">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card mb-2 mt-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-sm btn-primary d-none" id="btn-savetlwh" type="button">
                            <span class="tf-icons bx bxs-save"></span>&nbsp; Simpan
                        </button>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control d-none" name="cari_barang" id="cari_barang" placeholder="Cari Nama Barang">
                            </div>
                                
                        </div>
                   
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped nowrap py-1 px-1" id="daftarTableBarangToko">
                            <thead>

                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</div>