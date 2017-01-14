<ul class="nav nav-tabs">
    <li <?= $this->app->setActive('Project/ProjectController', 'overview', $project['default_view']) ?>>
        <?= $this->url->link(t('Overview'), 'Project/ProjectController', 'overview', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-overview', t('Keyboard shortcut: "%s"', 'v o')) ?>
    </li>
    <li <?= $this->app->setActive('Project/Board/BoardController', 'show', $project['default_view']) ?>>
        <?= $this->url->link(t('Board'), 'Project/Board/BoardController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-board', t('Keyboard shortcut: "%s"', 'v b')) ?>
    </li>
    <li <?= $this->app->setActive('CalendarController', 'show', $project['default_view']) ?>>
        <?= $this->url->link(t('Calendar'), 'CalendarController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-calendar', t('Keyboard shortcut: "%s"', 'v c')) ?>
    </li>
    <li <?= $this->app->setActive('Task/TaskController', 'index', $project['default_view']) ?>>
        <?= $this->url->link(t('List'), 'Task/TaskController', 'index', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-listing', t('Keyboard shortcut: "%s"', 'v l')) ?>
    </li>
    <?php if ($this->user->hasProjectAccess('Task/TaskController', 'gantt', $project['id'])): ?>
    <li <?= $this->app->setActive('Task/TaskController', 'gantt', $project['default_view']) ?>>
        <?= $this->url->link(t('Gantt'), 'Task/TaskController', 'gantt', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-gantt', t('Keyboard shortcut: "%s"', 'v g')) ?>
    </li>
    <?php endif ?>
</ul>
