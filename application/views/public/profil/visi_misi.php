<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('crumbs' => array('Profil' => site_url('profil'))));
$misi = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', setting('misi')))); ?>
<section class="section pt-5">
    <div class="container">
        <?php $this->load->view('public/profil/_nav'); ?>
        <div class="vision-hero reveal" data-testid="profile-vision">
            <span class="section-label light">Visi</span>
            <p><?= e(setting('visi')) ?></p>
        </div>
        <div class="row g-5 mt-1">
            <div class="col-lg-7">
                <span class="section-label">Misi</span>
                <h2 class="section-title">Misi Fakultas</h2>
                <ol class="mission-list" data-testid="profile-mission"><?php foreach ($misi as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ol>
            </div>
            <div class="col-lg-5">
                <span class="section-label">Tujuan</span>
                <h2 class="section-title"><?= e($page ? $page['title'] : 'Tujuan') ?></h2>
                <div class="prose"><?= $page ? $page['content'] : '' ?></div>
            </div>
        </div>
    </div>
</section>
