<div class="page-header">
<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('ProjectController', 'index')): ?>
    <li <?= $this->app->setActive('DashboardController', 'index') ?>><?= $this->url->link('<i class="fa fa-cubes"></i> '.t('My projects'), 'DashboardController', 'index') ?></li>
    <?php endif ?>
    <?php if ($this->user->hasAccess('DashboardController', 'stars')): ?>
    <li <?= $this->app->setActive('DashboardController', 'stars') ?>><?= $this->url->link('<i class="fa fa-star"></i> '.t('My stars'), 'DashboardController', 'stars') ?></li>
    <?php endif ?>
</ul>
</div>