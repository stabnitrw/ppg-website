<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="side-card">
    <h5>Cari Artikel</h5>
    <form action="<?= site_url(isset($base) ? $base : 'berita') ?>" method="get" role="search" data-testid="news-search-form">
        <div class="input-group">
            <input type="search" name="q" class="form-control" placeholder="Kata kunci…" value="<?= e(isset($q) ? $q : '') ?>" maxlength="100" aria-label="Kata kunci" data-testid="news-search-input">
            <button class="btn btn-navy" type="submit" aria-label="Cari" data-testid="news-search-btn"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div>
<div class="side-card">
    <h5>Kategori</h5>
    <ul class="list-unstyled cat-list mb-0">
        <?php foreach ($categories as $c): ?>
            <li><a href="<?= site_url('berita/kategori/' . $c['slug']) ?>" data-testid="category-link-<?= e($c['slug']) ?>"><?= e($c['name']) ?><span><?= (int) $c['total'] ?></span></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php if (!empty($agenda_side)): ?>
    <div class="side-card">
        <h5>Agenda Terdekat</h5>
        <?php foreach ($agenda_side as $g): ?>
            <a class="side-agenda" href="<?= e(news_url($g)) ?>"><span><?= tgl_indo($g['event_date']) ?></span><?= e($g['title']) ?></a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<div class="side-card side-cta d-none">
    <h5>Lapor Diri Peserta PPG</h5>
    <p class="small mb-3">Lakukan lapor diri secara daring dan pantau status verifikasi Anda.</p>
    <a href="<?= site_url('lapor-diri') ?>" class="btn btn-gold w-100" data-testid="sidebar-lapor-diri-btn">Lapor Diri</a>
</div>