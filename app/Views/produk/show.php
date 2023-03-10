    <div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Nama Produk
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['nama'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Kategori
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['kategori'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; SKU
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['sku'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; HS Code
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['hs_code'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Jenis
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['jenis'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Tipe
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['tipe'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Harga Jual
                </div>
            </div>
            <div class="col-md-9">
                Rp. <?= number_format($produk['harga_jual'], 0, ',', '.') ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Harga Beli
                </div>
            </div>
            <div class="col-md-9">
                Rp. <?= number_format($produk['harga_beli'], 0, ',', '.') ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Berat
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['berat'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Ukuran
                </div>
            </div>
            <div class="col-md-9">
                P : <?= $produk['panjang'] ?> &nbsp;&nbsp; L : <?= $produk['lebar'] ?> &nbsp;&nbsp; T : <?= $produk['tinggi'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Minimal Penjulan
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['minimal_penjualan'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Kelipatan Penjualan
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['kelipatan_penjualan'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Marketing
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['status_marketing'] ?>
            </div>
        </div>

        <hr class="mt-4">

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Gudang
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['gudang'] ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Stok di Gudang
                </div>
            </div>
            <div class="col-md-9">
                <?= $produk['stok'] ?> <?= $produk['satuan'] ?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Stok Virtual
                </div>
            </div>
            <div class="col-md-9">
                <?= ($tipe == 'SET' || $tipe == 'SINGLE') ? $bisa_membuat : $bisa_dipecah ?> <?= $produk['satuan'] ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">
                    &nbsp;&nbsp; Stok Total
                </div>
            </div>
            <div class="col-md-9">
                <?= ($tipe == 'SET' || $tipe == 'SINGLE') ? ($bisa_membuat + $produk['stok']) : ($bisa_dipecah + $produk['stok']) ?> <?= $produk['satuan'] ?>
            </div>
        </div>
    </div>

    <div class="table-responsive">

        <?php if ($tipe == 'SET' || $tipe == 'SINGLE') { ?>

            <h5 class="mb-3 mt-2">List Produk Komponen</h5>

            <?php if ($result == 'ok') { ?>

                <table class="table table-bordered table-striped table-secondary">
                    <thead>
                        <tr class="text-center">
                            <th width="10%" width="10%">No</th>
                            <th width="30%">Produk</th>
                            <th width="20%">Stok</th>
                            <th width="20%">Butuh</th>
                            <th width="20%">Bisa membuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($virtual_stok as $vs) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $vs['nama_produk'] ?></td>
                                <td class="text-center"><?= $vs['stok_bahan'] ?></td>
                                <td class="text-center"><?= $vs['qty_bahan'] ?></td>
                                <td class="text-center"><?= $vs['bisa_membuat'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>

                <h2 class="text-center mt-2"><?= $result ?></h2>

            <?php } ?>

        <?php } else { ?>

            <h5 class="mb-3 mt-2">List Produk Set</h5>

            <?php if ($result == 'ok') { ?>

                <table class="table table-bordered table-striped table-secondary">
                    <thead>
                        <tr class="text-center">
                            <th width="10%" width="10%">No</th>
                            <th width="30%">Produk</th>
                            <th width="20%">Stok</th>
                            <th width="20%">Pecahan</th>
                            <th width="20%">Bisa dipecah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($virtual_stok as $vs) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $vs['nama_produk'] ?></td>
                                <td class="text-center"><?= $vs['stok_jadi'] ?></td>
                                <td class="text-center"><?= $vs['qty_bahan'] ?></td>
                                <td class="text-center"><?= $vs['bisa_dipecah'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else { ?>

                <h2 class="text-center mt-2"><?= $result ?></h2>

            <?php } ?>

        <?php } ?>

    </div>