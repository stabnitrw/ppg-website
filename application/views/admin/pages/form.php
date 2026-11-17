<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k) use ($item) { return set_value($k, $item ? (string) $item[$k] : '', FALSE); }; ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<?= form_open_multipart(current_url(), array('data-testid' => 'page-form')) ?>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel">
            <div class="mb-3">
                <label class="form-label" for="title">Judul <span class="req">*</span></label>
                <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= e($v('title')) ?>" maxlength="200" required data-testid="page-title-input">
            </div>
            <?php if (!$item || !$item['is_system']): ?>
            <div class="mb-3">
                <label class="form-label" for="slug">Slug URL <span class="req">*</span></label>
                <div class="input-group"><span class="input-group-text small">/halaman/</span><input type="text" class="form-control" id="slug" name="slug" value="<?= e($v('slug')) ?>" required pattern="[a-z0-9\-_]+" data-testid="page-slug-input"></div>
                <div class="form-text">Huruf kecil, angka, dan tanda hubung. Contoh: <code>fasilitas-kampus</code></div>
            </div>
            <?php endif; ?>
            <label class="form-label" for="content">Isi <span class="req">*</span></label>
            <textarea class="form-control rich-editor" id="content" name="content" rows="16" data-testid="page-content-input"><?= e($v('content')) ?></textarea>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel">
            <h3 class="panel-title">Gambar Halaman</h3>
            <?php if ($item && $item['image']): ?>
                <img src="<?= e(upload_url('pages', $item['image'])) ?>" alt="" class="img-preview mb-2">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="remove_image" value="1" id="rm_img"><label class="form-check-label small" for="rm_img">Hapus gambar</label></div>
            <?php endif; ?>
            <input type="file" class="form-control img-input" name="image" accept=".jpg,.jpeg,.png" data-testid="page-image-input">
            <div class="form-text">Opsional. Ditampilkan di atas isi halaman kustom.</div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-navy flex-fill" data-testid="page-save-btn"><i class="bi bi-check2 me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/halaman') ?>" class="btn btn-light" data-testid="page-cancel-btn">Batal</a>
        </div>
    </div>
</div>
<?= form_close() ?>
