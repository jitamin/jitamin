<p><?= t('To reset your password click on this link:') ?></p>

<p><?= $this->url->to('PasswordResetController', 'change', ['token' => $token], '', true) ?></p>

<hr>
Hiject
