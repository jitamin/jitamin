<div class="page-header">
    <h2><?= t('Project star') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to star this project: "%s"?', $project['name']) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'Project/ProjectController', 'star', ['project_id' => $project['id']], true, 'btn btn-info') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Dashboard/DashboardController', 'stars', ['user_id' => $this->user->getId()], false, 'close-popover') ?>
    </div>
</div>
