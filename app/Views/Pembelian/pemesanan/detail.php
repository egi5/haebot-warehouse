<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>


<main class="p-md-3 p-2">

    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Buat List Produk Pemesanan</h3>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-dark" href="<?= site_url() ?>pemesanan">
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
                                    <th class="text-center" width="15%">SKU</th>
                                    <th class="text-center" width="30%">Produk</th>
                                    <th class="text-center" width="20%">Satuan</th>
                                    <th class="text-center" width="5%">Qty</th>
                                    <th class="text-center" width="20%">Total</th>
                                    <th class="text-center" width="5%">Aksi</th>
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
                    Detail Pemesanan
                </div>
                <div class="card-body" style="background-color: #E6ECF0;">
                    <form id="form_pemesanan" autocomplete="off" action="<?= site_url() ?>simpan_pemesanan" method="post">
                        <input type="hidden" name="id_pemesanan" value="<?= $pemesanan['id'] ?>">
                        <div class="mb-3">
                            <label for="no_pemesanan" class="form-label">Nomor Pemesanan</label>
                            <input disabled type="text" class="form-control" id="no_pemesanan" name="no_pemesanan" value="<?= $pemesanan['no_pemesanan'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <select disabled class="form-select" id="supplier" name="supplier">
                                <option value=""></option>
                                <?php foreach ($supplier as $sup) : ?>
                                    <option <?= ($sup['id'] == $pemesanan['id_supplier']) ? 'selected' : '' ?> value="<?= $sup['id'] ?>"><?= $sup['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <input type="hidden" name="id_supplier" id="id_supplier">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input disabled type="text" class="form-control" id="tanggal" name="tanggal" value="<?= $pemesanan['tanggal'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">Admin</label>
                            <select disabled class="form-select" id="user" name="user">
                                <option value=""></option>
                                <?php foreach ($user as $usr) : ?>
                                    <option <?= ($usr['id'] == user()->id) ? 'selected' : '' ?> value="<?= $usr['id'] ?>"><?= $usr['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <input type="hidden" name="id_user" id="id_user">
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body" style="background-color: #E6ECF0;">
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" id="simpan_pemesanan">Simpan Pemesanan <i class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>


</main>


<?= $this->include('MyLayout/js') ?>

<script>
    $(document).ready(function() {
        $("#id_produk").select2({
            theme: "bootstrap-5",
            placeholder: 'Cari Produk',
            initSelection: function(element, callback) {}
        });
        $("#supplier").select2({
            theme: "bootstrap-5",
        });
        $("#user").select2({
            theme: "bootstrap-5",
        });

        $('#tanggal').datepicker({
            format: "yyyy-mm-dd"
        });

        load_list();
        set_value_select2();
    })

    function load_list() {
        let id_pemesanan = '<?= $pemesanan['id'] ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/produks_pemesanan",
            data: 'id_pemesanan=' + id_pemesanan,
            dataType: "json",
            success: function(response) {
                $('#tabel_list_produk').html(response.list)
            },
            error: function(e) {
                alert('Error \n' + e.responseText);
            }
        });
    }

    function set_value_select2() {
        $('#id_supplier').val($('#supplier').val());
        $('#id_user').val($('#user').val());
    }

    $('#tambah_produk').click(function() {
        let id_produk = $('#id_produk').val();
        let qty = $('#qty').val();
        let id_pemesanan = '<?= $pemesanan['id'] ?>'

        if (id_produk != '' && qty != '') {
            $.ajax({
                type: "post",
                url: "<?= base_url() ?>/create_list_produk",
                data: 'id_pemesanan=' + id_pemesanan +
                    '&id_produk=' + id_produk +
                    '&qty=' + qty,
                dataType: "json",
                success: function(response) {
                    if (response.notif) {
                        Swal.fire(
                            'Berhasil',
                            'Berhasil menambah produk ke dalam List',
                            'success'
                        )
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

    $('#simpan_pemesanan').click(function() {
        let id_pemesanan = '<?= $pemesanan['id'] ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>/check_list_produk",
            data: 'id_pemesanan=' + id_pemesanan,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    Swal.fire({
                        title: 'Konfirmasi?',
                        text: "Apakah yakin menyimpan pemesanan ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Lanjut!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#form_pemesanan').submit();
                        }
                    })
                } else {
                    Swal.fire(
                        'Opss.',
                        'Tidak ada produk dalam pemesanan. pilih minimal satu produk dulu!',
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