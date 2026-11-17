<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k) { return set_value($k, setting($k)); };
$exts = explode('|', setting('doc_allowed_ext', 'pdf'));
$img_field = function ($key, $label, $hint) {
    $cur = setting($key);
    $src = $cur ? upload_url('settings', $cur) : ($key === 'hero_image' ? base_url('assets/img/hero.jpg') : logo_url($key));
    echo '<div class="col-md-4"><label class="form-label">' . $label . '</label><div class="logo-preview' . ($key === 'hero_image' ? ' wide' : '') . '"><img src="' . e($src) . '" alt=""></div>'
        . '<input type="file" class="form-control img-input mt-2" name="' . $key . '" accept=".jpg,.jpeg,.png" data-testid="setting-' . $key . '-input">'
        . '<div class="form-text">' . $hint . '</div>'
        . ($cur ? '<div class="form-check"><input class="form-check-input" type="checkbox" name="remove_' . $key . '" value="1" id="rm_' . $key . '"><label class="form-check-label small" for="rm_' . $key . '">Kembalikan ke bawaan</label></div>' : '')
        . '</div>';
}; ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<?= form_open_multipart('admin/pengaturan', array('data-testid' => 'settings-form')) ?>
<input type="hidden" name="tab" id="current-tab" value="umum">
<ul class="nav nav-tabs settings-tabs" role="tablist">
    <?php foreach (array('umum' => 'Identitas', 'beranda' => 'Beranda', 'kontak' => 'Kontak', 'lapor' => 'Lapor Diri & Upload') as $id => $label): ?>
        <li class="nav-item" role="presentation"><button class="nav-link <?= $id === 'umum' ? 'active' : '' ?>" data-bs-toggle="tab" data-bs-target="#tab-<?= $id ?>" type="button" role="tab" data-tab="<?= $id ?>" data-testid="settings-tab-<?= $id ?>"><?= $label ?></button></li>
    <?php endforeach; ?>
</ul>
<div class="tab-content panel settings-panel">
    <div class="tab-pane fade show active" id="tab-umum" role="tabpanel">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="site_name">Nama Website <span class="req">*</span></label><input class="form-control" id="site_name" name="site_name" value="<?= $v('site_name') ?>" required data-testid="setting-site-name"></div>
            <div class="col-md-3"><label class="form-label" for="site_short_name">Nama Singkat</label><input class="form-control" id="site_short_name" name="site_short_name" value="<?= $v('site_short_name') ?>" data-testid="setting-short-name"></div>
            <div class="col-md-3"><label class="form-label" for="institution_name">Nama Institusi</label><input class="form-control" id="institution_name" name="institution_name" value="<?= $v('institution_name') ?>" data-testid="setting-institution"></div>
            <div class="col-12"><label class="form-label" for="site_tagline">Tagline</label><input class="form-control" id="site_tagline" name="site_tagline" value="<?= $v('site_tagline') ?>" data-testid="setting-tagline"></div>
            <div class="col-12"><label class="form-label" for="meta_description">Deskripsi SEO</label><textarea class="form-control" id="meta_description" name="meta_description" rows="2" maxlength="300" data-testid="setting-meta"><?= $v('meta_description') ?></textarea></div>
            <?php $img_field('logo', 'Logo Fakultas PPG (opsional)', 'Tampil di samping logo STABN bila diunggah.'); ?>
            <?php $img_field('logo_stabn', 'Logo STABN Raden Wijaya', 'PNG transparan, persegi.'); ?>
            <?php $img_field('hero_image', 'Gambar Banner Beranda', 'Lanskap, min. 1600 px.'); ?>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-beranda" role="tabpanel">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="topbar_text">Teks Bar Atas</label><input class="form-control" id="topbar_text" name="topbar_text" value="<?= $v('topbar_text') ?>" data-testid="setting-topbar-text"></div>
            <div class="col-md-6"><label class="form-label" for="hero_badge">Label Kecil di Banner</label><input class="form-control" id="hero_badge" name="hero_badge" value="<?= $v('hero_badge') ?>" data-testid="setting-hero-badge"></div>
            <div class="col-12"><label class="form-label" for="hero_title">Judul Banner</label><input class="form-control" id="hero_title" name="hero_title" value="<?= $v('hero_title') ?>" data-testid="setting-hero-title"></div>
            <div class="col-12"><label class="form-label" for="hero_subtitle">Teks Sambutan Banner</label><textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3" data-testid="setting-hero-subtitle"><?= $v('hero_subtitle') ?></textarea></div>
            <div class="col-12"><label class="form-label" for="visi">Visi</label><textarea class="form-control" id="visi" name="visi" rows="3" data-testid="setting-visi"><?= $v('visi') ?></textarea></div>
            <div class="col-12"><label class="form-label" for="misi">Misi <small class="text-muted">(satu misi per baris)</small></label><textarea class="form-control" id="misi" name="misi" rows="6" data-testid="setting-misi"><?= $v('misi') ?></textarea></div>
            <div class="col-md-6"><label class="form-label" for="stats_title">Judul Bagian Statistik</label><input class="form-control" id="stats_title" name="stats_title" value="<?= $v('stats_title') ?>" data-testid="setting-stats-title"></div>
            <div class="col-md-6"><label class="form-label" for="stats_subtitle">Keterangan Statistik</label><input class="form-control" id="stats_subtitle" name="stats_subtitle" value="<?= $v('stats_subtitle') ?>" data-testid="setting-stats-subtitle"></div>
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="col-6 col-md-3"><label class="form-label small" for="stat_<?= $i ?>_value">Statistik <?= $i ?></label>
                    <input class="form-control mb-1" id="stat_<?= $i ?>_value" name="stat_<?= $i ?>_value" value="<?= $v('stat_' . $i . '_value') ?>" placeholder="Angka" data-testid="setting-stat-<?= $i ?>-value">
                    <input class="form-control" name="stat_<?= $i ?>_label" value="<?= $v('stat_' . $i . '_label') ?>" placeholder="Label" aria-label="Label statistik <?= $i ?>" data-testid="setting-stat-<?= $i ?>-label"></div>
            <?php endfor; ?>
            <div class="col-md-6"><label class="form-label" for="ppg_stages">Tahapan Program PPG <small class="text-muted">(baris: Judul|Keterangan)</small></label><textarea class="form-control small" id="ppg_stages" name="ppg_stages" rows="6" data-testid="setting-ppg-stages"><?= $v('ppg_stages') ?></textarea></div>
            <div class="col-md-6"><label class="form-label" for="service_links">Tautan Layanan <small class="text-muted">(baris: Judul|Keterangan|URL)</small></label><textarea class="form-control small" id="service_links" name="service_links" rows="6" data-testid="setting-service-links"><?= $v('service_links') ?></textarea></div>
            <div class="col-md-3"><label class="form-label" for="akreditasi_status">Status Akreditasi</label><input class="form-control" id="akreditasi_status" name="akreditasi_status" value="<?= $v('akreditasi_status') ?>" data-testid="setting-akreditasi-status"></div>
            <div class="col-md-3"><label class="form-label" for="akreditasi_lembaga">Lembaga Akreditasi</label><input class="form-control" id="akreditasi_lembaga" name="akreditasi_lembaga" value="<?= $v('akreditasi_lembaga') ?>" data-testid="setting-akreditasi-lembaga"></div>
            <div class="col-md-3"><label class="form-label" for="akreditasi_sk">Nomor SK</label><input class="form-control" id="akreditasi_sk" name="akreditasi_sk" value="<?= $v('akreditasi_sk') ?>" data-testid="setting-akreditasi-sk"></div>
            <div class="col-md-3"><label class="form-label" for="akreditasi_masa">Masa Berlaku</label><input class="form-control" id="akreditasi_masa" name="akreditasi_masa" value="<?= $v('akreditasi_masa') ?>" data-testid="setting-akreditasi-masa"></div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-kontak" role="tabpanel">
        <div class="row g-3">
            <div class="col-12"><label class="form-label" for="address">Alamat</label><textarea class="form-control" id="address" name="address" rows="2" data-testid="setting-address"><?= $v('address') ?></textarea></div>
            <div class="col-md-4"><label class="form-label" for="phone">Telepon</label><input class="form-control" id="phone" name="phone" value="<?= $v('phone') ?>" data-testid="setting-phone"></div>
            <div class="col-md-4"><label class="form-label" for="whatsapp">WhatsApp (format 62…)</label><input class="form-control" id="whatsapp" name="whatsapp" value="<?= $v('whatsapp') ?>" data-testid="setting-whatsapp"></div>
            <div class="col-md-4"><label class="form-label" for="email">Email <span class="req">*</span></label><input type="email" class="form-control" id="email" name="email" value="<?= $v('email') ?>" required data-testid="setting-email"></div>
            <div class="col-md-6"><label class="form-label" for="office_hours">Jam Layanan</label><input class="form-control" id="office_hours" name="office_hours" value="<?= $v('office_hours') ?>" data-testid="setting-hours"></div>
            <div class="col-md-6"><label class="form-label" for="maps_embed">URL Embed Google Maps</label><input class="form-control" id="maps_embed" name="maps_embed" value="<?= $v('maps_embed') ?>" placeholder="https://www.google.com/maps/embed?..." data-testid="setting-maps"></div>
            <div class="col-md-4"><label class="form-label" for="facebook">Facebook</label><input class="form-control" id="facebook" name="facebook" value="<?= $v('facebook') ?>" data-testid="setting-facebook"></div>
            <div class="col-md-4"><label class="form-label" for="instagram">Instagram</label><input class="form-control" id="instagram" name="instagram" value="<?= $v('instagram') ?>" data-testid="setting-instagram"></div>
            <div class="col-md-4"><label class="form-label" for="youtube">YouTube</label><input class="form-control" id="youtube" name="youtube" value="<?= $v('youtube') ?>" data-testid="setting-youtube"></div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-lapor" role="tabpanel">
        <div class="row g-3">
            <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="lapor_diri_open" name="lapor_diri_open" value="1" <?= setting('lapor_diri_open') === '1' ? 'checked' : '' ?> data-testid="setting-lapor-open"><label class="form-check-label fw-semibold" for="lapor_diri_open">Buka formulir lapor diri</label></div></div>
            <div class="col-md-6"><label class="form-label" for="lapor_diri_period">Periode Lapor Diri</label><input class="form-control" id="lapor_diri_period" name="lapor_diri_period" value="<?= $v('lapor_diri_period') ?>" placeholder="1 – 15 Desember 2026" data-testid="setting-lapor-period"></div>
            <div class="col-md-6"><label class="form-label" for="lapor_diri_hours">Keterangan Jam</label><input class="form-control" id="lapor_diri_hours" name="lapor_diri_hours" value="<?= $v('lapor_diri_hours') ?>" data-testid="setting-lapor-hours"></div>
            <div class="col-12"><label class="form-label" for="lapor_diri_info">Informasi / Ketentuan Lapor Diri</label><textarea class="form-control" id="lapor_diri_info" name="lapor_diri_info" rows="3" data-testid="setting-lapor-info"><?= $v('lapor_diri_info') ?></textarea></div>
            <div class="col-md-6"><label class="form-label" for="doc_requirements">Persyaratan Dokumen <span class="req">*</span></label>
                <textarea class="form-control font-monospace small" id="doc_requirements" name="doc_requirements" rows="6" required data-testid="setting-doc-requirements"><?= $v('doc_requirements') ?></textarea>
                <div class="form-text">Satu dokumen per baris: <code>kode|Nama Dokumen|1</code> (1 = wajib, 0 = opsional). Kode: huruf kecil/angka/garis bawah.</div></div>
            <div class="col-md-6"><label class="form-label" for="ppg_fields">Bidang Studi PPG <span class="req">*</span></label>
                <textarea class="form-control" id="ppg_fields" name="ppg_fields" rows="6" required data-testid="setting-ppg-fields"><?= $v('ppg_fields') ?></textarea>
                <div class="form-text">Satu bidang studi per baris.</div></div>
            <div class="col-md-6"><span class="form-label d-block">Format Dokumen Diizinkan</span>
                <?php foreach ($allowed_doc_ext as $x): ?><div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" name="doc_allowed_ext[]" value="<?= $x ?>" id="ext_<?= $x ?>" <?= in_array($x, $exts, TRUE) ? 'checked' : '' ?> data-testid="setting-ext-<?= $x ?>"><label class="form-check-label" for="ext_<?= $x ?>"><?= strtoupper($x) ?></label></div><?php endforeach; ?></div>
            <div class="col-md-3"><label class="form-label" for="doc_max_size_kb">Maks. Ukuran Dokumen (KB)</label><input type="number" class="form-control" id="doc_max_size_kb" name="doc_max_size_kb" value="<?= $v('doc_max_size_kb') ?>" min="100" max="20480" data-testid="setting-doc-max"></div>
            <div class="col-md-3"><label class="form-label" for="image_max_size_kb">Maks. Ukuran Gambar (KB)</label><input type="number" class="form-control" id="image_max_size_kb" name="image_max_size_kb" value="<?= $v('image_max_size_kb') ?>" min="100" max="10240" data-testid="setting-img-max"></div>
            <div class="col-md-3"><label class="form-label" for="image_max_width">Lebar Maks. Gambar (px)</label><input type="number" class="form-control" id="image_max_width" name="image_max_width" value="<?= $v('image_max_width') ?>" min="400" max="3000" data-testid="setting-img-width"></div>
            <div class="col-md-3"><label class="form-label" for="image_quality">Kualitas Kompresi (%)</label><input type="number" class="form-control" id="image_quality" name="image_quality" value="<?= $v('image_quality') ?>" min="40" max="95" data-testid="setting-img-quality"></div>
            <div class="col-md-3"><label class="form-label" for="news_per_page">Berita per Halaman</label><input type="number" class="form-control" id="news_per_page" name="news_per_page" value="<?= $v('news_per_page') ?>" min="1" max="50" data-testid="setting-news-per-page"></div>
            <div class="col-md-3"><label class="form-label" for="admin_per_page">Data per Halaman (Admin)</label><input type="number" class="form-control" id="admin_per_page" name="admin_per_page" value="<?= $v('admin_per_page') ?>" min="1" max="100" data-testid="setting-admin-per-page"></div>
            <div class="col-12"><div class="alert alert-info small mb-0"><i class="bi bi-info-circle me-1"></i>Batas upload server saat ini: <strong><?= e(ini_get('upload_max_filesize')) ?></strong> per file, <strong><?= e(ini_get('post_max_size')) ?></strong> per formulir. Ubah melalui cPanel &rarr; MultiPHP INI Editor jika diperlukan.</div></div>
        </div>
    </div>
</div>
<div class="mt-4"><button type="submit" class="btn btn-navy px-4" data-testid="settings-save-btn"><i class="bi bi-check2 me-1"></i>Simpan Pengaturan</button></div>
<?= form_close() ?>
