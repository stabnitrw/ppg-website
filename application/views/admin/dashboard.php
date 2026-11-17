<?php defined('BASEPATH') or exit('No direct script access allowed');
$types = news_types(); ?>
<div class="welcome-bar">
    <div>
        <h2>Selamat datang, <?= e($user['name']) ?></h2>
        <p><?= tgl_indo(date('Y-m-d')) ?> &middot; Ringkasan aktivitas website fakultas.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= site_url('admin/berita/tambah') ?>" class="btn btn-gold" data-testid="dash-add-news-btn"><i class="bi bi-plus-lg me-1"></i>Tulis Berita</a>
    </div>
</div>
<div class="row g-3 mb-4">
    <?php foreach (
        array(
            array('stat-news', 'bi-newspaper', 'Berita Terbit', $total_news, 'admin/berita?type=berita'),
            array('stat-announce', 'bi-megaphone', 'Pengumuman', $total_announce, 'admin/berita?type=pengumuman'),
            array('stat-agenda', 'bi-calendar-event', 'Agenda', $total_agenda, 'admin/berita?type=agenda'),
        ) as $c
    ): ?>
        <div class="col-6 col-xl-4">
            <a href="<?= site_url($c[4]) ?>" class="stat-card" data-testid="<?= $c[0] ?>">
                <i class="bi <?= $c[1] ?>"></i>
                <div><span><?= $c[2] ?></span><strong><?= (int) $c[3] ?></strong></div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel">
            <div class="panel-head">
                <h3>Konten Terbaru</h3><a href="<?= site_url('admin/berita') ?>" class="small">Kelola</a>
            </div>
            <ul class="list-plain">
                <?php foreach ($recent_news as $n): ?>
                    <li><a href="<?= site_url('admin/berita/edit/' . $n['id']) ?>"><?= e($n['title']) ?></a>
                        <span><span class="badge text-bg-light"><?= e($types[$n['type']]) ?></span> <?= $n['status'] === 'draft' ? '<span class="badge text-bg-warning">Draf</span>' : '' ?> <?= tgl_indo($n['published_at']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel">
            <div class="panel-head">
                <h3>Ringkasan</h3>
            </div>
            <ul class="list-plain">
                <li><span>Draf belum terbit</span><strong><?= (int) $total_drafts ?></strong></li>
                <li><span>Login terakhir</span><strong class="small"><?= tgl_indo($user['last_login'], TRUE) ?></strong></li>
            </ul>
        </div>
    </div>
</div>