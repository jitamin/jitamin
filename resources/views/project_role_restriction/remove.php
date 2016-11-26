<div class="page-header">
    <h2><?= t('Remove a project restriction') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this project restriction: "%s"?', $this->text->in($restriction['rule'], $restrictions)) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Yes'), 'ProjectRoleRestrictionController', 'remove', ['project_id' => $project['id'], 'restriction_id' => $restriction['restriction_id']], true, 'btn btn-danger') ?>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'ProjectRoleController', 'show', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</div>
