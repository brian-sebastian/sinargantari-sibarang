<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu; ?>/</span> <?= $title; ?></h4>
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
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#tambah_modal">
                        <span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Diskon</th>
                                    <th class="text-center">Nama Toko</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach ($data_diskon as $dd) : ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $dd["nama_diskon"] ?></td>
                                        <td><?= $dd["nama_toko"] ?></td>
                                        <td><?= $dd["nama_barang"] ?></td>
                                        <td class="text-center">
                                            <a onclick="lihat_diskon(<?= $dd['id_diskon'] ?>)">
                                                <button type="button" class="btn btn-sm btn-info">
                                                  <span class="tf-icons bx bx-show-alt"></span>&nbsp; Lihat
                                                </button>
                                            </a>
                                            <a onclick="ubah_diskon(<?= $dd['id_diskon'] ?>)">
                                                <button type="button" class="btn btn-sm btn-warning">
                                                  <span class="tf-icons bx bx-edit-alt"></span>&nbsp; Edit
                                                </button>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#remove_modal<?= $dd['id_diskon']; ?>">
                                              <span class="tf-icons bx bx-trash"></span>&nbsp; Hapus
                                            </button>
                                        </td>

                                        <!-- staticBackdrop removeModal -->
                                        <div class="modal fade" id="remove_modal<?= $dd['id_diskon']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="<?= $dd['id_diskon']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center p-5">
                                                        
                                                        <div class="mt-4">
                                                            <h4 class="mb-3">Apakah anda yakin akan menghapus data ini?</h4>
                                                            <p class="text-muted mb-4"> Data tidak akan bisa dikembalikan lagi jika sudah di hapus!</p>
                                                            <div class="hstack gap-2 justify-content-center">
                                                                <a class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                                                                <a href="<?= base_url('toko/diskon/hapus/') . base64_encode($dd['id_diskon']) ?>" class="btn btn-danger">Hapus</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php $no++;  endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    </>
                </div>

                <!-- Modal Tambah -->
                <div class="modal fade" id="tambah_modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Tambah Diskon</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="form_tambah" method="post">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <label for="type" class="form-label">Nama Diskon</label>
                                            <input type="text" id="nama_diskon" name="nama_diskon" class="form-control" />
                                            <small class="text-danger" id="err_nama_diskon"></small>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label for="type" class="form-label">Nama Toko</label>
                                            <?php if ($this->session->userdata('toko_id') == '') { ?>
                                                <select name="toko_id" id="toko_id_diskon" class="form-select" style="width: 100%;">
                                                    <option value="">--Pilih--</option>
                                                    <?php foreach ($data_toko as $dt) : ?>
                                                        <option value="<?= $dt["id_toko"] ?>" toko_id="<?= $dt["id_toko"] ?>">
                                                            <?= $dt["nama_toko"] ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php } else { ?>
                                                <select name="toko_id" id="toko_id_diskon" class="form-select" style="width: 100%;">
                                                    <option value="<?= $data_toko["id_toko"] ?>" toko_id="<?= $data_toko["id_toko"] ?>" selected>
                                                        <?= $data_toko["nama_toko"] ?>
                                                    </option>
                                                </select>
                                            <?php } ?>
                                            <small class="text-danger" id="err_toko_id_diskon"></small>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label for="type" class="form-label">Barang Toko</label>
                                            <select name="id_harga" id="id_harga_diskon" class="form-select" style="width: 100%;">
                                            </select>
                                            <small class="text-danger" id="err_id_harga_diskon"></small>
                                        </div>
                                        <div class="col-2 mb-3">
                                            <label for="type" class="form-label">Satuan Pembelian</label>
                                            <p class="contact-born text-muted satuan"></p>
                                        </div>
                                        <div class="col-2 mb-3">
                                            <label for="type" class="form-label">Minimal Pembelian</label>
                                            <input type="number" id="minimal_beli" name="minimal_beli" class="form-control" />
                                            <small class="text-danger" id="err_minimal_beli"></small>
                                        </div>
                                        <div class="col-3 mb-3">
                                            <label for="type" class="form-label">Harga Potongan</label>
                                            <input type="number" id="harga_potongan" name="harga_potongan" class="form-control" />
                                            <small class="text-danger" id="err_harga_potongan"></small>
                                        </div>
                                        <div class="col-2 mb-3">
                                            <label for="type" class="form-label">Tanggal Mulai</label>
                                            <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control" />
                                            <small class="text-danger" id="err_tgl_mulai"></small>
                                        </div>
                                        <div class="col-2 mb-3">
                                            <label for="type" class="form-label">Tanggal Akhir</label>
                                            <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" />
                                            <small class="text-danger" id="err_tgl_akhir"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btn_tambah">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Tambah -->
            </div>
        </div>
    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="lihatDiskon_Modal" tabindex="-1" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Detail Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="lihatDiskon">
                </div>
            </div>
        </div>
    </div>

    <!-- Grids in detail_modal -->
    <div class="modal fade" id="ubahDiskon_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_ubah" method="post">
                    <div class="modal-body" id="ubahDiskon">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn_ubah">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $("#btn_tambah").on("click", function() {

                const data_form = $("#form_tambah").serialize()

                $.ajax({

                    url: "<?= site_url("toko/diskon/tambah") ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // disabled button
                        $("#btn_tambah").prop("disabled", true)
                        $("#btn_tambah").html("Tunggu...")

                        // clear element error
                        $("#err_toko_id_diskon").html("")
                        $("#err_id_harga_diskon").html("")
                        $("#err_nama_diskon").html("")
                        $("#err_harga_potongan").html("")
                        $("#err_tgl_mulai").html("")
                        $("#err_tgl_akhir").html("")
                        $("#err_minimal_beli").html("")

                        // empty element error
                        $("#toko_id_diskon").val("")
                        $("#toko_id_diskon").trigger("change")
                        $("#id_harga_diskon").val("")
                        $("#id_harga_diskon").trigger("change")
                        $("#nama_diskon").val("")
                        $("#harga_potongan").val("")
                        $("#tgl_mulai").val("")
                        $("#tgl_akhir").val("")
                        $("#minimal_beli").val("")

                    },
                    success: function(result) {

                        if (result.status == "error") {

                            if (result.err_toko_id_diskon) {
                                $("#err_toko_id_diskon").html(result.err_toko_id_diskon)
                            }

                            if (result.err_id_harga_diskon) {
                                $("#err_id_harga_diskon").html(result.err_id_harga_diskon)
                            }

                            if (result.err_nama_diskon) {
                                $("#err_nama_diskon").html(result.err_nama_diskon)
                            }

                            if (result.err_harga_potongan) {
                                $("#err_harga_potongan").html(result.err_harga_potongan)
                            }

                            if (result.err_tgl_mulai) {
                                $("#err_tgl_mulai").html(result.err_tgl_mulai)
                            }

                            if (result.err_tgl_akhir) {
                                $("#err_tgl_akhir").html(result.err_tgl_akhir)
                            }

                            if (result.err_minimal_beli) {
                                $("#err_minimal_beli").html(result.err_minimal_beli)
                            }

                        } else {

                            window.location.reload()
                        }

                        $("#btn_tambah").prop("disabled", false)
                        $("#btn_tambah").html("Tambah")

                    }
                })
            })

            $("#btn_ubah").on("click", function() {

                const data_form = $("#form_ubah").serialize()

                $.ajax({
                    url: "<?= site_url('toko/diskon/ubah') ?>",
                    type: "POST",
                    data: data_form,
                    dataType: "json",
                    beforeSend: function() {

                        // clear element error
                        $("#err_toko_id_diskonU").html("")
                        $("#err_id_harga_diskonU").html("")
                        $("#err_nama_diskonU").html("")
                        $("#err_harga_potonganU").html("")
                        $("#err_tgl_mulaiU").html("")
                        $("#err_tgl_akhirU").html("")
                        $("#err_minimal_beliU").html("")

                        $("#btn_ubah").html("Tunggu...")
                        $("#btn_ubah").prop("disabled", true)

                    },
                    success: function(result) {

                        // alert(JSON.stringify(result))

                        if (result.status == "error") {

                            if (result.err_toko_id_diskonU) {
                                $("#err_toko_id_diskonU").html(result.err_toko_id_diskonU)
                            }

                            if (result.err_id_harga_diskonU) {
                                $("#err_id_harga_diskonU").html(result.err_id_harga_diskonU)
                            }

                            if (result.err_nama_diskonU) {
                                $("#err_nama_diskonU").html(result.err_nama_diskonU)
                            }

                            if (result.err_harga_potonganU) {
                                $("#err_harga_potonganU").html(result.err_harga_potonganU)
                            }

                            if (result.err_tgl_mulaiU) {
                                $("#err_tgl_mulaiU").html(result.err_tgl_mulaiU)
                            }

                            if (result.err_tgl_akhirU) {
                                $("#err_tgl_akhirU").html(result.err_tgl_akhirU)
                            }

                            if (result.err_minimal_beliU) {
                                $("#err_minimal_beliU").html(result.err_minimal_beliU)
                            }

                        } else {
                            window.location.reload()
                        }

                        $("#btn_ubah").html("Ubah")
                        $("#btn_ubah").prop("disabled", false)

                    }
                })

            })

        })

        function lihat_diskon(id) {
            // console.log(id)
            $('#lihatDiskon').empty()
            $.get('<?= base_url() ?>toko/diskon/lihat/' + btoa(id), function(data_diskon) {
                $('#lihatDiskon').html(data_diskon)
                $('#lihatDiskon_Modal').modal('show')
            })
        }
        function ubah_diskon(id) {
            // console.log(id)

            $('#ubahDiskon').empty()
            $.get('<?= base_url() ?>toko/diskon/ubah/' + btoa(id), function(data_diskon) {
                $('#ubahDiskon').html(data_diskon)
                $('#ubahDiskon_Modal').modal('show')
            })
        }

        document.getElementById('toko_id_diskon').addEventListener('change', function() {
            const id = $(this).find('option:selected').attr('toko_id');
            fetch("<?= base_url('toko/diskon/dt_toko/') ?>" + id, {
                    type: 'post',
                })
                .then((response) => response.text())
                .then((data) => {
                    document.getElementById('id_harga_diskon').innerHTML = data
                });

        });

        const id = document.getElementById('toko_id_diskon').value;
        fetch("<?= base_url('toko/diskon/dt_toko/') ?>" + id, {
                type: 'post',
            })
            .then((response) => response.text())
            .then((data) => {
                document.getElementById('id_harga_diskon').innerHTML = data
            });

        document.getElementById('id_harga_diskon').addEventListener('change',function(){
            const id_harga = document.getElementById('id_harga_diskon').value;
            $.ajax({
                url: "<?= base_url('toko/diskon/dt_barang_toko'); ?>",
                type: 'post',
                data: {
                    id_harga: id_harga
                },
                success: function(data) {
                    const obj = JSON.parse(data);
                    $('.satuan').html(obj.satuan);
                }
            });
        });
    </script>