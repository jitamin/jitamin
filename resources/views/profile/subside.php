<div class="subside subside-icons">
    <div class="subside-title">
        <h2><?= t('Profile') ?></h2>
    </div>
    <ul>
        <?php if ($this->user->hasAccess('ProfileController', 'show')): ?>
            <li <?= $this->app->setActive('ProfileController', 'show') ?>>
                <i class="fa fa-vcard"></i><?= $this->url->link(t('Summary'), 'ProfileController', 'show', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>
            <?php if ($this->user->hasAccess('ProfileController', 'timesheet')): ?>
                <li <?= $this->app->setActive('ProfileController', 'timesheet') ?>>
                    <i class="fa fa-history"></i><?= $this->url->link(t('Time tracking'), 'ProfileController', 'timesheet', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'lastLogin')): ?>
                <li <?= $this->app->setActive('ProfileController', 'lastLogin') ?>>
                    <i class="fa fa-sign-in"></i><?= $this->url->link(t('Last logins'), 'ProfileController', 'lastLogin', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'sessions')): ?>
                <li <?= $this->app->setActive('ProfileController', 'sessions') ?>>
                    <i class="fa fa-heartbeat"></i><?= $this->url->link(t('Persistent connections'), 'ProfileController', 'sessions', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'passwordReset')): ?>
                <li <?= $this->app->setActive('ProfileController', 'passwordReset') ?>>
                    <i class="fa fa-key"></i><?= $this->url->link(t('Password reset history'), 'ProfileController', 'passwordReset', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?= $this->hook->render('template:user:subside:information', ['user' => $user]) ?>
    </ul>

    <div class="subside-title">
        <h2><?= t('Actions') ?></h2>
    </div>
    <ul>
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>

            <?php if ($this->user->hasAccess('ProfileController', 'edit')): ?>
                <li <?= $this->app->setActive('ProfileController', 'edit') ?>>
                    <i class="fa fa-edit"></i><?= $this->url->link(t('Edit profile'), 'ProfileController', 'edit', ['user_id' => $user['id']]) ?>
                </li>
                <li <?= $this->app->setActive('AvatarFile') ?>>
                    <i class="fa fa-user-circle-o"></i><?= $this->url->link(t('Avatar'), 'AvatarFileController', 'show', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($user['is_ldap_user'] == 0 && $this->user->hasAccess('ProfileController', 'changePassword')): ?>
                <li <?= $this->app->setActive('ProfileController', 'changePassword') ?>>
                    <i class="fa fa-key"></i><?= $this->url->link(t('Change password'), 'ProfileController', 'changePassword', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($this->user->isCurrentUser($user['id']) && $this->user->hasAccess('TwoFactorController', 'index')): ?>
                <li <?= $this->app->setActive('TwoFactorController', 'index') ?>>
                    <i class="fa fa-check-square-o"></i><?= $this->url->link(t('Two factor authentication'), 'TwoFactorController', 'index', ['user_id' => $user['id']]) ?>
                </li>
            <?php elseif ($this->user->hasAccess('TwoFactorController', 'disable') && $user['twofactor_activated'] == 1): ?>
                <li <?= $this->app->setActive('TwoFactorController', 'disable') ?>>
                    <i class="fa fa-check-square-o"></i><?= $this->url->link(t('Two factor authentication'), 'TwoFactorController', 'disable', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($this->user->hasAccess('ProfileController', 'share')): ?>
                <li <?= $this->app->setActive('ProfileController', 'share') ?>>
                    <i class="fa fa-external-link"></i><?= $this->url->link(t('Public access'), 'ProfileController', 'share', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'notifications')): ?>
                <li <?= $this->app->setActive('ProfileController', 'notifications') ?>>
                    <i class="fa fa-bell"></i><?= $this->url->link(t('Notifications'), 'ProfileController', 'notifications', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'external')): ?>
                <li <?= $this->app->setActive('ProfileController', 'external') ?>>
                    <i class="fa fa-user-secret"></i><?= $this->url->link(t('External accounts'), 'ProfileController', 'external', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'integrations')): ?>
                <li <?= $this->app->setActive('ProfileController', 'integrations') ?>>
                    <i class="fa fa-puzzle-piece"></i><?= $this->url->link(t('Integrations'), 'ProfileController', 'integrations', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('ProfileController', 'api')): ?>
                <li <?= $this->app->setActive('ProfileController', 'api') ?>>
                    <i class="fa fa-rocket"></i><?= $this->url->link(t('API'), 'ProfileController', 'api', array('user_id' => $user['id'])) ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?php if ($this->user->hasAccess('UserController', 'changeAuthentication')): ?>
            <li <?= $this->app->setActive('UserController', 'changeAuthentication') ?>>
                <i class="fa fa-street-view"></i><?= $this->url->link(t('Edit Authentication'), 'UserController', 'changeAuthentication', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:user:subside:actions', ['user' => $user]) ?>
    </ul>
</div>
