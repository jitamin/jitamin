<div class="sidebar sidebar-icons">
    <ul>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'index') ?>>
            <i class="fa fa-cubes"></i><?= $this->url->link(t('My projects'), 'DashboardController', 'index') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'tasks') ?>>
            <i class="fa fa-tasks"></i><?= $this->url->link(t('My tasks'), 'DashboardController', 'tasks') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'subtasks') ?>>
            <i class="fa fa-bookmark"></i><?= $this->url->link(t('My subtasks'), 'DashboardController', 'subtasks') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('My calendar'), 'DashboardController', 'calendar') ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'activities') ?>>
            <i class="fa fa-history"></i><?= $this->url->link(t('My activities'), 'DashboardController', 'activities') ?>
        </li>
        <?= $this->hook->render('template:dashboard:sidebar', ['user' => $user]) ?>
    </ul>
</div>
