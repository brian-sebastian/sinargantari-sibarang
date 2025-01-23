<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Toko /</span> Shop Management</h4>


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
                    <h5>Setting</h5>
                </div>
            </div>
        </div>

        <?php if ($setting) : ?>
            <div class="card-body">
                <form action="<?= base_url('setting/update_setting') ?>" method="post" class="was-validated" enctype="multipart/form-data">
                    <div class="row">
                        <small class="text-info">Otomatis Terisi Ketika Mengisi Nama Instansi</small>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="kode_instansi" name="kode_instansi" placeholder="Kode Instansi" value="<?= set_value('kode_instansi', $setting['kode_instansi']) ?>" required readonly>
                            <label for="kode_instansi">Kode Instansi</label>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="instansi" name="instansi" placeholder="Instansi" value="<?= set_value('instansi', $setting['instansi']) ?>" required>
                                <label for="instansi">Instansi</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="owner" name="owner" placeholder="Owner" value="<?= set_value('owner', $setting['owner']) ?>" required>
                                <label for="owner">Owner</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="wa_instansi" name="wa_instansi" placeholder="whatsapp instansi" value="<?= set_value('wa_instansi', $setting['wa_instansi']) ?>" required>
                                <label for="wa_instansi">Whatsapp Instansi</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="wa_admin" name="wa_admin" placeholder="whatsapp instansi" value="<?= set_value('wa_admin', $setting['wa_admin']) ?>" required>
                                <label for="wa_admin">Whatsapp Admin</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="tlp_instansi" name="tlp_instansi" placeholder="telepon kantor instansi" value="<?= set_value('tlp_instansi', $setting['tlp_instansi']) ?>" required>
                                <label for="tlp_instansi">Telepon Kantor</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="ig_instansi" name="ig_instansi" placeholder="instagram instansi" value="<?= set_value('ig_instansi', $setting['ig_instansi']) ?>">
                                <label for="ig_instansi">Instagram Instansi</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fb_instansi" name="fb_instansi" placeholder="facebook instansi" value="<?= set_value('fb_instansi', $setting['fb_instansi']) ?>">
                                <label for="fb_instansi">Facebook Kantor</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email_instansi" name="email_instansi" placeholder="email instansi" value="<?= set_value('email_instansi', $setting['email_instansi']) ?>">
                                <label for="email_instansi">Email Kantor</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($setting['img_instansi'] == null) : ?>
                            <div class="col">
                                <label for="img_instansi">Logo Instansi</label>
                                <input type="file" class="form-control dropify" id="img_instansi" name="img_instansi" data-max-file-size="20M" data-allowed-file-extensions="jpeg jpg png">
                            </div>
                        <?php else : ?>
                            <div class="col">
                                <label for="img_instansi">Logo Saat Ini</label>
                                <img src="<?= base_url('assets/be/img/logo/') . $setting['img_instansi'] ?>" class="img-fluid" alt="<?= base_url('assets/be/img/logo/') . $setting['img_instansi'] ?>" srcset="<?= base_url('assets/be/img/logo/') . $setting['img_instansi'] ?>">
                            </div>
                            <div class="col">
                                <label for="img_instansi">Logo Instansi</label>
                                <input type="file" class="form-control dropify" id="img_instansi" name="img_instansi" data-max-file-size="20M" data-allowed-file-extensions="jpeg jpg png">
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">SIMPAN</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php else : ?>
            <div class="card-body">
                <form action="<?= base_url('setting/update_setting') ?>" method="post" class="was-validated" enctype="multipart/form-data">
                    <div class="row">
                        <small class="text-info">Otomatis Terisi Ketika Mengisi Nama Instansi</small>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="kode_instansi" name="kode_instansi" placeholder="Kode Instansi" required readonly>
                            <label for="kode_instansi">Kode Instansi</label>
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <small class="text-danger">*Required</small>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="instansi" name="instansi" placeholder="Instansi" required value="<?= set_value('instansi') ?>">
                                <label for="instansi">Instansi</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <small class="text-danger">*Required</small>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="owner" name="owner" placeholder="Owner" required value="<?= set_value('owner') ?>">
                                <label for="owner">Owner</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <small class="text-danger">*Required</small>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="wa_instansi" name="wa_instansi" placeholder="ex. 0812345675345" required value="<?= set_value('wa_instansi') ?>">
                                <label for="wa_instansi">Whatsapp Instansi</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <small class="text-danger">*Required</small>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="tlp_instansi" name="tlp_instansi" placeholder="ex. 0317884747" required value="<?= set_value('tlp_instansi') ?>">
                                <label for="tlp_instansi">Telepon Kantor</label>
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="ig_instansi" name="ig_instansi" placeholder="instagram instansi" value="<?= set_value('ig_instansi') ?>">
                                <label for="ig_instansi">Instagram Instansi</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fb_instansi" name="fb_instansi" placeholder="facebook instansi" value="<?= set_value('fb_instansi') ?>">
                                <label for="fb_instansi">Facebook Kantor</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="email_instansi" name="email_instansi" placeholder="email instansi" value="<?= set_value('email_instansi') ?>">
                                <label for="email_instansi">Email Kantor</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="img_instansi">Logo Instansi</label>
                            <input type="file" class="form-control dropify" id="img_instansi" name="img_instansi" data-max-file-size="5M" data-allowed-file-extensions="jpeg jpg png">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">SIMPAN</button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif ?>

        <script>
            const instansi = document.querySelector("#instansi");
            const kode_instansi = document.querySelector("#kode_instansi");

            instansi.addEventListener("keyup", function() {
                let preKodeUnit = instansi.value;
                preKodeUnit = preKodeUnit.replace(/\s+/g, '');
                kode_instansi.value = preKodeUnit.toUpperCase();
            });
        </script>
    </div>
    <!--/ Basic Bootstrap Table -->


</div>
<!--/ Responsive Table -->