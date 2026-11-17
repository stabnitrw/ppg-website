<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="profile-nav" data-testid="profile-subnav">
    <a href="<?= site_url('profil') ?>" class="<?= $current === '' ? 'active' : '' ?>" data-testid="profile-nav-tentang">Tentang</a>
    <?php foreach ($sections as $slug => $label): ?>
        <a href="<?= site_url('profil/' . $slug) ?>" class="<?= $current === $slug ? 'active' : '' ?>" data-testid="profile-nav-<?= $slug ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="<?= site_url('kontak') ?>" data-testid="profile-nav-kontak">Kontak</a>
</div>
