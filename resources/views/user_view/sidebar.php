<div class="sidebar sidebar-icons">
    <div class="sidebar-title">
        <h2><?= t('Information') ?></h2>
    </div>
    <ul>
        <?php if ($this->user->hasAccess('UserViewController', 'show')): ?>
            <li <?= $this->app->checkMenuSelection('UserViewController', 'show') ?>>
                <i class="fa fa-vcard"></i><?= $this->url->link(t('Summary'), 'UserViewController', 'show', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>
            <?php if ($this->user->hasAccess('UserViewController', 'timesheet')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'timesheet') ?>>
                    <i class="fa fa-history"></i><?= $this->url->link(t('Time tracking'), 'UserViewController', 'timesheet', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('UserViewController', 'lastLogin')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'lastLogin') ?>>
                    <i class="fa fa-sign-in"></i><?= $this->url->link(t('Last logins'), 'UserViewController', 'lastLogin', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('UserViewController', 'sessions')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'sessions') ?>>
                    <i class="fa fa-heartbeat"></i><?= $this->url->link(t('Persistent connections'), 'UserViewController', 'sessions', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('UserViewController', 'passwordReset')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'passwordReset') ?>>
                    <i class="fa fa-key"></i><?= $this->url->link(t('Password reset history'), 'UserViewController', 'passwordReset', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?= $this->hook->render('template:user:sidebar:information', ['user' => $user]) ?>
    </ul>

    <div class="sidebar-title">
        <h2><?= t('Actions') ?></h2>
    </div>
    <ul>
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>

            <?php if ($this->user->hasAccess('UserModificationController', 'show')): ?>
                <li <?= $this->app->checkMenuSelection('UserModificationController', 'show') ?>>
                    <i class="fa fa-edit"></i><?= $this->url->link(t('Edit profile'), 'UserModificationController', 'show', ['user_id' => $user['id']]) ?>
                </li>
                <li <?= $this->app->checkMenuSelection('AvatarFile') ?>>
                    <i class="fa fa-user-circle-o"></i><?= $this->url->link(t('Avatar'), 'AvatarFileController', 'show', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($user['is_ldap_user'] == 0 && $this->user->hasAccess('UserCredentialController', 'changePassword')): ?>
                <li <?= $this->app->checkMenuSelection('UserCredentialController', 'changePassword') ?>>
                    <i class="fa fa-key"></i><?= $this->url->link(t('Change password'), 'UserCredentialController', 'changePassword', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($this->user->isCurrentUser($user['id']) && $this->user->hasAccess('TwoFactorController', 'index')): ?>
                <li <?= $this->app->checkMenuSelection('TwoFactorController', 'index') ?>>
                    <i class="fa fa-check-square-o"></i><?= $this->url->link(t('Two factor authentication'), 'TwoFactorController', 'index', ['user_id' => $user['id']]) ?>
                </li>
            <?php elseif ($this->user->hasAccess('TwoFactorController', 'disable') && $user['twofactor_activated'] == 1): ?>
                <li <?= $this->app->checkMenuSelection('TwoFactorController', 'disable') ?>>
                    <i class="fa fa-check-square-o"></i><?= $this->url->link(t('Two factor authentication'), 'TwoFactorController', 'disable', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($this->user->hasAccess('UserViewController', 'share')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'share') ?>>
                    <i class="fa fa-external-link"></i><?= $this->url->link(t('Public access'), 'UserViewController', 'share', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('UserViewController', 'notifications')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'notifications') ?>>
                    <i class="fa fa-bell"></i><?= $this->url->link(t('Notifications'), 'UserViewController', 'notifications', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('UserViewController', 'external')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'external') ?>>
                    <i class="fa fa-user-secret"></i><?= $this->url->link(t('External accounts'), 'UserViewController', 'external', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('UserViewController', 'integrations')): ?>
                <li <?= $this->app->checkMenuSelection('UserViewController', 'integrations') ?>>
                    <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'UserViewController', 'integrations', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?php if ($this->user->hasAccess('UserCredentialController', 'changeAuthentication')): ?>
            <li <?= $this->app->checkMenuSelection('UserCredentialController', 'changeAuthentication') ?>>
                <i class="fa fa-street-view"></i><?= $this->url->link(t('Edit Authentication'), 'UserCredentialController', 'changeAuthentication', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:user:sidebar:actions', ['user' => $user]) ?>
    </ul>
</div>
