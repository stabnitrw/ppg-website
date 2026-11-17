<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k, $d = '') use ($item) { return set_value($k, $item ? (string) $item[$k] : $d); };
$role = $v('role', 'editor'); ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<div class="row"><div class="col-xl-7">
<div class="panel">
    <?= form_open(current_url(), array('data-testid' => 'user-form', 'autocomplete' => 'off')) ?>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Nama <span class="req">*</span></label><input class="form-control" id="name" name="name" value="<?= $v('name') ?>" required maxlength="100" data-testid="user-name-input"></div>
            <div class="col-md-6"><label class="form-label" for="username">Username <span class="req">*</span></label><input class="form-control" id="username" name="username" value="<?= $v('username') ?>" required minlength="4" maxlength="50" pattern="[A-Za-z0-9_\-]+" data-testid="user-username-input"></div>
            <div class="col-md-6"><label class="form-label" for="email">Email <span class="req">*</span></label><input type="email" class="form-control" id="email" name="email" value="<?= $v('email') ?>" required data-testid="user-email-input"></div>
            <div class="col-md-6"><label class="form-label" for="role">Peran</label>
                <select class="form-select" id="role" name="role" data-testid="user-role-select"><option value="editor" <?= $role === 'editor' ? 'selected' : '' ?>>Editor</option><option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrator</option></select></div>
            <div class="col-md-6"><label class="form-label" for="password">Password <?= $item ? '<small class="text-muted">(kosongkan jika tidak diubah)</small>' : '<span class="req">*</span>' ?></label><input type="password" class="form-control" id="password" name="password" minlength="8" maxlength="72" <?= $item ? '' : 'required' ?> autocomplete="new-password" data-testid="user-password-input"></div>
            <div class="col-md-6"><label class="form-label" for="password_confirm">Konfirmasi Password</label><input type="password" class="form-control" id="password_confirm" name="password_confirm" maxlength="72" autocomplete="new-password" data-testid="user-password-confirm-input"></div>
            <div class="col-12"><div class="form-text mt-0">Minimal 8 karakter, kombinasi huruf dan angka.</div></div>
            <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (!$item || $item['is_active']) ? 'checked' : '' ?> data-testid="user-active-switch"><label class="form-check-label" for="is_active">Akun aktif</label></div></div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-navy" data-testid="user-save-btn"><i class="bi bi-check2 me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/pengguna') ?>" class="btn btn-light" data-testid="user-cancel-btn">Batal</a>
        </div>
    <?= form_close() ?>
</div>
</div></div>
