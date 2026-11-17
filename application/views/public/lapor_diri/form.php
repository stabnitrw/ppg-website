<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('page_lead' => 'Formulir lapor diri daring bagi peserta Program Pendidikan Profesi Guru.'));
$inv = function ($f) { return form_error($f) ? ' is-invalid' : ''; };
$allowed = explode('|', setting('doc_allowed_ext', 'pdf|jpg|jpeg|png'));
$max_kb = (int) setting('doc_max_size_kb', 2048);
$accept = '.' . implode(',.', $allowed);
?>
<section class="section pt-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <?php if (!$open): ?>
                    <div class="empty-state" data-testid="lapor-diri-closed"><i class="bi bi-door-closed"></i><h3>Lapor diri sedang ditutup</h3><p>Silakan pantau pengumuman resmi fakultas untuk jadwal berikutnya.</p></div>
                <?php else: ?>
                <?php if ($general_error): ?>
                    <div class="alert alert-danger" role="alert" data-testid="lapor-form-error"><i class="bi bi-exclamation-triangle me-2"></i><?= e($general_error) ?></div>
                <?php endif; ?>
                <ol class="stepper" data-testid="lapor-stepper">
                    <li class="active" data-step="1"><span>1</span>Data Pribadi</li>
                    <li data-step="2"><span>2</span>Data Akademik</li>
                    <li data-step="3"><span>3</span>Dokumen</li>
                </ol>
                <?= form_open_multipart('lapor-diri/kirim', array('id' => 'lapor-form', 'class' => 'form-card', 'novalidate' => 'novalidate', 'data-testid' => 'lapor-form')) ?>
                    <fieldset class="form-step active" data-step="1">
                        <legend>Data Pribadi</legend>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="student_id">NIM / Nomor Peserta PPG <span class="req">*</span></label>
                                <input type="text" class="form-control<?= $inv('student_id') ?>" id="student_id" name="student_id" value="<?= set_value('student_id') ?>" maxlength="30" required pattern="[A-Za-z0-9.\-\/]+" data-testid="input-student-id">
                                <?= form_error('student_id') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="nik">NIK (16 digit) <span class="req">*</span></label>
                                <input type="text" class="form-control<?= $inv('nik') ?>" id="nik" name="nik" value="<?= set_value('nik') ?>" inputmode="numeric" maxlength="16" minlength="16" pattern="[0-9]{16}" required data-testid="input-nik">
                                <?= form_error('nik') ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="full_name">Nama Lengkap (sesuai ijazah) <span class="req">*</span></label>
                                <input type="text" class="form-control<?= $inv('full_name') ?>" id="full_name" name="full_name" value="<?= set_value('full_name') ?>" maxlength="150" minlength="3" required data-testid="input-full-name">
                                <?= form_error('full_name') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="birth_place">Tempat Lahir <span class="req">*</span></label>
                                <input type="text" class="form-control<?= $inv('birth_place') ?>" id="birth_place" name="birth_place" value="<?= set_value('birth_place') ?>" maxlength="80" required data-testid="input-birth-place">
                                <?= form_error('birth_place') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="birth_date">Tanggal Lahir <span class="req">*</span></label>
                                <input type="date" class="form-control<?= $inv('birth_date') ?>" id="birth_date" name="birth_date" value="<?= set_value('birth_date') ?>" max="<?= date('Y-m-d', strtotime('-17 years')) ?>" min="1940-01-01" required data-testid="input-birth-date">
                                <?= form_error('birth_date') ?>
                            </div>
                            <div class="col-md-6">
                                <span class="form-label d-block">Jenis Kelamin <span class="req">*</span></span>
                                <div class="d-flex gap-4 pt-1">
                                    <div class="form-check"><input class="form-check-input<?= $inv('gender') ?>" type="radio" name="gender" id="gender_l" value="L" <?= set_radio('gender', 'L') ?> required data-testid="input-gender-l"><label class="form-check-label" for="gender_l">Laki-laki</label></div>
                                    <div class="form-check"><input class="form-check-input<?= $inv('gender') ?>" type="radio" name="gender" id="gender_p" value="P" <?= set_radio('gender', 'P') ?> data-testid="input-gender-p"><label class="form-check-label" for="gender_p">Perempuan</label></div>
                                </div>
                                <?= form_error('gender') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone">Nomor HP / WhatsApp <span class="req">*</span></label>
                                <input type="tel" class="form-control<?= $inv('phone') ?>" id="phone" name="phone" value="<?= set_value('phone') ?>" placeholder="08xxxxxxxxxx" pattern="(\+62|62|0)[0-9]{8,13}" required data-testid="input-phone">
                                <?= form_error('phone') ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="email">Email Aktif <span class="req">*</span></label>
                                <input type="email" class="form-control<?= $inv('email') ?>" id="email" name="email" value="<?= set_value('email') ?>" maxlength="120" required data-testid="input-email">
                                <div class="form-text">Email digunakan bersama nomor registrasi untuk mengecek status.</div>
                                <?= form_error('email') ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="address">Alamat Lengkap <span class="req">*</span></label>
                                <textarea class="form-control<?= $inv('address') ?>" id="address" name="address" rows="3" maxlength="500" minlength="10" required data-testid="input-address"><?= set_value('address') ?></textarea>
                                <?= form_error('address') ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-step" data-step="2">
                        <legend>Data Akademik</legend>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="ppg_category">Jenis Program PPG <span class="req">*</span></label>
                                <select class="form-select<?= $inv('ppg_category') ?>" id="ppg_category" name="ppg_category" required data-testid="input-ppg-category">
                                    <option value="">— Pilih —</option>
                                    <option value="prajabatan" <?= set_select('ppg_category', 'prajabatan') ?>>PPG Prajabatan</option>
                                    <option value="dalam_jabatan" <?= set_select('ppg_category', 'dalam_jabatan') ?>>PPG Dalam Jabatan</option>
                                </select>
                                <?= form_error('ppg_category') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="ppg_field">Bidang Studi PPG <span class="req">*</span></label>
                                <select class="form-select<?= $inv('ppg_field') ?>" id="ppg_field" name="ppg_field" required data-testid="input-ppg-field">
                                    <?php foreach ($ppg_fields as $f): ?><option value="<?= e($f) ?>" <?= set_select('ppg_field', $f) ?>><?= e($f) ?></option><?php endforeach; ?>
                                </select>
                                <?= form_error('ppg_field') ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="last_education">Pendidikan Terakhir <span class="req">*</span></label>
                                <select class="form-select<?= $inv('last_education') ?>" id="last_education" name="last_education" required data-testid="input-last-education">
                                    <option value="">— Pilih —</option>
                                    <?php foreach (array('S1', 'D4', 'S2', 'S3') as $ed): ?><option value="<?= $ed ?>" <?= set_select('last_education', $ed) ?>><?= $ed ?></option><?php endforeach; ?>
                                </select>
                                <?= form_error('last_education') ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="graduation_year">Tahun Lulus <span class="req">*</span></label>
                                <input type="number" class="form-control<?= $inv('graduation_year') ?>" id="graduation_year" name="graduation_year" value="<?= set_value('graduation_year') ?>" min="1970" max="<?= date('Y') ?>" required data-testid="input-graduation-year">
                                <?= form_error('graduation_year') ?>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="gpa">IPK</label>
                                <input type="number" class="form-control<?= $inv('gpa') ?>" id="gpa" name="gpa" value="<?= set_value('gpa') ?>" min="0" max="4" step="0.01" placeholder="3.50" data-testid="input-gpa">
                                <?= form_error('gpa') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="university">Perguruan Tinggi Asal <span class="req">*</span></label>
                                <input type="text" class="form-control<?= $inv('university') ?>" id="university" name="university" value="<?= set_value('university') ?>" maxlength="150" required data-testid="input-university">
                                <?= form_error('university') ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="study_program">Program Studi <span class="req">*</span></label>
                                <input type="text" class="form-control<?= $inv('study_program') ?>" id="study_program" name="study_program" value="<?= set_value('study_program') ?>" maxlength="150" required data-testid="input-study-program">
                                <?= form_error('study_program') ?>
                            </div>
                            <div class="col-12" id="school-wrap">
                                <label class="form-label" for="school_name">Sekolah Tempat Mengajar <span class="req school-req">*</span></label>
                                <input type="text" class="form-control<?= $inv('school_name') ?>" id="school_name" name="school_name" value="<?= set_value('school_name') ?>" maxlength="150" data-testid="input-school-name">
                                <div class="form-text">Wajib diisi untuk peserta PPG Dalam Jabatan.</div>
                                <?= form_error('school_name') ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-step" data-step="3">
                        <legend>Unggah Dokumen</legend>
                        <p class="small text-muted">Format: <strong><?= e(strtoupper(implode(', ', $allowed))) ?></strong> &middot; Maksimal <strong><?= format_bytes($max_kb * 1024) ?></strong> per file.</p>
                        <div class="row g-3">
                            <?php foreach ($docs as $key => $d): ?>
                                <div class="col-md-6">
                                    <label class="form-label" for="doc_<?= $key ?>"><?= e($d['label']) ?><?= $d['required'] ? ' <span class="req">*</span>' : '' ?></label>
                                    <input type="file" class="form-control doc-input<?= isset($doc_errors[$key]) ? ' is-invalid' : '' ?>" id="doc_<?= $key ?>" name="doc_<?= $key ?>" accept="<?= e($accept) ?>" data-max-kb="<?= $max_kb ?>" data-ext="<?= e(implode(',', $allowed)) ?>" <?= $d['required'] ? 'required' : '' ?> data-testid="input-doc-<?= $key ?>">
                                    <?php if (isset($doc_errors[$key])): ?><div class="invalid-feedback d-block"><?= e($doc_errors[$key]) ?></div><?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-check mt-4">
                            <input class="form-check-input<?= $inv('agreement') ?>" type="checkbox" value="1" id="agreement" name="agreement" required <?= set_checkbox('agreement', '1') ?> data-testid="input-agreement">
                            <label class="form-check-label" for="agreement">Saya menyatakan bahwa data dan dokumen yang saya isi adalah benar dan dapat dipertanggungjawabkan.</label>
                            <?= form_error('agreement') ?>
                        </div>
                    </fieldset>

                    <div class="form-actions">
                        <button type="button" class="btn btn-outline-navy" id="btn-prev" data-testid="lapor-prev-btn" hidden><i class="bi bi-arrow-left me-1"></i>Sebelumnya</button>
                        <button type="button" class="btn btn-navy ms-auto" id="btn-next" data-testid="lapor-next-btn">Selanjutnya<i class="bi bi-arrow-right ms-1"></i></button>
                        <button type="submit" class="btn btn-gold ms-auto" id="btn-submit" data-testid="lapor-submit-btn" hidden><i class="bi bi-send me-1"></i>Kirim Lapor Diri</button>
                    </div>
                <?= form_close() ?>
                <?php endif; ?>
            </div>
            <aside class="col-lg-4">
                <div class="side-card">
                    <h5>Ketentuan Lapor Diri</h5>
                    <p class="small"><?= e(setting('lapor_diri_info')) ?></p>
                    <h6 class="small fw-bold mt-3">Dokumen yang disiapkan:</h6>
                    <ul class="doc-checklist">
                        <?php foreach ($docs as $d): ?><li><i class="bi bi-file-earmark-check"></i><?= e($d['label']) ?></li><?php endforeach; ?>
                    </ul>
                </div>
                <div class="side-card side-cta">
                    <h5>Sudah lapor diri?</h5>
                    <p class="small">Cek status verifikasi menggunakan nomor registrasi dan email Anda.</p>
                    <a href="<?= site_url('lapor-diri/cek') ?>" class="btn btn-gold w-100" data-testid="lapor-check-status-link">Cek Status</a>
                </div>
            </aside>
        </div>
    </div>
</section>
