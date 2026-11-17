<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url() ?>">Beranda</a></li>
                <?php if (!empty($crumbs)): foreach ($crumbs as $label => $url): ?>
                    <li class="breadcrumb-item"><a href="<?= e($url) ?>"><?= e($label) ?></a></li>
                <?php endforeach; endif; ?>
                <li class="breadcrumb-item active" aria-current="page"><?= e(isset($crumb_title) ? $crumb_title : $page_title) ?></li>
            </ol>
        </nav>
        <h1 data-testid="page-title"><?= e($page_title) ?></h1>
        <?php if (!empty($page_lead)): ?><p class="lead"><?= e($page_lead) ?></p><?php endif; ?>
    </div>
</section>
