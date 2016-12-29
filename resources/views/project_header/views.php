<ul class="views">
    <li <?= $this->app->setActive('Project/ProjectController') ?>>
        <i class="fa fa-eye"></i>
        <?= $this->url->link(t('Overview'), 'Project/ProjectController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-overview', t('Keyboard shortcut: "%s"', 'v o')) ?>
    </li>
    <li <?= $this->app->setActive('BoardController') ?>>
        <i class="fa fa-columns"></i>
        <?= $this->url->link(t('Board'), 'BoardController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-board', t('Keyboard shortcut: "%s"', 'v b')) ?>
    </li>
    <li <?= $this->app->setActive('CalendarController') ?>>
        <i class="fa fa-calendar"></i>
        <?= $this->url->link(t('Calendar'), 'CalendarController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-calendar', t('Keyboard shortcut: "%s"', 'v c')) ?>
    </li>
    <li <?= $this->app->setActive('Task/TaskController') ?>>
        <i class="fa fa-list"></i>
        <?= $this->url->link(t('List'), 'Task/TaskController', 'index', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-listing', t('Keyboard shortcut: "%s"', 'v l')) ?>
    </li>
    <?php if ($this->user->hasProjectAccess('Task/TaskGanttController', 'show', $project['id'])): ?>
    <li <?= $this->app->setActive('Task/TaskGanttController') ?>>
        <i class="fa fa-sliders"></i>
        <?= $this->url->link(t('Gantt'), 'Task/TaskGanttController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-gantt', t('Keyboard shortcut: "%s"', 'v g')) ?>
    </li>
    <?php endif ?>
</ul>
