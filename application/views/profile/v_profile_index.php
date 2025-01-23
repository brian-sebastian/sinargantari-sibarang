<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>
    <?php if ($this->session->flashdata("gagal")) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <?= $this->session->flashdata("gagal") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>
    <?php
    $checkProfileImage = profileImageCheck();

    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Detail</h5>
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <?php if ($this->session->userdata('role_id') == 1) : ?>
                            <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                <img src="<?= base_url('assets/be/img/avatars/av1.png') ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php else : ?>
                                <img src="<?= base_url('assets/file_foto_profile/') . $checkProfileImage['image_profile'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php endif ?>

                        <?php elseif ($this->session->userdata('role_id') == 2) : ?>
                            <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                <img src="<?= base_url('assets/be/img/avatars/av2.png') ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php else : ?>
                                <img src="<?= base_url('assets/file_foto_profile/') . $checkProfileImage['image_profile'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php endif ?>

                        <?php elseif ($this->session->userdata('role_id') == 3 || $this->session->userdata('role_id') == 4 || $this->session->userdata('role_id') == 5) : ?>

                            <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                <img src="<?= base_url('assets/be/img/avatars/av4.png') ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php else : ?>
                                <img src="<?= base_url('assets/file_foto_profile/') . $checkProfileImage['image_profile'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php endif ?>

                        <?php else : ?>
                            <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                <img src="<?= base_url('assets/be/img/avatars/av4.png') ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php else : ?>
                                <img src="<?= base_url('assets/file_foto_profile/') . $checkProfileImage['image_profile'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                            <?php endif ?>
                        <?php endif ?>

                        <!-- <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                    </div> -->


                        <div class="edit-icon" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="bx bx-pencil"></i> Ganti Foto
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <form id="formAccountSettings" action="<?= base_url('profile/edit') ?>" method="POST">
                        <input type="hidden" name="id_user" value="<?= $users['id_user'] ?>">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input class="form-control" type="text" id="username" name="username" value="<?= $users['username'] ?>" readonly autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="nama_user" class="form-label">Nama Lengkap</label>
                                <input required class="form-control" type="text" name="nama_user" id="nama_user" value="<?= $users['nama_user'] ?>" />
                                <?= form_error('nama_user', ' <small class="text-danger pl-3">', '</small>') ?>
                            </div>

                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
            <div style="display: none;">
                <div class="card">
                    <h5 class="card-header">Delete Account</h5>
                    <div class="card-body">
                        <div class="mb-3 col-12 mb-0">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                                <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                            </div>
                        </div>


                        <form id="formAccountDeactivation" onsubmit="return false">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                                <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                            </div>
                            <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="uploadPhotoForm" action="<?= base_url('profile/upload_photo') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" readonly name="user_id" id="user_id" class="form-control" value="<?= $this->session->userdata('id_user') ?>">
                    <input type="hidden" readonly name="username" id="username" class="form-control" value="<?= $this->session->userdata('username') ?>">
                    <input type="file" name="image_profile" id="image_profile" class="form-control" accept="image/png, image/jpeg">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChangesButton">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('saveChangesButton').addEventListener('click', function() {
        document.getElementById('uploadPhotoForm').submit();
    });
</script>