<div class="page-header">
    <h2><?= t('Project activation') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to enable this project: "%s"?', $project['name']) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'Project/ProjectStatusController', 'enable', ['project_id' => $project['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Project/ProjectSettingsController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</div>
