<form autocomplete="off" class="row g-3 mt-2" action="<?= site_url() ?>pemesanan" method="POST" id="form">

    <?= csrf_field() ?>

    <div class="row mb-3">
        <label for="no_pemesanan" class="col-sm-3 col-form-label">Nomor Pemesanan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="no_pemesanan" name="no_pemesanan" value="<?= $nomor_pemesanan_auto ?>">
            <div class="invalid-feedback error-no_pemesanan"></div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>">
            <div class="invalid-feedback error-tanggal"></div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="id_supplier" class="col-sm-3 col-form-label">Supplier</label>
        <div class="col-sm-9">
            <select class="form-control" name="id_supplier" id="id_supplier">
                <option value="">Pilih Supplier</option>
                <?php foreach ($supplier as $up) : ?>
                    <option value="<?= $up['id'] ?>"><?= $up['nama'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback error-id_supplier"></div>
        </div>
    </div>

    <div class="text-center mb-3">
        <button id="tombolSimpan" class="btn px-5 btn-outline-primary" type="submit">Lanjutkan <i class="fa-fw fa-solid fa-arrow-right"></i></button>
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
                    if (err.error_id_supplier) {
                        $('.error-id_supplier').html(err.error_id_supplier);
                        $('#id_supplier').addClass('is-invalid');
                    } else {
                        $('.error-id_supplier').html('');
                        $('#id_supplier').removeClass('is-invalid');
                        $('#id_supplier').addClass('is-valid');
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
        $("#id_supplier").select2({
            theme: "bootstrap-5",
            dropdownParent: $('#my-modal')
        });

        $('#tanggal').datepicker({
            format: "yyyy-mm-dd"
        });
    })
</script>