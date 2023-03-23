<?php if ($produk) { ?>
    <?php
    $no = 1;
    foreach ($produk as $pr) : ?>
        <tr>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center"><?= $no++ ?></td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')"><?= $pr['nama'] ?></td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center">-</td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center">-</td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center">-</td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center">-</td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center">-</td>
            <td onclick="addProduk(<?= $pr['id'] ?>, '<?= $pr['nama'] ?>')" class="text-center"><?= $pr['stok'] ?></td>
        </tr>
    <?php endforeach; ?>
<?php } else { ?>
    <tr>
        <td colspan="8" class="text-center">Tidak ada nama produk atau SKU dari keyword pencaraian.</td>
    </tr>
<?php } ?>

<script>
    function addProduk(id_produk, produk) {
        $('#produk').val(produk);
        $('#id_produk').val(id_produk);

        $('#my-modal').modal('hide')
    }
</script>