<?php
$no = 1;
foreach ($stokProduk as $sp) : ?>
    <tr style="vertical-align: middle;">
        <td class="text-center"><?= $no++ ?></td>
        <td><?= $sp['produk'] ?></td>
        <td class="text-center">
            <?= $sp['jumlah_virtual'] ?>
            <input type="hidden" id="jumlah_virtual_<?= $sp['id'] ?>" value="<?= $sp['jumlah_virtual'] ?>">
        </td>
        <input id="id_stok_<?= $sp['id'] ?>" type="hidden" value="<?= $sp['id'] ?>">
        <td class="text-center">
            <div hidden class="input-group" id="input_new_jumlahfisik_<?= $sp['id'] ?>">
                <input id="new_jumlah_fisik_<?= $sp['id'] ?>" type="number" min="1" class="form-control py-1 px-2" value="<?= $sp['jumlah_fisik'] ?>">
            </div>
            <div id="text_jumlah_fisik_<?= $sp['id'] ?>"><?= $sp['jumlah_fisik'] ?></div>
        </td>
        <td class="text-center"><?= $sp['selisih'] ?></td>
        <td class="text-center">

            <button hidden id="tombol_update_produk_<?= $sp['id'] ?>" onclick="update(<?= $sp['id'] ?>)" title="Update" type="button" class="px-2 py-0 btn btn-sm btn-outline-success"><i class="fa-fw fa-solid fa-check"></i></button>
            <button id="tombol_edit_produk_<?= $sp['id'] ?>" onclick="edit(<?= $sp['id'] ?>)" title="Edit" type="button" class="px-2 py-0 btn btn-sm btn-outline-primary"><i class="fa-fw fa-solid fa-pencil"></i></button>

            <form id="form_delete" method="POST" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="id_stockOpname" value="<?= $sp['id_stock_opname'] ?>">
            </form>
            <button onclick="confirm_delete(<?= $sp['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-xmark"></i></button>

        </td>
    </tr>
<?php endforeach; ?>

<script>
    $(document).ready(function() {
        // Alert
        var op = <?= (!empty(session()->getFlashdata('pesan')) ? json_encode(session()->getFlashdata('pesan')) : '""'); ?>;
        if (op != '') {
            Toast.fire({
                icon: 'success',
                title: op
            })
        }
    });
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
                $('#form_delete').attr('action', '<?= site_url() ?>stok_produk_delete/' + id);
                $('#form_delete').submit();
            }
        })
    }

    function edit(id) {
        $('#input_new_jumlahfisik_' + id).attr('hidden', false);
        $('#tombol_update_produk_' + id).attr('hidden', false);
        $('#text_jumlah_fisik_' + id).attr('hidden', true);
        $('#tombol_edit_produk_' + id).attr('hidden', true);
    }

    function update(id) {
        var id_stok          = <?= $stok['id'] ?>
        
        $.ajax({
            url: "<?= base_url() ?>stok_produk_update/" + id,
            type: 'post',
            data: 'id_stok=' + id_stok +
                  '&new_jumlah_fisik=' + $('#new_jumlah_fisik_' + id).val() +
                  '&jumlah_virtual=' + $('#jumlah_virtual_' + id).val(),
            dataType: 'json',
            success: function(response) {
                
                if (response) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil update list produk stock opname'
                    })
                    load_list();
                    $('#input_new_jumlahfisik_' + id).attr('hidden', true);
                    $('#tombol_update_produk_' + id).attr('hidden', true);
                    $('#text_jumlah_fisik_' + id).attr('hidden', false);
                    $('#tombol_edit_produk' + id).attr('hidden', false);
                } else {
                    alert('terjadi error update list produk')
                    console.log(response)
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert('Error: ' + errorThrown);
            }
        });
    }
</script>