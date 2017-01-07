<div class="page-header">
    <h2><?= t('Remove a file') ?></h2>
</div>

<form action="<?= $this->url->href('Project/ProjectFileController', 'remove', ['project_id' => $project['id'], 'file_id' => $file['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this file: "%s"?', $this->text->e($file['name'])) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Project/ProjectController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
