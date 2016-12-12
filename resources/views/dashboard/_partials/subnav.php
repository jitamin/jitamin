<div class="page-header">
<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('ProjectController', 'index')): ?>
    <li <?= $this->app->checkMenuSelection('DashboardController', 'index') ?>><?= $this->url->link('<i class="fa fa-cubes"></i> '.t('My projects'), 'DashboardController', 'index') ?></li>
    <?php endif ?>
    <?php if ($this->user->hasAccess('DashboardController', 'stars')): ?>
    <li <?= $this->app->checkMenuSelection('DashboardController', 'stars') ?>><?= $this->url->link('<i class="fa fa-sliders"></i> '.t('My stars'), 'DashboardController', 'stars', ['user_id' => $user['id']]) ?></li>
    <?php endif ?>
</ul>
</div>