<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Np</th>
                    <th scope="col">Nama barang</th>
                    <th scope="col">Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data as $d) : ?>
                    <tr>
                        <th scope="row"><?= $no ?></th>
                        <td><?= $d["nama_barang"] ?></td>
                        <td><?= $d["qty"] ?></td>
                    </tr>
                <?php $no++;
                endforeach ?>
            </tbody>
        </table>
    </div>
</div>