<div class="page-header">
    <h2><?= t('Project unstar') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-danger">
        <?= t('Do you really want to unstar this project: "%s"?', $project['name']) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'Project/ProjectController', 'unstar', ['project_id' => $project['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Dashboard/DashboardController', 'stars', ['user_id' => $this->user->getId()], false, 'close-popover') ?>
    </div>
</div>
