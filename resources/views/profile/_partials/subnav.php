<div class="page-header">
    <ul class="nav nav-tabs">
        <?php if ($this->user->hasAccess('ProfileController', 'show')): ?>
            <li <?= $this->app->setActive('ProfileController', 'show') ?>>
                <?= $this->url->link(t('Summary'), 'ProfileController', 'show', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>
            <?php if ($this->user->hasAccess('ProfileController', 'timesheet')): ?>
                <li <?= $this->app->setActive('ProfileController', 'timesheet') ?>>
                    <?= $this->url->link(t('Time tracking'), 'ProfileController', 'timesheet', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'lastLogin')): ?>
                <li <?= $this->app->setActive('ProfileController', 'lastLogin') ?>>
                    <?= $this->url->link(t('Last logins'), 'ProfileController', 'lastLogin', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'sessions')): ?>
                <li <?= $this->app->setActive('ProfileController', 'sessions') ?>>
                    <?= $this->url->link(t('Persistent connections'), 'ProfileController', 'sessions', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'passwordReset')): ?>
                <li <?= $this->app->setActive('ProfileController', 'passwordReset') ?>>
                    <?= $this->url->link(t('Password reset history'), 'ProfileController', 'passwordReset', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?= $this->hook->render('template:user:subside:information', ['user' => $user]) ?>
    </ul>
</div>