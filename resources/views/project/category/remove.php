<div class="page-header">
    <h2><?= t('Remove a category') ?></h2>
</div>

<form action="<?= $this->url->href('Project/CategoryController', 'remove', ['project_id' => $project['id'], 'category_id' => $category['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this category: "%s"?', $category['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Project/CategoryController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
