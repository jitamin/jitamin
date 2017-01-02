<div class="page-header">
    <ul class="nav nav-tabs">
        <?php if ($this->user->hasAccess('Profile/ProfileController', 'show')): ?>
            <li <?= $this->app->setActive('Profile/ProfileController', 'show') ?>>
                <?= $this->url->link(t('Summary'), 'Profile/ProfileController', 'show', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'timesheet')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'timesheet') ?>>
                    <?= $this->url->link(t('Time tracking'), 'Profile/ProfileController', 'timesheet', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'lastLogin')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'lastLogin') ?>>
                    <?= $this->url->link(t('Last logins'), 'Profile/ProfileController', 'lastLogin', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'sessions')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'sessions') ?>>
                    <?= $this->url->link(t('Persistent connections'), 'Profile/ProfileController', 'sessions', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'passwordReset')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'passwordReset') ?>>
                    <?= $this->url->link(t('Password reset history'), 'Profile/ProfileController', 'passwordReset', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
        <?php endif ?>
        <?php if ($this->user->hasAccess('Profile/ProfileController', 'edit')): ?>
            <li <?= $this->app->setActive('Profile/ProfileController', 'edit') ?>>
                <?= $this->url->link(t('Edit profile'), 'Profile/ProfileController', 'edit', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:user:subside:information', ['user' => $user]) ?>
    </ul>
</div>