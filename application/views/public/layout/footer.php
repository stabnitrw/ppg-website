<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
</main>
<footer class="site-footer" data-testid="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <span class="footer-logo"><img src="<?= e(logo_url('logo_stabn')) ?>" alt="Logo STABN Raden Wijaya"></span>
                <h5 class="footer-brand"><?= e(setting('site_short_name')) ?> <?= e(setting('institution_name')) ?></h5>
                <p class="small mb-0"><?= e(setting('site_name')) ?><br>Sekolah Tinggi Agama Buddha Negeri Raden Wijaya Wonogiri<br>Kementerian Agama Republik Indonesia</p>
            </div>
            <div class="col-md-6 col-lg-3">
                <h6>Kontak</h6>
                <ul class="list-unstyled footer-contact small">
                    <li><i class="bi bi-geo-alt-fill"></i><span><?= e(setting('address')) ?></span></li>
                    <li><i class="bi bi-telephone-fill"></i><span><?= e(setting('phone')) ?></span></li>
                    <li><i class="bi bi-envelope-fill"></i><span><?= e(setting('email')) ?></span></li>
                    <li><i class="bi bi-clock-fill"></i><span><?= e(setting('office_hours')) ?></span></li>
                </ul>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <h6>Profil</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?= site_url('profil') ?>">Tentang Fakultas</a></li>
                    <li><a href="<?= site_url('profil/visi-misi') ?>">Visi &amp; Misi</a></li>
                    <li><a href="<?= site_url('profil/struktur-organisasi') ?>">Struktur Organisasi</a></li>
                    <li><a href="<?= site_url('profil/pimpinan') ?>">Pimpinan</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-3 col-lg-3">
                <h6>Mahasiswa</h6>
                <ul class="list-unstyled footer-links">
                    <!-- <li><a href="<?= site_url('lapor-diri') ?>">Lapor Diri</a></li> -->
                    <li><a href="<?= site_url('lapor-diri/cek') ?>">Cek Status</a></li>
                    <li><a href="<?= site_url('pengumuman') ?>">Pengumuman</a></li>
                    <li><a href="<?= site_url('agenda') ?>">Agenda Akademik</a></li>
                </ul>
                <div class="social d-flex gap-2 mt-3 d-none">
                    <?php foreach (array('instagram' => 'bi-instagram', 'youtube' => 'bi-youtube', 'facebook' => 'bi-facebook') as $k => $icon): if (setting($k)): ?>
                            <a href="<?= e(setting($k)) ?>" target="_blank" rel="noopener" aria-label="<?= ucfirst($k) ?>" data-testid="footer-social-<?= $k ?>"><i class="bi <?= $icon ?>"></i></a>
                    <?php endif;
                    endforeach; ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>&copy; <?= date('Y') ?> <?= e(setting('site_short_name')) ?> <?= e(setting('institution_name')) ?></span>
            <a href="#" class="to-top-link" data-testid="footer-back-to-top">Kembali ke atas</a>
        </div>
    </div>
</footer>
<a href="#" class="back-to-top" aria-label="Kembali ke atas" data-testid="back-to-top"><i class="bi bi-arrow-up"></i></a>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/main.js') ?>?v=2"></script>
</body>

</html>