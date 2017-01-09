<div class="page-header">
    <h2><?= t('Remove a custom filter') ?></h2>
</div>

<form action="<?= $this->url->href('Project/CustomFilterController', 'remove', ['project_id' => $project['id'], 'filter_id' => $filter['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this custom filter: "%s"?', $filter['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Project/CustomFilterController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
