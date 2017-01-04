<div class="page-header">
    <h2><?= t('Remove project') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this project: "%s"?', $project['name']) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'Project/ProjectStatusController', 'remove', ['project_id' => $project['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Project/ProjectController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</div>
