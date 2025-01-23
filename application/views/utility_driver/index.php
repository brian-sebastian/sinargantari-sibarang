<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?>/</span> <?= $title ?></h4>

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

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">

                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Driver</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Driver POS 58MM</td>
                                    <td>
                                        <a href="<?= base_url('driver/downloadDriver58') ?>" target="_blank" class="btn btn-sm btn-success">Download</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Driver POS 80MM</td>
                                    <td>
                                        <a href="<?= base_url('driver/downloadDriver80') ?>" target="_blank" class="btn btn-sm btn-success">Download</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Driver POS 120MM</td>
                                    <td>
                                        <a href="<?= base_url('driver/downloadDriver120') ?>" target="_blank" class="btn btn-sm btn-success">Download</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>