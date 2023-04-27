<div class="row mb-2">
    <div class="col-md-3">
        <div class="fw-bold">No Stock Opname</div>
    </div>
    <div class="col-md-9">
        <?= $stockopname['nomor'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-3">
        <div class="fw-bold">Tanggal</div>
    </div>
    <div class="col-md-9">
        <?= $stockopname['tanggal'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-3">
        <div class="fw-bold">Gudang</div>
    </div>
    <div class="col-md-9">
        <?= $stockopname['gudang'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-3">
        <div class="fw-bold">Status</div>
    </div>
    <div class="col-md-9">
        <?= $stockopname['status'] ?>
    </div>
</div>

<br>


<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered" width="100%" id="tabelDetail">
        <thead style="background-color: #F6DCA9;" class="text-center border-secondary">
            <tr>
                <th class="text-center" width="5%">#</th>
                <th class="text-center" width="25%">Produk</th>
                <th class="text-center" width="15%">Jumlah Virtual</th>
                <th class="text-center" width="15%">Jumlah Fisik</th>
                <th class="text-center" width="15%">Selisih</th>
            </tr>
        </thead>
        <tbody id="tabel_list_produk">
            <?php
            $no = 1;
            foreach ($stockopnamedetail as $sod) : ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $sod['produk'] ?></td>
                    <td class="text-center"><?= $sod['jumlah_virtual'] ?></td>
                    <td class="text-center"><?= $sod['jumlah_fisik'] ?></td>
                    <td class="text-center"><?= $sod['selisih'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('MyLayout/js') ?>
<script>
    $(document).ready(function() {
        $('#tabelDetail').DataTable();
    })
</script>