<div class="page-header">
    <h2><?= t('Public access') ?></h2>
</div>

<?php if (!empty($user['token'])): ?>
    <div class="listing">
        <ul class="no-bullet">
            <li><strong><i class="fa fa-rss-square"></i> <?= $this->url->link(t('RSS feed'), 'FeedController', 'user', ['token' => $user['token']], false, '', '', true) ?></strong></li>
            <li><strong><i class="fa fa-calendar"></i> <?= $this->url->link(t('iCal feed'), 'ICalendarController', 'user', ['token' => $user['token']], false, '', '', true) ?></strong></li>
        </ul>
    </div>
    <?= $this->url->link(t('Disable public access'), 'Profile/ProfileController', 'share', ['user_id' => $user['id'], 'switch' => 'disable'], true, 'btn btn-danger') ?>
<?php else: ?>
    <?= $this->url->link(t('Enable public access'), 'Profile/ProfileController', 'share', ['user_id' => $user['id'], 'switch' => 'enable'], true, 'btn btn-success') ?>
<?php endif ?>
