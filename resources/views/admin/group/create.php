<div class="page-header">
    <h2><?= t('New group') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('Admin/GroupController', 'store') ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, ['autofocus', 'required', 'maxlength="100"']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Admin/GroupController', 'index', [], false, 'close-popover') ?>
    </div>
</form>
