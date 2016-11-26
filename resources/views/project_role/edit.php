<div class="page-header">
    <h2><?= t('Edit custom project role') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('ProjectRoleController', 'update', ['project_id' => $project['id'], 'role_id' => $role['role_id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('project_id', $values) ?>
    <?= $this->form->hidden('role_id', $values) ?>

    <?= $this->form->label(t('Role'), 'role') ?>
    <?= $this->form->text('role', $values, $errors, ['autofocus', 'required', 'maxlength="50"']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'ProjectRoleController', 'show', [], false, 'close-popover') ?>
    </div>
</form>
