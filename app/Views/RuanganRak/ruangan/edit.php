<form autocomplete="off" class="row g-3 mt-3" action="<?= site_url() ?>ruangan/<?= $ruangan['id'] ?>" method="POST" id="form">

    <?= csrf_field() ?>

    <input type="hidden" name="_method" value="PUT">

    <div class="row mb-3">
        <label for="nama" class="col-sm-3 col-form-label">Nama Ruangan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nama" name="nama" value="<?= $ruangan['nama']; ?>">
            <div class="invalid-feedback error_nama"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="deskripsi" class="col-sm-3 col-form-label">Kode Ruangan</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="kodeRuangan" name="kodeRuangan" value="<?= $ruangan['kode']; ?>">
            <div class="invalid-feedback error_kode"></div>
        </div>
    </div>

    <div class="text-center">
        <button id="#tombolUpdate" class="btn px-5 btn-outline-primary" type="submit">Update<i class="fa-fw fa-solid fa-check"></i></button>
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
                $('#tombolSimpan').html('Update <i class="fa-fw fa-solid fa-check"></i>');
                $('#tombolSimpan').prop('disabled', false);
            },
            success: function(response) {
                if (response.error) {
                    let err = response.error;

                    if (err.error_nama) {
                        $('.error_nama').html(err.error_nama);
                        $('#nama').addClass('is-invalid');
                    } else {
                        $('.error_nama').html('');
                        $('#nama').removeClass('is-invalid');
                        $('#nama').addClass('is-valid');
                    }
                    if (err.error_kode) {
                        $('.error_kode').html(err.error_kode);
                        $('#kodeRuangan').addClass('is-invalid');
                    } else {
                        $('.error_kode').html('');
                        $('#kodeRuangan').removeClass('is-invalid');
                        $('#kodeRuangan').addClass('is-valid');
                    }

                }
                if (response.success) {
                    $('#my-modal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    })
                    $('#tabel').DataTable().ajax.reload();
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
        return false
    })
</script>