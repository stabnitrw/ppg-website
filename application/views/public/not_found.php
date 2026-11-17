<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<section class="section notfound" data-testid="not-found-page">
    <div class="container text-center">
        <span class="nf-code">404</span>
        <h1>Halaman tidak ditemukan</h1>
        <p class="text-muted mb-4">Halaman yang Anda cari mungkin telah dipindahkan atau tidak tersedia.</p>
        <a href="<?= site_url() ?>" class="btn btn-navy" data-testid="not-found-home-btn">Kembali ke Beranda</a>
    </div>
</section>
