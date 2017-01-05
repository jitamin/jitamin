<div class="page-header">
    <h2><?= t('Remove a category') ?></h2>
</div>

<div class="confirm">
    <p class="alert alert-info">
        <?= t('Do you really want to remove this category: "%s"?', $category['name']) ?>
    </p>

    <div class="form-actions">
        <?= $this->url->link(t('Confirm'), 'CategoryController', 'remove', ['project_id' => $project['id'], 'category_id' => $category['id']], true, 'btn btn-danger') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'CategoryController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
    </div>
</div>
