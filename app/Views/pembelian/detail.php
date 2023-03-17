<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<main class="p-md-3 p-2">

    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Fix List Produk Pembelian</h3>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-dark" href="<?= site_url() ?>pembelian">
                <i class="fa-fw fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <hr class="mt-0 mb-4">

    <div class="row">
        <div class="col-md-9">

            <div class="card">
                <div class="card-header text-light" style="background-color: #3A98B9;">
                    List Produk
                </div>
                <div class="card-body" style="background-color: #E6ECF0;">

                    <div class="col-md-8">
                        <div class="input-group mb-3">
                            <select class="form-select" id="id_produk">
                                <option id="id_produk_default" value=""></option>
                                <?php foreach ($produk as $pr) : ?>
                                    <option value="<?= $pr['id'] ?>"><?= $pr['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <input autocomplete="off" type="text" class="form-control" placeholder="Qty" id="qty">
                            <button class="btn btn-secondary px-2" type="button" id="tambah_produk"><i class="fa-fw fa-solid fa-plus"></i></button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered" width="100%" id="tabel">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th class="text-center" width="10%">SKU</th>
                                    <th class="text-center" width="30%">Produk</th>
                                    <th class="text-center" width="20%">Satuan</th>
                                    <th class="text-center" width="10%">Qty</th>
                                    <th class="text-center" width="15%">Total</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel_list_produk">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3">

            <div class="card mb-3">
                <div class="card-header text-light" style="background-color: #3A98B9;">
                    Detail Pembelian
                </div>
                <div class="card-body" style="background-color: #E6ECF0;">

                    <div class="mb-2">Nomor Pembelian : <b><?= $pembelian['no_pembelian'] ?></b></div>
                    <div class="mb-2">Supplier : <b><?= $pembelian['supplier'] ?></b></div>
                    <div class="mb-2">Tanggal : <b><?= $pembelian['tanggal'] ?></b></div>
                    <div class="mb-2">Admin : <b><?= user()->name ?></b></div>

                    <hr>

                    <form id="form_pembelian" autocomplete="off" action="<?= site_url() ?>simpan_pembelian" method="post">
                        <?= csrf_field() ?>

                        <input type="hidden" name="id_pembelian" value="<?= $pembelian['id'] ?>">
                        <input type="hidden" name="id_admin" value="<?= user()->id ?>">

                        <div class="mb-3">
                            <label for="gudang" class="form-label">Diterima Gudang</label>
                            <select class="form-select" id="gudang" name="gudang">
                                <option value=""></option>
                                <?php foreach ($gudang as $gud) : ?>
                                    <option <?= ($gud['id'] == $pembelian['id_gudang']) ? 'selected' : '' ?> value="<?= $gud['id'] ?>"><?= $gud['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <p class="mb-1">Panjang</p>
                                    <div class="input-group mb-3">
                                        <input type="number" min="1" value="1" class="form-control" id="panjang" name="panjang">
                                        <span class="input-group-text px-2">m</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1">Lebar</p>
                                    <div class="input-group mb-3">
                                        <input type="number" min="1" value="1" class="form-control" id="lebar" name="lebar">
                                        <span class="input-group-text px-2">m</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1">Tinggi</p>
                                    <div class="input-group mb-3">
                                        <input type="number" min="1" value="1" class="form-control" id="tinggi" name="tinggi">
                                        <span class="input-group-text px-2">m</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Berat</label>
                            <div class="input-group mb-3">
                                <input type="number" min="1" value="1" class="form-control" id="berat" name="berat">
                                <span class="input-group-text px-2">kg</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Carton / Koli</label>
                            <input type="number" min="1" value="1" class="form-control" id="carton_koli" name="carton_koli">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <input type="text" class="form-control" id="catatan" name="catatan">
                        </div>

                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body" style="background-color: #E6ECF0;">
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" id="btn_simpan_pembelian">Simpan Pembelian <i class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>


</main>


<?= $this->include('MyLayout/js') ?>

<script>
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
        $("#id_produk").select2({
            theme: "bootstrap-5",
            placeholder: 'Cari Produk',
            initSelection: function(element, callback) {}
        });

        $('#tanggal').datepicker({
            format: "yyyy-mm-dd"
        });

        // Alert
        var op = <?= (!empty(session()->getFlashdata('pesan')) ? json_encode(session()->getFlashdata('pesan')) : '""'); ?>;
        if (op != '') {
            Toast.fire({
                icon: 'success',
                title: op
            })
        }

        load_list();
    })

    function load_list() {
        let id_pembelian = '<?= $pembelian['id'] ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/produks_pembelian",
            data: 'id_pembelian=' + id_pembelian,
            dataType: "json",
            success: function(response) {
                $('#tabel_list_produk').html(response.list)
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
    }

    $('#tambah_produk').click(function() {
        let id_produk = $('#id_produk').val();
        let qty = $('#qty').val();
        let id_pembelian = '<?= $pembelian['id'] ?>'

        if (id_produk != '' && qty != '') {
            $.ajax({
                type: "post",
                url: "<?= base_url() ?>/pembelian_detail",
                data: 'id_pembelian=' + id_pembelian +
                    '&id_produk=' + id_produk +
                    '&qty=' + qty,
                dataType: "json",
                success: function(response) {
                    if (response.notif) {
                        Toast.fire({
                            icon: 'success',
                            title: response.notif
                        })
                        load_list();
                        $('#qty').val('');
                        $('#id_produk').val('').trigger('change');
                    } else {
                        alert('terjadi error tambah list produk')
                    }
                },
                error: function(e) {
                    alert('Error \n' + e.responseText);
                }
            });
        } else {
            Swal.fire(
                'Ops.',
                'Pilih Produk dan Qty dulu.',
                'error'
            )
        }
    })

    $('#btn_simpan_pembelian').click(function() {
        if ($('#gudang').val() == '') {
            $('#gudang').removeClass('is-valid');
            $('#gudang').addClass('is-invalid');
        } else {
            $('#gudang').addClass('is-valid');
            $('#gudang').removeClass('is-invalid');
        }
        if ($('#panjang').val() == '') {
            $('#panjang').removeClass('is-valid');
            $('#panjang').addClass('is-invalid');
        } else {
            $('#panjang').addClass('is-valid');
            $('#panjang').removeClass('is-invalid');
        }
        if ($('#lebar').val() == '') {
            $('#lebar').removeClass('is-valid');
            $('#lebar').addClass('is-invalid');
        } else {
            $('#lebar').addClass('is-valid');
            $('#lebar').removeClass('is-invalid');
        }
        if ($('#tinggi').val() == '') {
            $('#tinggi').removeClass('is-valid');
            $('#tinggi').addClass('is-invalid');
        } else {
            $('#tinggi').addClass('is-valid');
            $('#tinggi').removeClass('is-invalid');
        }
        if ($('#berat').val() == '') {
            $('#berat').removeClass('is-valid');
            $('#berat').addClass('is-invalid');
        } else {
            $('#berat').addClass('is-valid');
            $('#berat').removeClass('is-invalid');
        }
        if ($('#carton_koli').val() == '') {
            $('#carton_koli').removeClass('is-valid');
            $('#carton_koli').addClass('is-invalid');
        } else {
            $('#carton_koli').addClass('is-valid');
            $('#carton_koli').removeClass('is-invalid');
        }
        if ($('#catatan').val() == '') {
            $('#catatan').removeClass('is-valid');
            $('#catatan').addClass('is-invalid');
        } else {
            $('#catatan').addClass('is-valid');
            $('#catatan').removeClass('is-invalid');
        }

        if ($('#gudang').val() != '' && $('#dimensi').val() != '' && $('#berat').val() != '' && $('#carton_koli').val() != '' && $('#catatan').val() != '') {
            simpan_pembelian();
        }
    })

    function simpan_pembelian() {
        let id_pembelian = '<?= $pembelian['id'] ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/check_produk_pembelian",
            data: 'id_pembelian=' + id_pembelian,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    Swal.fire({
                        title: 'Konfirmasi?',
                        text: "Apakah yakin menyimpan pembelian?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjut!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form_pembelian').submit();
                        }
                    })
                } else {
                    Swal.fire(
                        'Opss.',
                        'Tidak ada produk dalam pembelian. pilih minimal satu produk dulu!',
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

<?= $this->endSection() ?>