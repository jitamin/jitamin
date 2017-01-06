<div class="page-header">
    <h2><?= t('Edit project') ?></h2>
</div>
<form method="post" class="popover-form" action="<?= $this->url->href('Manage/ProjectSettingsController', 'update', ['project_id' => $project['id'], 'redirect' => 'edit']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>

    <?= $this->form->label(t('Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, ['required', 'maxlength="50"']) ?>

    <?= $this->form->label(t('Identifier'), 'identifier') ?>
    <?= $this->form->text('identifier', $values, $errors, ['maxlength="50"']) ?>
    <p class="form-help"><?= t('The project identifier is optional and must be alphanumeric, example: MYPROJECT.') ?></p>
    <?= $this->form->textEditor('description', $values, $errors, ['autofocus' => true]) ?>
    <hr>
    <?= $this->form->date(t('Start date'), 'start_date', $values, $errors) ?>
    <?= $this->form->date(t('End date'), 'end_date', $values, $errors) ?>
    <hr>
    <?= $this->form->label(t('Default priority'), 'priority_default') ?>
    <?= $this->form->number('priority_default', $values, $errors) ?>

    <?= $this->form->label(t('Lowest priority'), 'priority_start') ?>
    <?= $this->form->number('priority_start', $values, $errors) ?>

    <?= $this->form->label(t('Highest priority'), 'priority_end') ?>
    <?= $this->form->number('priority_end', $values, $errors) ?>

    <?= $this->form->label(t('Default view'), 'default_view') ?>
    <?= $this->form->select('default_view', $views, $values, $errors) ?>
    <hr>
    <div class="form-inline">
        <?= $this->form->label(t('Project owner'), 'owner_id') ?>
        <?= $this->form->select('owner_id', $owners, $values, $errors) ?>
    </div>

    <?php if ($this->user->hasProjectAccess('Project/ProjectController', 'create', $project['id'])): ?>
        <hr>
        <?= $this->form->checkbox('is_private', t('Private project'), 1, $project['is_private'] == 1) ?>
        <p class="form-help"><?= t('Private projects do not have users and groups management.') ?></p>
    <?php endif ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Project/ProjectController', 'show', ['project_id' => $project['id']], false, 'btn btn-default close-popover') ?>
    </div>
</form>
