<div class="col-md-4">
    <div class="input-group mt-2">
        <span class="me-4 mt-1 fs-5"> Cari Produk Lain</span>
        <input autocomplete="off" type="text" class="form-control" placeholder="Nama Produk atau SKU" id="input_produk_lain">
        <button class="btn btn-secondary px-2" type="button" id="cari_produk"><i class="fa-fw fa-solid fa-search"></i></button>
    </div>
</div>

<br>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" width="100%" id="tabel">
        <thead class="text-light" style="background-color: #3A98B9;">
            <tr>
                <th class="text-center" width="5%">#</th>
                <th class="text-center" width="34%">Produk</th>
                <th class="text-center" width="10%">Ratio</th>
                <th class="text-center" width="10%">AVGSL</th>
                <th class="text-center" width="10%">AVGBY</th>
                <th class="text-center" width="10%">Rate</th>
                <th class="text-center" width="11%">Last Buy</th>
                <th class="text-center" width="10%">Stok</th>
            </tr>
        </thead>
        <tbody id="list_produk_pencarian">
            <?php if ($produk_supplier) { ?>
                <?php
                $no = 1;
                foreach ($produk_supplier as $pr) : ?>
                    <tr>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center"><?= $no++ ?></td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')"><?= $pr['produk'] ?></td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center">-</td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center">-</td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center">-</td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center">-</td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center">-</td>
                        <td style="cursor: pointer;" onclick="addProdukSupplier(<?= $pr['id_produk'] ?>, '<?= $pr['produk'] ?>')" class="text-center"><?= $pr['stok'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php } else { ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada produk yang dipesan di supplier yang dipilih.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function addProdukSupplier(id_produk, produk) {
        $('#produk').val(produk);
        $('#id_produk').val(id_produk);

        $('#my-modal').modal('hide')
    }

    $('#cari_produk').click(function() {
        let input_produk_lain = $('#input_produk_lain').val();
        $.ajax({
            type: 'POST',
            url: '<?= site_url() ?>find_produk_by_nama_sku',
            data: 'input_produk_lain=' + input_produk_lain,
            dataType: 'json',
            success: function(res) {
                if (res.data) {
                    $('#list_produk_pencarian').html(res.data)
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        })
    })
</script>