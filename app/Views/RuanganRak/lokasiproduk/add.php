<form autocomplete="off" class="row g-3 mt-3" action="<?= site_url() ?>lokasiproduk/" method="POST" id="form">

    <?= csrf_field() ?>

    <div class="row mb-3">
        <label for="satuan" class="col-sm-3 col-form-label">Produk</label>
        <div class="col-sm-9">
            <select class="form-control" name="idProduk" id="idProduk">
                <option></option>
                <?php foreach ($produk as $pr) : ?>
                    <option value="<?= $pr['id'] ?>"><?= $pr['nama'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback error_idProduk"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="satuan" class="col-sm-3 col-form-label">Ruangan</label>
        <div class="col-sm-9">
            <select class="form-select" name="idRuangan" id="idRuangan">
                <option></option>
                <?php foreach ($ruangan as $ru) : ?>
                    <option value="<?= $ru['id'] ?>"><?= $ru['nama'] ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback error_idRuangan"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="satuan" class="col-sm-3 col-form-label">Rak</label>
        <div class="col-sm-9">
            <select class="form-select" name="idRak" id="idRak">
                <option selected value=""></option>
            </select>
            <div class="invalid-feedback error_idRak"></div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="deskripsi" class="col-sm-3 col-form-label">Stok</label>
        <div class="col-sm-9">
            <input type="number" class="form-control" id="stok" name="stok">
            <div class="invalid-feedback error_stok" id="stokValidasi"></div>
        </div>
    </div>

    <input type="hidden" id="stokAwal" name="stokAwal">

    <div class="text-center">
        <a class="btn px-5 btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Batal 
            <i class="fa-fw fa-solid fa-xmark"></i>
        </a>
        <button id="tombolSimpan" class="btn px-5 btn-outline-primary" type="submit">Simpan<i class="fa-fw fa-solid fa-check"></i></button>
    </div>
</form>

<?= $this->include('MyLayout/js') ?>

<script>
    $(document).ready(function() {
        $("#idProduk").select2({
            theme: "bootstrap-5",
            tags: true,
            dropdownParent: $('#my-modal')
        });
        $("#idRuangan").select2({
            theme: "bootstrap-5",
            tags: true,
            dropdownParent: $('#my-modal')
        });
        $("#idRak").select2({
            theme: "bootstrap-5",
            tags: true,
            dropdownParent: $('#my-modal')
        });

        $('#idRuangan').change(function() {
            let idRuangan = $(this).val();
            
            if (idRuangan != '') {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('lokasiproduk/rak_byruangan') ?>',
                    data: 'idRuangan=' + idRuangan,
                    success: function(html) {
                        $('#idRak').html(html);
                    }
                })
            } else {
                $('#idRak').html('<option selected value=""></option>');
            }
        })

        $('#idProduk').change(function() {
            let idProduk = $(this).val();
            
            if (idProduk != '') {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('lokasiproduk/stok_byidproduk') ?>',
                    data: 'idProduk=' + idProduk,
                    success: function(html) {
                        $('#stokAwal').val(html);
                        if(html == "Stok habis"){
                            $('#stokValidasi').html(html);
                            $('#stok').addClass('is-invalid');
                            $('#stok').attr('readonly', true);
                            $('#tombolSimpan').attr('hidden', true);
                        } else {
                            $('#stok').val(html);
                            $('#stokValidasi').html('');
                            $('#stok').removeClass('is-invalid');
                            $('#stok').addClass('is-valid');
                            $('#stok').attr('readonly', false);
                            $('#tombolSimpan').attr('hidden', false);
                        }
                    }
                })
            } else {
                $('#stok').val('');
            }
        })
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

                    if (err.error_idProduk) {
                        $('.error_idProduk').html(err.error_idProduk);
                        $('#idProduk').addClass('is-invalid');
                    } else {
                        $('.error_idProduk').html('');
                        $('#idProduk').removeClass('is-invalid');
                        $('#idProduk').addClass('is-valid');
                    }
                    if (err.error_idRuangan) {
                        $('.error_idRuangan').html(err.error_idRuangan);
                        $('#idRuangan').addClass('is-invalid');
                    } else {
                        $('.error_idRuangan').html('');
                        $('#idRuangan').removeClass('is-invalid');
                        $('#idRuangan').addClass('is-valid');
                    }
                    if (err.error_idRak) {
                        $('.error_idRak').html(err.error_idRak);
                        $('#idRak').addClass('is-invalid');
                    } else {
                        $('.error_idRak').html('');
                        $('#idRak').removeClass('is-invalid');
                        $('#idRak').addClass('is-valid');
                    }
                    if (err.error_stok) {
                        $('.error_stok').html(err.error_stok);
                        $('#stok').addClass('is-invalid');
                    } else {
                        $('.error_stok').html('');
                        $('#stok').removeClass('is-invalid');
                        $('#stok').addClass('is-valid');
                    }
                    
                }
                if (response.success) {
                    $('#my-modal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.success
                    });
                    location.href = "<?= base_url() ?>/produkruangan";
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
        return false
    })
</script>