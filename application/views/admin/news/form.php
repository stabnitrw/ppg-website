<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k, $d = '') use ($item) { return set_value($k, $item ? (string) $item[$k] : $d, FALSE); };
$pub = $item && $item['published_at'] ? date('Y-m-d\TH:i', strtotime($item['published_at'])) : date('Y-m-d\TH:i');
$type = $v('type', 'berita'); ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<?= form_open_multipart(current_url(), array('data-testid' => 'news-form')) ?>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel">
            <div class="mb-3">
                <label class="form-label" for="title">Judul <span class="req">*</span></label>
                <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= e($v('title')) ?>" maxlength="200" required data-testid="news-title-input">
            </div>
            <div class="mb-3">
                <label class="form-label" for="slug">Slug URL</label>
                <div class="input-group"><span class="input-group-text small">/berita/</span><input type="text" class="form-control" id="slug" name="slug" value="<?= e($v('slug')) ?>" placeholder="otomatis dari judul" data-testid="news-slug-input"></div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="excerpt">Ringkasan</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="2" maxlength="300" placeholder="Opsional – otomatis dari isi jika dikosongkan" data-testid="news-excerpt-input"><?= e($v('excerpt')) ?></textarea>
            </div>
            <div>
                <label class="form-label" for="content">Isi <span class="req">*</span></label>
                <textarea class="form-control rich-editor" id="content" name="content" rows="14" data-testid="news-content-input"><?= e($v('content')) ?></textarea>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel">
            <h3 class="panel-title">Publikasi</h3>
            <div class="mb-3">
                <label class="form-label" for="type">Jenis</label>
                <select class="form-select" id="type" name="type" data-testid="news-type-select">
                    <?php foreach (news_types() as $k => $t): ?><option value="<?= $k ?>" <?= $type === $k ? 'selected' : '' ?>><?= $t ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="category_id">Kategori</label>
                <select class="form-select" id="category_id" name="category_id" data-testid="news-category-select">
                    <option value="0">— Tanpa kategori —</option>
                    <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>" <?= (int) $v('category_id') === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="status">Status</label>
                <select class="form-select" id="status" name="status" data-testid="news-status-select">
                    <option value="published" <?= $v('status', 'published') === 'published' ? 'selected' : '' ?>>Terbit</option>
                    <option value="draft" <?= $v('status', 'published') === 'draft' ? 'selected' : '' ?>>Draf</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="published_at">Tanggal Terbit</label>
                <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="<?= e(set_value('published_at', $pub, FALSE)) ?>" data-testid="news-published-at-input">
            </div>
            <div id="agenda-fields" class="<?= $type === 'agenda' ? '' : 'd-none' ?>">
                <div class="mb-3">
                    <label class="form-label" for="event_date">Tanggal Kegiatan <span class="req">*</span></label>
                    <input type="date" class="form-control" id="event_date" name="event_date" value="<?= e($v('event_date')) ?>" data-testid="news-event-date-input">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="event_location">Lokasi Kegiatan</label>
                    <input type="text" class="form-control" id="event_location" name="event_location" value="<?= e($v('event_location')) ?>" maxlength="150" data-testid="news-event-location-input">
                </div>
            </div>
        </div>
        <div class="panel mt-4">
            <h3 class="panel-title">Gambar Utama</h3>
            <?php if ($item && $item['thumbnail']): ?>
                <img src="<?= e(upload_url('news', $item['thumbnail'])) ?>" alt="" class="img-preview mb-2">
                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="remove_thumbnail" value="1" id="rm_thumb" data-testid="news-remove-thumb"><label class="form-check-label small" for="rm_thumb">Hapus gambar</label></div>
            <?php endif; ?>
            <input type="file" class="form-control img-input" name="thumbnail" accept=".jpg,.jpeg,.png" data-testid="news-thumbnail-input">
            <div class="form-text">JPG/PNG, maks. <?= (int) setting('image_max_size_kb', 2048) ?> KB. Otomatis diperkecil ke lebar <?= (int) setting('image_max_width', 1200) ?> px.</div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-navy flex-fill" data-testid="news-save-btn"><i class="bi bi-check2 me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/berita') ?>" class="btn btn-light" data-testid="news-cancel-btn">Batal</a>
        </div>
    </div>
</div>
<?= form_close() ?>
