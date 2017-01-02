<div class="page-header">
    <h2><?= t('Edit user') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Profile/ProfileController', 'update', ['user_id' => $user['id'], 'redirect' => 'edit']) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('id', $values) ?>

    <?= $this->form->label(t('Username:'), 'username') ?>
    <?= $this->form->text('username', $values, $errors, ['required', isset($values['is_ldap_user']) && $values['is_ldap_user'] == 1 ? 'readonly' : '', 'maxlength="50"']) ?>

    <?= $this->form->label(t('Name:'), 'name') ?>
    <?= $this->form->text('name', $values, $errors, [$this->user->hasAccess('Profile/ProfileController', 'show/edit_name') ? '' : 'readonly']) ?>

    <?= $this->form->label(t('Email:'), 'email') ?>
    <?= $this->form->email('email', $values, $errors, ['required', $this->user->hasAccess('Profile/ProfileController', 'show/edit_email') ? '' : 'readonly']) ?>

    <?php if ($this->user->isAdmin()): ?>
        <?= $this->form->label(t('Role:'), 'role') ?>
        <?= $this->form->select('role', $roles, $values, $errors) ?>
    <?php endif ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'Profile/ProfileController', 'show', ['user_id' => $user['id']]) ?>
    </div>
</form>
