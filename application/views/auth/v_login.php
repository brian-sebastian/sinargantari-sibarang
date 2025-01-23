<!-- Content -->

<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->

            <?php if ($this->session->flashdata('message')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?= $this->session->flashdata('message') ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($this->session->flashdata('message_error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?= $this->session->flashdata('message_error') ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>


            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <?php
                    $queryGetSetting = $this->db->get('tbl_setting')->row_array();
                    ?>

                    <?php if ($queryGetSetting['instansi'] == NULL && $queryGetSetting['img_instansi'] == NULL) : ?>
                        <div class="app-brand justify-content-center">
                            <a href="javascript:void(0);" class="app-brand-link gap-2">
                                <span class="app-brand-text demo text-body fw-bolder"><img src="<?= base_url('assets/be/img/tb.png') ?>" alt="" width="100"> Toko Bangunan</span>
                            </a>
                        </div>
                    <?php else : ?>

                        <img class="d-block mx-auto mb-2" height="200px" src="<?= base_url('assets/be/img/logo/') . $queryGetSetting['img_instansi'] ?>" alt="<?= $queryGetSetting['instansi'] ?>" srcset="<?= base_url('assets/be/img/logo/') . $queryGetSetting['img_instansi'] ?>">
                        <div class="app-brand justify-content-center">
                            <span class="app-brand-text demo menu-text fw-bold ms-2"><?= $queryGetSetting['instansi'] ?></span>
                        </div>
                        <hr>

                    <?php endif ?>



                    <!-- /Logo -->
                    <h4 class="mb-2 text-center">Login To Your Account</h4>
                    <p class="mb-4 text-center">&nbsp;</p>

                    <form class="mb-3" action="<?= base_url('auth') ?>" method="post">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" required class="form-control" id="username" name="username" placeholder="Enter your username" value="<?= set_value('username') ?>" autofocus />
                            <?= form_error('username', ' <small class="text-danger pl-3">', '</small>') ?>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" required id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            <?= form_error('password', ' <small class="text-danger pl-3">', '</small>') ?>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                        </div>
                    </form>


                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>

<!-- / Content -->