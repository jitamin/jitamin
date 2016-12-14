<section id="main">
    <div class="page-header">
        <h2><?= e($user['name'] ?: $user['username']) ?></h2>
    </div>
    <?= $this->avatar->render($user['id'], $user['username'], $user['name'], $user['email'], $user['avatar_path'], 'avatar-left', 84) ?>
    <ul class="listing">
        <li><?= t('Username:') ?> <strong><?= $this->text->e($user['username']) ?></strong></li>
        <li><?= t('Name:') ?> <strong><?= $this->text->e($user['name']) ?: t('None') ?></strong></li>
        <li><?= t('Email:') ?> <strong><?= $this->text->e($user['email']) ?: t('None') ?></strong></li>
    </ul>
</section>

<div class="page-header">
    <h3><?= t('%s\'s activity', $user['name'] ?: $user['username']) ?></h3>
</div>

<?= $this->render('event/events', ['events' => $events]) ?>