<div class="page-header">
    <h2><?= t('Summary') ?></h2>
</div>
<ul class="listing">
    <li><?= t('Username:') ?> <?= $this->text->e($user['username']) ?></li>
    <li><?= t('Name:') ?> <?= $this->text->e($user['name']) ?: t('None') ?></li>
    <li><?= t('Email:') ?> <?= $this->text->e($user['email']) ?: t('None') ?></li>
    <li><?= t('Status:') ?> <?= $user['is_active'] ? t('Active') : t('Inactive') ?></li>
</ul>

<div class="page-header">
    <h2><?= t('Security') ?></h2>
</div>
<ul class="listing">
    <li><?= t('Role:') ?> <?= $this->user->getRoleName($user['role']) ?></li>
    <li><?= t('Account type:') ?> <?= $user['is_ldap_user'] ? t('Remote') : t('Local') ?></li>
    <li><?= $user['twofactor_activated'] == 1 ? t('Two factor authentication enabled') : t('Two factor authentication disabled') ?></li>
    <li><?= t('Number of failed login:') ?> <?= $user['nb_failed_login'] ?></li>
    <?php if ($user['lock_expiration_date'] != 0): ?>
        <li><?= t('Account locked until:') ?> <?= $this->dt->datetime($user['lock_expiration_date']) ?></li>
        <?php if ($this->user->isAdmin()): ?>
            <li>
                <?= $this->url->link(t('Unlock this user'), 'UserCredentialController', 'unlock', ['user_id' => $user['id']], true) ?>
            </li>
        <?php endif ?>
    <?php endif ?>
</ul>

<div class="page-header">
    <h2><?= t('Preferences') ?></h2>
</div>
<ul class="listing">
    <li><?= t('Timezone:') ?> <?= $this->text->in($user['timezone'], $timezones) ?></li>
    <li><?= t('Language:') ?> <?= $this->text->in($user['language'], $languages) ?></li>
    <li><?= t('Notifications:') ?> <?= $user['notifications_enabled'] == 1 ? t('Enabled') : t('Disabled') ?></li>
</ul>

<?php if (!empty($user['token'])): ?>
    <div class="page-header">
        <h2><?= t('Public access') ?></h2>
    </div>

    <div class="listing">
        <ul class="no-bullet">
            <li><i class="fa fa-rss-square"></i> <?= $this->url->link(t('RSS feed'), 'FeedController', 'user', ['token' => $user['token']], false, '', '', true) ?></li>
            <li><i class="fa fa-calendar"></i> <?= $this->url->link(t('iCal feed'), 'ICalendarController', 'user', ['token' => $user['token']], false, '', '', true) ?></li>
        </ul>
    </div>
<?php endif ?>
