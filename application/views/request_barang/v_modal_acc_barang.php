<div class="container" style="overflow: scroll;">
    <form id="formAcc">
        <?php if ($data["atribut_barang"] != "" && $data["atribut_barang"] != "[]") : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama barang</th>
                        <th>Jumlah minta</th>
                        <th>Jumlah stok tersedia</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <?php $listItem                     = array_map(function ($elemen) use ($data) {

                    $elemen["stok_toko"]            = getStokByIdBarangDanTokoId($elemen["id_barang"], $data["penerima_toko_id"]);
                    $elemen["id_harga_penerima"]    = getIdHargaByIdBarangDanTokoId($elemen["id_barang"], $data["penerima_toko_id"]);
                    $elemen["id_harga_request"]     = getIdHargaByIdBarangDanTokoId($elemen["id_barang"], $data["request_toko_id"]);

                    return $elemen;
                }, json_decode($data["atribut_barang"], TRUE)); ?>
                <?php $no = 1;
                foreach ($listItem as $l) : ?>
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $l["nama_barang"] ?></td>
                        <td><input name="qty_request[]" class="form-control qtyReq" type="number" value="<?= $l["qty_request"] ?>"></td>
                        <td>
                            <input class="form-control qtyStok" type="number" id="qtyStok<?= $l["id_barang"] ?>" value="<?= $l["stok_toko"] ?>" readonly>
                            <small class="text-danger err_stok" id="err_stok<?= $l["id_barang"] ?>"></small>
                        </td>
                        <td>
                            <?php if (array_key_exists("ket", $l)) : ?>
                                <?= $l["ket"] ?>
                            <?php else : ?>
                                -
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($data["status"] == "draft") : ?>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button class="btn btn-sm btn-danger doCancel">Cancel</button>
                                </div>
                            <?php else : ?>
                                Lock
                            <?php endif ?>
                        </td>
                        <input type="hidden" name="id_barang[]" class="form-contol" value="<?= $l["id_barang"] ?>">
                        <input type="hidden" name="nama_barang[]" class="form-control" value="<?= $l["nama_barang"] ?>">
                        <input type="hidden" name="ket[]" class="form-control" value="<?php if (array_key_exists("ket", $l)) {
                                                                                            echo $l["ket"];
                                                                                        } else {
                                                                                            echo "";
                                                                                        } ?>">
                        <input type="hidden" name="id_harga_penerima[]" class="form-control" value="<?= $l["id_harga_penerima"] ?>">
                        <input type="hidden" name="id_harga_request[]" class="form-control" value="<?= $l["id_harga_request"] ?>">
                    </tr>
                <?php $no++;
                endforeach ?>
            </table>
        <?php else : ?>
            <h4 class="text-dark">Data kosong.</h4>
        <?php endif ?>
        <input type="hidden" class="form-control" name="penerima_toko_id" value="<?= $data["penerima_toko_id"] ?>">
        <input type="hidden" class="form-control" name="request_toko_id" value="<?= $data["request_toko_id"] ?>">
        <input type="hidden" class="form-control" name="id_request" value="<?= $data["id_request"] ?>">
    </form>
</div>