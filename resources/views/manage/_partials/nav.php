<div class="page-header">
<ul class="nav nav-tabs">
    <?php if ($this->user->hasAccess('Manage/ProjectController', 'index')): ?>
    <li <?= $this->app->setActive('Manage/ProjectController', 'index') ?>><?= $this->url->link(t('Projects list'), 'Manage/ProjectController', 'index') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('Manage/ProjectController', 'gantt')): ?>
    <li <?= $this->app->setActive('Manage/ProjectController', 'gantt') ?>><?= $this->url->link(t('Projects Gantt chart'), 'Manage/ProjectController', 'gantt') ?></li>
            <?php endif ?>
    <?php if ($this->user->hasAccess('Manage/ProjectUserOverviewController', 'managers')): ?>
    <li <?= $this->app->setActive('Manage/ProjectUserOverviewController', 'managers') ?>><?= $this->url->link(t('Users overview'), 'Manage/ProjectUserOverviewController', 'managers') ?></li>
    <?php endif ?>
</ul>
</div>