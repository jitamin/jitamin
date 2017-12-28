<div class="form-login">
    <h2><?= t('Password Reset') ?></h2>
    <form method="post" action="<?= $this->url->href('Auth/PasswordResetController', 'store') ?>">
        <?= $this->form->csrf() ?>

        <?= $this->form->label(t('Username or Email'), 'username') ?>
        <?= $this->form->text('username', $values, $errors, ['autofocus']) ?>

        <?= $this->form->label(t('Enter the text below'), 'captcha') ?>
        <img src="<?= $this->url->href('CaptchaController', 'image') ?>" alt="Captcha" class="captcha-img">
        <?= $this->form->text('captcha', [], $errors) ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-success"><?= t('Change Password') ?></button>
        </div>
    </form>
</div>
