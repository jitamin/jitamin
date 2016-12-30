<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('Project/ProjectController', 'index')): ?>
    <li <?= $this->app->setActive('Project/ProjectController', 'index') ?>><?= $this->url->link(t('Projects list'), 'Project/ProjectController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('Project/ProjectController', 'gantt')): ?>
    <li <?= $this->app->setActive('Project/ProjectController', 'gantt') ?>><?= $this->url->link(t('Projects Gantt chart'), 'Project/ProjectController', 'gantt') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('Project/ProjectUserOverviewController', 'managers')): ?>
    <li <?= $this->app->setActive('Project/ProjectUserOverviewController', 'managers') ?>><?= $this->url->link(t('Users overview'), 'Project/ProjectUserOverviewController', 'managers') ?></li>
    <?php endif ?>
</ul>