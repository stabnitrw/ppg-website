<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/partials/page_hero', array('crumbs' => array('Profil' => site_url('profil'))));
if (!function_exists('render_org')) {
    function render_org($nodes)
    {
        echo '<ul>';
        foreach ($nodes as $n) {
            echo '<li><div class="org-node" data-testid="org-node-' . (int) $n['id'] . '"><span class="org-pos">' . e($n['position']) . '</span><strong>' . e($n['name']) . '</strong>'
                . ($n['unit'] ? '<small>' . e($n['unit']) . '</small>' : '') . '</div>';
            if ($n['children']) {
                render_org($n['children']);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}
?>
<section class="section pt-5">
    <div class="container">
        <?php $this->load->view('public/profil/_nav'); ?>
        <?php if ($tree): ?>
            <div class="org-scroll"><div class="org-tree" data-testid="org-tree"><?php render_org($tree); ?></div></div>
        <?php else: ?>
            <p class="text-muted">Struktur organisasi belum tersedia.</p>
        <?php endif; ?>
    </div>
</section>
