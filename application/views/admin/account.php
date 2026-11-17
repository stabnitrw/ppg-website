<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<div class="row"><div class="col-xl-7">
<div class="panel">
    <?= form_open('admin/akun', array('data-testid' => 'account-form', 'autocomplete' => 'off')) ?>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Nama</label><input class="form-control" id="name" name="name" value="<?= set_value('name', $user['name']) ?>" required data-testid="account-name-input"></div>
            <div class="col-md-6"><label class="form-label" for="email">Email</label><input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $user['email']) ?>" required data-testid="account-email-input"></div>
            <div class="col-md-6"><label class="form-label">Username</label><input class="form-control" value="<?= e($user['username']) ?>" disabled></div>
            <div class="col-md-6"><label class="form-label">Peran</label><input class="form-control" value="<?= $user['role'] === 'admin' ? 'Administrator' : 'Editor' ?>" disabled></div>
            <div class="col-12"><hr class="my-1"><h3 class="panel-title mt-2">Ganti Password</h3></div>
            <div class="col-md-6"><label class="form-label" for="new_password">Password Baru</label><input type="password" class="form-control" id="new_password" name="new_password" minlength="8" maxlength="72" autocomplete="new-password" data-testid="account-new-password"></div>
            <div class="col-md-6"><label class="form-label" for="new_password_confirm">Konfirmasi Password Baru</label><input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" maxlength="72" autocomplete="new-password" data-testid="account-new-password-confirm"></div>
            <div class="col-12"><label class="form-label" for="current_password">Password Saat Ini <span class="req">*</span></label><input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password" data-testid="account-current-password"><div class="form-text">Wajib diisi untuk menyimpan perubahan apa pun.</div></div>
        </div>
        <button type="submit" class="btn btn-navy mt-4" data-testid="account-save-btn"><i class="bi bi-check2 me-1"></i>Simpan Perubahan</button>
    <?= form_close() ?>
</div>
</div></div>
