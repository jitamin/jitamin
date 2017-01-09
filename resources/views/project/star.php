<div class="page-header">
    <h2><?= t('Project star') ?></h2>
</div>

<form action="<?= $this->url->href('Project/ProjectController', 'star', ['project_id' => $project['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to star this project: "%s"?', $project['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Dashboard/DashboardController', 'stars', ['user_id' => $this->user->getId()], false, 'close-popover') ?>
        </div>
    </div>
</form>
