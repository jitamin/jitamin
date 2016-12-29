<form method="post" action="<?= $this->url->href('Profile/TwoFactorController', 'check', ['user_id' => $this->user->getId()]) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->label(t('Code'), 'code') ?>
    <?= $this->form->text('code', [], [], ['placeholder="123456"', 'autofocus'], 'form-numeric') ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Check my code') ?></button>
    </div>
</form>
