<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('crumbs' => array('Lapor Diri' => site_url('lapor-diri')), 'page_lead' => 'Masukkan nomor registrasi dan email yang digunakan saat lapor diri.'));
$steps = array('baru' => 'Diterima Sistem', 'diverifikasi' => 'Verifikasi Berkas', 'final' => 'Keputusan');
$order = array('baru' => 1, 'diverifikasi' => 2, 'perlu_revisi' => 2, 'diterima' => 3, 'ditolak' => 3); ?>
<section class="section pt-5">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-5">
                <?= form_open('lapor-diri/cek', array('class' => 'form-card', 'data-testid' => 'check-status-form')) ?>
                    <h2 class="h4 mb-3">Cek Status</h2>
                    <div class="mb-3">
                        <label class="form-label" for="reg_number">Nomor Registrasi</label>
                        <input type="text" class="form-control text-uppercase<?= form_error('reg_number') ? ' is-invalid' : '' ?>" id="reg_number" name="reg_number" value="<?= set_value('reg_number') ?>" placeholder="PPG-<?= date('Y') ?>-00001" required maxlength="30" data-testid="check-reg-number">
                        <?= form_error('reg_number') ?>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control<?= form_error('email') ? ' is-invalid' : '' ?>" id="email" name="email" value="<?= set_value('email') ?>" required data-testid="check-email">
                        <?= form_error('email') ?>
                    </div>
                    <button type="submit" class="btn btn-navy w-100" data-testid="check-submit-btn"><i class="bi bi-search me-1"></i>Cek Status</button>
                <?= form_close() ?>
            </div>
            <?php if ($searched): ?>
            <div class="col-lg-7">
                <?php if (!$result): ?>
                    <div class="empty-state" data-testid="check-not-found"><i class="bi bi-question-circle"></i><h3>Data tidak ditemukan</h3><p>Pastikan nomor registrasi dan email sesuai dengan bukti lapor diri.</p></div>
                <?php else: $pos = $order[$result['status']]; ?>
                    <div class="status-card" data-testid="check-result">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div><small class="text-muted">Nomor Registrasi</small><h3 class="mb-0"><?= e($result['reg_number']) ?></h3></div>
                            <div data-testid="check-result-status"><?= status_badge($result['status']) ?></div>
                        </div>
                        <ol class="status-track">
                            <?php $i = 1; foreach ($steps as $k => $label): ?>
                                <li class="<?= $pos >= $i ? 'done' : '' ?> <?= ($i === 3 && $result['status'] === 'ditolak') ? 'rejected' : '' ?>"><span></span><?= $i === 3 && in_array($result['status'], array('diterima', 'ditolak'), TRUE) ? registration_statuses()[$result['status']][0] : $label ?></li>
                            <?php $i++; endforeach; ?>
                        </ol>
                        <dl class="receipt-grid">
                            <div><dt>Nama</dt><dd><?= e($result['full_name']) ?></dd></div>
                            <div><dt>Tanggal Kirim</dt><dd><?= tgl_indo($result['created_at'], TRUE) ?></dd></div>
                            <div><dt>Pembaruan Terakhir</dt><dd><?= tgl_indo($result['updated_at'] ? $result['updated_at'] : $result['created_at'], TRUE) ?></dd></div>
                            <div><dt>Dokumen</dt><dd><?= count($documents) ?> file terunggah</dd></div>
                        </dl>
                        <?php if ($result['public_note']): ?>
                            <div class="note-box" data-testid="check-public-note"><strong><i class="bi bi-chat-left-text me-1"></i>Catatan dari Admin</strong><p class="mb-0"><?= nl2br(e($result['public_note'])) ?></p></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
