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


    <?= $this->form->label(t('Skin'), 'application_skin') ?>
    <?= $this->form->select('application_skin', $skins, $values, $errors) ?>

    <?= $this->form->label(t('Layout'), 'application_layout') ?>
    <?= $this->form->select('application_layout', $layouts, $values, $errors) ?>

    <?= $this->form->label(t('Default Dashboard'), 'application_dashboard') ?>
    <?= $this->form->select('application_dashboard', $dashboards, $values, $errors) ?>

    <?= $this->form->label(t('Language'), 'application_language') ?>
    <?= $this->form->select('application_language', $languages, $values, $errors) ?>

    <?= $this->form->label(t('Timezone'), 'application_timezone') ?>
    <?= $this->form->select('application_timezone', $timezones, $values, $errors) ?>

    <?= $this->form->label(t('Date format'), 'application_date_format') ?>
    <?= $this->form->select('application_date_format', $date_formats, $values, $errors) ?>
    <p class="form-help"><?= t('ISO format is always accepted, example: "%s" and "%s"', date('Y-m-d'), date('Y_m_d')) ?></p>

    <?= $this->form->label(t('Date and time format'), 'application_datetime_format') ?>
    <?= $this->form->select('application_datetime_format', $datetime_formats, $values, $errors) ?>

    <?= $this->form->label(t('Time format'), 'application_time_format') ?>
    <?= $this->form->select('application_time_format', $time_formats, $values, $errors) ?>

    <?= $this->form->checkbox('password_reset', t('Enable "Forget Password"'), 1, $values['password_reset'] == 1) ?>

    <?= $this->form->label(t('Custom Stylesheet'), 'application_stylesheet') ?>
    <?= $this->form->textarea('application_stylesheet', $values, $errors) ?>

    <?= $this->hook->render('template:config:application', ['values' => $values, 'errors' => $errors]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
    </div>
</form>
