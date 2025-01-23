<!-- Layout container -->
<div class="layout-page">
    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">

        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">



            <ul class="navbar-nav flex-row align-items-center ms-auto">


                <?php if (!$this->session->userdata('toko_id') || $this->session->userdata('toko_id') == 1) : ?>
                    <div class="btn-group me-2 px-2">
                        <button type="button" class="btn btn-info btn-sm position-relative dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='bx bxs-bell-ring'></i>
                            <span id="count_message" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= showNumsOrderDateNow() ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu  dropdown-menu-end" id="msg_push">
                            <?php foreach (showOrderDateNow() as $trans) : ?>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url() . 'kasir/sales_order/detail/' . $this->secure->encrypt_url($trans['kode_order']) ?>">
                                        Transaksi Baru Marketplace
                                        <b>
                                            <?= $trans['kode_order'] ?>
                                        </b>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>


                    </div>
                <?php endif ?>


                <?php
                $checkProfileImage = profileImageCheck();
                ?>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <?php if ($this->session->userdata('role_id') == 1) : ?>
                                <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                    <img src="<?= base_url('assets/be/img/avatars/av1.png') ?>" alt class="w-px-40 h-auto rounded-circle">
                                <?php else : ?>
                                    <img src="<?= base_url('assets/file_foto_profile/') . $checkProfileImage['image_profile'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                <?php endif ?>
                            <?php elseif ($this->session->userdata('role_id') == 2) : ?>
                                <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                    <img src="<?= base_url('assets/be/img/avatars/av2.png') ?>" alt class="w-px-40 h-auto rounded-circle">
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
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>

                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <?php if ($this->session->userdata('role_id') == 1) : ?>
                                                <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                                    <img src="<?= base_url('assets/be/img/avatars/av1.png') ?>" alt class="w-px-40 h-auto rounded-circle">
                                                <?php else : ?>
                                                    <img src="<?= base_url('assets/file_foto_profile/') . $checkProfileImage['image_profile'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                                <?php endif ?>
                                            <?php elseif ($this->session->userdata('role_id') == 2) : ?>
                                                <?php if ($checkProfileImage['image_profile'] == null || $checkProfileImage['image_profile'] == '') : ?>
                                                    <img src="<?= base_url('assets/be/img/avatars/av2.png') ?>" alt class="w-px-40 h-auto rounded-circle">
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
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block"><?= $this->session->userdata('nama_user'); ?></span>
                                        <small class="text-muted"><?= $this->session->userdata('username'); ?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('profile/profil/') . $this->session->userdata('username') ?>">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item detail_change_button" href="#" data-username="<?= $this->session->userdata('username') ?>" data-bs-toggle="modal" data-bs-target="#changePassword">
                                <i class="bx bx-cog me-2"></i>
                                <span class="align-middle">Change Password</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#billing">
                                <span class="d-flex align-items-center align-middle">
                                    <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                    <span class="flex-grow-1 align-middle ms-1">Billing</span>
                                    <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </nav>
    <!-- / Navbar -->

    <!-- Modal -->
    <div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="data_password" method="post">
                    <input type="hidden" name="id_user" id="id_user">

                    <div class="modal-body">

                        <div class="row">
                            <!-- Account -->

                            <div class="row">
                                <div class="mb-3 col-md-12 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">New Password</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="passwordC" class="form-control" required name="password_edit" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger pl-3" id="err_password_edit"></small>

                                </div>
                                <div class="mb-3 col-md-12 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password Confirm</label>
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password2" class="form-control" required name="password2_edit" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    <small class="text-danger pl-3" id="err_password2_edit"></small>
                                </div>
                            </div>

                            <!-- /Account -->
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" id="simpan_password" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="billing" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Coming Soon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <h1>Fitur Belum tersedia</h1>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('.detail_change_button').on('click', function() {
            const username = $(this).data("username")

            $.ajax({
                url: "<?= site_url('profile/tampilan_edit') ?>",
                type: "POST",
                data: {
                    username: username
                },
                dataType: "JSON",
                beforeSend: function() {

                    $('#err_password_edit').html("")
                    $('#err_password2_edit').html("")
                },
                success: function(result) {

                    if (result.status == "berhasil") {
                        const response = result.response
                        $('#id_user').val(response.id_user);

                    }
                }
            })
        })

        $('#simpan_password').on('click', function() {
            const data_form = $('#data_password').serialize();
            $.ajax({
                url: '<?= base_url('profile/changepassword') ?>',
                type: "POST",
                data: data_form,
                dataType: "JSON",
                beforeSend: function() {
                    $('#err_password_edit').html("")
                    $('#err_password2_edit').html("")

                    $("#simpan_password").html("Tunggu...")
                    $("#simpan_password").prop("disabled", true)

                },
                success: function(result) {
                    if (result.status == 'error') {
                        if (result.err_password_edit) {
                            $('#err_password_edit').html(result.err_password_edit);
                        }
                        if (result.err_password2_edit) {
                            $('#err_password2_edit').html(result.err_password2_edit);
                        }
                    } else {
                        alert(result.response);
                        window.location.reload();
                    }

                    $("#simpan_password").html("Simpan")
                    $("#simpan_password").prop("disabled", false)
                }
            })
        })
    </script>

    <!-- Content wrapper -->
    <div class="content-wrapper">