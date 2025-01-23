<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Halaman <?= $title ?></title>
    <meta name="description" content="Sistem Informasi Barang | POS">
    <meta name="keywords" content="Point Of Sale, Marketplace">
    <meta name="author" content="SIBARA">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url() ?>assets/be/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Helpers -->
    <script src="<?= base_url() ?>assets/be/vendor/js/helpers.js"></script>
    <script src="<?= base_url() ?>assets/be/js/config.js"></script>

    <!-- SELECT2 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/select2/css/select2.min.css" />

    <!-- DROPIFY -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/dropify/css/dropify.css" />

    <!-- SUMMERNOTE -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/summernote/summernote.css" />

    <!-- FLATPICKR -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/flatpickr/flatpickr.min.css" />

    <!-- SWEET ALERT 2 -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/sweetalert2/sweetalert2.min.css" />

    <!-- DATATABLES -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/responsive.bootstrap5.min.css" />

    <!-- AUTO COMPLETE -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/autocomplete/new/autoComplete.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/be/vendor/libs/jquery-ui/jquery-ui.css" />

    <script src="<?= base_url() ?>assets/be/vendor/libs/jquery/jquery.js"></script>
    <!-- Jquery -->
    <script src="<?= base_url() ?>assets/library/jquery/jquery-3.7.1.min.js"></script>
    <script src="<?= base_url() ?>assets/be/vendor/libs/jquery/jquery-3.6.1.js"></script>


    <!-- PUSHER -->
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>

    <!-- ENCRYPT -->
    <script src="https://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>

    <!-- HIGHCART -->
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
        // Pusher.logToConsole = true;
        Pusher.logToConsole = false;
        let pusher = new Pusher("<?= $_ENV['PUSHER_KEY'] ?>", {
            cluster: "<?= $_ENV['PUSHER_CLUSTER'] ?>",
        });

        let channel = pusher.subscribe("my-channel");

        channel.bind("my-event", (data) => {
            // Method to be dispatched on trigger.
            // console.log(data);

            if (data.message == 'success') {
                let getTextCount = $('#count_message').text();
                let countingMessage = parseInt(getTextCount) + 1;
                let content = `<div class="alert alert-primary" role="alert">
                    ${data.message}
                    </div>`;
                let msgNotifContent = `<li><a class="dropdown-item">Transaksi Baru dengan kode ${countingMessage}</a></li>`
                $('#msg_pusher').html(content);
                $('#msg_push').append(msgNotifContent);
                $('#count_message').text(countingMessage);

            }
        });


        let channel2 = pusher.subscribe("dashboard");

        channel2.bind("test-event", (data) => {

            if (data.status == 0) {
                let getTextCount = $('#count_message').text();
                let countingMessage = parseInt(getTextCount) + 1;
                let content = `<div class="alert alert-primary" role="alert">
                    ${data.message}
                    </div>`;
                let msgNotifContent = `<li><a class="dropdown-item">${data.message} ${countingMessage}</a></li>`
                $('#msg_pusher').html(content);
                $('#msg_push').append(msgNotifContent);
                $('#count_message').text(countingMessage);
            }

        });
    </script>

    <script>
        Pusher.logToConsole = false;
        let pusherTransaction = new Pusher("<?= $_ENV['PUSHER_KEY'] ?>", {
            cluster: "<?= $_ENV['PUSHER_CLUSTER'] ?>",
        });

        let transaksi_channel = pusherTransaction.subscribe("transaksi_channel");

        transaksi_channel.bind("transaksi_event", (data) => {
            // Method to be dispatched on trigger.
            // console.log(data);

            if (data.message == 'success') {
                let getTextCount = $('#count_message').text();
                // let countingMessage = parseInt(getTextCount) + 1;
                let countingMessage = parseInt(getTextCount) + data.total_row;
                let msgNotifContent = `
                                <li>
                                <a class="dropdown-item" href="${data.url}">
                                    Transaksi Baru Marketplace
                                    <b>
                                        ${data.transaction_code}
                                    </b>
                                </a>
                            </li>
                                `
                $('#msg_push').append(msgNotifContent);
                $('#count_message').text(countingMessage);
            }

        });
    </script>


</head>

<body>