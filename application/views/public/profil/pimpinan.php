<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('crumbs' => array('Profil' => site_url('profil')))); ?>
<section class="section pt-5">
    <div class="container">
        <?php $this->load->view('public/profil/_nav'); ?>
        <div class="row g-4">
            <?php foreach ($profiles as $p): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="person-card h-100 reveal" data-testid="leader-card-<?= (int) $p['id'] ?>">
                        <?= avatar($p['name'], $p['photo'], 'person-photo') ?>
                        <div class="person-body">
                            <span class="person-pos"><?= e($p['position']) ?></span>
                            <h3><?= e($p['name']) ?></h3>
                            <?php if ($p['nip']): ?><small>NIP. <?= e($p['nip']) ?></small><?php endif; ?>
                            <?php if ($p['education']): ?><small><i class="bi bi-mortarboard"></i> <?= e($p['education']) ?></small><?php endif; ?>
                            <?php if ($p['bio']): ?><p><?= e($p['bio']) ?></p><?php endif; ?>
                            <?php if ($p['email']): ?><a href="mailto:<?= e($p['email']) ?>"><i class="bi bi-envelope"></i> <?= e($p['email']) ?></a><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (!$profiles): ?><p class="text-muted">Data pimpinan belum tersedia.</p><?php endif; ?>
        </div>
    </div>
</section>
