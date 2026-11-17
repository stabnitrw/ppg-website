<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('page_lead' => setting('site_tagline'))); ?>
<section class="section pt-5">
    <div class="container">
        <?php $this->load->view('public/profil/_nav'); ?>
        <div class="row g-5 align-items-center mt-1">
            <div class="col-lg-7 reveal">
                <span class="section-label">Gambaran Umum</span>
                <h2 class="section-title"><?= e($about ? $about['title'] : 'Tentang Fakultas') ?></h2>
                <div class="prose" data-testid="profile-about"><?= $about ? $about['content'] : '' ?></div>
            </div>
            <div class="col-lg-5 reveal"><div class="framed-img"><img src="<?= base_url('assets/img/about.jpg') ?>" alt="Kampus STABN Raden Wijaya" loading="lazy"></div></div>
        </div>
    </div>
</section>
<?php if ($welcome): ?>
<section class="section section-soft" id="sambutan" data-testid="profile-welcome">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-4 col-lg-3 text-center">
                <?= avatar($leader ? $leader['name'] : 'Dekan', $leader ? $leader['photo'] : '', 'leader-photo') ?>
                <?php if ($leader): ?><div class="welcome-sign dark mt-3"><strong><?= e($leader['name']) ?></strong><span><?= e($leader['position']) ?></span></div><?php endif; ?>
            </div>
            <div class="col-md-8 col-lg-9">
                <span class="section-label">Sambutan Pimpinan</span>
                <h2 class="section-title"><?= e($welcome['title']) ?></h2>
                <div class="prose"><?= $welcome['content'] ?></div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<section class="section">
    <div class="container">
        <div class="row g-4">
            <?php foreach (array(
                array('sejarah', 'bi-hourglass-split', 'Sejarah', 'Perjalanan dan latar belakang berdirinya fakultas.'),
                array('visi-misi', 'bi-bullseye', 'Visi & Misi', 'Arah, cita-cita, dan tujuan fakultas.'),
                array('struktur-organisasi', 'bi-diagram-3', 'Struktur Organisasi', 'Susunan pimpinan dan unit kerja.'),
                array('layanan', 'bi-mortarboard', 'Program & Layanan', 'Program PPG dan layanan akademik.'),
            ) as $c): ?>
                <div class="col-sm-6 col-lg-3">
                    <a href="<?= site_url('profil/' . $c[0]) ?>" class="info-tile link-tile h-100 reveal" data-testid="profile-tile-<?= $c[0] ?>"><i class="bi <?= $c[1] ?>"></i><h3><?= $c[2] ?></h3><p><?= $c[3] ?></p></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
