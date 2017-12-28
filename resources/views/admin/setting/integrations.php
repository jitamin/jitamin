<div class="page-header">
    <h2><?= t('Integration with third-party services') ?></h2>
</div>

<form method="post" action="<?= $this->url->href('Admin/SettingController', 'store', ['redirect' => 'integrations']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <?= $this->hook->render('template:config:integrations', ['values' => $values]) ?>

    <h3><img src="<?= $this->url->dir() ?>assets/img/gravatar-icon.png"/>&nbsp;<?= t('Gravatar') ?></h3>
    <div class="listing">
        <?= $this->form->checkbox('integration_gravatar', t('Enable Gravatar images'), 1, $values['integration_gravatar'] == 1) ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-success"><?= t('Save') ?></button>
    </div>
</form>
