<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin | <?= e(setting('site_short_name')) ?></title>
    <link rel="icon" href="<?= e(logo_url('logo_stabn')) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin.css') ?>?v=1" rel="stylesheet">
</head>
<body class="login-page">
<div class="login-wrap">
    <div class="login-side d-none d-lg-flex">
        <div>
            <img src="<?= e(logo_url('logo_stabn')) ?>" alt="" class="login-logo">
            <h1><?= e(setting('site_name')) ?></h1>
            <p><?= e(setting('institution_name')) ?></p>
        </div>
        <small>Panel administrasi untuk pengelolaan konten website dan layanan lapor diri peserta PPG.</small>
    </div>
    <div class="login-form">
        <div class="login-card">
            <div class="d-lg-none text-center mb-3"><img src="<?= e(logo_url('logo_stabn')) ?>" alt="" class="login-logo sm"></div>
            <h2>Masuk ke Panel Admin</h2>
            <p class="text-muted small mb-4">Gunakan akun yang diberikan oleh Administrator.</p>
            <?php if ($error): ?><div class="alert alert-danger small" role="alert" data-testid="login-error"><?= e($error) ?></div><?php endif; ?>
            <?= form_open('admin/login', array('data-testid' => 'login-form')) ?>
                <div class="mb-3">
                    <label class="form-label" for="login">Username atau Email</label>
                    <div class="input-icon"><i class="bi bi-person"></i><input type="text" class="form-control<?= form_error('login') ? ' is-invalid' : '' ?>" id="login" name="login" value="<?= set_value('login') ?>" required autofocus autocomplete="username" data-testid="login-username-input"></div>
                    <?= form_error('login', '<div class="invalid-feedback d-block">', '</div>') ?>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-icon"><i class="bi bi-lock"></i><input type="password" class="form-control<?= form_error('password') ? ' is-invalid' : '' ?>" id="password" name="password" required autocomplete="current-password" data-testid="login-password-input">
                        <button type="button" class="pw-toggle" data-target="#password" aria-label="Tampilkan password" data-testid="login-password-toggle"><i class="bi bi-eye"></i></button></div>
                    <?= form_error('password', '<div class="invalid-feedback d-block">', '</div>') ?>
                </div>
                <button type="submit" class="btn btn-navy w-100" data-testid="login-submit-btn">Masuk</button>
            <?= form_close() ?>
            <a href="<?= site_url() ?>" class="d-block text-center small mt-4" data-testid="login-back-site"><i class="bi bi-arrow-left me-1"></i>Kembali ke website</a>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.pw-toggle').forEach(function (b) {
    b.addEventListener('click', function () {
        var i = document.querySelector(b.dataset.target);
        i.type = i.type === 'password' ? 'text' : 'password';
        b.innerHTML = i.type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
});
</script>
</body>
</html>
