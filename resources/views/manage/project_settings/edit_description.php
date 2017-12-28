<div class="page-header">
    <h2><?= t('Edit description') ?></h2>
</div>
<form method="post" class="popover-form" action="<?= $this->url->href('Manage/ProjectSettingsController', 'update', ['project_id' => $project['id'], 'redirect' => 'description']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('name', $values) ?>
    <?= $this->form->textEditor('description', $values, $errors, ['autofocus' => true]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-success"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Project/ProjectController', 'show', ['project_id' => $project['id']], false, 'btn btn-default close-popover') ?>
    </div>
</form>
