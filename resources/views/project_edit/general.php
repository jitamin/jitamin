<div class="page-header">
    <h2><?= t('Edit project') ?></h2>
    <ul>
        <li class="active"><?= $this->url->link(t('General'), 'ProjectEditController', 'edit', ['project_id' => $project['id']], false, 'popover-link') ?></li>
        <li><?= $this->url->link(t('Dates'), 'ProjectEditController', 'dates', ['project_id' => $project['id']], false, 'popover-link') ?></li>
        <li><?= $this->url->link(t('Task priority'), 'ProjectEditController', 'priority', ['project_id' => $project['id']], false, 'popover-link') ?></li>
    </ul>
</div>
<form method="post" class="popover-form" action="<?= $this->url->href('ProjectEditController', 'update', ['project_id' => $project['id'], 'redirect' => 'edit']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>

    <?= $this->form->label(t('Name'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, ['required', 'maxlength="50"']) ?>

    <?= $this->form->label(t('Identifier'), 'identifier') ?>
    <?= $this->form->text('identifier', $values, $errors, ['maxlength="50"']) ?>
    <p class="form-help"><?= t('The project identifier is optional and must be alphanumeric, example: MYPROJECT.') ?></p>
    <?= $this->form->textEditor('description', $values, $errors, ['autofocus' => true]) ?>
    <hr>
    <div class="form-inline">
        <?= $this->form->label(t('Project owner'), 'owner_id') ?>
        <?= $this->form->select('owner_id', $owners, $values, $errors) ?>
    </div>

    <?php if ($this->user->hasProjectAccess('ProjectController', 'create', $project['id'])): ?>
        <hr>
        <?= $this->form->checkbox('is_private', t('Private project'), 1, $project['is_private'] == 1) ?>
        <p class="form-help"><?= t('Private projects do not have users and groups management.') ?></p>
    <?php endif ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'ProjectViewController', 'show', ['project_id' => $project['id']], false, 'btn btn-default close-popover') ?>
    </div>
</form>
