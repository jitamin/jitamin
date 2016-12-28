<div class="page-header">
<ul class="nav nav-tabs">
    <li <?= $this->app->setActive('TaskController', 'show') ?>>
            <?= $this->url->link(t('Summary'), 'TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <li <?= $this->app->setActive('ActivityController', 'task') ?>>
            <?= $this->url->link(t('Activities'), 'ActivityController', 'task', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <li <?= $this->app->setActive('TaskController', 'transitions') ?>>
        <?= $this->url->link(t('Transitions'), 'TaskController', 'transitions', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <li <?= $this->app->setActive('TaskController', 'analytics') ?>>
        <?= $this->url->link(t('Analytics'), 'TaskController', 'analytics', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <?php if ($task['time_estimated'] > 0 || $task['time_spent'] > 0): ?>
    <li <?= $this->app->setActive('TaskController', 'timetracking') ?>>
        <?= $this->url->link(t('Time tracking'), 'TaskController', 'timetracking', ['task_id' => $task['id'], 'project_id' => $task['project_id']]) ?>
    </li>
    <?php endif ?>
</ul>
</div>