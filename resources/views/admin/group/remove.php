<div class="page-header">
    <h2><?= t('Remove group') ?></h2>
</div>

<form action="<?= $this->url->href('Admin/GroupController', 'remove', ['group_id' => $group['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info"><?= t('Do you really want to remove this group: "%s"?', $group['name']) ?></p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Admin/GroupController', 'index', [], false, 'close-popover') ?>
        </div>
    </div>
</form>
