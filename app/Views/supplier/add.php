<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>

<main class="p-md-3 p-2">
    <div class="d-flex mb-0">
        <div class="me-auto mb-1">
            <h3 style="color: #566573;">Tambah Supplier</h3>
        </div>
        <div class="me-2 mb-1">
            <a class="btn btn-sm btn-outline-dark" href="<?= site_url() ?>supplier">
                <i class="fa-fw fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <hr class="mt-0 mb-4">

    <div class="col-md-10 mt-4 mb-5">

        <form autocomplete="off" class="row g-3 mt-3" action="<?= site_url() ?>supplier" method="POST">

            <?= csrf_field() ?>

            <div class="row mb-3">
                <label for="origin" class="col-sm-3 col-form-label">Origin</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('origin')) ? 'is-invalid' : ''; ?>" id="origin" name="origin" value="<?= old('origin'); ?>">
                    <div class="invalid-feedback"> <?= validation_show_error('origin'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="nama" class="col-sm-3 col-form-label">Nama Supplier</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('nama')) ? 'is-invalid' : ''; ?>" id="nama" name="nama" value="<?= old('nama'); ?>">
                    <div class="invalid-feedback"> <?= validation_show_error('nama'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="pemilik" class="col-sm-3 col-form-label">Pemilik</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('pemilik')) ? 'is-invalid' : ''; ?>" id="pemilik" name="pemilik" value="<?= old('pemilik'); ?>">
                    <div class="invalid-feedback"> <?= validation_show_error('pemilik'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="no_telp" class="col-sm-3 col-form-label">No Telp</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('no_telp')) ? 'is-invalid' : ''; ?>" id="no_telp" name="no_telp" value="<?= old('no_telp'); ?>">
                    <div class="invalid-feedback"><?= validation_show_error('no_telp'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="saldo" class="col-sm-3 col-form-label">Saldo Awal</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('saldo')) ? 'is-invalid' : ''; ?>" id="saldo" name="saldo" value="<?= old('saldo'); ?>">
                    <div class="invalid-feedback"><?= validation_show_error('saldo'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="note" class="col-sm-3 col-form-label">Note</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="note" name="note" value="<?= old('note'); ?>">
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
</main>

<?= $this->include('MyLayout/js') ?>

<script>
    $(document).ready(function() {
        $('#saldo').mask('000.000.000', {
            reverse: true
        });
    })
</script>

<?= $this->endSection() ?>