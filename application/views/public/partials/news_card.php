<?php defined('BASEPATH') OR exit('No direct script access allowed'); $types = news_types(); ?>
<article class="news-card h-100 reveal" data-testid="news-card-<?= (int) $item['id'] ?>">
    <a href="<?= e(news_url($item)) ?>" class="news-thumb" tabindex="-1" aria-hidden="true">
        <?= news_thumb($item) ?>
        <span class="news-type type-<?= e($item['type']) ?>"><?= e($types[$item['type']]) ?></span>
    </a>
    <div class="news-body">
        <div class="news-meta">
            <span><i class="bi bi-calendar3"></i><?= tgl_indo($item['type'] === 'agenda' && $item['event_date'] ? $item['event_date'] : $item['published_at']) ?></span>
            <?php if (!empty($item['category_name'])): ?>
                <a href="<?= site_url('berita/kategori/' . $item['category_slug']) ?>"><?= e($item['category_name']) ?></a>
            <?php endif; ?>
        </div>
        <h3><a href="<?= e(news_url($item)) ?>" data-testid="news-card-link-<?= (int) $item['id'] ?>"><?= e($item['title']) ?></a></h3>
        <p><?= e($item['excerpt'] ? excerpt($item['excerpt'], 130) : excerpt($item['content'], 130)) ?></p>
    </div>
</article>
