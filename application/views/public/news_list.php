<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('page_lead' => $type === 'agenda' ? 'Jadwal kegiatan akademik Fakultas PPG.' : 'Informasi terbaru seputar Fakultas Pendidikan Profesi Guru.'));
$tabs = array('berita' => 'Berita', 'pengumuman' => 'Pengumuman', 'agenda' => 'Agenda');
?>
<section class="section pt-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <ul class="nav nav-pills type-tabs">
                        <?php foreach ($tabs as $k => $label): ?>
                            <li class="nav-item"><a class="nav-link <?= $type === $k ? 'active' : '' ?>" href="<?= site_url($k) ?>" data-testid="news-tab-<?= $k ?>"><?= $label ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <small class="text-muted" data-testid="news-total"><?= (int) $total ?> artikel<?= $q !== '' ? ' untuk “' . e($q) . '”' : '' ?></small>
                </div>
                <?php if ($items): ?>
                    <div class="row g-4">
                        <?php foreach ($items as $item): ?>
                            <div class="col-md-6"><?php $this->load->view('public/partials/news_card', array('item' => $item)); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-5" data-testid="news-pagination"><?= $pagination ?></div>
                <?php else: ?>
                    <div class="empty-state" data-testid="news-empty"><i class="bi bi-search"></i><p>Tidak ada artikel yang ditemukan.</p></div>
                <?php endif; ?>
            </div>
            <aside class="col-lg-4">
                <?php $this->load->view('public/partials/news_sidebar', array('base' => $base, 'q' => $q, 'categories' => $categories, 'agenda_side' => $agenda_side)); ?>
            </aside>
        </div>
    </div>
</section>
