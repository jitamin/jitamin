<div class="dropdown">
    <a href="#" class="dropdown-menu dashboard-table-link">#<?= $project['id'] ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <li>
            <i class="fa fa-columns"></i>
            <?= $this->url->link(t('Board'), 'BoardViewController', 'show', ['project_id' => $project['id']]) ?>
        </li>
        <li>
            <i class="fa fa-calendar"></i>
            <?= $this->url->link(t('Calendar'), 'CalendarController', 'show', ['project_id' => $project['id']]) ?>
        </li>
        <li>
            <i class="fa fa-list"></i>
            <?= $this->url->link(t('Listing'), 'TaskController', 'index', ['project_id' => $project['id']]) ?>
        </li>
        <?php if ($this->user->hasProjectAccess('TaskGanttController', 'show', $project['id'])): ?>
        <li>
            <i class="fa fa-sliders"></i>
            <?= $this->url->link(t('Gantt'), 'TaskGanttController', 'show', ['project_id' => $project['id']]) ?>
        </li>
        <?php endif ?>

        <li>
            <i class="fa fa-dashboard"></i>&nbsp;
            <?= $this->url->link(t('Activity'), 'ActivityController', 'project', ['project_id' => $project['id']]) ?>
        </li>

        <?php if ($this->user->hasProjectAccess('AnalyticController', 'taskDistribution', $project['id'])): ?>
            <li>
                <i class="fa fa-line-chart"></i>&nbsp;
                <?= $this->url->link(t('Analytics'), 'AnalyticController', 'taskDistribution', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:project:dropdown', ['project' => $project]) ?>

        <?php if ($this->user->hasProjectAccess('ProjectController', 'edit', $project['id'])): ?>
            <li>
                <i class="fa fa-cog"></i>
                <?= $this->url->link(t('Settings'), 'ProjectSettingsController', 'show', ['project_id' => $project['id']]) ?>
            </li>
        <?php endif ?>
    </ul>
</div>
