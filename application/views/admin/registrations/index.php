<?php defined('BASEPATH') OR exit('No direct script access allowed'); $statuses = registration_statuses(); ?>
<div class="status-chips mb-3">
    <a href="<?= site_url('admin/lapor-diri') ?>" class="chip <?= $f['status'] === '' ? 'active' : '' ?>" data-testid="reg-chip-all">Semua <span><?= array_sum($status_counts) ?></span></a>
    <?php foreach ($statuses as $k => $s): ?>
        <a href="<?= site_url('admin/lapor-diri?status=' . $k) ?>" class="chip <?= $f['status'] === $k ? 'active' : '' ?>" data-testid="reg-chip-<?= $k ?>"><?= $s[0] ?> <span><?= (int) $status_counts[$k] ?></span></a>
    <?php endforeach; ?>
</div>
<div class="panel mb-3">
    <form method="get" action="<?= site_url('admin/lapor-diri') ?>" class="row g-2 align-items-end" data-testid="reg-filter-form">
        <div class="col-md-4"><label class="form-label small" for="q">Cari</label><input type="search" id="q" name="q" value="<?= e($f['q']) ?>" class="form-control" placeholder="Nama, no. registrasi, NIM, NIK, email" data-testid="reg-search-input"></div>
        <div class="col-6 col-md-2"><label class="form-label small" for="status">Status</label>
            <select id="status" name="status" class="form-select" data-testid="reg-status-filter"><option value="">Semua</option><?php foreach ($statuses as $k => $s): ?><option value="<?= $k ?>" <?= $f['status'] === $k ? 'selected' : '' ?>><?= $s[0] ?></option><?php endforeach; ?></select></div>
        <div class="col-6 col-md-2"><label class="form-label small" for="category">Program</label>
            <select id="category" name="category" class="form-select" data-testid="reg-category-filter"><option value="">Semua</option><option value="prajabatan" <?= $f['category'] === 'prajabatan' ? 'selected' : '' ?>>Prajabatan</option><option value="dalam_jabatan" <?= $f['category'] === 'dalam_jabatan' ? 'selected' : '' ?>>Dalam Jabatan</option></select></div>
        <div class="col-6 col-md-1"><label class="form-label small" for="from">Dari</label><input type="date" id="from" name="from" value="<?= e($f['from']) ?>" class="form-control px-2" data-testid="reg-from-filter"></div>
        <div class="col-6 col-md-1"><label class="form-label small" for="to">Sampai</label><input type="date" id="to" name="to" value="<?= e($f['to']) ?>" class="form-control px-2" data-testid="reg-to-filter"></div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-navy flex-fill" data-testid="reg-filter-btn"><i class="bi bi-funnel"></i> Filter</button><a href="<?= site_url('admin/lapor-diri') ?>" class="btn btn-light" title="Reset" data-testid="reg-reset-btn"><i class="bi bi-x-lg"></i></a></div>
    </form>
</div>
<div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
    <small class="text-muted" data-testid="reg-total">Menampilkan <?= count($items) ?> dari <?= (int) $total ?> data</small>
    <a href="<?= site_url('admin/lapor-diri/export') . ($export_query ? '?' . $export_query : '') ?>" class="btn btn-sm btn-success" data-testid="reg-export-btn"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Ekspor CSV (Excel)</a>
</div>
<div class="panel p-0">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" data-testid="reg-table">
        <thead><tr><th>No. Registrasi</th><th>Nama / NIM</th><th>Program</th><th>Kontak</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $r): ?>
            <tr data-testid="reg-row-<?= (int) $r['id'] ?>">
                <td class="fw-semibold text-nowrap"><?= e($r['reg_number']) ?></td>
                <td><strong><?= e($r['full_name']) ?></strong><div class="small text-muted"><?= e($r['student_id']) ?></div></td>
                <td class="small"><?= $r['ppg_category'] === 'prajabatan' ? 'Prajabatan' : 'Dalam Jabatan' ?><div class="text-muted"><?= e($r['ppg_field']) ?></div></td>
                <td class="small"><?= e($r['email']) ?><div class="text-muted"><?= e($r['phone']) ?></div></td>
                <td><?= status_badge($r['status']) ?></td>
                <td class="small text-nowrap"><?= tgl_indo($r['created_at']) ?></td>
                <td class="text-end"><a href="<?= site_url('admin/lapor-diri/detail/' . $r['id']) ?>" class="btn btn-sm btn-outline-navy" data-testid="reg-detail-<?= (int) $r['id'] ?>">Detail</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$items): ?><tr><td colspan="7" class="text-center text-muted py-5">Tidak ada data lapor diri.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<div class="mt-3"><?= $pagination ?></div>
