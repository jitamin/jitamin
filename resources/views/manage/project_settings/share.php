<div class="page-header">
    <h2><?= t('Public access') ?></h2>
</div>

<?php if ($project['is_public']): ?>

    <div class="listing">
        <ul class="no-bullet">
            <li><strong><i class="fa fa-share-alt"></i> <?= $this->url->link(t('Public link'), 'Project/Board/BoardController', 'readonly', ['token' => $project['token']], false, '', '', true) ?></strong></li>
            <li><strong><i class="fa fa-rss-square"></i> <?= $this->url->link(t('RSS feed'), 'FeedController', 'project', ['token' => $project['token']], false, '', '', true) ?></strong></li>
            <li><strong><i class="fa fa-calendar"></i> <?= $this->url->link(t('iCal feed'), 'ICalendarController', 'project', ['token' => $project['token']], false, '', '', true) ?></strong></li>
        </ul>
    </div>

    <?= $this->url->link(t('Disable public access'), 'Manage/ProjectSettingsController', 'updateSharing', ['project_id' => $project['id'], 'switch' => 'disable'], true, 'btn btn-danger') ?>
<?php else: ?>
    <?= $this->url->link(t('Enable public access'), 'Manage/ProjectSettingsController', 'updateSharing', ['project_id' => $project['id'], 'switch' => 'enable'], true, 'btn btn-success') ?>
<?php endif ?>
