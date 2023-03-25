<div class="row mb-2">
    <div class="col-md-3">
        <div class="fw-bold">Tanggal</div>
    </div>
    <div class="col-md-9">
        <?= $pemesanan['tanggal'] ?>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-3">
        <div class="fw-bold">Supplier</div>
    </div>
    <div class="col-md-9">
        <?= $pemesanan['supplier'] ?>
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

<hr>

<form autocomplete="off" class="row g-3 mt-2" action="<?= site_url() ?>save_repeat_pemesanan" method="POST" id="form">

    <?= csrf_field() ?>
    <div class="row mb-3 mt-4">
        <label for="no_pemesanan" class="col-sm-3 col-form-label">Nomor Pemesanan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="no_pemesanan" name="no_pemesanan" value="<?= $nomor_pemesanan_auto ?>">
            <div class="invalid-feedback error-no_pemesanan"></div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
        <div class="col-sm-9">
            <input onchange="ganti_no_pemesanan()" type="text" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>">
            <div class="invalid-feedback error-tanggal"></div>
        </div>
    </div>
    <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id'] ?>">

    <div class="text-center mb-3">
        <button id="tombolSimpan" class="btn px-5 btn-outline-primary" type="submit">Buat Pemesanan <i class="fa-fw fa-solid fa-arrow-right"></i></button>
    </div>

</form>


<script>
    $('#form').submit(function(e) {
        e.preventDefault();

        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#tombolSimpan').html('Tunggu <i class="fa-solid fa-spin fa-spinner"></i>');
                $('#tombolSimpan').prop('disabled', true);
            },
            complete: function() {
                $('#tombolSimpan').html('Simpan <i class="fa-fw fa-solid fa-check"></i>');
                $('#tombolSimpan').prop('disabled', false);
            },
            success: function(response) {
                if (response.error) {
                    let err = response.error;

                    if (err.error_no_pemesanan) {
                        $('.error-no_pemesanan').html(err.error_no_pemesanan);
                        $('#no_pemesanan').addClass('is-invalid');
                    } else {
                        $('.error-no_pemesanan').html('');
                        $('#no_pemesanan').removeClass('is-invalid');
                        $('#no_pemesanan').addClass('is-valid');
                    }
                    if (err.error_tanggal) {
                        $('.error-tanggal').html(err.error_tanggal);
                        $('#tanggal').addClass('is-invalid');
                    } else {
                        $('.error-tanggal').html('');
                        $('#tanggal').removeClass('is-invalid');
                        $('#tanggal').addClass('is-valid');
                    }
                }
                if (response.success) {
                    window.location.replace('<?= base_url() ?>/list_pemesanan/' + response.no_pemesanan);
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
        return false
    })


    $(document).ready(function() {
        $('#tanggal').datepicker({
            format: "yyyy-mm-dd"
        });
    })


    function ganti_no_pemesanan() {
        let tanggal = $('#tanggal').val()
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/ganti_no_pemesanan",
            data: 'tanggal=' + tanggal,
            dataType: "json",
            success: function(response) {
                if (response.no_pemesanan) {
                    $('#no_pemesanan').val(response.no_pemesanan)
                } else {
                    Swal.fire(
                        'Opss.',
                        'Terjadi kesalahan, hubungi IT Support',
                        'error'
                    )
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
    }
</script>