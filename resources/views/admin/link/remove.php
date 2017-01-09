<div class="page-header">
    <h2><?= t('Remove a link') ?></h2>
</div>

<form action="<?= $this->url->href('Admin/LinkController', 'remove', ['link_id' => $link['id']]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to remove this link: "%s"?', $link['label']) ?>
        </p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Admin/LinkController', 'index') ?>
        </div>
    </div>
</form>