<div class="page-header">
    <h2><?= t('Preferences') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Profile/ProfileController', 'update', ['user_id' => $user['id'], 'redirect' => 'preferences']) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('username', $values) ?>

    <?= $this->form->label(t('Language:'), 'language') ?>
    <?= $this->form->select('language', $languages, $values, $errors, [$this->user->hasAccess('Profile/ProfileController', 'show/edit_language') ? '' : 'disabled']) ?>

    <?= $this->form->label(t('Skin:'), 'skin') ?>
    <?= $this->form->select('skin', $skins, $values, $errors, [$this->user->hasAccess('Profile/ProfileController', 'show/edit_skin') ? '' : 'disabled']) ?>

    <?= $this->form->label(t('Layout:'), 'layout') ?>
    <?= $this->form->select('layout', $layouts, $values, $errors, [$this->user->hasAccess('Profile/ProfileController', 'show/edit_layout') ? '' : 'disabled']) ?>

    <?= $this->form->label(t('Default Dashboard:'), 'dashboard') ?>
    <?= $this->form->select('dashboard', $dashboards, $values, $errors, [$this->user->hasAccess('Profile/ProfileController', 'show/edit_dashboard') ? '' : 'disabled']) ?>

    <?= $this->form->label(t('Timezone:'), 'timezone') ?>
    <?= $this->form->select('timezone', $timezones, $values, $errors, [$this->user->hasAccess('Profile/ProfileController', 'show/edit_timezone') ? '' : 'disabled']) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-success"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Profile/ProfileController', 'show', ['user_id' => $user['id']]) ?>
    </div>
</form>
