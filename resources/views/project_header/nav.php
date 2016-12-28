<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('ProjectController', 'index')): ?>
    <li <?= $this->app->setActive('ProjectController', 'index') ?>><?= $this->url->link(t('Projects list'), 'ProjectController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('ProjectGanttController', 'index')): ?>
    <li <?= $this->app->setActive('ProjectGanttController', 'index') ?>><?= $this->url->link(t('Projects Gantt chart'), 'ProjectGanttController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('ProjectUserOverviewController', 'managers')): ?>
    <li <?= $this->app->setActive('ProjectUserOverviewController', 'managers') ?>><?= $this->url->link(t('Users overview'), 'ProjectUserOverviewController', 'managers') ?></li>
    <?php endif ?>
</ul>