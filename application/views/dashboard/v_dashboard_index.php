<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">

                <div class="card-header">

                    <div class="row">
                        <div class="col my-2 mx-2">
                            <h5>Hello, <?= $this->session->userdata('nama_user') ?></h5>
                        </div>
                    </div>

                    <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2) : ?>
                        <div class="form-group w-25 mb-2">
                            <label for="periode">Periode : </label>
                            <select name="periode" id="periode" class="form-control select2">
                                <?php for ($i = 0; $i <= 5; $i++) : ?>
                                    <option value="<?= date("Y") - $i ?>"><?= date("Y") - $i ?></option>
                                <?php endfor ?>
                            </select>
                        </div>
                    <?php endif ?>

                    <?php if (!$this->session->userdata("toko_id")) : ?>
                        <div class="form-group w-25 mb-2">
                            <label for="toko_id">Toko : </label>
                            <select name="toko_id" id="toko_id" class="form-control select2">
                                <option value="">Semua</option>
                                <?php foreach ($data_toko as $dt) : ?>
                                    <option value="<?= $dt["id_toko"] ?>"><?= $dt["nama_toko"] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="toko_id" id="toko_id" class="form-control" value="<?= $this->session->userdata("toko_id") ?>">
                    <?php endif ?>
                    <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2) : ?>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-primary" onclick="loadAjax(loadData)">Lihat data</button>
                        </div>
                    <?php endif ?>

                </div>
                <?php if ($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 2) : ?>
                    <div id="container" style="width:100%; height:400px;"></div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
</div>
<!-- / Content -->

<script>
    function loadData(parameter) {

        const chart = Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Rekapitulasi penjualan'
            },
            xAxis: {
                categories: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            },
            yAxis: {
                title: {
                    text: 'Penjualan'
                }
            },
            series: [{
                name: 'Penjualan',
                data: parameter.map(function(e) {
                    return parseInt(e)
                })
            }]
        });

    }

    function loadAjax(callback) {

        $.ajax({

            url: "<?= site_url('dashboard/penjualan') ?>",
            type: "get",
            data: {
                periode: $("#periode").val(),
                toko_id: $("#toko_id").val()
            },
            dataType: "json",
            success: function(result) {

                callback(result.response);
            }

        });

    }

    loadAjax(loadData)
</script>