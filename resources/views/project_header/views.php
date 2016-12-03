<ul class="views">
    <li <?= $this->app->checkMenuSelection('ProjectViewController') ?>>
        <i class="fa fa-eye"></i>
        <?= $this->url->link(t('Overview'), 'ProjectViewController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-overview', t('Keyboard shortcut: "%s"', 'v o')) ?>
    </li>
    <li <?= $this->app->checkMenuSelection('BoardViewController') ?>>
        <i class="fa fa-columns"></i>
        <?= $this->url->link(t('Board'), 'BoardViewController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-board', t('Keyboard shortcut: "%s"', 'v b')) ?>
    </li>
    <li <?= $this->app->checkMenuSelection('CalendarController') ?>>
        <i class="fa fa-calendar"></i>
        <?= $this->url->link(t('Calendar'), 'CalendarController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-calendar', t('Keyboard shortcut: "%s"', 'v c')) ?>
    </li>
    <li <?= $this->app->checkMenuSelection('TaskController') ?>>
        <i class="fa fa-list"></i>
        <?= $this->url->link(t('List'), 'TaskController', 'index', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-listing', t('Keyboard shortcut: "%s"', 'v l')) ?>
    </li>
    <?php if ($this->user->hasProjectAccess('TaskGanttController', 'show', $project['id'])): ?>
    <li <?= $this->app->checkMenuSelection('TaskGanttController') ?>>
        <i class="fa fa-sliders"></i>
        <?= $this->url->link(t('Gantt'), 'TaskGanttController', 'show', ['project_id' => $project['id'], 'q' => $filters['q']], false, 'view-gantt', t('Keyboard shortcut: "%s"', 'v g')) ?>
    </li>
    <?php endif ?>
</ul>
