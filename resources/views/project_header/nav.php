<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('ProjectController', 'index')): ?>
    <li <?= $this->app->checkMenuSelection('ProjectController', 'index') ?>><?= $this->url->link('<i class="fa fa-cubes"></i> '.t('Projects list'), 'ProjectController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('ProjectGanttController', 'show')): ?>
    <li <?= $this->app->checkMenuSelection('ProjectGanttController', 'show') ?>><?= $this->url->link('<i class="fa fa-sliders"></i> '.t('Projects Gantt chart'), 'ProjectGanttController', 'show') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('ProjectUserOverviewController', 'managers')): ?>
    <li <?= $this->app->checkMenuSelection('ProjectUserOverviewController', 'managers') ?>><?= $this->url->link('<i class="fa fa-user"></i> '.t('Users overview'), 'ProjectUserOverviewController', 'managers') ?></li>
    <?php endif ?>
</ul>