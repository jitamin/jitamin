<div class="page-header">
    <h2><?= t('Remove a custom role') ?></h2>
</div>

<form action="<?= $this->url->href('Project/ProjectRoleController', 'remove', ['project_id' => $project['id'], 'role_id' => $role['role_id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this custom role: "%s"? All people assigned to this role will become project member.', $role['role']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Project/ProjectRoleController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
