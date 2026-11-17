<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="toolbar">
    <p class="text-muted small mb-0"><strong>Administrator</strong>: akses penuh. <strong>Editor</strong>: mengelola konten &amp; lapor diri, tanpa pengaturan dan pengguna.</p>
    <a href="<?= site_url('admin/pengguna/tambah') ?>" class="btn btn-gold" data-testid="user-add-btn"><i class="bi bi-plus-lg me-1"></i>Tambah Pengguna</a>
</div>
<div class="panel p-0">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" data-testid="user-table">
        <thead><tr><th>Nama</th><th>Username</th><th>Peran</th><th>Status</th><th>Login Terakhir</th><th class="text-end">Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($items as $u): ?>
            <tr data-testid="user-row-<?= e($u['username']) ?>">
                <td><strong><?= e($u['name']) ?></strong><div class="small text-muted"><?= e($u['email']) ?></div></td>
                <td><?= e($u['username']) ?></td>
                <td><span class="badge role-<?= e($u['role']) ?>"><?= $u['role'] === 'admin' ? 'Administrator' : 'Editor' ?></span></td>
                <td><?= $u['is_active'] ? '<span class="badge text-bg-success">Aktif</span>' : '<span class="badge text-bg-secondary">Nonaktif</span>' ?><?= ($u['locked_until'] && $u['locked_until'] > date('Y-m-d H:i:s')) ? ' <span class="badge text-bg-danger">Terkunci</span>' : '' ?></td>
                <td class="small text-muted"><?= $u['last_login'] ? tgl_indo($u['last_login'], TRUE) : 'Belum pernah' ?></td>
                <td class="text-end text-nowrap">
                    <a href="<?= site_url('admin/pengguna/edit/' . $u['id']) ?>" class="btn btn-icon" data-testid="user-edit-<?= e($u['username']) ?>"><i class="bi bi-pencil"></i></a>
                    <?php if ((int) $u['id'] !== (int) $user['id']): ?>
                        <?= form_open('admin/pengguna/hapus/' . $u['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus akun ini?')) ?>
                            <button class="btn btn-icon text-danger" data-testid="user-delete-<?= e($u['username']) ?>"><i class="bi bi-trash"></i></button>
                        <?= form_close() ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
