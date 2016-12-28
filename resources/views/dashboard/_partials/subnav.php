<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('DashboardController', 'index') ?>>
            <?= $this->url->link(t('My projects'), 'DashboardController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'stars') ?>>
            <?= $this->url->link(t('My stars'), 'DashboardController', 'stars') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'tasks') ?>>
            <?= $this->url->link(t('My tasks'), 'DashboardController', 'tasks') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'subtasks') ?>>
            <?= $this->url->link(t('My subtasks'), 'DashboardController', 'subtasks') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'calendar') ?>>
            <?= $this->url->link(t('My calendar'), 'DashboardController', 'calendar') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'activities') ?>>
            <?= $this->url->link(t('My activities'), 'DashboardController', 'activities') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'notifications') ?>>
            <?= $this->url->link(t('My notifications'), 'DashboardController', 'notifications') ?>
        </li>
        <?= $this->hook->render('template:dashboard:subside', ['user' => $user]) ?>
    </ul>
</div>