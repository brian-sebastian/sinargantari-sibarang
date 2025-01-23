<script src="<?= base_url() ?>assets/be/vendor/libs/jquery/jquery.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/popper/popper.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/js/bootstrap.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/js/menu.js"></script>

<script src="<?= base_url() ?>assets/be/vendor/libs/imask/imask.js"></script>
<script>
    // const element = document.getElementsByClassName('arr-numberphone');
    const element = document.querySelector('.arr-numberphone');
    const maskOptions = {
        mask: '62800-0000-0000-0'
    };
    const mask = IMask(element, maskOptions);
</script>

<!-- DROPIFY -->
<script src="<?= base_url() ?>assets/be/vendor/libs/dropify/js/dropify.js"></script>
<script>
    $('.dropify').dropify({
        messages: {
            'default': 'Upload file here',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
</script>

<!-- ONSCAN JS -->
<script src="<?= base_url() ?>assets/be/vendor/libs/onscan_js/onscan.min.js"></script>

<!-- CKEDITOR -->
<script src="<?= base_url() ?>assets/be/vendor/libs/ckeditor/ckeditor.js"></script>

<!-- SUMMERNOTE -->
<script src="<?= base_url() ?>assets/be/vendor/libs/summernote/summernote.js"></script>

<!-- Vendors JS -->
<script src="<?= base_url() ?>assets/be/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- Main JS -->
<script src="<?= base_url() ?>assets/be/js/main.js"></script>

<!-- DATATABLES -->
<script src="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/jquery-3.5.1.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/datatables/bs5/responsive.bootstrap5.min.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/datatablesRowGroup/dataTables.rowGroup.min.js"></script>

<!-- SELECT2 -->

<script src="<?= base_url() ?>assets/be/vendor/libs/select2/js/select2.full.min.js"></script>

<!-- FLATPICKR -->
<script src="<?= base_url() ?>assets/be/vendor/libs/flatpickr/flatpickr.min.js"></script>

<!-- SWEETALERT2 -->
<script src="<?= base_url() ?>assets/be/vendor/libs/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/sweetalert2/sweetalert2.min.js"></script>

<!-- AUTO COMPLETE -->
<script src="<?= base_url() ?>assets/be/vendor/libs/autocomplete/new/autoComplete.min.js"></script>
<script src="<?= base_url() ?>assets/be/vendor/libs/jquery-ui/jquery-ui.js"></script>

<!-- EASY WEBCAM JS -->
<script src="<?= base_url() ?>assets/be/vendor/libs/webcam-easy/dist/webcam-easy.js"></script>

<!-- Page JS -->
<script src="<?= base_url() ?>assets/be/js/dashboards-analytics.js"></script>


<!-- Library Dynamic Datatables -->
<script src="<?= base_url() ?>assets/library/custom/dynamicDatatables/script.js"></script>


<script>
    CKEDITOR.replace('editor', {
        height: 300,
        filebrowserUploadUrl: '<?= base_url('barang/ck_editor_uploadimg') ?>',
        filebrowserUploadMethod: 'form'
    });
</script>

<script>
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }


    function formatCurrency(input, blur) {

        let input_val = input.val();

        let original_len = input_val.length;

        // initial caret position 
        let caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {


            let decimal_pos = input_val.indexOf(".");
            let left_side = input_val.substring(0, decimal_pos);

            left_side = formatNumber(left_side);

        } else {
            input_val = formatNumber(input_val);
            input_val = input_val;

        }

        input.val(input_val);
        let updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
</script>

<script>
    $(document).ready(function() {

        $('.datatables').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
            ],
            pageLength: 100,
        });
        $('.select2').select2();
        $('.select2-multiple').select2();
        $('.select2-tambah-modal').select2({
            dropdownParent: $('#tambah_modal'),
        })
        $('.select2-ubah-modal').select2({
            dropdownParent: $('#ubah_modal'),
        })
        $('.select2-cari-modal').select2({
            dropdownParent: $('#cari_modal'),
        })
        $('.select2-multiple-disabled').select2({
            disabled: true
        });
        $('#mySelect2Modal').select2({
            dropdownParent: $('#myModalSelect2')
        });
        $('.select22').select2({
            dropdownParent: $('#exampleModal')
        });

        $(".flatdatetimepickr").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        })

        $(".flatpickr").flatpickr({
            dateFormat: "Y-m-d",
        });

        $(".clockpickr").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i K"

        });

        $(".yearpicker").flatpickr({
            dateFormat: "Y"
        })

        $(".rangepicker").flatpickr({
            dateFormat: "Y-m-d",
            mode: "range"
        })

        let BASE_URL = "<?= base_url(); ?>";
        // $("#searchInputAutoComplete").autocomplete({
        //     source: function(request, response) {
        //         $.ajax({
        //             url: BASE_URL + 'test/search',
        //             dataType: 'json',
        //             data: {
        //                 term: request.term
        //             },
        //             success: function(data) {
        //                 console.log(data);
        //                 response($.map(data, function(item) {
        //                     return {
        //                         label: item.value,
        //                         value: item.value,
        //                         data: item.data
        //                     };
        //                 }));
        //             }
        //         });
        //     },
        //     minLength: 5 // Jumlah karakter minimum sebelum pencarian dimulai
        // });



        $("#searchInputAutoComplete").autocomplete({
            source: "<?php echo site_url('test/searchIssetMulti?'); ?>",
            minLength: 3, // set the minimum length to trigger autocomplete
            select: function(event, ui) {
                // handle the selected item, if needed
                console.log(ui.item.label);
                console.log(ui.item.value);
            }
        });
        $(".barcodeAutoCompleteScan").autocomplete({
            source: "<?php echo site_url('kasir/scan/searchBarcodeAutoComplete?'); ?>",
            minLength: 3, // set the minimum length to trigger autocomplete
            select: function(event, ui) {
                // handle the selected item, if needed
                console.log(ui.item.label);
                console.log(ui.item.value);
            }
        });

    });
</script>


<script>
    const flashData = $('.flash-data').data('flashdata');
    const flashGagal = $('.flash-data-gagal').data('flashdata');

    if (flashData) {
        Swal.fire({
            title: 'Berhasil',
            text: 'Data Berhasil ' + flashData,
            icon: 'success',
            timer: 1500,
        }, function() {
            location.reload();
        })
    }
    if (flashGagal) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: flashGagal,
            timer: 1500,
        }, function() {
            location.reload();
        })
    }
</script>



</body>

</html>

<!-- beautify ignore:end -->