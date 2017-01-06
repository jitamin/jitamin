<div class="page-header">
    <ul class="nav nav-tabs">
        <?php if ($this->user->hasAccess('SearchController', 'index')): ?>
        <li <?= $this->app->setActive('SearchController', 'index') ?>><?= $this->url->link(t('Search tasks'), 'SearchController', 'index') ?></li>
        <?php endif ?>
        <?php if ($this->user->hasAccess('SearchController', 'activity')): ?>
        <li <?= $this->app->setActive('SearchController', 'activity') ?>><?= $this->url->link(t('Search in activities'), 'SearchController', 'activity') ?></li>
        <?php endif ?>
    </ul>
</div>