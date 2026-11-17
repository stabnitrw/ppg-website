<?php defined('BASEPATH') OR exit('No direct script access allowed');
$statuses = registration_statuses();
$cats = array('prajabatan' => 'PPG Prajabatan', 'dalam_jabatan' => 'PPG Dalam Jabatan');
$uploaded = array();
foreach ($documents as $d) { $uploaded[$d['doc_type']] = $d; } ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <a href="<?= site_url('admin/lapor-diri') ?>" class="small" data-testid="reg-back-link"><i class="bi bi-arrow-left me-1"></i>Kembali ke daftar</a>
    <?php if ($user['role'] === 'admin'): ?>
        <?= form_open('admin/lapor-diri/hapus/' . $reg['id'], array('class' => 'd-inline', 'data-confirm' => 'Hapus data lapor diri ini beserta seluruh dokumennya? Tindakan ini tidak dapat dibatalkan.')) ?>
            <button class="btn btn-sm btn-outline-danger" data-testid="reg-delete-btn"><i class="bi bi-trash me-1"></i>Hapus Data</button>
        <?= form_close() ?>
    <?php endif; ?>
</div>
<div class="row g-4">
    <div class="col-xl-8">
        <div class="panel">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div><small class="text-muted">Nomor Registrasi</small><h2 class="reg-title" data-testid="reg-detail-number"><?= e($reg['reg_number']) ?></h2></div>
                <div data-testid="reg-detail-status"><?= status_badge($reg['status']) ?></div>
            </div>
            <h3 class="panel-title">Data Pribadi</h3>
            <dl class="detail-grid">
                <div><dt>Nama Lengkap</dt><dd data-testid="reg-detail-name"><?= e($reg['full_name']) ?></dd></div>
                <div><dt>NIM / No. Peserta</dt><dd><?= e($reg['student_id']) ?></dd></div>
                <div><dt>NIK</dt><dd><?= e($reg['nik']) ?></dd></div>
                <div><dt>Tempat, Tanggal Lahir</dt><dd><?= e($reg['birth_place']) ?>, <?= tgl_indo($reg['birth_date']) ?></dd></div>
                <div><dt>Jenis Kelamin</dt><dd><?= $reg['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></dd></div>
                <div><dt>No. HP / WhatsApp</dt><dd><a href="https://wa.me/<?= e(preg_replace('/^0/', '62', preg_replace('/\D/', '', $reg['phone']))) ?>" target="_blank" rel="noopener"><?= e($reg['phone']) ?></a></dd></div>
                <div><dt>Email</dt><dd><a href="mailto:<?= e($reg['email']) ?>"><?= e($reg['email']) ?></a></dd></div>
                <div class="full"><dt>Alamat</dt><dd><?= nl2br(e($reg['address'])) ?></dd></div>
            </dl>
            <h3 class="panel-title mt-4">Data Akademik</h3>
            <dl class="detail-grid">
                <div><dt>Program</dt><dd><?= e($cats[$reg['ppg_category']]) ?></dd></div>
                <div><dt>Bidang Studi</dt><dd><?= e($reg['ppg_field']) ?></dd></div>
                <div><dt>Pendidikan Terakhir</dt><dd><?= e($reg['last_education']) ?></dd></div>
                <div><dt>Perguruan Tinggi</dt><dd><?= e($reg['university']) ?></dd></div>
                <div><dt>Program Studi</dt><dd><?= e($reg['study_program']) ?></dd></div>
                <div><dt>Tahun Lulus / IPK</dt><dd><?= (int) $reg['graduation_year'] ?> / <?= $reg['gpa'] !== null ? e($reg['gpa']) : '-' ?></dd></div>
                <?php if ($reg['school_name']): ?><div class="full"><dt>Sekolah Tempat Mengajar</dt><dd><?= e($reg['school_name']) ?></dd></div><?php endif; ?>
            </dl>
            <h3 class="panel-title mt-4">Dokumen</h3>
            <div class="doc-list" data-testid="reg-documents">
                <?php foreach ($docs + array_fill_keys(array_diff(array_keys($uploaded), array_keys($docs)), array('label' => 'Dokumen lain', 'required' => FALSE)) as $key => $req): $d = isset($uploaded[$key]) ? $uploaded[$key] : null; ?>
                    <div class="doc-item <?= $d ? '' : 'missing' ?>">
                        <i class="bi <?= $d ? ($d['mime_type'] === 'application/pdf' ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image') : 'bi-file-earmark-x' ?>"></i>
                        <div class="flex-fill"><strong><?= e($req['label']) ?></strong><small><?= $d ? e($d['original_name']) . ' · ' . format_bytes($d['file_size']) : 'Tidak diunggah' ?></small></div>
                        <?php if ($d): ?>
                            <a href="<?= site_url('admin/lapor-diri/unduh/' . $d['id']) ?>?lihat=1" target="_blank" class="btn btn-icon" title="Lihat" data-testid="doc-view-<?= e($key) ?>"><i class="bi bi-eye"></i></a>
                            <a href="<?= site_url('admin/lapor-diri/unduh/' . $d['id']) ?>" class="btn btn-icon" title="Unduh" data-testid="doc-download-<?= e($key) ?>"><i class="bi bi-download"></i></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="panel">
            <h3 class="panel-title">Verifikasi</h3>
            <?= form_open('admin/lapor-diri/update/' . $reg['id'], array('data-testid' => 'reg-update-form')) ?>
                <div class="mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" data-testid="reg-status-select">
                        <?php foreach ($statuses as $k => $s): ?><option value="<?= $k ?>" <?= $reg['status'] === $k ? 'selected' : '' ?>><?= $s[0] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="public_note">Catatan untuk Peserta</label>
                    <textarea class="form-control" id="public_note" name="public_note" rows="3" maxlength="2000" placeholder="Tampil saat peserta mengecek status" data-testid="reg-public-note"><?= e($reg['public_note']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="admin_notes">Catatan Internal Admin</label>
                    <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" maxlength="5000" placeholder="Hanya terlihat oleh admin" data-testid="reg-admin-notes"><?= e($reg['admin_notes']) ?></textarea>
                </div>
                <button class="btn btn-navy w-100" data-testid="reg-update-btn"><i class="bi bi-check2 me-1"></i>Simpan Perubahan</button>
            <?= form_close() ?>
        </div>
        <div class="panel mt-4">
            <h3 class="panel-title">Riwayat</h3>
            <ul class="list-plain">
                <li><span>Dikirim</span><strong class="small"><?= tgl_indo($reg['created_at'], TRUE) ?></strong></li>
                <li><span>Diperbarui</span><strong class="small"><?= tgl_indo($reg['updated_at'], TRUE) ?></strong></li>
                <li><span>Alamat IP</span><strong class="small"><?= e($reg['ip_address']) ?></strong></li>
            </ul>
        </div>
    </div>
</div>
