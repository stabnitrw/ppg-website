<?php defined('BASEPATH') OR exit('No direct script access allowed');
$types = news_types();
$list_url = $item['type'] === 'berita' ? site_url('berita') : site_url($item['type']);
$this->load->view('public/partials/page_hero', array('crumbs' => array($types[$item['type']] => $list_url), 'crumb_title' => excerpt($item['title'], 50), 'page_title' => $item['title']));
?>
<section class="section pt-5">
    <div class="container">
        <div class="row g-5">
            <article class="col-lg-8" data-testid="news-detail">
                <div class="article-meta">
                    <span class="badge-type type-<?= e($item['type']) ?>"><?= e($types[$item['type']]) ?></span>
                    <span><i class="bi bi-calendar3"></i><?= tgl_indo($item['published_at']) ?></span>
                    <?php if ($item['category_name']): ?><a href="<?= site_url('berita/kategori/' . $item['category_slug']) ?>"><i class="bi bi-tag"></i><?= e($item['category_name']) ?></a><?php endif; ?>
                    <?php if ($item['author_name']): ?><span><i class="bi bi-person"></i><?= e($item['author_name']) ?></span><?php endif; ?>
                    <span><i class="bi bi-eye"></i><?= (int) $item['views'] ?> kali dibaca</span>
                </div>
                <?php if ($item['type'] === 'agenda' && $item['event_date']): ?>
                    <div class="event-box" data-testid="event-info">
                        <div><i class="bi bi-calendar-event"></i><strong>Tanggal</strong><?= tgl_indo($item['event_date']) ?></div>
                        <?php if ($item['event_location']): ?><div><i class="bi bi-geo-alt"></i><strong>Lokasi</strong><?= e($item['event_location']) ?></div><?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($item['thumbnail']): ?>
                    <figure class="article-cover"><img src="<?= e(upload_url('news', $item['thumbnail'])) ?>" alt="<?= e($item['title']) ?>"></figure>
                <?php endif; ?>
                <div class="prose article-content"><?= $item['content'] ?></div>
                <div class="share-bar">
                    <span>Bagikan:</span>
                    <a href="https://wa.me/?text=<?= rawurlencode($item['title'] . ' ' . current_url()) ?>" target="_blank" rel="noopener" aria-label="WhatsApp" data-testid="share-whatsapp"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode(current_url()) ?>" target="_blank" rel="noopener" aria-label="Facebook" data-testid="share-facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?= rawurlencode(current_url()) ?>&text=<?= rawurlencode($item['title']) ?>" target="_blank" rel="noopener" aria-label="X" data-testid="share-x"><i class="bi bi-twitter-x"></i></a>
                    <button type="button" class="copy-link" data-url="<?= e(current_url()) ?>" aria-label="Salin tautan" data-testid="share-copy"><i class="bi bi-link-45deg"></i></button>
                </div>
                <?php if ($related): ?>
                    <h3 class="related-title">Artikel Terkait</h3>
                    <div class="row g-3">
                        <?php foreach ($related as $r): $r['type'] = $item['type']; ?>
                            <div class="col-md-4">
                                <a href="<?= e(news_url($r)) ?>" class="related-card" data-testid="related-<?= e($r['slug']) ?>">
                                    <div class="related-thumb"><?= news_thumb($r) ?></div>
                                    <small><?= tgl_indo($r['published_at']) ?></small>
                                    <h4><?= e($r['title']) ?></h4>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
            <aside class="col-lg-4">
                <?php $this->load->view('public/partials/news_sidebar', array('base' => 'berita', 'q' => '', 'categories' => $categories)); ?>
            </aside>
        </div>
    </div>
</section>
