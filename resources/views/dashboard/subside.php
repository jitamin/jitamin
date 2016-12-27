<div class="subside subside-icons">
    <ul>
        <li <?= $this->app->setActive('DashboardController', 'index') ?>>
            <i class="fa fa-cubes"></i><?= $this->url->link(t('My projects'), 'DashboardController', 'index') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'tasks') ?>>
            <i class="fa fa-tasks"></i><?= $this->url->link(t('My tasks'), 'DashboardController', 'tasks') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'subtasks') ?>>
            <i class="fa fa-bookmark"></i><?= $this->url->link(t('My subtasks'), 'DashboardController', 'subtasks') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('My calendar'), 'DashboardController', 'calendar') ?>
        </li>
        <li <?= $this->app->setActive('DashboardController', 'activities') ?>>
            <i class="fa fa-history"></i><?= $this->url->link(t('My activities'), 'DashboardController', 'activities') ?>
        </li>
        <?= $this->hook->render('template:dashboard:subside', ['user' => $user]) ?>
    </ul>
</div>
