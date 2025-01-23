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

    <?php if ($this->session->userdata('toko_id') || $this->input->get('toko_id')) : ?>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div class="p-2">
                                <h4>Request Barang</h4>
                            </div>
                            <div class="p-2">
                                <button class="btn btn-sm btn-primary" id="btnAddRequestBarang" data-bs-toggle="modal" data-bs-target="#showModalAddRequestBarang"><i class="tf-icons bx bxs-plus-circle"></i> Tambah Request</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Add Request Barang -->
                    <div class="modal fade" id="showModalAddRequestBarang" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="" method="post">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Tambah Request</h1>
                                        <button type="button" class="btn-close" id="closeModalAddRequestBarang" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="show-message-when-invalid" class="d-none">
                                            <div class="alert alert-danger" role="alert" id="content-show-message-when-invalid">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="kode_request" class="form-label">Kode Request</label>
                                            <input type="text" class="form-control" name="kode_request" id="kode_request" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="request_toko_id" class="form-label">Nama Request Request</label>
                                            <input type="text" class="form-control" name="request_toko_id" id="request_toko_id" hidden readonly>

                                            <input type="text" class="form-control" name="pengirim" id="pengirim" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="penerima_toko_id" class="form-label">Pilih Penerima Toko</label>
                                            <div id="select-penerima-toko-id">
                                                <select name="penerima_toko_id" id="penerima_toko_id" class="form-select">
                                                    <option value="">Pilih Toko Penerima</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3" id="select-penerima-toko-id">
                                            <div id="messageselect-penerima-toko-id" class="d-none"></div>
                                            <div id="content-select-penerima-toko-id" class="d-none">
                                                <label for="barang_penerima_toko_id" class="form-label">Pilih Barang Toko</label>
                                                <select name="barang_penerima_toko_id" id="barang_penerima_toko_id" class="form-select">
                                                    <option value="">Pilih Barang Toko Penerima</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <hr>
                                            <h6>List Barang</h6>
                                            <span class="d-none" id="validate_message_checkeditem"></span>
                                            <span class="d-none text-danger" id="err_message_list_barang"></span>
                                            <div id="content-list-barang-selected-fix">
                                                <!-- ATTRIBUTE BARANG -->
                                                <input readonly type="hidden" id="atribut_barang_input" name="atribut_barang">
                                                <table id="selected-barang-table" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>ID Barang</th>
                                                            <th>Nama Barang</th>
                                                            <th>Quantity</th>
                                                            <th>Keterangan</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" id="closeModalAddRequestBarang" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="btnSaveAndAddRequestNew">Save changes</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive text-nowrap py-2 px-2">
                            <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kode Request</th>
                                        <th class="text-center">Permintaan ke Toko</th>
                                        <th class="text-center">List Permintaan</th>
                                        <th class="text-center">Pengirim</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($data_perequest as $dp) : ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $dp["kode_request"] ?></td>
                                            <td><?= $dp["nama_toko"] ?></td>
                                            <td>
                                                <?php
                                                $dataJsonDecode = json_decode($dp['atribut_barang'], true);
                                                ?>
                                                <?php foreach ($dataJsonDecode as $keyItem => $valueItem) : ?>
                                                    <?php
                                                    $id_barang = $valueItem['id_barang'];
                                                    $nama_barang = $valueItem['nama_barang'];
                                                    $qty_request = $valueItem['qty_request'];
                                                    $ket = $valueItem['ket'];
                                                    ?>
                                                    <h6><span class="badge bg-info"><?= $nama_barang . "($qty_request)" ?></span></h6>

                                                <?php endforeach ?>
                                            </td>
                                            <td><?= $dp["pengirim"] ?></td>
                                            <td>
                                                <?php if ($dp["status"] == STATUS_REQUEST_DRAFT) : ?>
                                                    <h6><span class="badge bg-secondary"><?= STATUS_REQUEST_DRAFT ?></span></h6>
                                                <?php endif ?>
                                                <?php if ($dp["status"] == STATUS_REQUEST_ACCEPTED) : ?>
                                                    <h6><span class="badge bg-success"><?= STATUS_REQUEST_ACCEPTED ?></span></h6>
                                                <?php endif ?>
                                                <?php if ($dp["status"] == STATUS_REQUEST_DECLINE) : ?>
                                                    <h6><span class="badge bg-danger"><?= STATUS_REQUEST_DECLINE ?></span></h6>
                                                <?php endif ?>
                                            </td>
                                            <td class="text-center">
                                                <!-- <a onclick="lihat_diskon(<?= $dp['id_request'] ?>)">
                                                    <button type="button" class="btn btn-sm btn-info">
                                                        <span class="tf-icons bx bx-show-alt"></span>&nbsp; Lihat
                                                    </button>
                                                </a>
                                                <a onclick="ubah_diskon(<?= $dp['id_request'] ?>)">
                                                    <button type="button" class="btn btn-sm btn-warning">
                                                        <span class="tf-icons bx bx-edit-alt"></span>&nbsp; Edit
                                                    </button>
                                                </a> -->
                                                <button type="button" class="btn btn-sm btn-danger remove_request_barang_modal" data-id_request="<?= $dp['id_request'] ?>">
                                                    <span class="tf-icons bx bx-trash"></span>&nbsp; Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Form Accept Request Barang</h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive text-nowrap py-2 px-2">
                            <table class="table table-striped dt-responsive nowrap datatables py-1 px-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kode Request</th>
                                        <th class="text-center">Permintaan dari Toko</th>
                                        <!-- <th class="text-center">List Permintaan</th> -->
                                        <th class="text-center">Pengirim</th>
                                        <!-- <th class="text-center">Status</th> -->
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($data_penerima_request as $dpr) : ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= $dpr["kode_request"] ?></td>
                                            <td><?= $dpr["nama_toko"] ?></td>
                                            <!-- <td><?= $dpr["atribut_barang"] ?></td> -->
                                            <td><?= $dpr["pengirim"] ?></td>
                                            <!-- <td>
                                            <?php if ($dpr["status"] == "draft") { ?>
                                                <small class='text-primary'><?= $dpr["status"] ?></small>
                                            <?php } elseif ($dpr["status"] == "proses") { ?>
                                                <small class='text-warning'><?= $dpr["status"] ?></small>
                                            <?php } else { ?>
                                                <small class='text-success'><?= $dpr["status"] ?></small>
                                            <?php } ?>
                                        </td> -->
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-info accDetail" data-bs-toggle="modal" data-bs-target="#accModal" data-id="<?= $dpr["id_request"] ?>" data-status="<?= $dpr["status"] ?>">
                                                    <span class="tf-icons bx bx-show-alt"></span>&nbsp; Lihat
                                                </button>
                                            </td>
                                        </tr>
                                    <?php $no++;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="accModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-content">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-success" id="accRequest">Accept</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- When Superadmin / developer -->
    <?php else : ?>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6>Pilih Toko Untuk Melakukan Request</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?= base_url('barang/request_barang') ?>">
                            <div class="mb-3">
                                <select class="form-select" name="toko_id">
                                    <option selected value="">Pilih Toko</option>
                                    <?php foreach ($data_toko as $key => $valueToko) : ?>
                                        <option value="<?= $this->secure->encrypt_url($valueToko['id_toko']) ?>"><?= $valueToko['nama_toko'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Pilih</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endif ?>


    <script>
        // TODO : ACCEPTED REQUEST FUNCTION
        $("body").on("click", ".accDetail", function() {

            const id = $(this).data("id")
            const status = $(this).data("status")

            $.ajax({
                url: "<?= site_url('barang/request_barang/acc_detail') ?>",
                type: "post",
                data: {
                    id: id
                },
                dataType: "json",
                beforeSend: function() {

                    if (status == "draft") {
                        $("#accRequest").addClass("d-block")
                        $("#accRequest").removeClass("d-none")
                    } else {

                        $("#accRequest").addClass("d-none")
                        $("#accRequest").removeClass("d-block")
                    }

                },
                success: function({
                    status,
                    response
                }) {

                    if (status == "berhasil") {

                        $("#modal-content").html(response)

                    } else {

                        alert(response)
                        window.location.reload()

                    }

                }
            });
        })

        $("body").on("click", ".doCancel", function() {
            const parents = $(this).parent().parent().parent()
            parents.remove()
        })

        $("#accRequest").on("click", function() {

            const formAcc = $("#formAcc").serialize()

            $.ajax({
                url: "<?= site_url('barang/request_barang/acc_submit') ?>",
                type: "post",
                data: formAcc,
                dataType: "json",
                beforeSend: function() {
                    $(".err_stok").html("")
                    $("#accRequest").html("Loading...").prop("disabled", true)
                },
                success: function({
                    status,
                    response,
                    ...obj
                }) {

                    if (status == "error") {

                        if (obj.err_barang) {

                            const barang = JSON.parse(obj.err_barang)

                            barang.forEach(element => {

                                $(`#qtyStok${element.id_barang}`).val(element.stok)
                                $(`#err_stok${element.id_barang}`).html(element.pesan)

                            });

                        }

                        $("#accRequest").html("Accept").prop("disabled", false)
                    } else {

                        alert(response)
                        window.location.reload()

                    }
                }
            });

        })


        // TODO : START TO REQUEST
        $('#btnAddRequestBarang').on('click', function() {
            <?php
            $toko_id_session = $this->session->userdata('toko_id');
            $toko_id_url = $this->input->get('toko_id');
            $currentTokoId = '';
            if ($toko_id_session) {
                $currentTokoId = $toko_id_session;
            }
            if ($toko_id_url) {
                $currentTokoId = $toko_id_url;
            }
            ?>
            let currentTokoId = "<?= $currentTokoId ?>";
            let BASE_URL = "<?= base_url(); ?>";
            $.ajax({
                type: "GET",
                url: BASE_URL + 'barang/request_barang/checkRequestBarang',
                dataType: "JSON",
                data: {
                    toko_id: currentTokoId
                },
                success: function(response) {
                    console.log(response);
                    $('#kode_request').val(response.kode_request);
                    $('#request_toko_id').val(response.request_toko_id);
                    $('#pengirim').val(response.pengirim);

                    let selectPenerimaToko = $('#penerima_toko_id');

                    selectPenerimaToko.empty();

                    selectPenerimaToko.append($('<option>', {
                        value: "",
                        text: "Pilih Toko Penerima"
                    }));

                    $.each(response.list_penerima_toko_id, function(index, toko) {
                        let option = $('<option>', {
                            value: toko.id_toko,
                            text: toko.nama_toko
                        });
                        selectPenerimaToko.append(option);
                    });


                    $('#showModalAddRequestBarang').modal('show')
                }
            });
        });

        $('#penerima_toko_id').on('change', function() {
            let selectedValue = $(this).val();

            if (selectedValue == "" || selectedValue == undefined || selectedValue == null) {
                $('#messageselect-penerima-toko-id').removeClass('d-none').addClass('d-block');
                $('#content-select-penerima-toko-id').removeClass('d-block').addClass('d-none');

                let messageErrWhenNull = `<span class="text-danger">Maaf Kamu Belum Memilih Toko, Silahkan Pilih Toko</span>`;
                $('#messageselect-penerima-toko-id').html(messageErrWhenNull);
            } else {
                let BASE_URL = "<?= base_url(); ?>"
                $.ajax({
                    type: "POST",
                    url: BASE_URL + 'barang/request_barang/getBarangTokoSelectAjax',
                    dataType: "JSON",
                    data: {
                        toko_id: selectedValue
                    },
                    success: function(response) {

                        if (response.err_code == 0) {
                            $('#messageselect-penerima-toko-id').removeClass('d-block').addClass('d-none');
                            $('#content-select-penerima-toko-id').removeClass('d-none').addClass('d-block');

                            let selectBarangPenerimaToko = $('#barang_penerima_toko_id');

                            selectBarangPenerimaToko.empty();

                            selectBarangPenerimaToko.append($('<option>', {
                                value: "",
                                text: "Pilih Barang Toko Penerima"
                            }));


                            $.each(response.list_barang_toko, function(index, barang) {
                                let option = $('<option>', {
                                    value: barang.id_barang,
                                    text: barang.nama_barang
                                });
                                selectBarangPenerimaToko.append(option);
                            });

                        } else {

                            $('#messageselect-penerima-toko-id').removeClass('d-none').addClass('d-block');
                            $('#content-select-penerima-toko-id').removeClass('d-block').addClass('d-none');

                            let messageErr = `<span class="text-danger">${response.message}</span>`;
                            $('#messageselect-penerima-toko-id').html(messageErr);
                        }
                        console.log(response);
                    }
                });
            }

            // alert(`Selected value: ${selectedValue}`);
        });

        $('#barang_penerima_toko_id').on('change', function() {
            let selectedBarangToko = $(this).val();

            if (selectedBarangToko == "" || selectedBarangToko == undefined || selectedBarangToko == null) {
                $('#err_message_list_barang').removeClass('d-none').addClass('d-block');
                let messageErr = `Maaf Kamu Belum Memilih Barang, Silahkan pilih barang`;
                $('#err_message_list_barang').html(messageErr);
            } else {
                $('#err_message_list_barang').removeClass('d-block').addClass('d-none');
                let messageSucc = `Barang Ditemukan`;
                $('#err_message_list_barang').html(messageSucc);

                let BASE_URL = "<?= base_url(); ?>"
                $.ajax({
                    type: "POST",
                    url: BASE_URL + 'barang/request_barang/getSelectedDetailBarang',
                    data: {
                        id_barang: selectedBarangToko
                    },
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);
                        if (response && response.err_code == 0) {


                            let barangData = {
                                id_barang: response.data_barang.id_barang,
                                nama_barang: response.data_barang.nama_barang,
                                qty_request: "",
                                ket: ""
                                // qty_request: $('#qty_request_input').val(),
                                // ket: $('#keterangan_input').val()
                            };
                            addSelectedBarangToTable(barangData);

                            // let existingData = $('#atribut_barang_input').val() || '[]';
                            // existingData = JSON.parse(existingData);

                            // existingData.push(barangData);

                            // let jsonData = JSON.stringify(existingData);

                            // $('#atribut_barang_input').val(jsonData);

                            $('#barang_penerima_toko_id option:selected').remove();
                        } else {
                            alert("Error fetching barang details or no data found.");
                        }
                    }
                });
            }
            // alert(selectedBarangToko);
        });

        let itemNumber = $('#selected-barang-table tbody tr').length + 1;

        function addSelectedBarangToTable(barangData) {

            let row = $('<tr>');

            // Add item number column
            row.append($('<td>').text(itemNumber++));

            row.append($('<td>').text(barangData.id_barang));
            row.append($('<td>').text(barangData.nama_barang));


            let qtyInput = $('<input>').attr('type', 'text').addClass('form-control').val(barangData.qty_request);
            let ketInput = $('<input>').attr('type', 'text').addClass('form-control').val(barangData.ket);

            row.append($('<td>').append(qtyInput));
            row.append($('<td>').append(ketInput));

            let tickBtn = $('<button>').addClass('btn btn-sm btn-success tick-btn').html('<i class="bx bxs-check-square" ></i> ');

            let cancelBtn = $('<button>').addClass('btn btn-sm btn-danger cancel-btn').html('<i class="bx bxs-x-square" ></i> ');

            cancelBtn.click(function() {
                let option = $('<option>').val(barangData.id_barang).text(barangData.nama_barang);
                $('#barang_penerima_toko_id').append(option);
                row.remove();

                updateItemNumbers();
            });

            tickBtn.click(function(ev) {
                // Prevent the default behavior
                ev.preventDefault();

                // console.log(barangData);

                // Assign the updated values from the input fields to barangData
                barangData.qty_request = qtyInput.val();
                barangData.ket = ketInput.val();

                if (barangData.qty_request == '' || barangData.ket == '') {
                    $('#validate_message_checkeditem').removeClass('d-none').addClass('d-block text-danger');
                    $('#validate_message_checkeditem').html("Quantity & Keterangan Harus Diisi");
                    return;
                }

                if (barangData.qty_request != '' && barangData.ket != '') {

                    // Set the input fields as read-only
                    qtyInput.prop('readonly', true);
                    ketInput.prop('readonly', true);



                    // Parse the existing data from the input field
                    let existingData = $('#atribut_barang_input').val() ? JSON.parse($('#atribut_barang_input').val()) : [];

                    // Find the index of the existing data with the same id_barang
                    let existingIndex = existingData.findIndex(item => item.id_barang === barangData.id_barang);

                    if (existingIndex !== -1) {
                        // If the id_barang already exists, update its qty, ket, and nama_barang
                        existingData[existingIndex].qty_request = barangData.qty_request;
                        existingData[existingIndex].ket = barangData.ket;
                    } else {
                        // If the id_barang does not exist, push it as a new item
                        existingData.push({
                            id_barang: barangData.id_barang,
                            nama_barang: barangData.nama_barang,
                            qty_request: barangData.qty_request,
                            ket: barangData.ket,
                        });
                    }

                    // Update the value of atribut_barang_input with the updated data array
                    $('#atribut_barang_input').val(JSON.stringify(existingData));

                    // Remove the tick button and cancel button
                    tickBtn.remove();
                    cancelBtn.remove();
                }

            });

            // Validate the qty input field
            qtyInput.on('blur', function() {
                let qtyValue = $(this).val();
                if (!qtyValue.trim() || isNaN(qtyValue)) {
                    // If qty is empty or not a number, show an error message
                    $(this).addClass('is-invalid');
                    $('#validate_message_checkeditem').removeClass('d-none').addClass('d-block text-danger');
                    $('#validate_message_checkeditem').html("Qty Harus Berupa Angka");
                } else {
                    // If qty is a number, remove any error message
                    $(this).removeClass('is-invalid');
                    $('#validate_message_checkeditem').removeClass('d-block').addClass('d-none text-danger');
                }
            });

            // Validate the ket input field
            ketInput.on('blur', function() {
                let ketValue = $(this).val();
                if (!ketValue.trim()) {
                    // If ket is empty, show an error message
                    $(this).addClass('is-invalid');
                } else {
                    // If ket is not empty, remove any error message
                    $(this).removeClass('is-invalid');
                }
            });

            row.append($('<td>').append(cancelBtn).append(tickBtn));
            $('#selected-barang-table tbody').append(row);
        }

        // TODO : Function to update item numbers
        function updateItemNumbers() {
            $('#selected-barang-table tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $(document).on('click', '.cancel-btn', function() {
            let row = $(this).closest('tr');
            let idBarang = row.find('td:first').text();
        });

        $('#btnSaveAndAddRequestNew').on('click', function() {

            // Check if any tick buttons have not been clicked
            let notClicked = $('.tick-btn').length > 0;

            if (notClicked) {
                // Show alert if any tick buttons have not been clicked
                // alert("Please click all tick buttons before saving.");
                $('#validate_message_checkeditem').removeClass('d-none').addClass('d-block text-danger');
                $('#validate_message_checkeditem').html("Silahkan Lakukan Check Item Untuk Melanjutkan Request");
                return;
            }
            $('#validate_message_checkeditem').removeClass('d-none text-danger').addClass('d-block text-success');
            $('#validate_message_checkeditem').html("Evertything Okey");

            let BASE_URL = "<?= base_url(); ?>"
            let kodeRequest = $('#kode_request').val();
            let requestTokoId = $('#request_toko_id').val();
            let penerimaTokoId = $('#penerima_toko_id').val();
            let pengirim = $('#pengirim').val();

            //TODO :  This will contain the JSON string of barangData array
            let atributBarang = $('#atribut_barang_input').val();

            let requestData = {
                kode_request: kodeRequest,
                request_toko_id: requestTokoId,
                penerima_toko_id: penerimaTokoId,
                pengirim: pengirim,
                atribut_barang: atributBarang
            };

            // Perform Ajax request to insert data into the database
            $.ajax({
                type: "POST",
                url: BASE_URL + 'barang/request_barang/saveAndAddRequest',
                data: requestData,
                dataType: "JSON",
                success: function(response) {
                    if (response.err_code == 0) {
                        // Swal.fire({
                        //     title: "Success!",
                        //     text: `${response.message}`,
                        //     icon: "success"
                        // });
                        alert(`${response.message}`);
                        let contentMessageSuccess = `<span>${response.message}</span>`
                        $('#show-message-when-invalid').removeClass('alert-danger').addClass('alert-success');
                        $('#show-message-when-invalid').removeClass('d-none').addClass('d-block');
                        $('#content-show-message-when-invalid').html(contentMessageSuccess);

                        $('#closeModalAddRequestBarang').trigger('click');
                        location.reload();
                    } else {
                        $('#show-message-when-invalid').removeClass('d-none').addClass('d-block');
                        let contentMessageErr = `<span>${response.message}</span>`
                        $('#content-show-message-when-invalid').html(contentMessageErr);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });


        $('#closeModalAddRequestBarang').on('click', function() {
            $('#showModalAddRequestBarang').modal('hide')
        });

        $('#closeModalAddRequestBarang2').on('click', function() {
            $('#showModalAddRequestBarang').modal('hide')
        });

        $('body').on('click', `.remove_request_barang_modal`, function() {
            let id_request = $(this).attr('data-id_request');
            Swal.fire({
                title: "Apakah Yakin Menghapus Request Barang?",
                text: "Data Yang hapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    let BASE_URL = "<?= base_url(); ?>"
                    $.ajax({
                        type: "POST",
                        url: BASE_URL + 'barang/request_barang/deleteRequest',
                        data: {
                            id_request: id_request
                        },
                        dataType: "JSON",
                        success: function(response) {
                            if (response.err_code == 0) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Request Barang Berhasil dihapus.",
                                    icon: "success"
                                });
                                location.reload();
                            } else {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Request Barang Gagal dihapus.",
                                    icon: "error"
                                });
                                location.reload();

                            }

                        }
                    });
                }
            });
        });
    </script>