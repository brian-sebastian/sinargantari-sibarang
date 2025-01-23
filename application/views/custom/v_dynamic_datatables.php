<table id="dynamicTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Kode transaksi</th>
            <th>Terbayar</th>
            <th>Kembalian</th>
            <th>Tagihan</th>
            <th>Total diskon</th>
            <th>Tagihan setelah diskon</th>
            <th>Total biaya kirim</th>
            <th>Total tagihan</th>
            <th>Tipe transaksi</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Kode transaksi</th>
            <th>Terbayar</th>
            <th>Kembalian</th>
            <th>Tagihan</th>
            <th>Total diskon</th>
            <th>Tagihan setelah diskon</th>
            <th>Total biaya kirim</th>
            <th>Total tagihan</th>
            <th>Tipe transaksi</th>
        </tr>
    </tfoot>
</table>

<script>
    $(function() {
        initializeTable("server-side", {
            "ajax": {
                url: "<?= site_url('custom/ajax_datatables') ?>",
                type: "post",
                dataType: "json"
            }
        })

    })
</script>