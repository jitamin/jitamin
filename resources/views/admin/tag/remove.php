<div class="page-header">
    <h2><?= t('Remove a tag') ?></h2>
</div>

<form action="<?= $this->url->href('Admin/TagController', 'remove', ['tag_id' => $tag['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this tag: "%s"?', $tag['name']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Admin/TagController', 'index', [], false, 'close-popover') ?>
        </div>
    </div>
</form>
