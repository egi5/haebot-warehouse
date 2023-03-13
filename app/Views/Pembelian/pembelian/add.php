<div id="div-pencarian-pemesanan">
    <div class="row mb-3">
        <label for="cari_no_pemesanan" class="col-sm-3 col-form-label">Nomor Pemesanan</label>
        <div class="col-sm-9">
            <div class="input-group mb-3">
                <input autocomplete="off" type="text" class="form-control" id="cari_no_pemesanan" name="cari_no_pemesanan">
                <button class="btn btn-secondary px-2 rounded-end" type="button" id="tombolCariPemesanan">Cari <i class="fa-fw fa-solid fa-search"></i></button>
                <span class="invalid-feedback error-cari_no_pemesanan"></span>
            </div>
        </div>
    </div>
</div>

<div id="div-hasil-cari-pemesanan" hidden>
</div>

<div id="div-form" hidden>
    <form autocomplete="off" class="row g-3 mt-2" action="<?= site_url() ?>pembelian" method="POST" id="form">

        <?= csrf_field() ?>

        <input type="hidden" class="form-control" id="no_pemesanan" name="no_pemesanan">

        <div class="text-center mb-3">
            <button id="tombolSimpan" class="btn px-5 btn-outline-primary" type="submit">Proses Pembelian <i class="fa-fw fa-solid fa-arrow-right"></i></button>
        </div>
    </form>
</div>



<script>
    $('#tombolCariPemesanan').click(function() {

        let no = $('#cari_no_pemesanan').val();

        if (no == '') {
            $('.error-cari_no_pemesanan').html('nomor pemesanan belum diisi');
            $('#cari_no_pemesanan').addClass('is-invalid');
        } else {
            $.ajax({
                type: 'GET',
                url: '<?= site_url() ?>pemesanan/' + no,
                dataType: 'json',
                beforeSend: function() {
                    $('#tombolCariPemesanan').html('Tunggu <i class="fa-solid fa-spin fa-spinner"></i>');
                    $('#tombolCariPemesanan').prop('disabled', true);
                },
                complete: function() {
                    $('#tombolCariPemesanan').html('Cari <i class="fa-fw fa-solid fa-search"></i>');
                    $('#tombolCariPemesanan').prop('disabled', false);
                },
                success: function(res) {
                    if (res.data) {
                        $('#div-hasil-cari-pemesanan').html(res.data)
                        $('#div-hasil-cari-pemesanan').prop('hidden', false)
                        $('#div-form').prop('hidden', false)
                        $('#no_pemesanan').val(no)

                        $('#cari_no_pemesanan').removeClass('is-invalid');
                        $('#cari_no_pemesanan').addClass('is-valid');
                    } else {
                        Swal.fire(
                            'Ops.',
                            'Tidak ditemukan pemesanan dg no.' + no,
                            'error'
                        )
                        $('#div-hasil-cari-pemesanan').html('')
                        $('#div-hasil-cari-pemesanan').prop('hidden', true)
                        $('#div-form').prop('hidden', true)
                        $('#no_pemesanan').val('')

                        $('#cari_no_pemesanan').removeClass('is-valid');
                        $('.error-cari_no_pemesanan').html('no pemesanan tidak ditemukan.');
                        $('#cari_no_pemesanan').addClass('is-invalid');
                    }
                },
                error: function(e) {
                    alert('Error \n' + e.responseText);
                }
            })
        }

    })

    // $('#form').submit(function(e) {
    //     e.preventDefault();

    //     $.ajax({
    //         type: "post",
    //         url: $(this).attr('action'),
    //         data: $(this).serialize(),
    //         dataType: "json",
    //         beforeSend: function() {
    //             $('#tombolSimpan').html('Tunggu <i class="fa-solid fa-spin fa-spinner"></i>');
    //             $('#tombolSimpan').prop('disabled', true);
    //         },
    //         complete: function() {
    //             $('#tombolSimpan').html('Simpan <i class="fa-fw fa-solid fa-check"></i>');
    //             $('#tombolSimpan').prop('disabled', false);
    //         },
    //         success: function(response) {
    //             if (response.error) {
    //                 let err = response.error;

    //                 if (err.error_no_pemesanan) {
    //                     $('.error-no_pemesanan').html(err.error_no_pemesanan);
    //                     $('#no_pemesanan').addClass('is-invalid');
    //                 } else {
    //                     $('.error-no_pemesanan').html('');
    //                     $('#no_pemesanan').removeClass('is-invalid');
    //                     $('#no_pemesanan').addClass('is-valid');
    //                 }
    //                 if (err.error_id_supplier) {
    //                     $('.error-id_supplier').html(err.error_id_supplier);
    //                     $('#id_supplier').addClass('is-invalid');
    //                 } else {
    //                     $('.error-id_supplier').html('');
    //                     $('#id_supplier').removeClass('is-invalid');
    //                     $('#id_supplier').addClass('is-valid');
    //                 }
    //                 if (err.error_tanggal) {
    //                     $('.error-tanggal').html(err.error_tanggal);
    //                     $('#tanggal').addClass('is-invalid');
    //                 } else {
    //                     $('.error-tanggal').html('');
    //                     $('#tanggal').removeClass('is-invalid');
    //                     $('#tanggal').addClass('is-valid');
    //                 }
    //             }
    //             if (response.success) {
    //                 window.location.replace('<?= base_url() ?>/list_pemesanan/' + response.no_pemesanan);
    //             }
    //         },
    //         error: function(e) {
    //             alert('Error \n' + e.responseText);
    //         }
    //     });
    //     return false
    // })

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