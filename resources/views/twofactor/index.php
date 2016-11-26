<div class="page-header">
    <h2><?= t('Two factor authentication') ?></h2>
</div>

<form method="post" action="<?= $this->url->href('TwoFactorController', $user['twofactor_activated'] == 1 ? 'deactivate' : 'show', ['user_id' => $user['id']]) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <p><?= t('Two-Factor Provider: ') ?><strong><?= $this->text->e($provider) ?></strong></p>
    <div class="form-actions">
        <?php if ($user['twofactor_activated'] == 1): ?>
            <button type="submit" class="btn btn-danger"><?= t('Disable two-factor authentication') ?></button>
        <?php else: ?>
            <button type="submit" class="btn btn-info"><?= t('Enable two-factor authentication') ?></button>
        <?php endif ?>
    </div>
</form>
