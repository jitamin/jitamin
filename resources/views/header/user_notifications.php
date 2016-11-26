<?php if ($this->user->hasNotifications()): ?>
    <li class="notification">
        <?= $this->url->link('<i class="fa fa-bell web-notification-icon"></i>', 'DashboardController', 'notifications', ['user_id' => $this->user->getId()], false, '', t('Unread notifications')) ?>
    </li>
<?php endif ?>
