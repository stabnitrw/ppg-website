<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('page_lead' => 'Kami siap membantu kebutuhan informasi dan layanan akademik Anda.')); ?>
<section class="section pt-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach (array(
                array('bi-geo-alt', 'Alamat', setting('address')),
                array('bi-telephone', 'Telepon', setting('phone')),
                array('bi-envelope', 'Email', setting('email')),
                array('bi-clock', 'Jam Layanan', setting('office_hours')),
            ) as $i => $c): ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="info-tile h-100 reveal" data-testid="contact-tile-<?= $i ?>"><i class="bi <?= $c[0] ?>"></i><h3><?= $c[1] ?></h3><p><?= e($c[2]) ?></p></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row g-4 mt-1">
            <div class="col-lg-8"><div class="map-wrap map-lg reveal"><iframe src="<?= e(setting('maps_embed')) ?>" title="Peta lokasi" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div></div>
            <div class="col-lg-4">
                <div class="side-card side-cta h-100">
                    <h5>Layanan Cepat</h5>
                    <p class="small">Hubungi admin fakultas melalui WhatsApp pada jam layanan.</p>
                    <?php if (setting('whatsapp')): ?>
                        <a href="https://wa.me/<?= e(preg_replace('/\D/', '', setting('whatsapp'))) ?>" target="_blank" rel="noopener" class="btn btn-gold w-100 mb-2" data-testid="contact-whatsapp-btn"><i class="bi bi-whatsapp me-1"></i>Chat WhatsApp</a>
                    <?php endif; ?>
                    <a href="mailto:<?= e(setting('email')) ?>" class="btn btn-outline-light w-100" data-testid="contact-email-btn"><i class="bi bi-envelope me-1"></i>Kirim Email</a>
                </div>
            </div>
        </div>
    </div>
</section>
