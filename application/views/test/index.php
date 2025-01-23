<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Barang /</span> List</h4>


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
                    <h6>Test Auto Complete</h6>
                </div>
            </div>
        </div>
        <div class="body">
            <div class="row">
                <form action="" method="get">
                    <div class="row">

                        <div class="ui-widget">
                            <label for="tags">Tags: </label>
                            <input id="searchInputAutoComplete" class="form-control">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>