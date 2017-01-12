<div class="page-header">
    <ul class="nav nav-tabs">
        <?php if ($this->user->isAdmin() || $this->user->isCurrentUser($user['id'])): ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'edit')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'edit') ?>>
                    <?= $this->url->link(t('Edit profile'), 'Profile/ProfileController', 'edit', ['user_id' => $user['id']]) ?>
                </li>
                 <li <?= $this->app->setActive('Profile/ProfileController', 'preferences') ?>>
                    <?= $this->url->link(t('Preferences'), 'Profile/ProfileController', 'preferences', ['user_id' => $user['id']]) ?>
                </li>
                <li <?= $this->app->setActive('Profile/AvatarController') ?>>
                    <?= $this->url->link(t('Avatar'), 'Profile/AvatarController', 'show', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($user['is_ldap_user'] == 0 && $this->user->hasAccess('Profile/ProfileController', 'changePassword')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'changePassword') ?>>
                    <?= $this->url->link(t('Change password'), 'Profile/ProfileController', 'changePassword', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($this->user->isCurrentUser($user['id']) && $this->user->hasAccess('Profile/TwoFactorController', 'index')): ?>
                <li <?= $this->app->setActive('Profile/TwoFactorController', 'index') ?>>
                    <?= $this->url->link(t('Two factor authentication'), 'Profile/TwoFactorController', 'index', ['user_id' => $user['id']]) ?>
                </li>
            <?php elseif ($this->user->hasAccess('Profile/TwoFactorController', 'disable') && $user['twofactor_activated'] == 1): ?>
                <li <?= $this->app->setActive('Profile/TwoFactorController', 'disable') ?>>
                    <?= $this->url->link(t('Two factor authentication'), 'Profile/TwoFactorController', 'disable', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>

            <?php if ($this->user->hasAccess('Profile/ProfileController', 'share')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'share') ?>>
                    <?= $this->url->link(t('Public access'), 'Profile/ProfileController', 'share', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'notifications')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'notifications') ?>>
                    <?= $this->url->link(t('Notifications'), 'Profile/ProfileController', 'notifications', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'external')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'external') ?>>
                    <?= $this->url->link(t('External accounts'), 'Profile/ProfileController', 'external', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'integrations')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'integrations') ?>>
                    <?= $this->url->link(t('Integrations'), 'Profile/ProfileController', 'integrations', ['user_id' => $user['id']]) ?>
                </li>
            <?php endif ?>
            <?php if ($this->user->hasAccess('Profile/ProfileController', 'api')): ?>
                <li <?= $this->app->setActive('Profile/ProfileController', 'api') ?>>
                    <?= $this->url->link(t('API'), 'Profile/ProfileController', 'api', array('user_id' => $user['id'])) ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?php if ($this->user->hasAccess('Admin/UserController', 'changeAuthentication')): ?>
            <li <?= $this->app->setActive('Admin/UserController', 'changeAuthentication') ?>>
                <?= $this->url->link(t('Edit Authentication'), 'Admin/UserController', 'changeAuthentication', ['user_id' => $user['id']]) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:user:subnav:information', ['user' => $user]) ?>
    </ul>
</div>