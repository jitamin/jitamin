<div class="page-header">
    <h2><?= t('Remove project') ?></h2>
</div>

<form action="<?= $this->url->href('Manage/ProjectStatusController', 'remove', ['project_id' => $project['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this project: "%s"?', $project['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?> <?= $this->url->link(t('cancel'), 'Project/ProjectController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
