<div class="page-header">
    <ul class="nav nav-tabs">
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>
            <?php if ($this->user->hasAccess('Profile/HistoryController', 'timesheet')): ?>
                <li <?= $this->app->setActive('Profile/HistoryController', 'timesheet') ?>>
                    <?= $this->url->link(t('Time tracking'), 'Profile/HistoryController', 'timesheet', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/HistoryController', 'lastLogin')): ?>
                <li <?= $this->app->setActive('Profile/HistoryController', 'lastLogin') ?>>
                    <?= $this->url->link(t('Last logins'), 'Profile/HistoryController', 'lastLogin', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/HistoryController', 'sessions')): ?>
                <li <?= $this->app->setActive('Profile/HistoryController', 'sessions') ?>>
                    <?= $this->url->link(t('Persistent connections'), 'Profile/HistoryController', 'sessions', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/HistoryController', 'passwordReset')): ?>
                <li <?= $this->app->setActive('Profile/HistoryController', 'passwordReset') ?>>
                    <?= $this->url->link(t('Password reset history'), 'Profile/HistoryController', 'passwordReset', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
        <?php endif ?>
    </ul>
</div>