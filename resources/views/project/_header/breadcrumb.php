<div class="breadcrumb">
    <i class="fa fa-inbox fa-fw"></i><?= $project['name'] ?>
    <?php if (! empty($project['description'])): ?>
        <small class="tooltip" title="<?= $this->text->markdownAttribute($project['description']) ?>">
            <i class="fa fa-info-circle"></i>
        </small>
    <?php endif ?>
    <div class="pull-right">
        <ul class="project-toolbar">
        <?php if ($this->user->isStargazer($project['id'], $this->user->getId())): ?>
        <li>
            <i class="fa fa-star-o"></i>
            <?= $this->url->link(t('Unstar'), 'Project/ProjectController', 'unstar', ['project_id' => $project['id']], false, 'popover') ?>
        </li>
        <?php else: ?>
        <li>
            <i class="fa fa-star"></i>
            <?= $this->url->link(t('Star'), 'Project/ProjectController', 'star', ['project_id' => $project['id']], false, 'popover') ?>
        </li>
        <?php endif ?>
        <?php if ($this->user->hasProjectAccess('Task/TaskController', 'create', $project['id'])): ?>
        <li>
            <i class="fa fa-plus"></i>
            <?= $this->url->link(t('Create'), 'Task/TaskController', 'create', ['project_id' => $project['id']], false, 'popover large') ?>
        </li>
        <?php endif ?>
        </ul>
    </div>
</div>
