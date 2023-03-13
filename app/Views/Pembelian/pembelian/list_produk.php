<?php
$no = 1;
foreach ($produk_pemesanan as $pr) : ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $pr['sku'] ?></td>
        <td><?= $pr['produk'] ?></td>
        <td>Rp. <?= number_format($pr['harga_satuan'], 0, ',', '.') ?></td>
        <td><?= $pr['qty'] ?></td>
        <td>Rp. <?= number_format($pr['total_harga'], 0, ',', '.') ?></td>
        <td class="text-center">

            <form id="form_delete" method="POST" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="id_pemesanan" value="<?= $pr['id_pemesanan'] ?>">
            </form>
            <button onclick="confirm_delete(<?= $pr['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-xmark"></i></button>

        </td>
    </tr>
<?php endforeach; ?>

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
                $('#form_delete').attr('action', '<?= site_url() ?>pemesanan_detail/' + id);
                $('#form_delete').submit();
            }
        })
    }
</script>