<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('Project/ProjectController', 'index')): ?>
    <li <?= $this->app->setActive('ProjectController', 'index') ?>><?= $this->url->link(t('Projects list'), 'Project/ProjectController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('Project/ProjectGanttController', 'index')): ?>
    <li <?= $this->app->setActive('Project/ProjectGanttController', 'index') ?>><?= $this->url->link(t('Projects Gantt chart'), 'Project/ProjectGanttController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('Project/ProjectUserOverviewController', 'managers')): ?>
    <li <?= $this->app->setActive('Project/ProjectUserOverviewController', 'managers') ?>><?= $this->url->link(t('Users overview'), 'Project/ProjectUserOverviewController', 'managers') ?></li>
    <?php endif ?>
</ul>