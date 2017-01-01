<?php echo $this->render('admin/setting/update', ['is_outdated' => $is_outdated, 'current_version' => $current_version, 'latest_version' => $latest_version]) ?>
<div class="page-header">
    <h2><?= t('Application settings') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Admin/SettingController', 'store', ['redirect' => 'index']) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Application URL'), 'application_url') ?>
    <?= $this->form->text('application_url', $values, $errors, ['placeholder="http://example.jitamin.com/"']) ?>
    <p class="form-help"><?= t('Example: http://example.jitamin.com/ (used to generate absolute URLs)') ?></p>

    <?= $this->form->label(t('Application Name'), 'application_name') ?>
    <?= $this->form->text('application_name', $values, $errors, ['placeholder="Jitamin"']) ?>
    <p class="form-help"><?= t('Example: Jitamin (used to show on the navbar)') ?></p>

    <?= $this->form->label(t('Application Description'), 'application_description') ?>
    <?= $this->form->textarea('application_description', $values, $errors) ?>

    <?= $this->form->checkbox('password_reset', t('Enable "Forget Password"'), 1, $values['password_reset'] == 1) ?>

    <?= $this->hook->render('template:config:application', ['values' => $values, 'errors' => $errors]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
    </div>
</form>
