<?php defined('BASEPATH') or exit('No direct script access allowed');
$full_title = !empty($page_title) ? $page_title . ' | ' . setting('site_short_name') . ' ' . setting('institution_name') : setting('site_name') . ' – ' . setting('institution_name');
$desc = !empty($meta_description) ? $meta_description : setting('meta_description');
$seg1 = $this->uri->segment(1);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($full_title) ?></title>
    <meta name="description" content="<?= e($desc) ?>">
    <meta property="og:title" content="<?= e($full_title) ?>">
    <meta property="og:description" content="<?= e($desc) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e(current_url()) ?>">
    <?php if (!empty($meta_image)): ?>
        <meta property="og:image" content="<?= e($meta_image) ?>"><?php endif; ?>
    <link rel="canonical" href="<?= e(current_url()) ?>">
    <link rel="icon" href="<?= e(logo_url('logo_stabn')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>?v=5" rel="stylesheet">
</head>

<body>
    <a class="visually-hidden-focusable skip-link" href="#konten">Langsung ke konten utama</a>

    <div class="topbar d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <span><?= e(setting('topbar_text')) ?></span>
            <div class="d-flex gap-4 align-items-center">
                <span><i class="bi bi-telephone me-1"></i><?= e(setting('phone')) ?></span>
                <span><i class="bi bi-envelope me-1"></i><?= e(setting('email')) ?></span>
                <!-- <a href="<?= site_url('admin/login') ?>" data-testid="topbar-admin-login" aria-label="Login Admin"><i class="bi bi-person-lock"></i></a> -->
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg site-nav sticky-top" aria-label="Navigasi utama" data-testid="main-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url() ?>" data-testid="nav-brand">
                <img src="<?= e(base_url('assets/img/logo-kemenag.png')) ?>" alt="Logo Kementerian Agama" class="brand-logo">
                <img src="<?= e(logo_url('logo_stabn')) ?>" alt="Logo STABN Raden Wijaya" class="brand-logo">
                <img src="<?= e(base_url('assets/img/logo-ppg-transformasi.png')) ?>" alt="Logo PPG Transformasi Pendidikan" class="brand-logo brand-logo-ppg">
                <span class="brand-text">
                    <strong><?= e(setting('site_short_name')) ?> <?= e(preg_replace('/\s+Wonogiri$/', '', setting('institution_name'))) ?></strong>
                    <small><?= e(setting('site_name')) ?></small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu" data-testid="nav-toggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link <?= $seg1 === null ? 'active' : '' ?>" href="<?= site_url() ?>" data-testid="nav-home">Beranda</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $seg1 === 'profil' ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-testid="nav-profil">Profil</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('profil') ?>" data-testid="nav-profil-tentang">Tentang Fakultas</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('profil/sejarah') ?>" data-testid="nav-profil-sejarah">Sejarah</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('profil/visi-misi') ?>" data-testid="nav-profil-visi">Visi &amp; Misi</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('profil/struktur-organisasi') ?>" data-testid="nav-profil-struktur">Struktur Organisasi</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('profil/pimpinan') ?>" data-testid="nav-profil-pimpinan">Pimpinan</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('profil/layanan') ?>" data-testid="nav-profil-layanan">Program &amp; Layanan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($seg1, array('berita', 'pengumuman', 'agenda'), TRUE) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-testid="nav-informasi">Informasi</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('berita') ?>" data-testid="nav-berita">Berita</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('pengumuman') ?>" data-testid="nav-pengumuman">Pengumuman</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('agenda') ?>" data-testid="nav-agenda">Agenda Akademik</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= $seg1 === 'lapor-diri' ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-testid="nav-layanan">Layanan</a>
                        <ul class="dropdown-menu">
                            <!-- <li><a class="dropdown-item" href="<?= site_url('lapor-diri') ?>" data-testid="nav-layanan-lapor">Lapor Diri</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('lapor-diri/cek') ?>" data-testid="nav-layanan-cek">Cek Status Lapor Diri</a></li> -->
                            <?php foreach (setting_lines('service_links') as $i => $l): if (isset($l[2]) && preg_match('#^https?://#', $l[2])): ?>
                                    <li><a class="dropdown-item" href="<?= e($l[2]) ?>" target="_blank" rel="noopener" data-testid="nav-layanan-link-<?= $i ?>"><?= e($l[0]) ?> <i class="bi bi-box-arrow-up-right small ms-1"></i></a></li>
                            <?php endif;
                            endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link <?= $seg1 === 'kontak' ? 'active' : '' ?>" href="<?= site_url('kontak') ?>" data-testid="nav-kontak">Kontak</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0 d-none">
                        <a class="btn btn-navy px-4" href="<?= site_url('lapor-diri') ?>" data-testid="nav-lapor-diri">Lapor Diri <?= date('Y') ?><i class="bi bi-arrow-right ms-2"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main id="konten">