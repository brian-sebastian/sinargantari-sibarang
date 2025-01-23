<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak barcode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">




</head>

<body>
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            font: 12pt "Arial";
        }

        .page {
            width: auto;
            /* height: 210mm; */

            /* A4 */
            /* width: 297mm;
            height: 210mm; */


            /* F4 */
            /* width: 330mm;
            height: 210mm; */


            /* padding: 10mm; */


            margin: 10mm auto;

            /* INI NIH BIKIN HALAMAN KEDUA KOSONG JIKA DATA HABIS */
            /* page-break-after: always; */



            /* width: 297mm;
            height: 210mm;
            padding: 20mm; */

            /* border: 1px #D3D3D3 solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); */
        }

        .subpage {
            padding: 0;
            width: 100%;
            height: auto;
            /* width: 100%;
            height: 100%; */

            margin: 20px;
            box-sizing: border-box;
        }

        @media print {
            @page {
                /* size: landscape; */
                size: portrait;
                margin: 10mm;
            }

            html,
            body {
                /* width: 330mm;
                height: 210mm; */
                margin-bottom: 10px;
                width: 297mm;
                height: 210mm;
                font: 10px "Arial";
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                height: initial;
                box-shadow: initial;
                background: initial;

                /* INI NIH BIKIN HALAMAN KEDUA KOSONG JIKA DATA HABIS */
                /* page-break-after: always; */
            }

            .header-row {
                font-size: 10px;
                font-weight: bold;
            }


            .table-responsive {
                overflow: hidden !important;
            }


        }

        .header-row {
            font-size: 12px;
            font-weight: bold;
        }
    </style>

    <ul>
        <?php foreach ($data as $d) : ?>
            <li style="display: inline-block;" class="px-2 py-2">
                <div>
                    <img src="https://quickchart.io/barcode?type=code128&text=<?= $d["barcode_barang"] ?>&width=250&height=80&includeText=true" alt="https://quickchart.io/barcode?type=code128&text=<?= $d["barcode_barang"] ?>&width=250&height=80&includeText=true" style="margin: auto; display: block;">
                    <br>
                    <p class="text-center fw-bold"><?= $d["nama_barang"] ?></p>
                </div>
                <hr>
            </li>
            <!-- <div class="row w-100">
                <div class="col-3 mb-2">
                    <img src="https://quickchart.io/barcode?type=code128&text=<?= $d["barcode_barang"] ?>&width=250&height=80&includeText=true" alt="https://quickchart.io/barcode?type=code128&text=<?= $d["barcode_barang"] ?>&width=250&height=80&includeText=true">
                </div>
            </div> -->
        <?php endforeach ?>
    </ul>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>