<?php defined('BASEPATH') OR exit('No direct script access allowed');
$public = array('tentang' => 'profil', 'sambutan' => 'profil#sambutan', 'sejarah' => 'profil/sejarah', 'visi-misi' => 'profil/visi-misi', 'layanan' => 'profil/layanan'); ?>
<div class="toolbar">
    <p class="text-muted small mb-0">Halaman sistem membentuk menu Profil (Tentang, Sambutan, Sejarah, Tujuan, Layanan). Visi &amp; misi diatur di <a href="<?= site_url('admin/pengaturan') ?>">Pengaturan</a>.</p>
    <a href="<?= site_url('admin/halaman/tambah') ?>" class="btn btn-gold" data-testid="page-add-btn"><i class="bi bi-plus-lg me-1"></i>Halaman Baru</a>
</div>
<div class="panel p-0">
    <table class="table table-hover align-middle mb-0" data-testid="page-table">
        <thead><tr><th>Judul</th><th>Alamat</th><th>Diperbarui</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($items as $p): $url = isset($public[$p['slug']]) ? $public[$p['slug']] : 'halaman/' . $p['slug']; ?>
            <tr data-testid="page-row-<?= e($p['slug']) ?>">
                <td><strong><?= e($p['title']) ?></strong> <?= $p['is_system'] ? '<span class="badge text-bg-light">Sistem</span>' : '' ?></td>
                <td class="small"><a href="<?= site_url($url) ?>" target="_blank">/<?= e($url) ?></a></td>
                <td class="small text-muted"><?= tgl_indo($p['updated_at'] ?: $p['created_at'], TRUE) ?></td>
                <td class="text-end text-nowrap">
                    <a href="<?= site_url('admin/halaman/edit/' . $p['id']) ?>" class="btn btn-icon" data-testid="page-edit-<?= e($p['slug']) ?>"><i class="bi bi-pencil"></i></a>
                    <?php if (!$p['is_system']): ?>
                        <?= form_open('admin/halaman/hapus/' . $p['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus halaman ini?')) ?>
                            <button class="btn btn-icon text-danger" data-testid="page-delete-<?= e($p['slug']) ?>"><i class="bi bi-trash"></i></button>
                        <?= form_close() ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
