<div class="page-header">
    <h2><?= t('Remove plugin') ?></h2>
</div>

<form action="<?= $this->url->href('Admin/PluginController', 'uninstall', ['pluginId' => $plugin_id]) ?>" method="post" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="confirm">
        <p class="alert alert-info"><?= t('Do you really want to remove this plugin: "%s"?', $plugin->getPluginName()) ?></p>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><?= t('Confirm') ?></button>
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'Admin/PluginController', 'show', [], false, 'close-popover') ?>
        </div>
    </div>
</form>
