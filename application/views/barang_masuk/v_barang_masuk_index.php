<div class="container-xxl flex-grow-1 container-p-y">


    <h4 class="py-3 mb-4"><span class="text-muted fw-light"><?= $title_menu ?> /</span> <?= $title ?></h4>

    <?php if ($this->session->flashdata("berhasil")) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?= $this->session->flashdata("berhasil") ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif ?>

    <div class="row" id="pilih-toko">
        <div class="col-xl">
            <?php if ($this->session->userdata('toko_id') == '') : ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4>Pilih Toko</h4>
                                <br>
                                <form>
                                    <div class=" row align-items-center">
                                        <div class="col-10">
                                            <select class="form-control select2" name="toko_id" id="toko_id">
                                                <option value="">-- Pilih Toko --</option>
                                                <?php foreach ($toko as $dtToko) : ?>
                                                    <?php $select = ($this->session->userdata('toko_id') == $dtToko['id_toko']) ? 'selected' : ''; ?>
                                                    <option value="<?= $dtToko['id_toko'] ?>" <?= $select ?>><?= $dtToko['nama_toko'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-2 text-left ">
                                            <button type="button" class="btn btn-sm text-white" id="btn_cari" style="background-color: #213363;">Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
							<div class="col-lg-4"></div>
							<div class="col-lg-2">	
								<button type="button" data-bs-toggle="modal" data-bs-target="#modalImportBarangMasuk" class="btn btn-sm bg-success text-white" id="btn_import_excel" ><span class='tf-icons bx bx-import'></span>&nbsp; Import Excel</button>
							</div>
						

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="modalImportBarangMasuk" tabindex="-1" aria-labelledby="modalDoImport" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action=" #<?//=base_url('barang/list/import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalDoImport">Import Data Barang Masuk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
							<div class="col-lg-12">
									<label class="col-sm-2 col-form-label" for="basic-default-message">Pilih Tipe</label>
								<div class="col-sm-10">
									<div class="form-check form-check-inline">
										<input class="form-check-input" name="tipe" type="radio" id="antar_toko" value="antar_toko">
										<label for="antar_toko" class="form-check-label">Antar Toko Atau Gudang</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" name="tipe" type="radio" id="gudangsupplier" value="gudangsupplier">
										<label for="gudangsupplier" class="form-check-label">Supplier Ke Gudang</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" name="tipe" type="radio" id="toko_luar" value="toko_luar">
										<label for="toko_luar" class="form-check-label">Toko Luar</label>
									</div>
								</div>

							</div>
                            <div class="col d-none">
                                <p>Silahkan download file format excel berikut : <a href="<?= base_url('assets/file_format_import/format_barang_import.xlsx') ?>" class="btn btn-sm btn-info">Download</a></p>
                            </div>
                            <div class="col d-none">
                                <label for="file_barang">File Excel Barang</label>
                                <input type="file" class="form-control" name="file_barang" id="file_barang" max accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if ($this->session->userdata('toko_id') == '') : ?>
        <div class="alert alert-danger alert-dismissible " id="pesan-eror" role="alert">
            Data Tidak Dapat Ditampilkan. Kamu Belum Memilih Toko. Silahkan Memilih Toko
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    <?php endif; ?>

    <!-- Basic Layout -->
    <div class="row d-none" id="row-table">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button type="button" id="add_idtoko" class="btn btn-primary btn-sm"><span class="tf-icons bx bxs-plus-circle"></span>&nbsp; Tambah</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap py-2 px-2">
                        <table class="table table-striped dt-responsive nowrap datatables py-1 px-1 w-100" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Toko Beli</th>
                                    <th>Ke Toko</th>
                                    <th>Barang</th>
                                    <th>Jumlah Masuk</th>
                                    <th>Bukti Beli</th>
                                    <th>Tipe</th>
                                    <?php if($supplier_tampil) : ?>
                                        <th>Nama Supplier</th>
                                        <th>Nomor Supplier</th>
                                    <?php endif; ?>
                                    <th>Tanggal Beli</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
           

            initializeTable("server-side", {
                ajax: {
                    url: "<?= site_url('barang/masuk/ajax') ?>",
                    type: "post",
                    data: function(data) {
                        data.toko_id = $('#toko_id').val()
                        $('#add_idtoko').attr('id_toko', data.toko_id);
                    },
                    dataType: "json",
                },
                drawCallback: function(settings) {

                    const toko_id = $('#toko_id').val();
                    const toko = '<?= $this->session->userdata('toko_id') ?>';
        
                    if (toko.length > 0) {
                        $('#pesan-eror').addClass('d-none');
                        $('#pilih-toko').addClass('d-none');
                        $('#row-table').removeClass('d-none');
                    } else if (toko_id) {
                        $('#row-table').removeClass('d-none');
                        $('#pesan-eror').addClass('d-none');
                    } else {
                        $('#row-table').addClass('d-none');
                    }

                }
            })

        })


        $("#btn_cari").on("click", function() {
            $("#btn_cari").prop("disabled", true)
            $("#btn_cari").html("Mohon tunggu...")
            setTimeout(function() {

                dynamicTable.draw()

                $("#btn_cari").prop("disabled", false)
                $("#btn_cari").html("Cari")

            }, 1000)

        })

        $('#add_idtoko').click(function(event) {
            event.preventDefault();
            let tokoId = $(this).attr('id_toko');
            let encryptTokoId = btoa(tokoId);
            let url = '<?= base_url('barang/masuk/tambah') ?>?toko_id=' + encryptTokoId;
            window.open(url, '_blank');
        });

		$('#btn_import_excel').on('click', function(){
			
		})
    </script>
