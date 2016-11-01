<div class="dropdown">
    <a href="#" class="dropdown-menu dashboard-table-link">#<?= $project['id'] ?> <i class="fa fa-caret-down"></i></a>
    <ul>
        <li>
            <i class="fa fa-columns"></i>
            <?= $this->url->link(t('Board'), 'BoardViewController', 'show', array('project_id' => $project['id'])) ?>
        </li>
        <li>
            <i class="fa fa-calendar"></i>
            <?= $this->url->link(t('Calendar'), 'CalendarController', 'show', array('project_id' => $project['id'])) ?>
        </li>
        <li>
            <i class="fa fa-list"></i>
            <?= $this->url->link(t('Listing'), 'TaskListController', 'show', array('project_id' => $project['id'])) ?>
        </li>
        <?php if ($this->user->hasProjectAccess('TaskGanttController', 'show', $project['id'])): ?>
        <li>
            <i class="fa fa-sliders"></i>
            <?= $this->url->link(t('Gantt'), 'TaskGanttController', 'show', array('project_id' => $project['id'])) ?>
        </li>
        <?php endif ?>

        <li>
            <i class="fa fa-dashboard"></i>&nbsp;
            <?= $this->url->link(t('Activity'), 'ActivityController', 'project', array('project_id' => $project['id'])) ?>
        </li>

        <?php if ($this->user->hasProjectAccess('AnalyticController', 'taskDistribution', $project['id'])): ?>
            <li>
                <i class="fa fa-line-chart"></i>&nbsp;
                <?= $this->url->link(t('Analytics'), 'AnalyticController', 'taskDistribution', array('project_id' => $project['id'])) ?>
            </li>
        <?php endif ?>

        <?= $this->hook->render('template:project:dropdown', array('project' => $project)) ?>

        <?php if ($this->user->hasProjectAccess('ProjectEditController', 'edit', $project['id'])): ?>
            <li>
                <i class="fa fa-cog"></i>
                <?= $this->url->link(t('Settings'), 'ProjectSettingsController', 'show', array('project_id' => $project['id'])) ?>
            </li>
        <?php endif ?>
    </ul>
</div>
