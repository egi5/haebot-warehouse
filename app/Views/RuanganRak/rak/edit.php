<form autocomplete="off" class="row g-3 mt-3" action="<?= site_url() ?>rak/<?= $rak['id'] ?>" method="POST" id="form">

    <?= csrf_field() ?>

    <input type="hidden" name="_method" value="PUT">

    <div class="row mb-3">
        <label for="deskripsi" class="col-sm-3 col-form-label">Ruangan</label>
        <div class="col-sm-9">
            <select class="form-control" name="idRuangan" id="idRuangan">
                <?php foreach ($ruangan as $r) : ?>
                    <option <?= (old('id_ruangan', $rak['id_ruangan']) == $r['id']) ? 'selected' : ''; ?> value="<?= $r['id'] ?>-krisna-<?= $r['nama'] ?>"><?= $r['nama'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback error_ruangan"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="nama" class="col-sm-3 col-form-label">Nama Rak</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="nama" name="nama" value="<?= $rak['nama']; ?>">
            <div class="invalid-feedback error_nama"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="deskripsi" class="col-sm-3 col-form-label">Kode Rak</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="kodeRak" name="kodeRak" value="<?= $rak['kode']; ?>">
            <div class="invalid-feedback error_kode"></div>
        </div>
    </div>

    <div class="text-center">
        <a class="btn px-5 btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Batal 
            <i class="fa-fw fa-solid fa-xmark"></i>
        </a>
        <button id="#tombolUpdate" class="btn px-5 btn-outline-primary" type="submit">Update<i class="fa-fw fa-solid fa-check"></i></button>
    </div>
</form>

<?= $this->include('MyLayout/js') ?>

<script>
    $('#form').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            type: "post",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                $('#tombolUpdate').html('Tunggu <i class="fa-solid fa-spin fa-spinner"></i>');
                $('#tombolUpdate').prop('disabled', true);
            },
            complete: function() {
                $('##tombolUpdate').html('Update <i class="fa-fw fa-solid fa-check"></i>');
                $('#tombolUpdate').prop('disabled', false);
            },
            success: function(response) {
                if (response.error) {
                    let err = response.error;

                    if (err.error_idRuangan) {
                        $('.error_ruangan').html(err.error_idRuangan);
                        $('#idRuangan').addClass('is-invalid');
                    } else {
                        $('.error_ruangan').html('');
                        $('#idRuangan').removeClass('is-invalid');
                        $('#idRuangan').addClass('is-valid');
                    }
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
                        $('#kodeRak').addClass('is-invalid');
                    } else {
                        $('.error_kode').html('');
                        $('#kodeRak').removeClass('is-invalid');
                        $('#kodeRak').addClass('is-valid');
                    }
                    
                }
                if (response.success) {
                    $('#my-modal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    });
                    location.href = "<?= base_url() ?>/rak";
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
        return false
    })
</script>