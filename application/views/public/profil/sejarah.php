<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('crumbs' => array('Profil' => site_url('profil')))); ?>
<section class="section pt-5">
    <div class="container">
        <?php $this->load->view('public/profil/_nav'); ?>
        <div class="row justify-content-center mt-2">
            <div class="col-lg-9">
                <div class="prose" data-testid="profile-section-content"><?= $page ? $page['content'] : '<p>Konten belum tersedia.</p>' ?></div>
            </div>
        </div>
    </div>
</section>
