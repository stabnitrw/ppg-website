<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    </div>
    <footer class="admin-footer">&copy; <?= date('Y') ?> <?= e(setting('site_name')) ?> – <?= e(setting('institution_name')) ?></footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($editor)): ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/lang/summernote-id-ID.min.js"></script>
<?php endif; ?>
<script>window.ADMIN = {uploadUrl: '<?= site_url('admin/berita/unggah-gambar') ?>'};</script>
<script src="<?= base_url('assets/js/admin.js') ?>?v=1"></script>
</body>
</html>
