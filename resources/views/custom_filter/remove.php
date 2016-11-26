<section id="main">
    <div class="page-header">
        <h2><?= t('Remove a custom filter') ?></h2>
    </div>

    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this custom filter: "%s"?', $filter['name']) ?>
        </p>

        <div class="form-actions">
            <?= $this->url->link(t('Yes'), 'CustomFilterController', 'remove', ['project_id' => $project['id'], 'filter_id' => $filter['id']], true, 'btn btn-danger') ?>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'CustomFilterController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</section>
