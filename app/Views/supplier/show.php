<div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Origin</div>
        </div>
        <div class="col-md-9">
            <?= $supplier['origin'] ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Nama Supplier</div>
        </div>
        <div class="col-md-9">
            <?= $supplier['nama'] ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Pemilik</div>
        </div>
        <div class="col-md-9">
            <?= $supplier['pemilik'] ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Telepon</div>
        </div>
        <div class="col-md-9">
            <?= $supplier['no_telp'] ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Alamat</div>
        </div>
        <div class="col-md-9">
            <?php foreach ($alamat as $al) : ?>
                <div class="mb-2">
                    <p class="mb-0"><?= $al['nama'] ?></p>
                    <p class="mb-0"><?= $al['detail_alamat'] ?>, <?= $al['kelurahan'] ?>, <?= $al['kecamatan'] ?>, <?= $al['kota'] ?>, <?= $al['provinsi'] ?></p>
                    <p class="mb-0">PIC : <?= $al['pic'] ?></p>
                    <p class="mb-0">Telp : <?= $al['no_telp'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Link</div>
        </div>
        <div class="col-md-9">
            <?php foreach ($link as $li) : ?>
                <p class="mb-2"><?= $li['nama'] ?> : <?= $li['link'] ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Customer Service</div>
        </div>
        <div class="col-md-9">
            <?php foreach ($customer_service as $cs) : ?>
                <p class="mb-2"><?= $cs['nama'] ?> : <?= $cs['no_telp'] ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Saldo</div>
        </div>
        <div class="col-md-9">
            Rp. <?= number_format($supplier['saldo'], 0, ',', '.'); ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Status</div>
        </div>
        <div class="col-md-9">
            <?= $supplier['status'] ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="fw-bold">Note</div>
        </div>
        <div class="col-md-9">
            <?= $supplier['note'] ?>
        </div>
    </div>

    <hr class="my-4">

    <?php foreach ($pj as $pj) : ?>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="fw-bold">Admin <?= $pj['urutan'] ?></div>
            </div>
            <div class="col-md-9">
                <?= $pj['nama_pj'] ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>