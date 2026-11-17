<?php defined('BASEPATH') or exit('No direct script access allowed');
$hero = setting('hero_image') ? upload_url('settings', setting('hero_image')) : base_url('assets/img/hero.jpg');
$slides = $slides ? $slides : array();
array_unshift($slides, array('title' => setting('site_name'), 'slug' => '', 'thumbnail' => '', 'published_at' => '', 'img' => $hero));
$misi = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', setting('misi'))));
// $stages = setting_lines('ppg_stages');
$stages = null;
$docs = doc_requirements();
?>
<section class="hero-slider" data-testid="home-hero">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-inner">
            <?php foreach ($slides as $i => $s): $img = isset($s['img']) ? $s['img'] : upload_url('news', $s['thumbnail']); ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" style="background-image:url('<?= e($img) ?>')" data-testid="hero-slide-<?= $i ?>">
                    <div class="slide-caption d-none d-md-block">
                        <?php if ($s['slug']): ?><a href="<?= site_url('berita/' . $s['slug']) ?>"><strong><?= e(excerpt($s['title'], 60)) ?></strong></a><span><?= tgl_indo($s['published_at']) ?></span>
                        <?php else: ?><strong><?= e(setting('institution_name')) ?></strong><span>The Institution Of Wisdom and Compassion</span><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hero-overlay">
            <div class="container">
                <span class="hero-badge reveal"><i class="dot"></i><?= e(setting('hero_badge')) ?></span>
                <h1 class="reveal" data-testid="hero-title"><?= e(setting('hero_title')) ?></h1>
                <p class="hero-lead reveal"><?= e(setting('hero_subtitle')) ?></p>
                <div class="d-flex flex-wrap gap-2 reveal">
                    <!-- <a href="<?= site_url('lapor-diri') ?>" class="btn btn-gold" data-testid="hero-lapor-diri-btn">Lapor Diri <?= date('Y') ?><i class="bi bi-arrow-right ms-2"></i></a> -->
                    <a href="<?= site_url('profil') ?>" class="btn btn-glass" data-testid="hero-profil-btn">Profil Program Studi</a>
                </div>
            </div>
        </div>
        <?php if (count($slides) > 1): ?>
            <div class="hero-controls">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Sebelumnya" data-testid="hero-prev"><i class="bi bi-chevron-left"></i></button>
                <div class="carousel-indicators">
                    <?php foreach ($slides as $i => $s): ?><button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button><?php endforeach; ?>
                </div>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Berikutnya" data-testid="hero-next"><i class="bi bi-chevron-right"></i></button>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="lapor-float">
    <div class="container">
        <div class="lapor-card reveal" data-testid="home-lapor-card">
            <div class="lc-info">
                <span class="section-label">PPG <?= date('Y') ?></span>
                <h2>Lapor Diri Peserta PPG</h2>
                <strong class="lc-period" data-testid="lapor-period"><?= e(setting('lapor_diri_period')) ?></strong>
                <small><?= e(setting('lapor_diri_hours')) ?></small>
                <span class="lc-status <?= setting('lapor_diri_open') === '1' ? 'open' : 'closed' ?>" data-testid="lapor-open-status"><i class="dot"></i><?= setting('lapor_diri_open') === '1' ? 'Pendaftaran dibuka' : 'Pendaftaran ditutup' ?></span>
            </div>
            <ol class="lc-steps d-none">
                <li><span>1</span>
                    <div><strong>Isi biodata</strong><small>Data diri, NIK, dan kontak aktif</small></div>
                </li>
                <li><span>2</span>
                    <div><strong>Data akademik</strong><small>Program, perguruan tinggi, dan tahun lulus</small></div>
                </li>
                <li><span>3</span>
                    <div><strong>Unggah <?= count($docs) ?> berkas</strong><small><?= e(implode(', ', array_map(function ($d) {
                                                                                        return preg_replace('/\s*\(.*\)/', '', $d['label']);
                                                                                    }, $docs))) ?></small></div>
                </li>
            </ol>
            <div class="lc-cta d-none">
                <a href="<?= site_url('lapor-diri') ?>" class="btn btn-navy btn-lg w-100" data-testid="cta-lapor-diri-btn">Mulai Lapor Diri<i class="bi bi-arrow-right ms-2"></i></a>
                <p>Sudah lapor diri? <a href="<?= site_url('lapor-diri/cek') ?>" data-testid="cta-check-status-btn">Cek status</a></p>
            </div>
        </div>
    </div>
</section>

<section class="section section-soft d-none" data-testid="home-stats">
    <div class="container">
        <div class="sec-head">
            <h2><?= e(setting('stats_title')) ?></h2>
            <p><?= e(setting('stats_subtitle')) ?></p>
        </div>
        <div class="row g-3">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="col-6 col-lg-3">
                    <div class="num-card reveal" data-testid="home-stat-<?= $i ?>"><strong><?= e(setting('stat_' . $i . '_value')) ?></strong><span><?= e(setting('stat_' . $i . '_label')) ?></span></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="section" data-testid="home-profile-video">
    <div class="container">
        <div class="sec-head">
            <h2>Video Profil Program Studi</h2>
        </div>
        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-lg reveal">
            <iframe src="https://drive.google.com/file/d/1_22DdLFikWuuxXC7ZjsWFdi6SyNGLFsA/preview" title="Video Profil Program Studi" loading="lazy" allow="encrypted-media; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</section>

<?php if ($stages): ?>
    <section class="section section-soft" data-testid="home-stages">
        <div class="container">
            <div class="sec-head">
                <h2>Tahapan Program PPG</h2>
                <p>Urutan kegiatan peserta dari lapor diri hingga sertifikat pendidik.</p>
            </div>
            <ol class="stages">
                <?php foreach ($stages as $i => $st): ?>
                    <li class="reveal <?= $i === 0 ? 'current' : '' ?>" data-testid="home-stage-<?= $i + 1 ?>"><span><?= $i + 1 ?></span><strong><?= e($st[0]) ?></strong><small><?= e(isset($st[1]) ? $st[1] : '') ?></small></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>
<?php endif; ?>

<section class="section" data-testid="home-news">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="sec-head mb-0">
                <h2>Berita Terbaru</h2>
                <p>Kabar kegiatan dan informasi dari Program Studi PPG.</p>
            </div>
            <a href="<?= site_url('berita') ?>" class="link-arrow" data-testid="home-all-news-btn">Semua berita <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($news as $n): ?>
                <div class="col-sm-6 col-lg-3"><?php $this->load->view('public/partials/news_card', array('item' => $n)); ?></div>
            <?php endforeach; ?>
            <?php if (!$news): ?><p class="text-muted">Belum ada berita.</p><?php endif; ?>
        </div>
    </div>
</section>

<section class="section section-soft" data-testid="home-info">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="panel-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="panel-h">Pengumuman</h3><a href="<?= site_url('pengumuman') ?>" class="link-arrow small" data-testid="home-all-announcements">Lihat semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="announce-list">
                        <?php foreach ($announcements as $a): ?>
                            <a href="<?= e(news_url($a)) ?>" class="announce-item" data-testid="home-announcement-<?= (int) $a['id'] ?>">
                                <div class="date-block"><strong><?= date('d', strtotime($a['published_at'])) ?></strong><span><?= substr(tgl_indo($a['published_at']), strpos(tgl_indo($a['published_at']), ' ') + 1, 3) ?></span></div>
                                <div>
                                    <h4><?= e($a['title']) ?></h4>
                                    <p><?= e(excerpt($a['excerpt'] ? $a['excerpt'] : $a['content'], 110)) ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <?php if (!$announcements): ?><p class="text-muted mb-0">Belum ada pengumuman.</p><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="panel-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="panel-h">Agenda Akademik</h3><a href="<?= site_url('agenda') ?>" class="link-arrow small" data-testid="home-all-agenda">Semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <?php foreach ($agenda as $g): ?>
                        <a href="<?= e(news_url($g)) ?>" class="agenda-row" data-testid="home-agenda-<?= (int) $g['id'] ?>">
                            <span class="agenda-date"><i class="bi bi-calendar-event"></i><?= tgl_indo($g['event_date']) ?></span>
                            <h4><?= e($g['title']) ?></h4>
                            <?php if ($g['event_location']): ?><small><i class="bi bi-geo-alt"></i> <?= e($g['event_location']) ?></small><?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (!$agenda): ?><p class="text-muted mb-0">Belum ada agenda.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($welcome): ?>
    <section class="section" data-testid="home-welcome">
        <div class="container">
            <div class="welcome-card reveal">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 col-lg-3 text-center"><?= avatar($leader ? $leader['name'] : 'Dekan', $leader ? $leader['photo'] : '', 'leader-photo') ?></div>
                    <div class="col-md-8 col-lg-9">
                        <span class="section-label"><?= e($welcome['title']) ?></span>
                        <blockquote class="welcome-quote"><?= e(excerpt($welcome['content'], 380)) ?></blockquote>
                        <?php if ($leader): ?><div class="welcome-sign"><strong><?= e($leader['name']) ?></strong><span><?= e($leader['position']) ?></span></div><?php endif; ?>
                        <a href="<?= site_url('profil') ?>#sambutan" class="link-arrow mt-3" data-testid="home-welcome-more">Baca sambutan lengkap <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section pt-0" data-testid="home-vision">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="accred-card h-100 reveal" data-testid="home-accreditation">
                    <span class="section-label light">Akreditasi Program Studi</span>
                    <h3><?= e(setting('akreditasi_status')) ?></h3>
                    <dl>
                        <?php foreach (array('akreditasi_lembaga' => 'Lembaga', 'akreditasi_sk' => 'Nomor SK', 'akreditasi_masa' => 'Masa berlaku') as $k => $label): if (setting($k)): ?>
                                <div>
                                    <dt><?= $label ?></dt>
                                    <dd><?= e(setting($k)) ?></dd>
                                </div>
                        <?php endif;
                        endforeach; ?>
                    </dl>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="vision-card h-100 reveal">
                    <span class="section-label">Visi Program Studi</span>
                    <blockquote><?= e(setting('visi')) ?></blockquote>
                    <ol class="mini-mission"><?php foreach (array_slice($misi, 0, 3) as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ol>
                    <div class="d-flex flex-wrap gap-4"><a href="<?= site_url('profil/visi-misi') ?>" class="link-arrow" data-testid="home-vision-more">Visi dan misi lengkap <i class="bi bi-arrow-right"></i></a><a href="<?= site_url('profil/struktur-organisasi') ?>" class="link-arrow" data-testid="home-structure-link">Struktur organisasi <i class="bi bi-arrow-right"></i></a></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $links = setting_lines('service_links');
if ($links): ?>
    <section class="section pt-0" data-testid="home-links">
        <div class="container">
            <h3 class="panel-h mb-3">Tautan Layanan</h3>
            <div class="row g-3">
                <?php foreach ($links as $i => $l): $ext = isset($l[2]) && preg_match('#^https?://#', $l[2]); ?>
                    <div class="col-sm-6 col-lg-3">
                        <a href="<?= e(link_url(isset($l[2]) ? $l[2] : '')) ?>" class="link-card" <?= $ext ? 'target="_blank" rel="noopener"' : '' ?> data-testid="service-link-<?= $i ?>">
                            <div><strong><?= e($l[0]) ?></strong><small><?= e(isset($l[1]) ? $l[1] : '') ?></small></div><i class="bi <?= $ext ? 'bi-box-arrow-up-right' : 'bi-arrow-right' ?>"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>