<div class="row">

    <div class="col-md-6">

        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">No Pembelian</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['no_pembelian'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Tanggal</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['tanggal'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Supplier</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['supplier'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Origin</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['origin'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Admin</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['admin'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Status</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['status'] ?>
            </div>
        </div>

    </div>


    <div class="col-md-6">

        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Gudang</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['gudang'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Tanggal Datang</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['tanggal_datang'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Dimensi (PxLxT)</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['panjang'] ?> x <?= $pembelian['lebar'] ?> x <?= $pembelian['tinggi'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Berat</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['berat'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Carton/Koli</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['carton_koli'] ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="fw-bold">Catatan</div>
            </div>
            <div class="col-md-8">
                <?= $pembelian['catatan'] ?>
            </div>
        </div>

    </div>

</div>


<br>


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
            foreach ($pembelian_detail as $pr) : ?>
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
                <td colspan="5" class="text-end fw-bold pe-4 py-2">Grand Total</td>
                <td class="py-2">Rp. <?= number_format($pembelian['total_harga_produk'], 0, ',', '.')  ?></td>
            </tr>
        </tbody>
    </table>
</div>