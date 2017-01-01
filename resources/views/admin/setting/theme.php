<div class="page-header">
    <h2><?= t('Theme settings') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Admin/SettingController', 'store', ['redirect' => 'theme']) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Skin'), 'application_skin') ?>
    <?= $this->form->select('application_skin', $skins, $values, $errors) ?>

    <?= $this->form->label(t('Layout'), 'application_layout') ?>
    <?= $this->form->select('application_layout', $layouts, $values, $errors) ?>

    <?= $this->form->label(t('Default Dashboard'), 'application_dashboard') ?>
    <?= $this->form->select('application_dashboard', $dashboards, $values, $errors) ?>

    <?= $this->form->label(t('Custom Stylesheet'), 'application_stylesheet') ?>
    <?= $this->form->textarea('application_stylesheet', $values, $errors) ?>

    <?= $this->hook->render('template:admin/setting:theme', ['values' => $values, 'errors' => $errors]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
    </div>
</form>
