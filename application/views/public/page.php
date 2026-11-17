<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero'); ?>
<section class="section pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <?php if (!empty($page['image'])): ?><figure class="article-cover"><img src="<?= e(upload_url('pages', $page['image'])) ?>" alt="<?= e($page['title']) ?>"></figure><?php endif; ?>
                <div class="prose" data-testid="page-content"><?= $page['content'] ?></div>
            </div>
        </div>
    </div>
</section>
