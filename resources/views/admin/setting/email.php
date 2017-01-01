<div class="page-header">
    <h2><?= t('Email settings') ?></h2>
</div>
<form method="post" action="<?= $this->url->href('Admin/SettingController', 'store', ['redirect' => 'email']) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>

    <?= $this->form->label(t('Email sender address'), 'mail_sender_address') ?>
    <?= $this->form->text('mail_sender_address', $values, $errors, ['placeholder="'.MAIL_FROM.'"']) ?>

    <?= $this->form->label(t('Email transport'), 'mail_transport') ?>
    <?= $this->form->select('mail_transport', $mail_transports, $values, $errors) ?>

    <?= $this->hook->render('template:config:email', ['values' => $values, 'errors' => $errors]) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-info"><?= t('Save') ?></button>
    </div>
</form>
