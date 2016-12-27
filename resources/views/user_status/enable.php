<div class="page-header">
    <h2><?= t('Enable user') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info"><?= t('Do you really want to enable this user: "%s"?', $user['name'] ?: $user['username']) ?></p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'UserStatusController', 'enable', ['user_id' => $user['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'UserController', 'index', [], false, 'close-popover') ?>
    </div>
</div>
