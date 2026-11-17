<?php defined('BASEPATH') or exit('No direct script access allowed');
$s2 = $this->uri->segment(2);
$is_admin = $user['role'] === 'admin';
$menu = array(
    array('', 'bi-grid-1x2', 'Dashboard', TRUE),
    // array('lapor-diri', 'bi-person-vcard', 'Lapor Diri', TRUE),
    array('_', 'Konten'),
    array('berita', 'bi-newspaper', 'Berita & Pengumuman', TRUE),
    array('kategori', 'bi-tags', 'Kategori Berita', TRUE),
    array('halaman', 'bi-file-earmark-text', 'Halaman & Profil', TRUE),
    array('pimpinan', 'bi-person-badge', 'Profil Pimpinan', TRUE),
    array('struktur', 'bi-diagram-3', 'Struktur Organisasi', TRUE),
    array('_', 'Sistem'),
    array('pengaturan', 'bi-gear', 'Pengaturan Website', $is_admin),
    array('pengguna', 'bi-people', 'Pengguna Admin', $is_admin),
    array('akun', 'bi-person-circle', 'Akun Saya', TRUE),
);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($page_title) ?> | Admin <?= e(setting('site_short_name')) ?></title>
    <link rel="icon" href="<?= e(logo_url('logo_stabn')) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <?php if (!empty($editor)): ?>
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet"><?php endif; ?>
    <link href="<?= base_url('assets/css/admin.css') ?>?v=1" rel="stylesheet">
    <meta name="csrf-name" content="<?= $this->security->get_csrf_token_name() ?>">
    <meta name="csrf-hash" content="<?= $this->security->get_csrf_hash() ?>">
</head>

<body class="admin">
    <aside class="sidebar" id="sidebar" data-testid="admin-sidebar">
        <a href="<?= site_url('admin') ?>" class="sidebar-brand">
            <img src="<?= e(logo_url('logo_stabn')) ?>" alt="">
            <span><strong><?= e(setting('site_short_name')) ?></strong><small>Panel Administrasi</small></span>
        </a>
        <nav class="sidebar-nav">
            <?php foreach ($menu as $m): if ($m[0] === '_'): ?>
                    <div class="nav-heading"><?= $m[1] ?></div>
                <?php elseif ($m[3]): $active = ($m[0] === '' ? ($s2 === null || $s2 === 'dashboard') : $s2 === $m[0]) || ($m[0] === 'lapor-diri' && $s2 === 'lapor_diri'); ?>
                    <a href="<?= site_url('admin/' . $m[0]) ?>" class="<?= $active ? 'active' : '' ?>" data-testid="sidebar-<?= $m[0] === '' ? 'dashboard' : $m[0] ?>"><i class="bi <?= $m[1] ?>"></i><?= $m[2] ?></a>
            <?php endif;
            endforeach; ?>
        </nav>
        <div class="sidebar-foot">
            <a href="<?= site_url() ?>" target="_blank" rel="noopener" data-testid="sidebar-view-site"><i class="bi bi-box-arrow-up-right"></i>Lihat Website</a>
        </div>
    </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="main">
        <header class="topnav">
            <button class="btn btn-icon d-lg-none" id="sidebarToggle" aria-label="Menu" data-testid="admin-sidebar-toggle"><i class="bi bi-list"></i></button>
            <h1 class="topnav-title" data-testid="admin-page-title"><?= e($page_title) ?></h1>
            <div class="dropdown ms-auto">
                <button class="btn user-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-testid="admin-user-menu">
                    <span class="user-avatar"><?= e(strtoupper(mb_substr($user['name'], 0, 1))) ?></span>
                    <span class="d-none d-sm-inline"><?= e($user['name']) ?></span>
                    <span class="badge role-<?= e($user['role']) ?>" data-testid="admin-user-role"><?= $user['role'] === 'admin' ? 'Administrator' : 'Editor' ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= site_url('admin/akun') ?>" data-testid="menu-account"><i class="bi bi-person me-2"></i>Akun Saya</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><?= form_open('admin/logout', array('class' => 'm-0')) ?><button type="submit" class="dropdown-item text-danger" data-testid="admin-logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button><?= form_close() ?></li>
                </ul>
            </div>
        </header>
        <div class="content">
            <?= flash_alerts() ?>