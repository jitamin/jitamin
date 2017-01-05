<div class="page-header">
<ul class="nav nav-tabs">
    <li <?= $this->app->setActive('Task/TaskController', 'show') ?>>
            <?= $this->url->link(t('Summary'), 'Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <li <?= $this->app->setActive('ActivityController', 'task') ?>>
            <?= $this->url->link(t('Activities'), 'ActivityController', 'task', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <li <?= $this->app->setActive('Task/TaskController', 'transitions') ?>>
        <?= $this->url->link(t('Transitions'), 'Task/TaskController', 'transitions', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <li <?= $this->app->setActive('Task/TaskController', 'analytics') ?>>
        <?= $this->url->link(t('Analytics'), 'Task/TaskController', 'analytics', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <?php if ($task['time_estimated'] > 0 || $task['time_spent'] > 0): ?>
    <li <?= $this->app->setActive('Task/TaskController', 'timetracking') ?>>
        <?= $this->url->link(t('Time tracking'), 'Task/TaskController', 'timetracking', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <?php endif ?>
</ul>
</div>