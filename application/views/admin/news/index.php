<?php defined('BASEPATH') OR exit('No direct script access allowed'); $types = news_types(); ?>
<div class="toolbar">
    <form method="get" action="<?= site_url('admin/berita') ?>" class="filters" data-testid="news-filter-form">
        <input type="search" name="q" value="<?= e($f['q']) ?>" class="form-control" placeholder="Cari judul…" data-testid="admin-news-search">
        <select name="type" class="form-select" data-testid="admin-news-type-filter">
            <option value="">Semua jenis</option>
            <?php foreach ($types as $k => $v): ?><option value="<?= $k ?>" <?= $f['type'] === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?>
        </select>
        <select name="status" class="form-select" data-testid="admin-news-status-filter">
            <option value="">Semua status</option>
            <option value="published" <?= $f['status'] === 'published' ? 'selected' : '' ?>>Terbit</option>
            <option value="draft" <?= $f['status'] === 'draft' ? 'selected' : '' ?>>Draf</option>
        </select>
        <button class="btn btn-navy" data-testid="admin-news-filter-btn"><i class="bi bi-funnel"></i> Filter</button>
    </form>
    <a href="<?= site_url('admin/berita/tambah') ?>" class="btn btn-gold" data-testid="admin-news-add-btn"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="panel p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" data-testid="admin-news-table">
            <thead><tr><th>Judul</th><th>Jenis</th><th>Kategori</th><th>Status</th><th>Terbit</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($items as $n): ?>
                <tr data-testid="admin-news-row-<?= (int) $n['id'] ?>">
                    <td class="mw-td"><strong><?= e($n['title']) ?></strong><div class="small text-muted"><?= e($n['author_name']) ?> &middot; <?= (int) $n['views'] ?> dibaca</div></td>
                    <td><span class="badge text-bg-light"><?= e($types[$n['type']]) ?></span></td>
                    <td class="small"><?= e($n['category_name'] ?: '-') ?></td>
                    <td><?= $n['status'] === 'published' ? '<span class="badge text-bg-success">Terbit</span>' : '<span class="badge text-bg-warning">Draf</span>' ?></td>
                    <td class="small text-nowrap"><?= tgl_indo($n['published_at']) ?></td>
                    <td class="text-end text-nowrap">
                        <?php if ($n['status'] === 'published'): ?><a href="<?= site_url('berita/' . $n['slug']) ?>" target="_blank" class="btn btn-icon" title="Lihat" data-testid="admin-news-view-<?= (int) $n['id'] ?>"><i class="bi bi-eye"></i></a><?php endif; ?>
                        <a href="<?= site_url('admin/berita/edit/' . $n['id']) ?>" class="btn btn-icon" title="Edit" data-testid="admin-news-edit-<?= (int) $n['id'] ?>"><i class="bi bi-pencil"></i></a>
                        <?= form_open('admin/berita/hapus/' . $n['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus berita ini?')) ?>
                            <button class="btn btn-icon text-danger" title="Hapus" data-testid="admin-news-delete-<?= (int) $n['id'] ?>"><i class="bi bi-trash"></i></button>
                        <?= form_close() ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$items): ?><tr><td colspan="6" class="text-center text-muted py-5">Tidak ada data.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <small class="text-muted">Total: <?= (int) $total ?> data</small>
    <?= $pagination ?>
</div>
