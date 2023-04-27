<form autocomplete="off" class="row g-3 mt-3" action="<?= site_url() ?>stockopname/" method="POST" id="form">

    <?= csrf_field() ?>

    <div class="row mb-3">
        <label for="nama" class="col-sm-2 col-form-label">Nomor</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nomor" name="nomor" value="<?= $nomor_stok_auto ?>">
            <div class="invalid-feedback error_nomor"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="nama" class="col-sm-2 col-form-label">Tanggal</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>">
            <div class="invalid-feedback error_tanggal"></div>
        </div>
    </div>

    <div class="text-center">
        <button id="#tombolSimpan" class="btn px-5 btn-outline-primary" type="submit">Lanjutkan<i class="fa-fw fa-solid fa-check"></i></button>
    </div>
</form>


<script>
    $(document).ready(function() {
        $('#tanggal').datepicker({
            format: "yyyy-mm-dd"
        });
    })


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

                    if (err.error_nomor) {
                        $('.error_nomor').html(err.error_nomor);
                        $('#nomor').addClass('is-invalid');
                    } else {
                        $('.error_nomor').html('');
                        $('#nomor').removeClass('is-invalid');
                        $('#nomor').addClass('is-valid');
                    }
                    if (err.error_tanggal) {
                        $('.error_tanggal').html(err.error_tanggal);
                        $('#tanggal').addClass('is-invalid');
                    } else {
                        $('.error_tanggal').html('');
                        $('#tanggal').removeClass('is-invalid');
                        $('#tanggal').addClass('is-valid');
                    }
                }
                if (response.success) {
                    window.location.replace('<?= base_url() ?>/list_stok/' + response.idStok);
                }
                if (response.coba) {
                    alert(coba);
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
        return false
    })
</script>