<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>



    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="<?= base_url('toko/supplier/tambah') ?>" class="btn btn-primary btn-sm"><span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1 w-100" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Supplier</th>
                                    <th>No Telpon</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    </>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            initializeTable("server-side", {
                ajax: {
                    url: "<?= site_url('toko/supplier/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        // data.toko_id = $('#toko_id').val()

                    },
                    dataType: "json",
                },

            })

        })
    </script>