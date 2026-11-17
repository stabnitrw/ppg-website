<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k, $d = '') use ($item) { return set_value($k, $item ? (string) $item[$k] : $d); }; ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<?= form_open_multipart(current_url(), array('data-testid' => 'profile-form')) ?>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="name">Nama Lengkap & Gelar <span class="req">*</span></label><input type="text" class="form-control" id="name" name="name" value="<?= $v('name') ?>" required maxlength="150" data-testid="profile-name-input"></div>
                <div class="col-md-6"><label class="form-label" for="position">Jabatan <span class="req">*</span></label><input type="text" class="form-control" id="position" name="position" value="<?= $v('position') ?>" required maxlength="150" data-testid="profile-position-input"></div>
                <div class="col-md-6"><label class="form-label" for="nip">NIP</label><input type="text" class="form-control" id="nip" name="nip" value="<?= $v('nip') ?>" maxlength="30" data-testid="profile-nip-input"></div>
                <div class="col-md-6"><label class="form-label" for="email">Email</label><input type="email" class="form-control" id="email" name="email" value="<?= $v('email') ?>" maxlength="120" data-testid="profile-email-input"></div>
                <div class="col-md-8"><label class="form-label" for="education">Pendidikan</label><input type="text" class="form-control" id="education" name="education" value="<?= $v('education') ?>" maxlength="200" data-testid="profile-education-input"></div>
                <div class="col-md-4"><label class="form-label" for="sort_order">Urutan</label><input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= $v('sort_order', '0') ?>" min="0" data-testid="profile-order-input"></div>
                <div class="col-12"><label class="form-label" for="bio">Biografi Singkat</label><textarea class="form-control" id="bio" name="bio" rows="5" maxlength="2000" data-testid="profile-bio-input"><?= $v('bio') ?></textarea></div>
                <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?= (!$item || $item['is_active']) ? 'checked' : '' ?> data-testid="profile-active-switch"><label class="form-check-label" for="is_active">Tampilkan di website</label></div></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel">
            <h3 class="panel-title">Foto</h3>
            <?php if ($item && $item['photo']): ?>
                <img src="<?= e(upload_url('profiles', $item['photo'])) ?>" alt="" class="img-preview portrait mb-2">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="rm_photo"><label class="form-check-label small" for="rm_photo">Hapus foto</label></div>
            <?php endif; ?>
            <input type="file" class="form-control img-input" name="photo" accept=".jpg,.jpeg,.png" data-testid="profile-photo-input">
            <div class="form-text">Disarankan rasio potret 4:5.</div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-navy flex-fill" data-testid="profile-save-btn"><i class="bi bi-check2 me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/pimpinan') ?>" class="btn btn-light" data-testid="profile-cancel-btn">Batal</a>
        </div>
    </div>
</div>
<?= form_close() ?>
