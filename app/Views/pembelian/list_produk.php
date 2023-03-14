<?php
$no = 1;
foreach ($produk_pembelian as $pr) : ?>
    <tr style="vertical-align: middle;">
        <td><?= $no++ ?></td>
        <td><?= $pr['sku'] ?></td>
        <td><?= $pr['produk'] ?></td>

        <form action="<?= base_url() ?>update_produk_pembelian" method="post">
            <input type="hidden" name="id_produk" value="<?= $pr['id'] ?>">
            <input type="hidden" name="id_pmb" value="<?= $pembelian['id'] ?>">
            <td>
                <div class="input-group">
                    <span class="input-group-text py-1 px-2" id="basic-addon1">Rp. </span>
                    <input name="new_harga_satuan" type="text" class="form-control py-1 px-2" value="<?= number_format($pr['harga_satuan'], 0, ',', '.') ?>">
                </div>
            </td>
            <td>
                <input name="new_qty" type="number" class="form-control py-1 px-3" value="<?= $pr['qty'] ?>">
            </td>

            <td>Rp. <?= number_format($pr['total_harga'], 0, ',', '.') ?></td>
            <td class="text-center">

                <button title="Update" type="submit" class="px-2 py-0 btn btn-sm btn-outline-primary"><i class="fa-fw fa-solid fa-check"></i></button>
        </form>

        <form id="form_delete" method="POST" class="d-inline">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id_pembelian" value="<?= $pr['id_pembelian'] ?>">
        </form>
        <button onclick="confirm_delete(<?= $pr['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-xmark"></i></button>

        </td>
    </tr>
<?php endforeach; ?>
<tr class="fs-5">
    <td colspan="5" class="text-end fw-bold pe-4 py-2">Grand Total</td>
    <td colspan="2" class="py-2">Rp. <?= number_format($pembelian['total_harga_produk'], 0, ',', '.')  ?></td>
</tr>

<script>
    function confirm_delete(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Apakah yakin menghapus produk ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_delete').attr('action', '<?= site_url() ?>pembelian_detail/' + id);
                $('#form_delete').submit();
            }
        })
    }
</script>