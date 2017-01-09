<div class="page-header">
    <h2><?= t('Remove a tag') ?></h2>
</div>

<form action="<?= $this->url->href('Manage/ProjectTagController', 'remove', ['tag_id' => $tag['id'], 'project_id' => $project['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this tag: "%s"?', $tag['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Manage/ProjectTagController', 'index', ['project_id' => $project['id']], false, 'close-popover') ?>
        </div>
    </div>
</form>
