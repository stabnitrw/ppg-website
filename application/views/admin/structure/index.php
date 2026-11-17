<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="toolbar">
    <a href="<?= site_url('profil/struktur-organisasi') ?>" target="_blank" class="small" data-testid="structure-preview-link"><i class="bi bi-eye me-1"></i>Lihat bagan di website</a>
    <a href="<?= site_url('admin/struktur/tambah') ?>" class="btn btn-gold" data-testid="structure-add-btn"><i class="bi bi-plus-lg me-1"></i>Tambah Jabatan</a>
</div>
<div class="panel p-0">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" data-testid="structure-table">
        <thead><tr><th>Jabatan</th><th>Nama</th><th>Unit</th><th>Urutan</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($items as $s): ?>
            <tr data-testid="structure-row-<?= (int) $s['id'] ?>">
                <td><span style="padding-left:<?= $s['level'] * 1.5 ?>rem"><?= $s['level'] ? '<i class="bi bi-arrow-return-right text-muted me-1"></i>' : '' ?><strong><?= e($s['position']) ?></strong></span></td>
                <td><?= e($s['name']) ?></td>
                <td class="small"><?= e($s['unit'] ?: '-') ?></td>
                <td><?= (int) $s['sort_order'] ?></td>
                <td class="text-end text-nowrap">
                    <a href="<?= site_url('admin/struktur/edit/' . $s['id']) ?>" class="btn btn-icon" data-testid="structure-edit-<?= (int) $s['id'] ?>"><i class="bi bi-pencil"></i></a>
                    <?= form_open('admin/struktur/hapus/' . $s['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus jabatan ini? Bawahannya akan dipindahkan ke atasan jabatan ini.')) ?>
                        <button class="btn btn-icon text-danger" data-testid="structure-delete-<?= (int) $s['id'] ?>"><i class="bi bi-trash"></i></button>
                    <?= form_close() ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$items): ?><tr><td colspan="5" class="text-center text-muted py-4">Belum ada data.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
