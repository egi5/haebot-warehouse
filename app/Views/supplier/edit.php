<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>

<main class="p-md-3 p-2">
    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Edit Supplier</h3>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-dark" href="<?= site_url() ?>supplier">
                <i class="fa-fw fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <hr class="mt-0 mb-3">

    <div class="row">
        <div class="col-md-6 mt-2 mb-5">

            <form autocomplete="off" class="mt-1" action="<?= site_url() ?>supplier/<?= $supplier['id'] ?>" method="POST">

                <?= csrf_field() ?>

                <input type="hidden" name="_method" value="PUT">

                <div class="row mb-3">
                    <label for="origin" class="col-sm-2 col-form-label">Origin</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= (validation_show_error('origin')) ? 'is-invalid' : ''; ?>" id="origin" name="origin" value="<?= old('origin', $supplier['origin']); ?>">
                        <div class="invalid-feedback"> <?= validation_show_error('origin'); ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= (validation_show_error('nama')) ? 'is-invalid' : ''; ?>" id="nama" name="nama" value="<?= old('nama', $supplier['nama']); ?>">
                        <div class="invalid-feedback"> <?= validation_show_error('nama'); ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="pemilik" class="col-sm-2 col-form-label">Pemilik</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= (validation_show_error('pemilik')) ? 'is-invalid' : ''; ?>" id="pemilik" name="pemilik" value="<?= old('pemilik', $supplier['pemilik']); ?>">
                        <div class="invalid-feedback"> <?= validation_show_error('pemilik'); ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="no_telp" class="col-sm-2 col-form-label">No Telp</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?= (validation_show_error('no_telp')) ? 'is-invalid' : ''; ?>" id="no_telp" name="no_telp" value="<?= old('no_telp', $supplier['no_telp']); ?>">
                        <div class="invalid-feedback"><?= validation_show_error('no_telp'); ?></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="note" class="col-sm-2 col-form-label">Note</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="note" name="note" value="<?= old('note', $supplier['note']); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="status" id="status">
                            <option <?= (old('status', $supplier['status']) == 'Active') ? 'selected' : ''; ?> value="Active">Active</option>
                            <option <?= (old('status', $supplier['status']) == 'Inactive') ? 'selected' : ''; ?> value="Inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback error-status"><?= validation_show_error('status'); ?></div>
                    </div>
                </div>

                <div class="text-center">
                    <a class="btn px-5 btn-outline-danger" href="<?= site_url() ?>supplier">
                        Batal <i class="fa-fw fa-solid fa-xmark"></i>
                    </a>
                    <button class="btn px-5 btn-outline-primary" type="submit">Simpan <i class="fa-fw fa-solid fa-check"></i></button>
                </div>
            </form>

        </div>

        <div class="col-md-6 mt-2 mb-5">

            <!-- ADMIN PENANGGUNGJAWAB -->
            <div class="d-flex mb-0">
                <div class="me-auto">
                    <h5 class="mb-2">Admin</h5>
                </div>
                <div>
                    <button class="btn btn-sm btn-secondary mb-2 py-0" data-bs-toggle="modal" data-bs-target="#modal-add-pj">
                        <i class="fa-fw fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped table-secondary">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="55%">Admin</th>
                            <th width="20%">Urutan</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($pj as $pj) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $pj['nama_pj'] ?></td>
                                <td class="text-center"><?= $pj['urutan'] ?></td>
                                <td class="text-center">
                                    <a title="Edit" class="px-2 py-0 btn btn-sm btn-outline-primary" onclick="edit_pj(<?= $pj['id'] ?>)">
                                        <i class="fa-fw fa-solid fa-pen"></i>
                                    </a>
                                    <form id="form_delete_pj" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">
                                    </form>
                                    <button onclick="confirm_delete_pj(<?= $pj['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- ALAMAT -->
            <div class="d-flex mb-0">
                <div class="me-auto">
                    <h5 class="mb-2">Alamat</h5>
                </div>
                <div>
                    <button class="btn btn-sm btn-secondary mb-2 py-0" data-bs-toggle="modal" data-bs-target="#modal-add-alamat">
                        <i class="fa-fw fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped table-secondary">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="25%">Nama</th>
                            <th width="60%">Alamat, PIC, Telp</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($alamat as $al) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $al['nama'] ?></td>
                                <td>
                                    <?= $al['detail_alamat'] ?>, <?= $al['kelurahan'] ?>, <?= $al['kecamatan'] ?>, <?= $al['kota'] ?>, <?= $al['provinsi'] ?>
                                    <br>
                                    PIC : <?= $al['pic'] ?>
                                    <br>
                                    TELP : <?= $al['no_telp'] ?>
                                </td>
                                <td class="text-center">
                                    <form id="form_delete_alamat" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">
                                    </form>
                                    <button onclick="confirm_delete_alamat(<?= $al['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- LINK -->
            <div class="d-flex mb-0">
                <div class="me-auto">
                    <h5 class="mb-2">Link</h5>
                </div>
                <div>
                    <button class="btn btn-sm btn-secondary mb-2 py-0" data-bs-toggle="modal" data-bs-target="#modal-add-link">
                        <i class="fa-fw fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped table-secondary">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="25%">Nama</th>
                            <th width="60%">Link</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($link as $li) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $li['nama'] ?></td>
                                <td><?= $li['link'] ?></td>
                                <td class="text-center">
                                    <form id="form_delete_link" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">
                                    </form>
                                    <button onclick="confirm_delete_link(<?= $li['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- CUSTOMER SERVICE -->
            <div class="d-flex mb-0">
                <div class="me-auto">
                    <h5 class="mb-2">Customer Service</h5>
                </div>
                <div>
                    <button class="btn btn-sm btn-secondary mb-2 py-0" data-bs-toggle="modal" data-bs-target="#modal-add-cs">
                        <i class="fa-fw fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped table-secondary">
                    <thead>
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="40%">Nama</th>
                            <th width="35%">Telp</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($customer_service as $cs) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= $cs['nama'] ?></td>
                                <td><?= $cs['no_telp'] ?></td>
                                <td class="text-center">
                                    <a title="Edit" class="px-2 py-0 btn btn-sm btn-outline-primary" onclick="edit_cs(<?= $cs['id'] ?>)">
                                        <i class="fa-fw fa-solid fa-pen"></i>
                                    </a>
                                    <form id="form_delete_cs" method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">
                                    </form>
                                    <button onclick="confirm_delete_cs(<?= $cs['id'] ?>)" title="Hapus" type="button" class="px-2 py-0 btn btn-sm btn-outline-danger"><i class="fa-fw fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>


<!-- Modal add penanggung jawab -->
<div class="modal fade" id="modal-add-pj" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Admin</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate class="needs-validation" id="form-add-pj" autocomplete="off" action="<?= site_url() ?>supplierpj" method="POST">

                    <?= csrf_field() ?>

                    <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Admin</label>
                        <select required class="form-control" name="id_user" id="id_user">
                            <option value=""></option>
                            <?php foreach ($users as $us) : ?>
                                <option value="<?= $us['id'] ?>"><?= $us['nama'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-outline-secondary mb-2 btn-sm" type="submit">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit penanggung jawab -->
<div class="modal fade" id="modal-edit-pj" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Admin</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate class="needs-validation" id="form-edit-pj" autocomplete="off" method="POST">

                    <?= csrf_field() ?>

                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input required type="number" class="form-control" id="edit-urutan" name="edit-urutan">
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-outline-secondary mb-2 btn-sm" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal add alamat -->
<div class="modal fade" id="modal-add-alamat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Alamat</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate class="needs-validation" id="form-add-alamat" autocomplete="off" action="<?= site_url() ?>supplieralamat" method="POST">

                    <?= csrf_field() ?>

                    <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama Alamat</label>
                        <input required type="text" class="form-control" name="nama">
                    </div>

                    <!-- PROVINSI -->
                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <select required class="form-select" id="id_provinsi" name="id_provinsi">
                            <option selected value=""></option>
                            <?php foreach ($provinsi as $prov) { ?>
                                <option value="<?= $prov['id'] ?>"><?= $prov['nama'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- KOTA -->
                    <div class="mb-3">
                        <label class="form-label">Kota</label>
                        <select required class="form-select" id="id_kota" name="id_kota">
                            <option selected value=""></option>
                        </select>
                    </div>

                    <!-- KECAMATAN -->
                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select required class="form-select" id="id_kecamatan" name="id_kecamatan">
                            <option selected value=""></option>
                        </select>
                    </div>

                    <!-- KELURAHAN -->
                    <div class="mb-3">
                        <label class="form-label">Kelurahan</label>
                        <select required class="form-select" id="id_kelurahan" name="id_kelurahan">
                            <option selected value=""></option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detail Alamat</label>
                        <input required type="text" class="form-control" name="detail_alamat">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">PIC</label>
                        <input required type="text" class="form-control" name="pic">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No Telp</label>
                        <input required type="text" class="form-control" name="no_telp">
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-outline-secondary mb-2 btn-sm" type="submit">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal add link -->
<div class="modal fade" id="modal-add-link" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Link</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate class="needs-validation" id="form-add-link" autocomplete="off" action="<?= site_url() ?>supplierlink" method="POST">

                    <?= csrf_field() ?>

                    <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input required type="text" class="form-control" id="nama" name="nama">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link</label>
                        <input required type="text" class="form-control" id="link" name="link">
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-outline-secondary mb-2 btn-sm" type="submit">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal add CS -->
<div class="modal fade" id="modal-add-cs" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah CS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate class="needs-validation" id="form-add-cs" autocomplete="off" action="<?= site_url() ?>suppliercs" method="POST">

                    <?= csrf_field() ?>

                    <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input required type="text" class="form-control" id="nama" name="nama">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No Telp</label>
                        <input required type="text" class="form-control" id="no_telp" name="no_telp">
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-outline-secondary mb-2 btn-sm" type="submit">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal edit CS -->
<div class="modal fade" id="modal-edit-cs" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit CS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form novalidate class="needs-validation" id="form-edit-cs" autocomplete="off" method="POST">

                    <?= csrf_field() ?>

                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id_supplier" value="<?= $supplier['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input required type="text" class="form-control" id="edit-nama_cs" name="nama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telp</label>
                        <input required type="text" class="form-control" id="edit-no_telp_cs" name="no_telp">
                    </div>

                    <hr class="my-4">

                    <button class="btn btn-outline-secondary mb-2 btn-sm" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('MyLayout/js') ?>
<?= $this->include('MyLayout/validation') ?>

<script>
    $(document).ready(function() {
        $("#id_provinsi").select2({
            theme: "bootstrap-5",
            dropdownParent: $('#modal-add-alamat')
        });
        $("#id_kota").select2({
            theme: "bootstrap-5",
            dropdownParent: $('#modal-add-alamat')
        });
        $("#id_kecamatan").select2({
            theme: "bootstrap-5",
            dropdownParent: $('#modal-add-alamat')
        });
        $("#id_kelurahan").select2({
            theme: "bootstrap-5",
            dropdownParent: $('#modal-add-alamat')
        });

        // Alert
        var op = <?= (!empty(session()->getFlashdata('pesan')) ? json_encode(session()->getFlashdata('pesan')) : '""'); ?>;
        if (op != '') {
            Toast.fire({
                icon: 'success',
                title: op
            })
        }
    })

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


    // RANTAI WILAYAH
    $(document).ready(function() {
        $('#id_provinsi').change(function() {
            let id_provinsi = $(this).val();
            if (id_provinsi != '') {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('wilayah/kota_by_provinsi') ?>',
                    data: '&id_provinsi=' + id_provinsi,
                    success: function(html) {
                        $('#id_kota').html(html);
                        $('#id_kecamatan').html('<option selected value=""></option>');
                        $('#id_kelurahan').html('<option selected value=""></option>');
                    }
                })
            } else {
                $('#id_kota').html('<option selected value=""></option>');
                $('#id_kecamatan').html('<option selected value=""></option>');
                $('#id_kelurahan').html('<option selected value=""></option>');
            }
        })

        $('#id_kota').change(function() {
            let id_kota = $(this).val();
            if (id_kota != '') {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('wilayah/kecamatan_by_kota') ?>',
                    data: '&id_kota=' + id_kota,
                    success: function(html) {
                        $('#id_kecamatan').html(html);
                        $('#id_kelurahan').html('<option selected value=""></option>');
                    }
                })
            } else {
                $('#id_kecamatan').html('<option selected value=""></option>');
                $('#id_kelurahan').html('<option selected value=""></option>');
            }
        })

        $('#id_kecamatan').change(function() {
            let id_kecamatan = $(this).val();
            if (id_kecamatan != '') {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('wilayah/kelurahan_by_kecamatan') ?>',
                    data: '&id_kecamatan=' + id_kecamatan,
                    success: function(html) {
                        $('#id_kelurahan').html(html);
                    }
                })
            } else {
                $('#id_kelurahan').html('<option selected value=""></option>');
            }
        })
    })


    // PENANGGUNG JAWAB
    function edit_pj(id) {
        $.ajax({
            type: "get",
            url: "<?= site_url() ?>supplierpj/" + id + "/edit",
            dataType: "json",
            success: function(response) {
                $('#form-edit-pj').attr('action', '<?= site_url() ?>supplierpj/' + id);
                $('#edit-urutan').val(response.urutan);
                $('#modal-edit-pj').modal('toggle')
            }
        });
    }

    function confirm_delete_pj(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Apakah yakin menghapus Admin?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_delete_pj').attr('action', '<?= site_url() ?>supplierpj/' + id);
                $('#form_delete_pj').submit();
            }
        })
    }


    // ALAMAT
    function confirm_delete_alamat(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Apakah yakin menghapus alamat?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_delete_alamat').attr('action', '<?= site_url() ?>supplieralamat/' + id);
                $('#form_delete_alamat').submit();
            }
        })
    }


    // LINK
    function confirm_delete_link(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Apakah yakin menghapus link?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_delete_link').attr('action', '<?= site_url() ?>supplierlink/' + id);
                $('#form_delete_link').submit();
            }
        })
    }


    // CS
    function edit_cs(id) {
        $.ajax({
            type: "get",
            url: "<?= site_url() ?>suppliercs/" + id + "/edit",
            dataType: "json",
            success: function(res) {
                $('#form-edit-cs').attr('action', '<?= site_url() ?>suppliercs/' + id);
                $('#edit-nama_cs').val(res.nama);
                $('#edit-no_telp_cs').val(res.no_telp);
                $('#modal-edit-cs').modal('toggle')
            }
        });
    }

    function confirm_delete_cs(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Apakah yakin menghapus CS?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form_delete_cs').attr('action', '<?= site_url() ?>suppliercs/' + id);
                $('#form_delete_cs').submit();
            }
        })
    }
</script>

<?= $this->endSection() ?>