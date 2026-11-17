<?php defined('BASEPATH') OR exit('No direct script access allowed');
$v = function ($k, $d = '') use ($item) { return set_value($k, $item ? (string) $item[$k] : $d); };
$parent = (int) $v('parent_id', '0'); ?>
<?php if ($error): ?><div class="alert alert-danger" data-testid="form-error"><?= e($error) ?></div><?php endif; ?>
<?php if (validation_errors()): ?><div class="alert alert-danger" data-testid="form-validation-errors"><?= validation_errors('<div>', '</div>') ?></div><?php endif; ?>
<div class="row"><div class="col-xl-7">
<div class="panel">
    <?= form_open(current_url(), array('data-testid' => 'structure-form')) ?>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="position">Jabatan <span class="req">*</span></label><input type="text" class="form-control" id="position" name="position" value="<?= $v('position') ?>" required maxlength="150" data-testid="structure-position-input"></div>
            <div class="col-md-6"><label class="form-label" for="name">Nama Pejabat <span class="req">*</span></label><input type="text" class="form-control" id="name" name="name" value="<?= $v('name') ?>" required maxlength="150" data-testid="structure-name-input"></div>
            <div class="col-md-6"><label class="form-label" for="parent_id">Atasan Langsung</label>
                <select class="form-select" id="parent_id" name="parent_id" data-testid="structure-parent-select">
                    <option value="0">— Puncak struktur —</option>
                    <?php foreach ($options as $o): ?><option value="<?= (int) $o['id'] ?>" <?= $parent === (int) $o['id'] ? 'selected' : '' ?>><?= str_repeat('— ', $o['level']) . e($o['position'] . ' (' . $o['name'] . ')') ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4"><label class="form-label" for="unit">Unit</label><input type="text" class="form-control" id="unit" name="unit" value="<?= $v('unit') ?>" maxlength="150" data-testid="structure-unit-input"></div>
            <div class="col-md-2"><label class="form-label" for="sort_order">Urutan</label><input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= $v('sort_order', '0') ?>" min="0" data-testid="structure-order-input"></div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-navy" data-testid="structure-save-btn"><i class="bi bi-check2 me-1"></i>Simpan</button>
            <a href="<?= site_url('admin/struktur') ?>" class="btn btn-light" data-testid="structure-cancel-btn">Batal</a>
        </div>
    <?= form_close() ?>
</div>
</div></div>
