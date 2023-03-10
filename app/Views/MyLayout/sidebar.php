<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu div-menu">
            <div class="nav">
                <br>

                <?php if (has_permission('Dashboard') || has_permission('pembelian')) : ?>
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

                <?php if (has_permission('Data Master')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>produk">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-fax"></i>
                        </div>
                        Produk
                    </a>
                <?php endif; ?>

                <?php if (has_permission('Data Master')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>supplier">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-handshake-simple"></i>
                        </div>
                        Supplier
                    </a>
                <?php endif; ?>

                <br>

                <?php if (has_permission('Dashboard') || has_permission('pembelian')) : ?>
                    <small class="my-0 ms-3 text-secondary">Purchase</small>
                <?php endif; ?>

                <?php if (has_permission('pembelian')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>pemesanan">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-regular fa-rectangle-list"></i>
                        </div>
                        Pemesanan
                    </a>
                <?php endif; ?>

                <?php if (has_permission('pembelian')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>pembelian">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-list-check"></i>
                        </div>
                        Fixing Plan Pemesanan
                    </a>
                <?php endif; ?>

                <?php if (has_permission('pembelian')) : ?>
                    <a class="nav-link" href="<?= base_url() ?>pembelian">
                        <div class="sb-nav-link-icon">
                            <i class="fa-fw fa-solid fa-bars"></i>
                        </div>
                        Data Pembelian
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