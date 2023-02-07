<?= $this->extend('MyLayout/template') ?>

<?= $this->section('content') ?>

<main class="p-md-3 p-2">
    <h3 class="mb-3" style="color: #566573;">Edit Customer</h3>

    <hr class="mt-0 mb-4">

    <div class="col-md-10 mt-4">

        <form autocomplete="off" class="row g-3 mt-3" action="<?= site_url() ?>customer/<?= $customer['id'] ?>" method="POST">

            <?= csrf_field() ?>

            <input type="hidden" name="_method" value="PUT">

            <div class="row mb-3">
                <label for="nama" class="col-sm-3 col-form-label">Nama Customer</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('nama')) ? 'is-invalid' : ''; ?>" id="nama" name="nama" value="<?= old('nama', $customer['nama']); ?>">
                    <div class="invalid-feedback"> <?= validation_show_error('nama'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('alamat')) ? 'is-invalid' : ''; ?>" id="alamat" name="alamat" value="<?= old('alamat', $customer['alamat']); ?>">
                    <div class="invalid-feedback"><?= validation_show_error('alamat'); ?></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="no_telp" class="col-sm-3 col-form-label">No Telp</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control <?= (validation_show_error('no_telp')) ? 'is-invalid' : ''; ?>" id="no_telp" name="no_telp" value="<?= old('no_telp', $customer['no_telp']); ?>">
                    <div class="invalid-feedback"><?= validation_show_error('no_telp'); ?></div>
                </div>
            </div>

            <div class="col-md-9 offset-3">
                <a class="btn px-5 btn-outline-danger" href="<?= site_url() ?>customer">
                    Batal <i class="fa-fw fa-solid fa-xmark"></i>
                </a>
                <button class="btn px-5 btn-outline-primary" type="submit">Simpan <i class="fa-fw fa-solid fa-check"></i></button>
            </div>
        </form>

    </div>
</main>

<?= $this->include('MyLayout/js') ?>

<?= $this->endSection() ?>