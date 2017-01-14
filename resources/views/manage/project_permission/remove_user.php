<div class="page-header">
    <h2><?= t('Remove user') ?></h2>
</div>

<form action="<?= $this->url->href('Manage/ProjectPermissionController', 'removeUser', ['project_id' => $project['id'], 'user_id' => $user['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info"><?= t('Do you really want to remove this user: "%s"? on the project "%s"', $user['name'] ?: $user['username'], $project['name']) ?></p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Manage/ProjectPermissionController', 'index', [], false, 'close-popover') ?>
        </div>
    </div>
</form>
