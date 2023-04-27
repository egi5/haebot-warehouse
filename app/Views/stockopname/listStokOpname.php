<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>


<main class="p-md-3 p-2">

    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Buat List Stok Produk</h3>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-dark" href="<?= site_url() ?>stockopname">
                <i class="fa-fw fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <hr class="mt-0 mb-4">

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header text-light" style="background-color: #3A98B9;">
                    List Produk
                </div>
                <div class="card-body" style="background-color: #E6ECF0;">
                    <form autocomplete="off" class="row g-3" action="<?= site_url() ?>stockopnamedetail" method="POST" id="form">
                        <div class="mb-10">
                            <p class="mb-1">Produk</p>
                            <!-- <div class="input-group"> -->
                                <select style="width: 60%;" name="idProduk" id="idProduk">
                                    <option>Pilih Produk</option>
                                    <?php foreach ($produk as $pr) : ?>
                                        <option value="<?= $pr['id'] ?>"><?= $pr['nama'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="invalid-feedback error_idProduk"></div>
                            <!-- </div> -->
                        </div>
                        <div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <p class="mb-1">Stok Fisik</p>
                                    <!-- <div class="input-group mb-3"> -->
                                        <input style="width: 90%;" type="number" class="form-control" placeholder="Jumlah Stok Fisik" name="stokFisik" id="stokFisik">
                                    <!-- </div> -->
                                </div>
                                <div class="col-sm-3">
                                    <p class="mb-1">Jumlah Stok Virtual</p>
                                    <!-- <div class="input-group mb-3"> -->
                                        <input style="width: 90%;" type="text" class="form-control" name="stokVirtual" id="stokVirtual" readonly="">
                                        <div class="invalid-feedback error_stok"></div>
                                    <!-- </div> -->
                                </div>
                                <div class="col-sm-4">
                                    <br>
                                    <span style="color: #f05348;" class="title-total" name="selisih" id="selisih"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="input-group mb-1">
                                <button class="btn btn-secondary px-2" type="submit" id="tambah_produk"><i class="fa-fw fa-solid fa-plus"></i>Tambah Produk</button>
                                <input type="hidden" class="form-control" name="idStokOpname" id="idStokOpname" value="<?= $stok['id'] ?>">
                            </div>
                        </div>
                        
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" width="100%" id="tabel">
                            <thead style="background-color: #F6DCA9;" class="text-center border-secondary">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th class="text-center" width="25%">Produk</th>
                                    <th class="text-center" width="15%">Jumlah Virtual</th>
                                    <th class="text-center" width="15%">Jumlah Fisik</th>
                                    <th class="text-center" width="15%">Selisih</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel_list_produk">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <form autocomplete="off" class="mt-2" action="<?= site_url() ?>simpanstok" method="POST" id="formSelesai">
                    <input type="hidden" class="form-control" name="idStokOpname" id="idStokOpname" value="<?= $stok['id'] ?>">
                    <button id="#tombolSimpan" type="submit" class="btn px-5 btn-outline-primary">Simpan<i class="fa-fw fa-solid fa-check"></i></button>
                </form>
            </div>
        </div>
    </div>
</main>


<?= $this->include('MyLayout/js') ?>



<!-- Modal -->
<div class="modal fade" id="my-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="judulModal"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="isiModal">

            </div>
        </div>
    </div>
</div>
<!-- Modal -->



<script>
    // Bahan Alert
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        background: '#EC7063',
        color: '#fff',
        iconColor: '#fff',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })


    $(document).ready(function() {
        $("#idProduk").select2({
            theme: "bootstrap-5",
        });

        // $('#tabel').DataTable();

        $('#idProduk').change(function() {
            let idProduk = $(this).val();

            if (idProduk != '') {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('stockopname/stokbyproduk') ?>',
                    data: 'idProduk=' + idProduk,
                    success: function(html) {
                        $('#stokVirtual').val(html);
                    }
                })
            } else {
                $('#stokVirtual').val('');
            }
        })


        $('#stokFisik').on('input',function() {
            var stokVirtual = $('#stokVirtual').val();
            var stokFisik   = $('#stokFisik').val();
            var selisih     = stokVirtual - stokFisik;
            $("#selisih").html("Selisih stok : "+selisih);
            $("#selisihStok").val(selisih);
        });

        load_list();

        // Alert
        var op = <?= (!empty(session()->getFlashdata('pesan')) ? json_encode(session()->getFlashdata('pesan')) : '""'); ?>;
        if (op != '') {
            Toast.fire({
                icon: 'success',
                title: op
            })
        }
    })


    function load_list() {
        var idStockOpname = $('#idStokOpname').val();
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/list_stok_produk",
            data: 'idStockOpname=' + idStockOpname,
            dataType: "json",
            success: function(response) {
                $('#tabel_list_produk').html(response.list)
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
    }


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
                    if (err.error_stok) {
                        $('.error_stok').html(err.error_stok);
                        $('#stokFisik').addClass('is-invalid');
                    } else {
                        $('.error_stok').html('');
                        $('#stokFisik').removeClass('is-invalid');
                        $('#stokFisik').addClass('is-valid');
                    }

                }
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil menambah list produk stock opname'
                    })
                    load_list();
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
        return false
    })


    $('#tombolSimpan').click(function() {
        let idStockOpname = $('#idStokOpname').val();
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/check_list_produk",
            data: 'idStockOpname=' + idStockOpname,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    Swal.fire({
                        title: 'Konfirmasi?',
                        text: "Apakah yakin selesaikan stock opname ini ?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjut!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#formSelesai').submit();
                        }
                    })
                } else {
                    Swal.fire(
                        'Opss.',
                        'Tidak ada produk dalam . pilih minimal satu produk dulu!',
                        'error'
                    )
                }
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
    })

</script>

<?= $this->endSection() ?>