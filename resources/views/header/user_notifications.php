<li class="notification">
<?php if ($this->user->hasNotifications()): ?>
    <?= $this->url->link('<i class="fa fa-bell web-notification-icon"></i>', 'DashboardController', 'notifications', [], false, '', t('You have unread notifications')) ?>
<?php else: ?>
    <?= $this->url->link('<i class="fa fa-bell"></i>', 'DashboardController', 'notifications', [], false, '', t('You have no unread notifications')) ?>
<?php endif ?>
</li>
