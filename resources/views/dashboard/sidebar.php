<div class="sidebar sidebar-icons">
    <ul>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'show') ?>>
            <i class="fa fa-cubes"></i><?= $this->url->link(t('My projects'), 'DashboardController', 'show', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'tasks') ?>>
            <i class="fa fa-tasks"></i><?= $this->url->link(t('My tasks'), 'DashboardController', 'tasks', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'subtasks') ?>>
            <i class="fa fa-bookmark"></i><?= $this->url->link(t('My subtasks'), 'DashboardController', 'subtasks', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'calendar') ?>>
            <i class="fa fa-calendar"></i><?= $this->url->link(t('My calendar'), 'DashboardController', 'calendar', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'activity') ?>>
            <i class="fa fa-history"></i><?= $this->url->link(t('My activity stream'), 'DashboardController', 'activity', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('DashboardController', 'notifications') ?>>
            <i class="fa fa-bell"></i><?= $this->url->link(t('My notifications'), 'DashboardController', 'notifications', array('user_id' => $user['id'])) ?>
        </li>
        <?= $this->hook->render('template:dashboard:sidebar', array('user' => $user)) ?>
    </ul>
</div>
