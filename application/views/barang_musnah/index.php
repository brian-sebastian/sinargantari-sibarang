<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>


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
        <div class="card-body">
            <div class="table-responsive text-nowrap py-2 px-2">
                <table class="table table-striped nowrap py-1 px-1" id="dynamicTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Kode Barang</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Nama Toko</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Tanggal Musnah</th>
                            <th class="text-center">Bukti Musnah</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

</div>
<!--/ Responsive Table -->

<script>
    $(function() {

        initializeTable("server-side", {
            ajax: {
                url: "<?= site_url('barang_cacat/musnah_cacat/ajax') ?>",
                type: "post",
                dataType: "json",
            },
            "ordering": false,
            "searching": true,
            "info": true,
            "lengthChange": true,
            "paging": true,
        })

    })
</script>