<div class="page-header">
    <h2><?= t('Project unstar') ?></h2>
</div>

<form action="<?= $this->url->href('Project/ProjectController', 'unstar', ['project_id' => $project['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-danger">
            <?= t('Do you really want to unstar this project: "%s"?', $project['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Dashboard/DashboardController', 'stars', ['user_id' => $this->user->getId()], false, 'close-popover') ?>
        </div>
    </div>
</form>
