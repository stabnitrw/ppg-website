<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="toolbar">
    <p class="text-muted small mb-0">Profil dengan urutan terkecil tampil sebagai pimpinan pada sambutan di beranda.</p>
    <a href="<?= site_url('admin/pimpinan/tambah') ?>" class="btn btn-gold" data-testid="profile-add-btn"><i class="bi bi-plus-lg me-1"></i>Tambah Profil</a>
</div>
<div class="panel p-0">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" data-testid="profile-table">
        <thead><tr><th style="width:70px"></th><th>Nama & Jabatan</th><th>NIP</th><th>Urutan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($items as $p): ?>
            <tr data-testid="profile-row-<?= (int) $p['id'] ?>">
                <td><?= avatar($p['name'], $p['photo'], 'thumb-sm') ?></td>
                <td><strong><?= e($p['name']) ?></strong><div class="small text-muted"><?= e($p['position']) ?></div></td>
                <td class="small"><?= e($p['nip'] ?: '-') ?></td>
                <td><?= (int) $p['sort_order'] ?></td>
                <td><?= $p['is_active'] ? '<span class="badge text-bg-success">Tampil</span>' : '<span class="badge text-bg-secondary">Disembunyikan</span>' ?></td>
                <td class="text-end text-nowrap">
                    <a href="<?= site_url('admin/pimpinan/edit/' . $p['id']) ?>" class="btn btn-icon" data-testid="profile-edit-<?= (int) $p['id'] ?>"><i class="bi bi-pencil"></i></a>
                    <?= form_open('admin/pimpinan/hapus/' . $p['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus profil ini?')) ?>
                        <button class="btn btn-icon text-danger" data-testid="profile-delete-<?= (int) $p['id'] ?>"><i class="bi bi-trash"></i></button>
                    <?= form_close() ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$items): ?><tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
