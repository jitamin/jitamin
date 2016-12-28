<div class="page-header">
    <ul class="nav nav-tabs">
        <li <?= $this->app->setActive('DashboardController', 'index') ?>>
            <?= $this->url->link('<i class="fa fa-cubes"></i> '.t('My projects'), 'DashboardController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'stars') ?>>
            <?= $this->url->link('<i class="fa fa-star"></i> '.t('My stars'), 'DashboardController', 'stars') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'tasks') ?>>
            <?= $this->url->link('<i class="fa fa-tasks"></i> '.t('My tasks'), 'DashboardController', 'tasks') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'subtasks') ?>>
            <?= $this->url->link('<i class="fa fa-bookmark"></i> '.t('My subtasks'), 'DashboardController', 'subtasks') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'calendar') ?>>
            <?= $this->url->link('<i class="fa fa-calendar"></i> '.t('My calendar'), 'DashboardController', 'calendar') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'activities') ?>>
            <?= $this->url->link('<i class="fa fa-history"></i> '.t('My activities'), 'DashboardController', 'activities') ?>
        </li>
        <?= $this->hook->render('template:dashboard:subside', ['user' => $user]) ?>
    </ul>
</div>