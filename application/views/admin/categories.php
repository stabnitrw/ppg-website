<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel">
            <h3 class="panel-title"><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
            <?= form_open('admin/kategori/simpan', array('data-testid' => 'category-form')) ?>
                <input type="hidden" name="id" value="<?= $edit ? (int) $edit['id'] : 0 ?>">
                <div class="mb-3">
                    <label class="form-label" for="name">Nama Kategori <span class="req">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= e($edit ? $edit['name'] : '') ?>" maxlength="80" required data-testid="category-name-input">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" maxlength="255" data-testid="category-desc-input"><?= e($edit ? $edit['description'] : '') ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-navy" data-testid="category-save-btn"><i class="bi bi-check2 me-1"></i>Simpan</button>
                    <?php if ($edit): ?><a href="<?= site_url('admin/kategori') ?>" class="btn btn-light" data-testid="category-cancel-btn">Batal</a><?php endif; ?>
                </div>
            <?= form_close() ?>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel p-0">
            <table class="table table-hover align-middle mb-0" data-testid="category-table">
                <thead><tr><th>Nama</th><th>Slug</th><th>Berita</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($items as $c): ?>
                    <tr data-testid="category-row-<?= (int) $c['id'] ?>">
                        <td><strong><?= e($c['name']) ?></strong><div class="small text-muted"><?= e($c['description']) ?></div></td>
                        <td class="small"><?= e($c['slug']) ?></td>
                        <td><?= (int) $c['total'] ?></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url('admin/kategori?edit=' . $c['id']) ?>" class="btn btn-icon" data-testid="category-edit-<?= (int) $c['id'] ?>"><i class="bi bi-pencil"></i></a>
                            <?= form_open('admin/kategori/hapus/' . $c['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus kategori ini? Berita terkait tidak ikut terhapus.')) ?>
                                <button class="btn btn-icon text-danger" data-testid="category-delete-<?= (int) $c['id'] ?>"><i class="bi bi-trash"></i></button>
                            <?= form_close() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$items): ?><tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
