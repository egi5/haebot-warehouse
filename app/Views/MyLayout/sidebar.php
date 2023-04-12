<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu div-menu">
            <div class="nav">
                <br>

                <?php if (has_permission('Dashboard') || has_permission('Penanggung Jawab Gudang')) : ?>
                    <small class="my-0 ms-3 text-secondary">Data</small>
                <?php endif; ?>

                <?php if (has_permission('Dashboard')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>dashboard">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-gauge-high"></i>
                        </div>
                        Dashboard
                    </a>
                <?php endif; ?>

                <?php if (has_permission('Penanggung Jawab Gudang')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>produk">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-fax"></i>
                        </div>
                        Produk
                    </a>
                <?php endif; ?>

                <?php if (has_permission('Penanggung Jawab Gudang')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>produk">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-fax"></i>
                        </div>
                        Ruangan & Rak
                    </a>
                <?php endif; ?>

            </div>
        </div>
        <div class="sb-sidenav-footer py-1">
            <div class="small">Masuk sebagai :</div>
            <?= user()->name ?>
        </div>
    </nav>
</div>