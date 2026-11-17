<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('crumbs' => array('Lapor Diri' => site_url('lapor-diri'))));
$cats = array('prajabatan' => 'PPG Prajabatan', 'dalam_jabatan' => 'PPG Dalam Jabatan'); ?>
<section class="section pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="receipt" data-testid="lapor-success">
                    <div class="receipt-head">
                        <div class="success-icon"><i class="bi bi-check-lg"></i></div>
                        <h2>Lapor diri berhasil dikirim</h2>
                        <p>Simpan nomor registrasi berikut untuk mengecek status verifikasi.</p>
                        <div class="reg-number" data-testid="reg-number"><?= e($reg['reg_number']) ?></div>
                    </div>
                    <dl class="receipt-grid">
                        <div><dt>Nama Lengkap</dt><dd data-testid="receipt-name"><?= e($reg['full_name']) ?></dd></div>
                        <div><dt>NIM / No. Peserta</dt><dd><?= e($reg['student_id']) ?></dd></div>
                        <div><dt>Program</dt><dd><?= e($cats[$reg['ppg_category']]) ?> – <?= e($reg['ppg_field']) ?></dd></div>
                        <div><dt>Email</dt><dd><?= e($reg['email']) ?></dd></div>
                        <div><dt>Tanggal Kirim</dt><dd><?= tgl_indo($reg['created_at'], TRUE) ?></dd></div>
                        <div><dt>Status</dt><dd data-testid="receipt-status"><?= status_badge($reg['status']) ?></dd></div>
                    </dl>
                    <div class="receipt-docs">
                        <h6>Dokumen terunggah</h6>
                        <ul><?php foreach ($documents as $d): ?><li><i class="bi bi-file-earmark-check"></i><?= e(isset($docs[$d['doc_type']]) ? $docs[$d['doc_type']]['label'] : $d['doc_type']) ?> <small>(<?= format_bytes($d['file_size']) ?>)</small></li><?php endforeach; ?></ul>
                    </div>
                    <div class="d-flex flex-wrap gap-2 justify-content-center no-print">
                        <button type="button" class="btn btn-navy" onclick="window.print()" data-testid="print-receipt-btn"><i class="bi bi-printer me-1"></i>Cetak Bukti</button>
                        <a href="<?= site_url('lapor-diri/cek') ?>" class="btn btn-outline-navy" data-testid="success-check-status-btn">Cek Status</a>
                        <a href="<?= site_url() ?>" class="btn btn-link" data-testid="success-home-btn">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
