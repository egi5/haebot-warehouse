<div id="div-pencarian-pemesanan">
    <div class="row mb-3">
        <label for="cari_no_pemesanan" class="col-sm-3 col-form-label">Nomor Pemesanan</label>
        <div class="col-sm-7">
            <input autocomplete="off" type="text" class="form-control" id="cari_no_pemesanan" name="cari_no_pemesanan">
            <span class="invalid-feedback error-cari_no_pemesanan"></span>
        </div>
        <div class="col-sm-2  d-grid gap-2">
            <button id="tombolCariPemesanan" class="btn btn-outline-secondary">Cari <i class="fa-fw fa-solid fa-search"></i></button>
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
                type: "post",
                url: "<?= base_url() ?>check_pembelian",
                data: 'no_pemesanan=' + no,
                dataType: "json",
                success: function(response) {
                    if (response.pemesanan_already_exist) {
                        Swal.fire(
                            'Opss.',
                            'No pemesanan ' + no + ' sudah diproses ke pembelian.',
                            'error'
                        )
                        $('#div-hasil-cari-pemesanan').html('')
                        $('#div-hasil-cari-pemesanan').prop('hidden', true)
                        $('#div-form').prop('hidden', true)
                        $('#no_pemesanan').val('')

                        $('#cari_no_pemesanan').removeClass('is-valid');
                        $('.error-cari_no_pemesanan').html('No pemesanan ' + no + ' sudah diproses ke pembelian.');
                        $('#cari_no_pemesanan').addClass('is-invalid');
                    } else if (response.not_found_pemesanan) {
                        Swal.fire(
                            'Ops.',
                            'Tidak ditemukan pemesanan dg no ' + no,
                            'error'
                        )
                        $('#div-hasil-cari-pemesanan').html('')
                        $('#div-hasil-cari-pemesanan').prop('hidden', true)
                        $('#div-form').prop('hidden', true)
                        $('#no_pemesanan').val('')

                        $('#cari_no_pemesanan').removeClass('is-valid');
                        $('.error-cari_no_pemesanan').html('Tidak ditemukan pemesanan dg no ' + no);
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
                                } else if (res.error) {
                                    Swal.fire(
                                        'Ops.',
                                        'No pemesanan ' + no + ' masih status pending.',
                                        'error'
                                    )
                                    $('#div-hasil-cari-pemesanan').html('')
                                    $('#div-hasil-cari-pemesanan').prop('hidden', true)
                                    $('#div-form').prop('hidden', true)
                                    $('#no_pemesanan').val('')

                                    $('#cari_no_pemesanan').removeClass('is-valid');
                                    $('.error-cari_no_pemesanan').html('No pemesanan ' + no + ' masih status pending.');
                                    $('#cari_no_pemesanan').addClass('is-invalid');
                                }
                            },
                            error: function(e) {
                                alert('Error \n' + e.responseText);
                            }
                        })
                    }
                },
                error: function(e) {
                    alert('Error \n' + e.responseText);
                }
            });
        }
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