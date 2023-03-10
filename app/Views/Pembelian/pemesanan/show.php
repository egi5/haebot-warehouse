<div class="row mb-2">
    <div class="col-md-2">
        <div class="fw-bold">No Pemesanan</div>
    </div>
    <div class="col-md-10">
        <?= $pemesanan['no_pemesanan'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-2">
        <div class="fw-bold">Tanggal</div>
    </div>
    <div class="col-md-10">
        <?= $pemesanan['tanggal'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-2">
        <div class="fw-bold">Supplier</div>
    </div>
    <div class="col-md-10">
        <?= $pemesanan['supplier'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-2">
        <div class="fw-bold">Admin</div>
    </div>
    <div class="col-md-10">
        <?= $pemesanan['admin'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-2">
        <div class="fw-bold">Status</div>
    </div>
    <div class="col-md-10">
        <?= $pemesanan['status'] ?>
    </div>
</div>

<hr>


<div class="table-responsive">
    <table class="table table-sm table-bordered" width="100%" id="tabel">
        <thead>
            <tr>
                <th class="text-center" width="5%">#</th>
                <th class="text-center" width="15%">SKU</th>
                <th class="text-center" width="35%">Produk</th>
                <th class="text-center" width="20%">Satuan</th>
                <th class="text-center" width="5%">Qty</th>
                <th class="text-center" width="20%">Total</th>
            </tr>
        </thead>
        <tbody id="tabel_list_produk">
            <?php
            $no = 1;
            foreach ($pemesanan_detail as $pr) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $pr['sku'] ?></td>
                    <td><?= $pr['produk'] ?></td>
                    <td>Rp. <?= number_format($pr['harga_satuan'], 0, ',', '.') ?></td>
                    <td><?= $pr['qty'] ?></td>
                    <td>Rp. <?= number_format($pr['total_harga'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="fs-5">
                <td colspan="5" class="text-end fw-bold pe-4 py-2">Perkiraan Biaya</td>
                <td class="py-2">Rp. <?= number_format($pemesanan['total_harga_produk'], 0, ',', '.')  ?></td>
            </tr>
        </tbody>
    </table>
</div>