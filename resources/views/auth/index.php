<div class="form-login">

    <?= $this->hook->render('template:auth:login-form:before') ?>

    <?php if (isset($errors['login'])): ?>
        <p class="alert alert-error"><?= $this->text->e($errors['login']) ?></p>
    <?php endif ?>

    <h2><?= t('Sign in to %s', $this->app->setting('application_name') ?: 'Hiject') ?></h2>

    <?php if (!HIDE_LOGIN_FORM) : ?>
    <form method="post" action="<?= $this->url->href('AuthController', 'check') ?>">

        <?= $this->form->csrf() ?>

        <?= $this->form->label(t('Username or Email'), 'username') ?>
        <?= $this->form->text('username', $values, $errors, ['autofocus']) ?>

        <?= $this->form->label(t('Password'), 'password') ?>
        <?= $this->form->password('password', $values, $errors) ?>

        <?php if (isset($captcha) && $captcha): ?>
            <?= $this->form->label(t('Enter the text below'), 'captcha') ?>
            <img src="<?= $this->url->href('CaptchaController', 'image') ?>" alt="Captcha">
            <?= $this->form->text('captcha', [], $errors) ?>
        <?php endif ?>

        <?php if (REMEMBER_ME_AUTH): ?>
            <?= $this->form->checkbox('remember_me', t('Remember Me'), 1, true) ?>
        <?php endif ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-block"><?= t('Sign in') ?></button>
        </div>
        <?php if ($this->app->setting('password_reset') == 1): ?>
            <div class="reset-password">
                <?= $this->url->link(t('Forgot password?'), 'PasswordResetController', 'create') ?>
            </div>
        <?php endif ?>
    </form>
    <?php endif ?>

    <?= $this->hook->render('template:auth:login-form:after') ?>
</div>
